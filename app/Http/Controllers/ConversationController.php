<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Services\ActivityLogger;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ConversationController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $conversations = Conversation::with(['participants.user:id,name','messages' => function($q){
            $q->latest()->limit(1);
        }])
            ->forUser($userId)
            ->orderByDesc(
                Message::select('created_at')
                    ->whereColumn('messages.conversation_id', 'conversations.id')
                    ->latest()
                    ->limit(1)
            )
            ->get();

        $data = $conversations->map(function($c) use ($userId){
            $last = $c->messages->first();
            $unread = Message::where('conversation_id', $c->id)
                ->when($c->participants, function($q) use ($c,$userId){
                    $participant = $c->participants->firstWhere('user_id', $userId);
                    if ($participant && $participant->last_read_message_id) {
                        $q->where('id','>', $participant->last_read_message_id);
                    }
                })
                ->where('user_id','<>',$userId)
                ->count();
            return [
                'id' => $c->id,
                'type' => $c->type,
                'title' => $c->isDirect() ? $this->directTitleFor($c, $userId) : $c->title,
                'participants' => $c->participants->pluck('user.name','user.id'),
                'last_message' => $last ? [
                    'body' => $last->body,
                    'user_id' => $last->user_id,
                    'at' => $last->created_at->toIso8601String()
                ] : null,
                'unread' => $unread,
            ];
        });

        if ($request->wantsJson()) {
            return response()->json($data);
        }

        return view('messaging.index', [
            'initialConversations' => $data
        ]);
    }

    public function show(Request $request, Conversation $conversation)
    {
        $this->authorize('view', $conversation);
        $userId = $request->user()->id;

        $messages = $conversation->messages()
            ->with('user:id,name')
            ->latest()
            ->limit(30)
            ->get()
            ->reverse()
            ->values();

        return response()->json([
            'conversation' => [
                'id' => $conversation->id,
                'type' => $conversation->type,
                'title' => $conversation->isDirect() ? $this->directTitleFor($conversation, $userId) : $conversation->title,
            ],
            'messages' => $messages->map(fn($m) => [
                'id' => $m->id,
                'body' => $m->body,
                'attachments' => $m->attachments,
                'user' => ['id'=>$m->user->id,'name'=>$m->user->name],
                'at' => $m->created_at->toIso8601String(),
            ])
        ]);
    }

    public function direct(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id|different:auth_user_id'
        ], [
            'user_id.different' => 'You cannot start a direct conversation with yourself.'
        ]);

        $userId = $request->user()->id;
        $otherId = (int) $data['user_id'];

        // Find existing direct conversation with exactly these two participants
        $conversation = Conversation::where('type','direct')
            ->whereHas('participants', fn($q) => $q->where('user_id',$userId))
            ->whereHas('participants', fn($q) => $q->where('user_id',$otherId))
            ->whereDoesntHave('participants', fn($q) => $q->whereNotIn('user_id', [$userId, $otherId]))
            ->first();

        if (!$conversation) {
            $conversation = DB::transaction(function() use ($userId,$otherId){
                $c = Conversation::create([
                    'type' => 'direct',
                    'created_by' => $userId,
                ]);
                ConversationParticipant::create(['conversation_id'=>$c->id,'user_id'=>$userId]);
                ConversationParticipant::create(['conversation_id'=>$c->id,'user_id'=>$otherId]);
                ActivityLogger::log('conversation.create', 'conversation', $c->id, ['mode' => 'direct']);
                return $c;
            });
        }

        return response()->json(['id' => $conversation->id]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:80',
            'participants' => 'required|array|min:2',
            'participants.*' => 'exists:users,id|distinct|different:auth_user_id'
        ]);

        $userId = $request->user()->id;
        $participantIds = array_unique(array_map('intval', $data['participants']));
        if (in_array($userId, $participantIds)) {
            return response()->json(['message' => 'Creator should not be duplicated in participants'], 422);
        }

        $conversation = DB::transaction(function () use ($userId, $data, $participantIds) {
            $c = Conversation::create([
                'type' => 'group',
                'title' => $data['title'],
                'created_by' => $userId,
            ]);
            ConversationParticipant::create(['conversation_id'=>$c->id,'user_id'=>$userId]);
            foreach ($participantIds as $pid) {
                ConversationParticipant::create(['conversation_id'=>$c->id,'user_id'=>$pid]);
            }
            ActivityLogger::log('conversation.create', 'conversation', $c->id, ['mode' => 'group']);
            return $c;
        });

        return response()->json(['id' => $conversation->id], 201);
    }

    public function markRead(Request $request, Conversation $conversation)
    {
        $this->authorize('view', $conversation);
        $latestId = $conversation->messages()->max('id');
        if ($latestId) {
            ConversationParticipant::where('conversation_id',$conversation->id)
                ->where('user_id',$request->user()->id)
                ->update(['last_read_message_id' => $latestId]);
        }
        return response()->json(['ok' => true]);
    }

    // Participants list
    public function participantsIndex(Request $request, Conversation $conversation)
    {
        $this->authorize('view', $conversation);
        $participants = $conversation->participants()->with('user:id,name,role')->get()->map(function($p){
            return [
                'user_id' => $p->user_id,
                'name' => $p->user->name,
                'role' => $p->user->role ?? null,
                'joined_at' => $p->joined_at?->toIso8601String(),
            ];
        });
        return response()->json(['participants' => $participants]);
    }

    public function addParticipants(Request $request, Conversation $conversation)
    {
        if ($conversation->isDirect()) {
            return response()->json(['message' => 'Cannot modify participants of a direct conversation.'], 422);
        }
        $this->authorize('addParticipant', $conversation);
        $data = $request->validate([
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'integer|exists:users,id'
        ]);
        $existingIds = $conversation->participants()->pluck('user_id')->all();
        $latestMessageId = $conversation->messages()->max('id');
        $added = [];
        DB::transaction(function () use ($conversation, $data, $existingIds, $latestMessageId, &$added) {
            foreach ($data['user_ids'] as $uid) {
                if (in_array($uid, $existingIds)) continue;
                ConversationParticipant::create([
                    'conversation_id' => $conversation->id,
                    'user_id' => $uid,
                    'last_read_message_id' => $latestMessageId,
                ]);
                $added[] = $uid;
            }
        });
        if ($added) {
            ActivityLogger::log('participant.add', 'conversation', $conversation->id, ['user_ids' => $added]);
        }
        return $this->participantsIndex($request, $conversation);
    }

    public function removeParticipant(Request $request, Conversation $conversation, User $user)
    {
        if ($conversation->isDirect()) {
            return response()->json(['message' => 'Cannot modify participants of a direct conversation.'], 422);
        }
        $this->authorize('removeParticipant', $conversation);
        if ($conversation->created_by == $user->id) {
            return response()->json(['message' => 'Cannot remove the conversation creator.'], 422);
        }
        $deleted = ConversationParticipant::where('conversation_id', $conversation->id)
            ->where('user_id', $user->id)
            ->delete();
        if ($deleted) {
            ActivityLogger::log('participant.remove', 'conversation', $conversation->id, ['user_id' => $user->id]);
        }
        return $this->participantsIndex($request, $conversation);
    }

    public function leave(Request $request, Conversation $conversation)
    {
        $this->authorize('view', $conversation);
        if ($conversation->isDirect()) {
            return response()->json(['message' => 'Cannot leave a direct conversation.'], 422);
        }
        $userId = $request->user()->id;
        if ($conversation->created_by == $userId) {
            return response()->json(['message' => 'Creator cannot leave the conversation.'], 422);
        }
        $deleted = ConversationParticipant::where('conversation_id', $conversation->id)
            ->where('user_id', $userId)
            ->delete();
        if ($deleted) {
            ActivityLogger::log('participant.leave', 'conversation', $conversation->id, ['user_id' => $userId]);
        }
        return response()->json(['ok' => true]);
    }

    public function updateTitle(Request $request, Conversation $conversation)
    {
        if ($conversation->isDirect()) {
            return response()->json(['message' => 'Cannot rename a direct conversation.'], 422);
        }
        $this->authorize('rename', $conversation);
        $data = $request->validate([
            'title' => 'required|string|max:80'
        ]);
        $conversation->update(['title' => $data['title']]);
        ActivityLogger::log('conversation.rename', 'conversation', $conversation->id, ['title' => $data['title']]);
        return response()->json(['id' => $conversation->id, 'title' => $conversation->title]);
    }

    public function userSearch(Request $request, Conversation $conversation)
    {
        // Must be able to view conversation to search for adding participants
        $this->authorize('view', $conversation);
        if ($conversation->isDirect()) {
            return response()->json(['users' => []]);
        }
        $term = trim($request->get('q', ''));
        if ($term === '') return response()->json(['users' => []]);
        $existing = $conversation->participants()->pluck('user_id')->all();
        $existing[] = $request->user()->id;
        $query = \App\Models\User::query()
            ->whereNotIn('id', $existing)
            ->where(function($q) use ($term){
                $q->where('name','like',"%{$term}%")
                  ->orWhere('email','like',"%{$term}%");
            })
            ->orderBy('name')
            ->limit(10)
            ->get(['id','name','email']);
        return response()->json(['users' => $query]);
    }

    private function directTitleFor(Conversation $conversation, int $viewerId): string
    {
        $other = $conversation->participants
            ->firstWhere('user_id','!=',$viewerId)?->user;
        return $other?->name ?? 'Direct';
    }
}

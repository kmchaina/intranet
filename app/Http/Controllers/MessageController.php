<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\Message;
use App\Http\Resources\MessageResource;
use App\Models\TempMessageAttachment;
use App\Models\MessageAttachment;
use App\Events\MessageCreated;
use App\Events\MessageDeleted;
use App\Events\ConversationUserTyping;
use App\Models\MessageDraft;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\ActivityLogger;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class MessageController extends Controller
{
    use AuthorizesRequests;

    private function attachmentConfig(): array
    {
        return config('messaging.attachments');
    }
    public function index(Request $request, Conversation $conversation)
    {
        $this->authorize('view', $conversation);
        $validated = $request->validate([
            'after_id' => 'nullable|integer|min:1'
        ]);

        $afterId = $validated['after_id'] ?? null;

        $query = $conversation->messages()->withTrashed()
            ->with('user:id,name')
            ->with('conversation:id,type,title,created_by')
            ->with('reactions.user:id,name');

        if ($afterId) {
            $query->where('id', '>', $afterId)->orderBy('id');
        } else {
            // Get last 30 by id descending then resort ascending
            $query->orderByDesc('id')->limit(30);
        }

        $messages = $query->get()->sortBy('id')->values();

        return response()->json([
            'messages' => MessageResource::collection($messages)
        ]);
    }

    // Phase 11: fetch older messages before a given message id (infinite scroll backwards)
    public function older(Request $request, Conversation $conversation)
    {
        $this->authorize('view', $conversation);
        $validated = $request->validate([
            'before_id' => 'required|integer|min:1',
            'limit' => 'nullable|integer|min:1|max:50'
        ]);
        $limit = $validated['limit'] ?? 30;
        $beforeId = $validated['before_id'];
        $query = $conversation->messages()->withTrashed()
            ->with('user:id,name')
            ->with('conversation:id,type,title,created_by')
            ->with('reactions.user:id,name')
            ->where('id', '<', $beforeId)
            ->orderByDesc('id')
            ->limit($limit);
        $messages = $query->get()->sortBy('id')->values();
        return response()->json([
            'messages' => MessageResource::collection($messages)
        ]);
    }

    public function store(Request $request, Conversation $conversation)
    {
        try {
            $this->authorize('send', $conversation);
            // Phase 3: Rate limiting
            $limitCfg = config('messaging.rate_limit');
            $maxPerMinute = (int)($limitCfg['messages_per_minute'] ?? 30);
            $limiterKey = 'messages:send:user:' . $request->user()->id;
            if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($limiterKey, $maxPerMinute)) {
                $retry = \Illuminate\Support\Facades\RateLimiter::availableIn($limiterKey);
                return response()->json([
                    'message' => 'Rate limit exceeded. Please wait ' . $retry . 's',
                    'retry_after' => $retry,
                    'limit' => $maxPerMinute
                ], 429);
            }
            \Illuminate\Support\Facades\RateLimiter::hit($limiterKey, 60); // decay in 60 seconds
            $data = $request->validate([
                'body' => 'required_without:tokens|string|nullable|max:5000',
                'tokens' => 'array|max:5',
                'tokens.*' => 'string|size:26', // ulid length
            ]);

            // Phase 4: Resolve attachment tokens -> sanitized attachment payload
            $attachments = [];
            $attachmentRecords = [];
            if (!empty($data['tokens'])) {
                $records = TempMessageAttachment::whereIn('id', $data['tokens'])
                    ->where('user_id', $request->user()->id)
                    ->whereNull('consumed_at')
                    ->get();
                $byId = $records->keyBy('id');
                foreach ($data['tokens'] as $t) { // preserve order client provided
                    if ($byId->has($t)) {
                        $r = $byId->get($t);
                        $attachments[] = [ // response payload (legacy inline array)
                            'name' => $r->original_name,
                            'url' => asset('storage/' . $r->path), // Temporary URL for preview
                            'size' => (int)$r->size,
                            'mime' => $r->mime,
                            'ext' => $r->ext,
                            'kind' => $r->kind,
                        ];
                        $attachmentRecords[] = $r; // for persistence later
                    }
                }
                // Mark consumed
                TempMessageAttachment::whereIn('id', $byId->keys())->update(['consumed_at' => now()]);
            }

            if (empty($data['body']) && empty($attachments)) {
                return response()->json(['message' => 'Message body or attachments required'], 422);
            }

            // Pass attachments explicitly to closure to avoid global state
            $resolved = $attachments;
            $message = DB::transaction(function () use ($request, $conversation, $data, $resolved, $attachmentRecords) {
                $m = $conversation->messages()->create([
                    'user_id' => $request->user()->id,
                    'body' => $data['body'] ?? null,
                    // Inline attachments array kept temporarily for backwards compatibility.
                    'attachments' => $resolved,
                ]);
                // Persist attachments in canonical table linking to message
                if (!empty($attachmentRecords)) {
                    $now = now();
                    foreach ($attachmentRecords as $idx => $rec) {
                        $dbAttachment = MessageAttachment::create([
                            'message_id' => $m->id,
                            'user_id' => $request->user()->id,
                            'original_name' => $rec->original_name,
                            'path' => $rec->path,
                            'mime' => $rec->mime,
                            'size' => $rec->size,
                            'ext' => $rec->ext,
                            'kind' => $rec->kind,
                            'created_at' => $rec->created_at,
                            'linked_at' => $now,
                        ]);
                        // Update the resolved attachments array with proper download URL
                        if (isset($resolved[$idx])) {
                            $resolved[$idx]['url'] = route('messages.attachments.download', ['attachment' => $dbAttachment->id]);
                            $resolved[$idx]['id'] = $dbAttachment->id;
                        }
                    }
                    // Update message with correct URLs
                    $m->update(['attachments' => $resolved]);
                }
                ConversationParticipant::where('conversation_id', $conversation->id)
                    ->where('user_id', $request->user()->id)
                    ->update(['last_read_message_id' => $m->id]);
                ActivityLogger::log('message.post', 'message', $m->id, ['conversation_id' => $conversation->id]);
                return $m;
            });

            // Phase 4: ensure response remains a flat resource (id, body, attachments...)
            $resource = new MessageResource($message->load('user:id,name'));
            // Return flat JSON (not wrapped) to keep contract stable for frontend & tests
            // Broadcast outside transaction commit callback (message persisted)
            broadcast(new MessageCreated($message));
            return response()->json($resource->toArray($request), 201);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Message store error: ' . $e->getMessage(), [
                'exception' => $e,
                'conversation_id' => $conversation->id ?? null,
                'user_id' => $request->user()->id ?? null,
            ]);
            return response()->json([
                'message' => 'Failed to send message: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, Conversation $conversation, Message $message)
    {
        // Ensure the message belongs to this conversation
        if ($message->conversation_id !== $conversation->id) {
            abort(404);
        }
        $this->authorize('deleteMessage', [$conversation, $message]);
        // Unified behavior: always soft delete (tombstone) for auditability & consistent frontend handling.
        if (!$message->trashed()) {
            $message->delete();
        }
        ActivityLogger::log('message.delete', 'message', $message->id, [
            'conversation_id' => $conversation->id,
            'soft' => true,
        ]);
        broadcast(new MessageDeleted($conversation->id, $message->id, optional($message->deleted_at)->toIso8601String()));
        return response()->json([
            'id' => $message->id,
            'deleted' => true,
            'deleted_at' => optional($message->deleted_at)->toIso8601String(),
        ]);
    }

    public function uploadAttachments(Request $request, Conversation $conversation)
    {
        $this->authorize('view', $conversation);
        $cfg = $this->attachmentConfig();
        $maxSizeKb = $cfg['max_size_kb'];
        $allowed = $cfg['allowed_mimes'];
        $maxPer = $cfg['max_per_message'];

        $request->validate([
            'files' => 'required|array|min:1',
            'files.*' => 'file|max:' . $maxSizeKb . '|mimetypes:' . implode(',', $allowed),
        ]);

        if (count($request->file('files', [])) > $maxPer) {
            return response()->json(['message' => 'Too many files. Max ' . $maxPer], 422);
        }

        $tokens = [];
        foreach ($request->file('files') as $file) {
            $original = $file->getClientOriginalName();
            $ext = strtolower($file->getClientOriginalExtension());
            $safeExt = preg_replace('/[^a-z0-9]+/', '', $ext);
            $hashName = sha1(uniqid('', true) . $original) . ($safeExt ? '.' . $safeExt : '');
            $path = $file->storeAs('chat/' . date('Y') . '/' . date('m'), $hashName, 'public');
            $mime = $file->getClientMimeType();
            $kind = str_starts_with($mime, 'image/') ? 'image' : (str_contains($mime, 'pdf') ? 'doc' : (str_contains($mime, 'officedocument') ? 'doc' : 'other'));
            $token = strtolower(str()->ulid());
            TempMessageAttachment::create([
                'id' => $token,
                'user_id' => $request->user()->id,
                'original_name' => $original,
                'path' => $path,
                'mime' => $mime,
                'size' => $file->getSize(),
                'ext' => $safeExt,
                'kind' => $kind,
                'created_at' => now(),
            ]);
            $tokens[] = $token;
        }

        // Backward compatibility: some existing tests/clients expect immediate attachment metadata.
        // We return tokens (for message send) plus a synthetic attachments array mirroring final structure
        // so legacy flows that still post 'attachments' directly continue to work during migration period.
        $fakeMeta = [];
        foreach ($tokens as $t) {
            $temp = \App\Models\TempMessageAttachment::find($t);
            if ($temp) {
                $fakeMeta[] = [
                    'name' => $temp->original_name,
                    'url' => asset('storage/' . $temp->path),
                    'size' => (int)$temp->size,
                    'mime' => $temp->mime,
                    'ext' => $temp->ext,
                    'kind' => $temp->kind,
                ];
            }
        }
        return response()->json(['tokens' => $tokens, 'attachments' => $fakeMeta]);
    }

    // Ephemeral typing indicator broadcast. Does not persist anything.
    public function typing(Request $request, Conversation $conversation)
    {
        $this->authorize('view', $conversation);
        $user = $request->user();
        $throttle = (int) config('messaging.typing.throttle_seconds', 2);
        $key = 'typing:conv:' . $conversation->id . ':user:' . $user->id;
        // Simple throttle using cache add (exists within window => skip broadcast)
        if (!cache()->add($key, 1, $throttle)) {
            return response()->json(['status' => 'skipped']);
        }
        broadcast(new ConversationUserTyping($conversation->id, $user->id, $user->name))->toOthers();
        return response()->json([
            'status' => 'ok',
            'idle_timeout_seconds' => (int) config('messaging.typing.idle_timeout_seconds', 6),
        ]);
    }

    // Phase 11: Draft endpoints
    public function getDraft(Request $request, Conversation $conversation)
    {
        $this->authorize('view', $conversation);
        $draft = MessageDraft::where('conversation_id', $conversation->id)->where('user_id', $request->user()->id)->first();
        return response()->json(['body' => $draft?->body]);
    }
    public function saveDraft(Request $request, Conversation $conversation)
    {
        $this->authorize('view', $conversation);
        $data = $request->validate([
            'body' => 'nullable|string|max:' . config('messaging.drafts.max_length', 5000)
        ]);
        $draft = MessageDraft::updateOrCreate(
            ['conversation_id' => $conversation->id, 'user_id' => $request->user()->id],
            ['body' => $data['body'] ?? null, 'updated_at' => now()]
        );
        return response()->json(['saved' => true, 'body' => $draft->body]);
    }
    public function clearDraft(Request $request, Conversation $conversation)
    {
        $this->authorize('view', $conversation);
        MessageDraft::where('conversation_id', $conversation->id)->where('user_id', $request->user()->id)->delete();
        return response()->json(['cleared' => true]);
    }

    // Phase 11E: Presence heartbeat
    public function presenceHeartbeat(Request $request)
    {
        $user = $request->user();
        $ttl = (int) config('messaging.presence.ttl_seconds', 60);
        $key = 'presence:user:' . $user->id;
        cache()->put($key, now()->toIso8601String(), $ttl);
        return response()->json(['ok' => true, 'ttl' => $ttl]);
    }

    // Phase 11E: Get presence status for conversation participants
    public function getPresence(Request $request, Conversation $conversation)
    {
        $this->authorize('view', $conversation);

        // Get all participant user IDs
        $participantIds = $conversation->participants()->pluck('user_id')->toArray();

        // Check which ones are online (have recent heartbeat)
        $ttl = (int) config('messaging.presence.ttl_seconds', 60);
        $online = [];

        foreach ($participantIds as $userId) {
            $key = 'presence:user:' . $userId;
            if (cache()->has($key)) {
                $online[] = $userId;
            }
        }

        return response()->json([
            'conversation_id' => $conversation->id,
            'online_user_ids' => $online,
            'ttl' => $ttl
        ]);
    }

    // Phase 10: Search within a single conversation
    public function searchConversation(Request $request, Conversation $conversation)
    {
        $this->authorize('view', $conversation);
        $queryString = trim((string)$request->query('q', ''));
        $min = (int) config('messaging.search.min_length', 2);
        if (mb_strlen($queryString) < $min) {
            return response()->json(['results' => [], 'q' => $queryString]);
        }
        $limit = (int) config('messaging.search.max_results', 20);
        $messages = $conversation->messages()
            ->whereNotNull('body')
            ->where('body', 'like', '%' . str_replace(['%', '_'], ['\%', '\_'], $queryString) . '%')
            ->orderByDesc('id')
            ->limit($limit)
            ->with('user:id,name')
            ->get();
        return response()->json([
            'q' => $queryString,
            'results' => MessageResource::collection($messages),
        ]);
    }

    // Phase 10: Global search across all user's conversations (participant constraint enforced)
    public function searchAll(Request $request)
    {
        $user = $request->user();
        $queryString = trim((string)$request->query('q', ''));
        $min = (int) config('messaging.search.min_length', 2);
        if (mb_strlen($queryString) < $min) {
            return response()->json(['results' => [], 'q' => $queryString]);
        }
        $limit = (int) config('messaging.search.max_results', 20);
        // Join messages with conversation_participants to restrict scope
        $escaped = str_replace(['%', '_'], ['\%', '\_'], $queryString);
        $messages = Message::query()
            ->select('messages.*')
            ->join('conversation_participants as cp', 'cp.conversation_id', '=', 'messages.conversation_id')
            ->where('cp.user_id', $user->id)
            ->whereNotNull('messages.body')
            ->where('messages.body', 'like', '%' . $escaped . '%')
            ->orderByDesc('messages.id')
            ->limit($limit)
            ->with('user:id,name', 'conversation:id,type,title')
            ->get();
        // Provide minimal conversation context alongside each message
        $results = $messages->map(function ($m) {
            $res = (new MessageResource($m))->toArray(request());
            $res['conversation'] = [
                'id' => $m->conversation_id,
                'title' => $m->conversation?->title,
                'type' => $m->conversation?->type,
            ];
            return $res;
        });
        return response()->json([
            'q' => $queryString,
            'results' => $results,
        ]);
    }

    /**
     * Download a message attachment with authorization.
     * Only participants of the conversation can download attachments.
     */
    public function downloadAttachment(Request $request, MessageAttachment $attachment)
    {
        // Get the message and conversation
        $message = $attachment->message;
        if (!$message) {
            abort(404, 'Message not found');
        }

        $conversation = $message->conversation;
        if (!$conversation) {
            abort(404, 'Conversation not found');
        }

        // Check if user is a participant
        $this->authorize('view', $conversation);

        // Serve the file
        $filePath = storage_path('app/public/' . $attachment->path);

        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        return response()->download($filePath, $attachment->original_name, [
            'Content-Type' => $attachment->mime,
        ]);
    }

    public function toggleReaction(Request $request, Message $message)
    {
        $conversation = $message->conversation;
        $this->authorize('view', $conversation);

        $validated = $request->validate([
            'emoji' => 'required|string|max:10'
        ]);

        $user = $request->user();

        $reaction = $message->reactions()
            ->where('user_id', $user->id)
            ->where('emoji', $validated['emoji'])
            ->first();

        if ($reaction) {
            // Remove reaction
            $reaction->delete();
            return response()->json(['removed' => true]);
        } else {
            // Add reaction
            $message->reactions()->create([
                'user_id' => $user->id,
                'emoji' => $validated['emoji']
            ]);
            return response()->json(['added' => true]);
        }
    }
}

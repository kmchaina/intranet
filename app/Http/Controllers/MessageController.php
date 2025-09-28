<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\Message;
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

        $query = $conversation->messages()->with('user:id,name');
        if (!empty($validated['after_id'])) {
            $query->where('id','>',$validated['after_id']);
        } else {
            $query->latest()->limit(30); // initial batch
        }

        $messages = $query->orderBy('id')->get();

        return response()->json([
            'messages' => $messages->map(fn($m) => [
                'id' => $m->id,
                'body' => $m->body,
                'attachments' => $m->attachments,
                'user' => ['id'=>$m->user->id,'name'=>$m->user->name],
                'at' => $m->created_at->toIso8601String(),
            ])
        ]);
    }

    public function store(Request $request, Conversation $conversation)
    {
        $this->authorize('send', $conversation);
        $data = $request->validate([
            'body' => 'required_without:attachments|string|nullable|max:5000',
            'attachments' => 'array|max:5',
            'attachments.*.name' => 'required_with:attachments|string|max:120',
            'attachments.*.url' => 'required_with:attachments|url|max:2048',
        ]);

        if (empty($data['body']) && empty($data['attachments'])) {
            return response()->json(['message' => 'Message body or attachments required'], 422);
        }

        $message = DB::transaction(function () use ($request, $conversation, $data) {
            $m = $conversation->messages()->create([
                'user_id' => $request->user()->id,
                'body' => $data['body'] ?? null,
                'attachments' => $data['attachments'] ?? [],
            ]);
            ConversationParticipant::where('conversation_id', $conversation->id)
                ->where('user_id', $request->user()->id)
                ->update(['last_read_message_id' => $m->id]);
            ActivityLogger::log('message.post', 'message', $m->id, ['conversation_id' => $conversation->id]);
            return $m;
        });

        return response()->json([
            'id' => $message->id,
            'body' => $message->body,
            'attachments' => $message->attachments,
            'user_id' => $message->user_id,
            'at' => $message->created_at->toIso8601String(),
        ], 201);
    }

    public function destroy(Request $request, Conversation $conversation, Message $message)
    {
        // Ensure the message belongs to this conversation
        if ($message->conversation_id !== $conversation->id) {
            abort(404);
        }
        $this->authorize('deleteMessage', [$conversation, $message]);
        $message->delete();
        ActivityLogger::log('message.delete', 'message', $message->id, ['conversation_id' => $conversation->id]);
        return response()->noContent();
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
            'files.*' => 'file|max:'.$maxSizeKb.'|mimetypes:'.implode(',', $allowed),
        ]);

        if (count($request->file('files', [])) > $maxPer) {
            return response()->json(['message' => 'Too many files. Max '.$maxPer], 422);
        }

        $stored = [];
        foreach ($request->file('files') as $file) {
            $original = $file->getClientOriginalName();
            $ext = strtolower($file->getClientOriginalExtension());
            $safeExt = preg_replace('/[^a-z0-9]+/','', $ext);
            $hashName = sha1(uniqid('', true).$original).($safeExt ? '.'.$safeExt : '');
            $path = $file->storeAs('chat/'.date('Y').'/'.date('m'), $hashName, 'public');
            $mime = $file->getClientMimeType();
            $kind = str_starts_with($mime, 'image/') ? 'image' : (str_contains($mime, 'pdf') ? 'doc' : (str_contains($mime,'officedocument') ? 'doc' : 'other'));
            $stored[] = [
                'name' => $original,
                'url' => asset('storage/'.$path),
                'size' => $file->getSize(),
                'mime' => $mime,
                'ext' => $safeExt,
                'kind' => $kind,
            ];
        }

        return response()->json(['attachments' => $stored]);
    }
}

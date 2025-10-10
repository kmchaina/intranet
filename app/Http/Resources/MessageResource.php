<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        // Get conversation - either loaded or from the query builder context
        $conversation = $this->conversation ?? $this->resource->conversation;
        $user = $request->user();

        // Calculate can_delete
        $canDelete = false;
        if ($user && $conversation) {
            try {
                $canDelete = $user->can('deleteMessage', [$conversation, $this->resource]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning('Failed to check deleteMessage permission', [
                    'message_id' => $this->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $deleted = !is_null($this->deleted_at);

        // Group reactions by emoji
        $reactions = [];
        if (!$deleted) {
            try {
                $reactions = $this->reactions->groupBy('emoji')->map(function ($group, $emoji) use ($user) {
                    return [
                        'emoji' => $emoji,
                        'count' => $group->count(),
                        'users' => $group->pluck('user.name')->toArray(),
                        'user_reacted' => $user ? $group->contains('user_id', $user->id) : false,
                    ];
                })->values()->toArray();
            } catch (\Exception $e) {
                // Silently fail if reactions can't be loaded
            }
        }

        return [
            'id' => $this->id,
            'deleted' => $deleted,
            'deleted_at' => $deleted ? optional($this->deleted_at)->toIso8601String() : null,
            'body' => $deleted ? null : $this->body,
            'attachments' => $deleted ? [] : ($this->attachments ?? []),
            'reactions' => $reactions,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
            'at' => optional($this->created_at)->toIso8601String(),
            'can_delete' => $canDelete,
        ];
    }
}

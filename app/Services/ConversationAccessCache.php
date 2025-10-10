<?php

namespace App\Services;

use App\Models\ConversationParticipant;
use Illuminate\Support\Facades\Log;

/**
 * Request-scoped membership cache to avoid repetitive participant existence queries.
 */
class ConversationAccessCache
{
    private array $loaded = [];
    private array $membership = []; // userId => [conversationId => true]

    public function hydrate(int $userId, array $conversationIds): void
    {
        $need = array_diff($conversationIds, array_keys($this->membership[$userId] ?? []));
        if (!$need) return;
        $rows = ConversationParticipant::where('user_id', $userId)
            ->whereIn('conversation_id', $need)
            ->pluck('conversation_id');
        foreach ($rows as $cid) {
            $this->membership[$userId][$cid] = true;
        }
    }

    public function isParticipant(int $userId, int $conversationId): bool
    {
        if (isset($this->membership[$userId][$conversationId])) {
            return true;
        }
        // Lazy single lookup
        $exists = ConversationParticipant::where('user_id', $userId)
            ->where('conversation_id', $conversationId)
            ->exists();
        if ($exists) {
            $this->membership[$userId][$conversationId] = true;
        }
        return $exists;
    }
}

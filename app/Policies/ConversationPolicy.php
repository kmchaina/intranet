<?php

namespace App\Policies;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Services\ConversationAccessCache;

class ConversationPolicy
{
    public function view(User $user, Conversation $conversation): bool
    {
        // Cached membership check
        $cache = app(ConversationAccessCache::class);
        return $cache->isParticipant($user->id, $conversation->id);
    }

    public function send(User $user, Conversation $conversation): bool
    {
        return $this->view($user, $conversation);
    }

    public function addParticipant(User $user, Conversation $conversation): bool
    {
        if ($conversation->isDirect()) {
            return false;
        }
        return $user->id === $conversation->created_by || $user->isSuperAdmin();
    }

    public function removeParticipant(User $user, Conversation $conversation): bool
    {
        return $this->addParticipant($user, $conversation);
    }

    public function rename(User $user, Conversation $conversation): bool
    {
        if ($conversation->isDirect()) return false;
        return $user->id === $conversation->created_by || $user->isSuperAdmin();
    }

    public function destroy(User $user, Conversation $conversation): bool
    {
        // For direct conversations, any participant can delete (from their view)
        if ($conversation->isDirect()) {
            return $this->view($user, $conversation);
        }

        // For group conversations, only creator or admin can delete
        return $user->id === $conversation->created_by || $user->isSuperAdmin();
    }

    public function deleteMessage(User $user, Conversation $conversation, Message $message): bool
    {
        // Super admins can delete any message at any time
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Users can only delete their own messages
        if ($message->user_id !== $user->id) {
            return false;
        }

        // Users can delete their own messages within 5 minutes
        $fiveMinutes = 5 * 60; // 5 minutes in seconds
        $messageAge = now()->diffInSeconds($message->created_at);

        return $messageAge < $fiveMinutes;
    }
}

<?php

namespace App\Policies;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;

class ConversationPolicy
{
    public function view(User $user, Conversation $conversation): bool
    {
        return $conversation->participants()->where('user_id', $user->id)->exists();
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
        return $user->id === $conversation->created_by || $user->isSuperAdmin();
    }

    public function deleteMessage(User $user, Conversation $conversation, Message $message): bool
    {
        // Super admin override
        if ($user->isSuperAdmin()) return true;
        // Author within 5-minute window
        if ($message->user_id !== $user->id) return false;
        if (!$message->created_at) return false;
        return $message->created_at->diffInMinutes(now()) < 5;
    }
}

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

    public function deleteMessage(User $user, Message $message): bool
    {
        if ($user->isSuperAdmin()) return true;
        return $message->user_id === $user->id && $message->created_at->gt(now()->subMinutes(5));
    }
}

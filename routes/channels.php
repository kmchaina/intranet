<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\ConversationParticipant;

Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
    return ConversationParticipant::where('conversation_id', $conversationId)
        ->where('user_id', $user->id)
        ->exists();
});

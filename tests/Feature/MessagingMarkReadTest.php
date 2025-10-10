<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessagingMarkReadTest extends TestCase
{
    use RefreshDatabase;

    public function test_mark_read_sets_last_read_and_returns_unread_zero()
    {
        $a = User::factory()->create();
        $b = User::factory()->create();
        $conv = Conversation::create(['type' => 'direct', 'created_by' => $a->id]);
        ConversationParticipant::create(['conversation_id' => $conv->id, 'user_id' => $a->id]);
        ConversationParticipant::create(['conversation_id' => $conv->id, 'user_id' => $b->id]);
        // Seed messages from other user so they count unread for $a
        $m1 = Message::create(['conversation_id' => $conv->id, 'user_id' => $b->id, 'body' => 'One']);
        $m2 = Message::create(['conversation_id' => $conv->id, 'user_id' => $b->id, 'body' => 'Two']);

        $this->actingAs($a);
        // Precondition: participant last_read null
        $this->assertDatabaseHas('conversation_participants', [
            'conversation_id' => $conv->id,
            'user_id' => $a->id,
            'last_read_message_id' => null,
        ]);

        $resp = $this->postJson("/messages/conversations/{$conv->id}/mark-read")
            ->assertStatus(200)
            ->assertJson([
                'conversation_id' => $conv->id,
                'latest_message_id' => $m2->id,
                'unread' => 0,
            ]);

        $this->assertDatabaseHas('conversation_participants', [
            'conversation_id' => $conv->id,
            'user_id' => $a->id,
            'last_read_message_id' => $m2->id,
        ]);
    }
}

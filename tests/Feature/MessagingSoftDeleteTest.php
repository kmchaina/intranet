<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessagingSoftDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_author_soft_delete_message_returns_tombstone()
    {
        $author = User::factory()->create();
        $other = User::factory()->create();
        $conv = Conversation::create(['type' => 'direct', 'created_by' => $author->id]);
        ConversationParticipant::create(['conversation_id' => $conv->id, 'user_id' => $author->id]);
        ConversationParticipant::create(['conversation_id' => $conv->id, 'user_id' => $other->id]);
        $msg = Message::create(['conversation_id' => $conv->id, 'user_id' => $author->id, 'body' => 'Secret']);

        $this->actingAs($author);
        $del = $this->deleteJson("/messages/conversations/{$conv->id}/items/{$msg->id}")
            ->assertStatus(200)
            ->json();
        $this->assertTrue($del['deleted']);
        $this->assertNotNull($del['deleted_at']);

        // Reload via list endpoint; body should be null
        $list = $this->getJson("/messages/conversations/{$conv->id}/items")
            ->assertStatus(200)
            ->json('messages');
        $found = collect($list)->firstWhere('id', $msg->id);
        $this->assertTrue($found['deleted']);
        $this->assertNull($found['body']);
        $this->assertEquals([], $found['attachments']);
    }
}

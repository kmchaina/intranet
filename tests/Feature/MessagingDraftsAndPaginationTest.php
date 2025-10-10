<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessagingDraftsAndPaginationTest extends TestCase
{
    use RefreshDatabase;

    private function conv(User $a, User $b): Conversation
    {
        $c = Conversation::create(['type' => 'direct', 'created_by' => $a->id]);
        ConversationParticipant::create(['conversation_id' => $c->id, 'user_id' => $a->id]);
        ConversationParticipant::create(['conversation_id' => $c->id, 'user_id' => $b->id]);
        return $c;
    }

    public function test_draft_save_get_and_clear()
    {
        $u1 = User::factory()->create();
        $u2 = User::factory()->create();
        $conv = $this->conv($u1, $u2);
        $this->actingAs($u1);
        $this->getJson("/messages/conversations/{$conv->id}/draft")
            ->assertStatus(200)->assertJson(['body' => null]);
        $this->putJson("/messages/conversations/{$conv->id}/draft", ['body' => 'Working draft'])
            ->assertStatus(200)->assertJson(['saved' => true, 'body' => 'Working draft']);
        $this->getJson("/messages/conversations/{$conv->id}/draft")
            ->assertStatus(200)->assertJson(['body' => 'Working draft']);
        $this->deleteJson("/messages/conversations/{$conv->id}/draft")
            ->assertStatus(200)->assertJson(['cleared' => true]);
        $this->getJson("/messages/conversations/{$conv->id}/draft")
            ->assertStatus(200)->assertJson(['body' => null]);
    }

    public function test_older_messages_pagination()
    {
        $u1 = User::factory()->create();
        $u2 = User::factory()->create();
        $conv = $this->conv($u1, $u2);
        // Seed 40 messages
        for ($i = 1; $i <= 40; $i++) {
            Message::create(['conversation_id' => $conv->id, 'user_id' => $u1->id, 'body' => 'Msg ' . $i]);
        }
        $this->actingAs($u1);
        // Initial fetch (index) returns last 30 (11..40)
        $first = $this->getJson("/messages/conversations/{$conv->id}/items")
            ->assertStatus(200)->json('messages');
        $this->assertEquals(30, count($first));
        $oldestShown = $first[0]['id']; // should be id 11
        $older = $this->getJson("/messages/conversations/{$conv->id}/items/older?before_id={$oldestShown}")
            ->assertStatus(200)->json('messages');
        $this->assertGreaterThan(0, count($older));
        $this->assertEquals('Msg 10', $older[count($older) - 1]['body']);
    }
}

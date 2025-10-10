<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessagingSearchTest extends TestCase
{
    use RefreshDatabase;

    private function seedConversation(User $a, User $b): Conversation
    {
        $conv = Conversation::create(['type' => 'direct', 'created_by' => $a->id]);
        ConversationParticipant::create(['conversation_id' => $conv->id, 'user_id' => $a->id]);
        ConversationParticipant::create(['conversation_id' => $conv->id, 'user_id' => $b->id]);
        return $conv;
    }

    public function test_conversation_scoped_search_returns_matches()
    {
        $a = User::factory()->create();
        $b = User::factory()->create();
        $conv = $this->seedConversation($a, $b);
        Message::create(['conversation_id' => $conv->id, 'user_id' => $a->id, 'body' => 'Alpha bravo charlie']);
        Message::create(['conversation_id' => $conv->id, 'user_id' => $b->id, 'body' => 'Delta echo foxtrot']);
        $this->actingAs($a);
        $resp = $this->getJson("/messages/conversations/{$conv->id}/search?q=bravo")
            ->assertStatus(200)
            ->json();
        $this->assertCount(1, $resp['results']);
        $this->assertEquals('Alpha bravo charlie', $resp['results'][0]['body']);
    }

    public function test_global_search_filters_to_participant_conversations()
    {
        $a = User::factory()->create();
        $b = User::factory()->create();
        $c = User::factory()->create();
        $conv1 = $this->seedConversation($a, $b);
        $conv2 = $this->seedConversation($b, $c); // a is NOT participant here
        Message::create(['conversation_id' => $conv1->id, 'user_id' => $a->id, 'body' => 'Project Phoenix kickoff']);
        Message::create(['conversation_id' => $conv2->id, 'user_id' => $b->id, 'body' => 'Secret Phoenix plan']);
        $this->actingAs($a);
        $resp = $this->getJson('/messages/search?q=Phoenix')
            ->assertStatus(200)
            ->json();
        $this->assertCount(1, $resp['results']);
        $this->assertEquals($conv1->id, $resp['results'][0]['conversation']['id']);
    }

    public function test_short_query_returns_empty_results()
    {
        $a = User::factory()->create();
        $b = User::factory()->create();
        $conv = $this->seedConversation($a, $b);
        Message::create(['conversation_id' => $conv->id, 'user_id' => $a->id, 'body' => 'Ping']);
        $this->actingAs($a);
        $resp = $this->getJson('/messages/search?q=p')
            ->assertStatus(200)
            ->json();
        $this->assertEquals([], $resp['results']);
    }
}

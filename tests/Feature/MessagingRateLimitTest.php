<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class MessagingRateLimitTest extends TestCase
{
    use RefreshDatabase;

    private function conversationWith(User $a, User $b): Conversation
    {
        $c = Conversation::create([
            'type' => 'direct',
            'title' => null,
            'created_by' => $a->id,
        ]);
        ConversationParticipant::create(['conversation_id' => $c->id, 'user_id' => $a->id]);
        ConversationParticipant::create(['conversation_id' => $c->id, 'user_id' => $b->id]);
        return $c;
    }

    public function test_message_send_rate_limit_triggers_and_resets()
    {
        // Lower the limit for fast test
        config(['messaging.rate_limit.messages_per_minute' => 3]);

        $sender = User::factory()->create();
        $other  = User::factory()->create();
        $conv   = $this->conversationWith($sender, $other);

        $this->actingAs($sender);

        // Ensure clean slate (in case reused cache between tests)
        RateLimiter::clear('messages:send:user:' . $sender->id);

        // First 3 within limit
        for ($i = 1; $i <= 3; $i++) {
            $resp = $this->postJson("/messages/conversations/{$conv->id}/items", ['body' => 'Msg ' . $i]);
            $resp->assertStatus(201)->assertJsonFragment(['body' => 'Msg ' . $i]);
        }

        // 4th should be blocked
        $blocked = $this->postJson("/messages/conversations/{$conv->id}/items", ['body' => 'Blocked']);
        $blocked->assertStatus(429)->assertJsonStructure(['message', 'retry_after', 'limit']);
        $this->assertEquals(3, $blocked->json('limit'));

        // Simulate reset by clearing rate limiter (instead of waiting real time)
        RateLimiter::clear('messages:send:user:' . $sender->id);
        $afterReset = $this->postJson("/messages/conversations/{$conv->id}/items", ['body' => 'After reset']);
        $afterReset->assertStatus(201)->assertJsonFragment(['body' => 'After reset']);
    }
}

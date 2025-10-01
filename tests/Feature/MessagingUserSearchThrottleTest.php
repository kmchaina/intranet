<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessagingUserSearchThrottleTest extends TestCase
{
    use RefreshDatabase;

    private function group(User $creator, array $others = [], string $title = 'Throttle'): Conversation
    {
        $c = Conversation::create(['type' => 'group', 'title' => $title, 'created_by' => $creator->id]);
        ConversationParticipant::create(['conversation_id' => $c->id, 'user_id' => $creator->id]);
        foreach ($others as $o) {
            ConversationParticipant::create(['conversation_id' => $c->id, 'user_id' => $o->id]);
        }
        return $c;
    }

    /**
     * The route is throttled at 30 requests per minute per user.
     * We simulate 31 quick consecutive requests and expect the last one to be rejected (429).
     */
    public function test_user_search_throttle_triggers_after_limit()
    {
        $alice = User::factory()->create();
        $bob = User::factory()->create();
        $carol = User::factory()->create();
        $conv = $this->group($alice, [$bob, $carol]);

        $this->actingAs($alice);

        // First 30 should pass (200) even if empty results
        for ($i = 1; $i <= 30; $i++) {
            $r = $this->getJson("/messages/conversations/{$conv->id}/user-search?q=user");
            $r->assertStatus(200);
        }

        // 31st should be throttled
        $this->getJson("/messages/conversations/{$conv->id}/user-search?q=user")
            ->assertStatus(429);
    }
}

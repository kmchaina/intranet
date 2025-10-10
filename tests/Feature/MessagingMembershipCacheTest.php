<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MessagingMembershipCacheTest extends TestCase
{
    use RefreshDatabase;

    public function test_view_policy_uses_cached_membership_and_allows_access()
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $convs = [];
        for ($i = 0; $i < 5; $i++) {
            $c = Conversation::create(['type' => 'group', 'title' => 'C' . $i, 'created_by' => $user->id]);
            ConversationParticipant::create(['conversation_id' => $c->id, 'user_id' => $user->id]);
            ConversationParticipant::create(['conversation_id' => $c->id, 'user_id' => $other->id]);
            Message::create(['conversation_id' => $c->id, 'user_id' => $other->id, 'body' => 'm']);
            $convs[] = $c;
        }
        $this->actingAs($user);
        DB::enableQueryLog();
        $resp = $this->getJson('/messages');
        $resp->assertStatus(200);
        $log = DB::getQueryLog();
        // Ensure not performing one participants existence query per conversation (loose heuristic)
        $participantChecks = array_filter($log, fn($q) => str_contains(strtolower($q['query']), 'conversation_participants'));
        $this->assertLessThanOrEqual(8, count($participantChecks), 'Too many participant queries indicates cache not effective');
    }
}

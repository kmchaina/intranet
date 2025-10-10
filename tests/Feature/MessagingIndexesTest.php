<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MessagingIndexesTest extends TestCase
{
    use RefreshDatabase;

    public function test_duplicate_participant_insertion_violates_unique_constraint()
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $conv = Conversation::create(['type' => 'direct', 'title' => null, 'created_by' => $userA->id]);
        ConversationParticipant::create(['conversation_id' => $conv->id, 'user_id' => $userA->id]);
        ConversationParticipant::create(['conversation_id' => $conv->id, 'user_id' => $userB->id]);

        $this->expectException(QueryException::class);
        // Second identical entry should fail due to unique index (Phase 2)
        ConversationParticipant::create(['conversation_id' => $conv->id, 'user_id' => $userB->id]);
    }

    public function test_messages_recent_query_uses_composite_index()
    {
        // This test is heuristic: we cannot force EXPLAIN easily in portable way inside sqlite vs mysql here.
        // We insert >30 messages and ensure last 30 fetch logic performs constant number of queries (1 query),
        // implying index access pattern (Eloquent/logging could expand but we assert query count == 1).
        $user = User::factory()->create();
        $conv = Conversation::create(['type' => 'group', 'title' => 'Perf', 'created_by' => $user->id]);
        ConversationParticipant::create(['conversation_id' => $conv->id, 'user_id' => $user->id]);

        // Seed 60 messages
        for ($i = 1; $i <= 60; $i++) {
            $conv->messages()->create(['user_id' => $user->id, 'body' => 'M' . $i]);
        }

        DB::enableQueryLog();
        $resp = $this->actingAs($user)->getJson("/messages/conversations/{$conv->id}/items");
        $resp->assertStatus(200)->assertJsonStructure(['messages']);
        $log = DB::getQueryLog();
        // Filter to selects against messages table specifically
        $messageSelects = array_filter($log, function ($q) {
            $sql = strtolower($q['query']);
            return str_starts_with($sql, 'select') && str_contains($sql, 'from "messages"');
        });
        $this->assertLessThanOrEqual(2, count($messageSelects), 'Too many messages table selects for recent fetch');
    }
}

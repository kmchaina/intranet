<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Conversation;
use App\Models\ConversationParticipant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessagingParticipantsTest extends TestCase
{
    use RefreshDatabase;

    private function groupConv(User $creator, array $others = []): Conversation
    {
        $c = Conversation::create(['type' => 'group', 'title' => 'Team', 'created_by' => $creator->id]);
        ConversationParticipant::create(['conversation_id'=>$c->id,'user_id'=>$creator->id]);
        foreach ($others as $o) {
            ConversationParticipant::create(['conversation_id'=>$c->id,'user_id'=>$o->id]);
        }
        return $c;
    }

    private function directConv(User $a, User $b): Conversation
    {
        $c = Conversation::create(['type' => 'direct', 'created_by' => $a->id]);
        ConversationParticipant::create(['conversation_id'=>$c->id,'user_id'=>$a->id]);
        ConversationParticipant::create(['conversation_id'=>$c->id,'user_id'=>$b->id]);
        return $c;
    }

    public function test_list_participants()
    {
        $creator = User::factory()->create();
        $u1 = User::factory()->create();
        $conv = $this->groupConv($creator, [$u1]);
        $this->actingAs($creator);
        $this->getJson("/messages/conversations/{$conv->id}/participants")
            ->assertStatus(200)
            ->assertJsonCount(2, 'participants');
    }

    public function test_add_participants_authorized()
    {
        $creator = User::factory()->create();
        $new = User::factory()->create();
        $conv = $this->groupConv($creator);
        $this->actingAs($creator);
        $this->postJson("/messages/conversations/{$conv->id}/participants", ['user_ids' => [$new->id]])
            ->assertStatus(200)
            ->assertJsonFragment(['user_id' => $new->id]);
    }

    public function test_add_participants_rejects_direct()
    {
        $a = User::factory()->create();
        $b = User::factory()->create();
        $c = User::factory()->create();
        $conv = $this->directConv($a,$b);
        $this->actingAs($a);
        $this->postJson("/messages/conversations/{$conv->id}/participants", ['user_ids' => [$c->id]])
            ->assertStatus(422);
    }

    public function test_remove_participant()
    {
        $creator = User::factory()->create();
        $u1 = User::factory()->create();
        $conv = $this->groupConv($creator, [$u1]);
        $this->actingAs($creator);
        $this->deleteJson("/messages/conversations/{$conv->id}/participants/{$u1->id}")
            ->assertStatus(200)
            ->assertJsonMissing(['user_id' => $u1->id]);
    }

    public function test_cannot_remove_creator()
    {
        $creator = User::factory()->create();
        $u1 = User::factory()->create();
        $conv = $this->groupConv($creator, [$u1]);
        $this->actingAs($u1);
        // u1 is not creator; attempt to remove creator should fail due to policy (403) OR explicit rule if authorized
        $this->deleteJson("/messages/conversations/{$conv->id}/participants/{$creator->id}")
            ->assertStatus(403); // policy should block removeParticipant for non-creator
    }

    public function test_leave_conversation()
    {
        $creator = User::factory()->create();
        $u1 = User::factory()->create();
        $conv = $this->groupConv($creator, [$u1]);
        $this->actingAs($u1);
        $this->postJson("/messages/conversations/{$conv->id}/leave")
            ->assertStatus(200)
            ->assertJson(['ok' => true]);
    }

    public function test_creator_cannot_leave()
    {
        $creator = User::factory()->create();
        $u1 = User::factory()->create();
        $conv = $this->groupConv($creator, [$u1]);
        $this->actingAs($creator);
        $this->postJson("/messages/conversations/{$conv->id}/leave")
            ->assertStatus(422);
    }
}

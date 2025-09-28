<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Conversation;
use App\Models\ConversationParticipant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessagingRenameTest extends TestCase
{
    use RefreshDatabase;

    private function groupConversation(User $creator, array $others = [], string $title = 'Alpha'): Conversation
    {
        $c = Conversation::create(['type' => 'group', 'title' => $title, 'created_by' => $creator->id]);
        ConversationParticipant::create(['conversation_id' => $c->id, 'user_id' => $creator->id]);
        foreach ($others as $o) {
            ConversationParticipant::create(['conversation_id' => $c->id, 'user_id' => $o->id]);
        }
        return $c;
    }

    public function test_creator_can_rename_group()
    {
        $creator = User::factory()->create();
        $other = User::factory()->create();
        $conv = $this->groupConversation($creator, [$other]);
        $this->actingAs($creator);

        $resp = $this->patchJson("/messages/conversations/{$conv->id}/title", ['title' => 'Project X']);
        $resp->assertStatus(200)->assertJson(['title' => 'Project X']);
        $this->assertDatabaseHas('conversations', ['id' => $conv->id, 'title' => 'Project X']);
    }

    public function test_non_creator_cannot_rename_group()
    {
        $creator = User::factory()->create();
        $other = User::factory()->create();
        $conv = $this->groupConversation($creator, [$other]);
        $this->actingAs($other);

        $resp = $this->patchJson("/messages/conversations/{$conv->id}/title", ['title' => 'Hack']);
        $resp->assertStatus(403);
    }

    public function test_cannot_rename_direct()
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $conv = Conversation::create(['type' => 'direct', 'created_by' => $user->id]);
        ConversationParticipant::create(['conversation_id' => $conv->id, 'user_id' => $user->id]);
        ConversationParticipant::create(['conversation_id' => $conv->id, 'user_id' => $other->id]);

        $this->actingAs($user);
        $resp = $this->patchJson("/messages/conversations/{$conv->id}/title", ['title' => 'Nope']);
        $resp->assertStatus(422);
    }

    public function test_validation_title_required()
    {
        $creator = User::factory()->create();
        $conv = $this->groupConversation($creator);
        $this->actingAs($creator);
        $resp = $this->patchJson("/messages/conversations/{$conv->id}/title", ['title' => '']);
        $resp->assertStatus(422);
    }
}

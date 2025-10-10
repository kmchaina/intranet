<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Carbon;

class MessagingDeleteTest extends TestCase
{
    use RefreshDatabase;

    private function group(User $creator, array $others = [], string $title = 'Del'): Conversation
    {
        $c = Conversation::create(['type' => 'group', 'title' => $title, 'created_by' => $creator->id]);
        ConversationParticipant::create(['conversation_id' => $c->id, 'user_id' => $creator->id]);
        foreach ($others as $o) {
            ConversationParticipant::create(['conversation_id' => $c->id, 'user_id' => $o->id]);
        }
        return $c;
    }

    public function test_author_can_soft_delete_message()
    {
        $alice = User::factory()->create();
        $bob = User::factory()->create();
        $conv = $this->group($alice, [$bob]);
        $msg = Message::create(['conversation_id' => $conv->id, 'user_id' => $alice->id, 'body' => 'Temp']);
        $this->actingAs($alice);
        $resp = $this->deleteJson("/messages/conversations/{$conv->id}/items/{$msg->id}");
        $resp->assertStatus(200)->assertJson(['id' => $msg->id, 'deleted' => true]);
        $this->assertSoftDeleted('messages', ['id' => $msg->id]);
    }

    public function test_author_soft_delete_old_message()
    {
        $alice = User::factory()->create();
        $conv = $this->group($alice);
        $msg = Message::create(['conversation_id' => $conv->id, 'user_id' => $alice->id, 'body' => 'Old', 'created_at' => now()->subMinutes(60), 'updated_at' => now()->subMinutes(60)]);
        $this->actingAs($alice);
        $this->deleteJson("/messages/conversations/{$conv->id}/items/{$msg->id}")->assertStatus(200);
        $this->assertSoftDeleted('messages', ['id' => $msg->id]);
    }

    public function test_non_author_cannot_delete()
    {
        $alice = User::factory()->create();
        $bob = User::factory()->create();
        $conv = $this->group($alice, [$bob]);
        $msg = Message::create(['conversation_id' => $conv->id, 'user_id' => $alice->id, 'body' => 'Hey']);

        $this->actingAs($bob);
        $this->deleteJson("/messages/conversations/{$conv->id}/items/{$msg->id}")->assertStatus(403);
        $this->assertDatabaseHas('messages', ['id' => $msg->id]);
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessagingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Optionally seed roles or required data if needed
    }

    public function test_direct_conversation_created_and_reused()
    {
        $a = User::factory()->create();
        $b = User::factory()->create();

        $this->actingAs($a);

        $resp1 = $this->postJson('/messages/direct', ['user_id' => $b->id]);
        $resp1->assertStatus(200)->assertJsonStructure(['id']);
        $id1 = $resp1->json('id');

        $resp2 = $this->postJson('/messages/direct', ['user_id' => $b->id]);
        $resp2->assertStatus(200)->assertJson(['id' => $id1]);
    }

    public function test_group_conversation_creation_and_message_flow()
    {
        $creator = User::factory()->create();
        $u1 = User::factory()->create();
        $u2 = User::factory()->create();

        $this->actingAs($creator);
        $resp = $this->postJson('/messages/group', [
            'title' => 'Project Alpha',
            'participants' => [$u1->id, $u2->id]
        ]);
        $resp->assertStatus(201)->assertJsonStructure(['id']);
        $cid = $resp->json('id');

        // Post message
        $msgResp = $this->postJson("/messages/conversations/$cid/items", [ 'body' => 'Hello team' ]);
        $msgResp->assertStatus(201)->assertJsonStructure(['id','body','user_id']);

        // Fetch conversation messages
        $list = $this->getJson("/messages/conversations/$cid");
        $list->assertStatus(200)->assertJsonStructure(['conversation','messages']);
        $this->assertEquals('Hello team', $list->json('messages.0.body'));
    }

    public function test_unauthorized_user_cannot_view_or_post()
    {
        $creator = User::factory()->create();
        $outsider = User::factory()->create();
        $participant = User::factory()->create();

        $conv = Conversation::create(['type' => 'group', 'title' => 'Secret', 'created_by' => $creator->id]);
        ConversationParticipant::create(['conversation_id' => $conv->id, 'user_id' => $creator->id]);
        ConversationParticipant::create(['conversation_id' => $conv->id, 'user_id' => $participant->id]);

        $this->actingAs($outsider);
        $this->getJson("/messages/conversations/{$conv->id}")->assertStatus(403);
        $this->postJson("/messages/conversations/{$conv->id}/items", ['body' => 'Hack'])->assertStatus(403);
    }
}

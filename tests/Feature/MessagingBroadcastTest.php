<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use App\Events\MessageCreated;
use App\Events\MessageDeleted;
use Tests\TestCase;

class MessagingBroadcastTest extends TestCase
{
    use RefreshDatabase;

    public function test_message_created_and_deleted_events_dispatched()
    {
        Event::fake([MessageCreated::class, MessageDeleted::class]);
        $u = User::factory()->create();
        $v = User::factory()->create();
        $conv = Conversation::create(['type' => 'direct', 'created_by' => $u->id]);
        ConversationParticipant::create(['conversation_id' => $conv->id, 'user_id' => $u->id]);
        ConversationParticipant::create(['conversation_id' => $conv->id, 'user_id' => $v->id]);
        $this->actingAs($u);
        $create = $this->postJson("/messages/conversations/{$conv->id}/items", ['body' => 'Hello']);
        $create->assertStatus(201);
        $mid = $create->json('id');
        Event::assertDispatched(MessageCreated::class, fn($e) => $e->message->id === $mid);
        $del = $this->deleteJson("/messages/conversations/{$conv->id}/items/{$mid}");
        $del->assertStatus(200);
        Event::assertDispatched(MessageDeleted::class, fn($e) => $e->messageId === $mid);
    }
}

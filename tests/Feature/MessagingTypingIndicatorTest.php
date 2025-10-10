<?php

namespace Tests\Feature;

use App\Events\ConversationUserTyping;
use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class MessagingTypingIndicatorTest extends TestCase
{
    use RefreshDatabase;

    public function test_typing_event_dispatched_and_throttled()
    {
        Event::fake([ConversationUserTyping::class]);
        $u = User::factory()->create();
        $v = User::factory()->create();
        $conv = Conversation::create(['type' => 'direct', 'created_by' => $u->id]);
        ConversationParticipant::create(['conversation_id' => $conv->id, 'user_id' => $u->id]);
        ConversationParticipant::create(['conversation_id' => $conv->id, 'user_id' => $v->id]);
        $this->actingAs($u);
        $r1 = $this->postJson("/messages/conversations/{$conv->id}/typing")->assertStatus(200)->json();
        $this->assertEquals('ok', $r1['status']);
        // Second immediate call should skip due to throttle
        $r2 = $this->postJson("/messages/conversations/{$conv->id}/typing")->assertStatus(200)->json();
        $this->assertEquals('skipped', $r2['status']);
        Event::assertDispatched(ConversationUserTyping::class, 1);
    }
}

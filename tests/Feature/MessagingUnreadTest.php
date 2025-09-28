<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessagingUnreadTest extends TestCase
{
    use RefreshDatabase;

    private function group(User $creator, array $others = [], string $title = 'Unread'): Conversation
    {
        $c = Conversation::create(['type' => 'group', 'title' => $title, 'created_by' => $creator->id]);
        ConversationParticipant::create(['conversation_id' => $c->id, 'user_id' => $creator->id]);
        foreach ($others as $o) {
            ConversationParticipant::create(['conversation_id' => $c->id, 'user_id' => $o->id]);
        }
        return $c;
    }

    public function test_unread_count_increases_and_mark_read_resets()
    {
        $alice = User::factory()->create();
        $bob = User::factory()->create();
        $conv = $this->group($alice, [$bob]);

        // Bob sends 3 messages
        Message::create(['conversation_id' => $conv->id, 'user_id' => $bob->id, 'body' => 'Hi']);
        Message::create(['conversation_id' => $conv->id, 'user_id' => $bob->id, 'body' => 'Update']);
        Message::create(['conversation_id' => $conv->id, 'user_id' => $bob->id, 'body' => 'Ping']);

        $this->actingAs($alice);
        $list = $this->getJson('/messages')->assertStatus(200)->json();
        $entry = collect($list)->firstWhere('id', $conv->id);
        $this->assertEquals(3, $entry['unread']);

        // Mark read
        $this->postJson("/messages/conversations/{$conv->id}/mark-read")->assertStatus(200);
        $list2 = $this->getJson('/messages')->assertStatus(200)->json();
        $entry2 = collect($list2)->firstWhere('id', $conv->id);
        $this->assertEquals(0, $entry2['unread']);

        // Bob sends another
        Message::create(['conversation_id' => $conv->id, 'user_id' => $bob->id, 'body' => 'Later']);
        $list3 = $this->getJson('/messages')->assertStatus(200)->json();
        $entry3 = collect($list3)->firstWhere('id', $conv->id);
        $this->assertEquals(1, $entry3['unread']);
    }
}

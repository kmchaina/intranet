<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MessagingAttachmentTokenTest extends TestCase
{
    use RefreshDatabase;

    private function conv(User $a, User $b): Conversation
    {
        $c = Conversation::create(['type' => 'direct', 'title' => null, 'created_by' => $a->id]);
        ConversationParticipant::create(['conversation_id' => $c->id, 'user_id' => $a->id]);
        ConversationParticipant::create(['conversation_id' => $c->id, 'user_id' => $b->id]);
        return $c;
    }

    public function test_upload_returns_tokens_and_store_consumes_them()
    {
        Storage::fake('public');
        $u1 = User::factory()->create();
        $u2 = User::factory()->create();
        $conv = $this->conv($u1, $u2);
        $this->actingAs($u1);

        $upload = $this->postJson("/messages/conversations/{$conv->id}/attachments", [
            'files' => [UploadedFile::fake()->create('pic.png', 40, 'image/png')]
        ])->assertStatus(200)->assertJsonStructure(['tokens']);
        $token = $upload->json('tokens.0');

        // Send message using token
        $resp = $this->postJson("/messages/conversations/{$conv->id}/items", [
            'body' => 'With image',
            'tokens' => [$token]
        ])->assertStatus(201);
        $payload = $resp->json();
        if (!is_array($payload)) {
            fwrite(STDERR, "Raw response: " . $resp->getContent());
        }
        // Debug fallback: ensure we fail with context if structure changes
        $this->assertIsArray($payload, 'Message creation response not JSON object');
        $this->assertArrayHasKey('id', $payload, 'Message resource missing id');
        $this->assertArrayHasKey('attachments', $payload, 'Message resource missing attachments key: keys=' . implode(',', array_keys($payload)));
        $this->assertCount(1, $payload['attachments']);
        $this->assertEquals('pic.png', $payload['attachments'][0]['name']);

        // Reuse should silently ignore (token consumed) -> no duplicate attachment
        $resp2 = $this->postJson("/messages/conversations/{$conv->id}/items", [
            'body' => 'Second',
            'tokens' => [$token]
        ])->assertStatus(201);
        $payload2 = $resp2->json();
        $this->assertArrayHasKey('attachments', $payload2);
        $this->assertCount(0, $payload2['attachments']);
    }

    public function test_cannot_use_tokens_of_other_user()
    {
        Storage::fake('public');
        $u1 = User::factory()->create();
        $u2 = User::factory()->create();
        $conv = $this->conv($u1, $u2);
        $this->actingAs($u1);
        $upload = $this->postJson("/messages/conversations/{$conv->id}/attachments", [
            'files' => [UploadedFile::fake()->create('doc.pdf', 10, 'application/pdf')]
        ])->assertStatus(200);
        $token = $upload->json('tokens.0');

        // Other user attempts to use token (should yield no attachments)
        $this->actingAs($u2);
        $resp = $this->postJson("/messages/conversations/{$conv->id}/items", [
            'body' => 'Trying foreign token',
            'tokens' => [$token]
        ])->assertStatus(201);
        $payload = $resp->json();
        $this->assertArrayHasKey('attachments', $payload);
        $this->assertCount(0, $payload['attachments']);
    }
}

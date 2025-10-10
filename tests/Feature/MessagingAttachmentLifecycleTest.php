<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\User;
use App\Models\MessageAttachment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MessagingAttachmentLifecycleTest extends TestCase
{
    use RefreshDatabase;

    public function test_message_attachment_persisted_and_orphan_cleanup()
    {
        Storage::fake('public');
        $u1 = User::factory()->create();
        $u2 = User::factory()->create();
        $conv = Conversation::create(['type' => 'direct', 'created_by' => $u1->id]);
        ConversationParticipant::create(['conversation_id' => $conv->id, 'user_id' => $u1->id]);
        ConversationParticipant::create(['conversation_id' => $conv->id, 'user_id' => $u2->id]);
        $this->actingAs($u1);

        // Upload to temp
        $upload = $this->postJson("/messages/conversations/{$conv->id}/attachments", [
            'files' => [UploadedFile::fake()->create('alpha.png', 30, 'image/png')]
        ])->assertStatus(200);
        $token = $upload->json('tokens.0');

        // Send message linking token
        $resp = $this->postJson("/messages/conversations/{$conv->id}/items", [
            'body' => 'See image',
            'tokens' => [$token]
        ])->assertStatus(201);
        $mid = $resp->json('id');
        $this->assertDatabaseHas('message_attachments', ['message_id' => $mid]);

        // Create manual orphan (simulate failure to link) older than threshold
        $orphan = MessageAttachment::create([
            'message_id' => null,
            'user_id' => $u1->id,
            'original_name' => 'stale.txt',
            'path' => 'chat/' . date('Y') . '/' . date('m') . '/stale.txt',
            'mime' => 'text/plain',
            'size' => 10,
            'ext' => 'txt',
            'kind' => 'other',
            'linked_at' => null,
        ]);
        // Backdate creation time via direct DB update for purge
        DB::table('message_attachments')->where('id', $orphan->id)->update(['created_at' => now()->subHours(7)]);

        Artisan::call('messaging:purge-orphan-attachments', ['--older' => 6]);
        $this->assertDatabaseMissing('message_attachments', ['id' => $orphan->id]);
    }
}

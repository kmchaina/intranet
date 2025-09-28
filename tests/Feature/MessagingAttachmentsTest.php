<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Conversation;
use App\Models\ConversationParticipant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MessagingAttachmentsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    private function conversationFor(User $user, array $others = []): Conversation
    {
        $conv = Conversation::create(['type' => 'group', 'title' => 'Files', 'created_by' => $user->id]);
        ConversationParticipant::create(['conversation_id' => $conv->id, 'user_id' => $user->id]);
        foreach ($others as $o) {
            ConversationParticipant::create(['conversation_id' => $conv->id, 'user_id' => $o->id]);
        }
        return $conv;
    }

    public function test_upload_valid_file_returns_metadata()
    {
        $user = User::factory()->create();
        $conv = $this->conversationFor($user);
        $this->actingAs($user);

        $resp = $this->postJson("/messages/conversations/{$conv->id}/attachments", [
            // Use generic file with allowed mime (jpeg) without needing GD
            'files' => [UploadedFile::fake()->create('photo.jpg', 100, 'image/jpeg')]
        ]);

        $resp->assertStatus(200)->assertJsonStructure(['attachments'=>[['name','url','size','mime','ext','kind']]]);
        Storage::disk('public')->assertExists('chat/'.date('Y').'/'.date('m')); // folder exists
    }

    public function test_upload_rejects_disallowed_mime()
    {
        $user = User::factory()->create();
        $conv = $this->conversationFor($user);
        $this->actingAs($user);

        $file = UploadedFile::fake()->create('script.sh', 2, 'text/x-shellscript');
        $resp = $this->postJson("/messages/conversations/{$conv->id}/attachments", [ 'files' => [$file] ]);
        $resp->assertStatus(422);
    }

    public function test_upload_rejects_oversize()
    {
        $user = User::factory()->create();
        $conv = $this->conversationFor($user);
        $this->actingAs($user);

    // 6MB file (exceeds 5MB limit) using generic create to avoid GD
    $file = UploadedFile::fake()->create('large.jpg', 6000, 'image/jpeg');
        $resp = $this->postJson("/messages/conversations/{$conv->id}/attachments", [ 'files' => [$file] ]);
        $resp->assertStatus(422);
    }

    public function test_cannot_upload_to_unrelated_conversation()
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $conv = Conversation::create(['type' => 'group', 'title' => 'Hidden', 'created_by' => $other->id]);
        ConversationParticipant::create(['conversation_id' => $conv->id, 'user_id' => $other->id]);

        $this->actingAs($user);
    $resp = $this->postJson("/messages/conversations/{$conv->id}/attachments", [ 'files' => [UploadedFile::fake()->create('p.jpg', 50, 'image/jpeg')] ]);
        $resp->assertStatus(403);
    }

    public function test_send_message_with_uploaded_attachments()
    {
        $user = User::factory()->create();
        $conv = $this->conversationFor($user);
        $this->actingAs($user);

        $upload = $this->postJson("/messages/conversations/{$conv->id}/attachments", [
            'files' => [UploadedFile::fake()->create('doc.png', 120, 'image/png')]
        ])->assertStatus(200);

        $attachmentMeta = $upload->json('attachments');
        $msg = $this->postJson("/messages/conversations/{$conv->id}/items", [
            'body' => 'See attached',
            'attachments' => $attachmentMeta,
        ]);
        $msg->assertStatus(201)->assertJsonStructure(['id','attachments']);
    }
}

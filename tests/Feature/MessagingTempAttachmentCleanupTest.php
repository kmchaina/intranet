<?php

namespace Tests\Feature;

use App\Models\TempMessageAttachment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MessagingTempAttachmentCleanupTest extends TestCase
{
    use RefreshDatabase;

    public function test_purge_command_removes_stale_unconsumed_files()
    {
        Storage::fake('public');
        $u = User::factory()->create();
        // Create two stale attachments (simulate past by manual created_at)
        $oldTime = now()->subHours(2);
        foreach (['a.jpg', 'b.png'] as $file) {
            $path = 'chat/' . date('Y') . '/' . date('m') . '/' . $file;
            Storage::disk('public')->put($path, 'data');
            TempMessageAttachment::create([
                'id' => strtolower(str()->ulid()),
                'user_id' => $u->id,
                'original_name' => $file,
                'path' => $path,
                'mime' => 'image/jpeg',
                'size' => 4,
                'ext' => 'jpg',
                'kind' => 'image',
                'created_at' => $oldTime,
            ]);
        }
        // Fresh (should survive)
        $freshPath = 'chat/' . date('Y') . '/' . date('m') . '/fresh.jpg';
        Storage::disk('public')->put($freshPath, 'data');
        $fresh = TempMessageAttachment::create([
            'id' => strtolower(str()->ulid()),
            'user_id' => $u->id,
            'original_name' => 'fresh.jpg',
            'path' => $freshPath,
            'mime' => 'image/jpeg',
            'size' => 4,
            'ext' => 'jpg',
            'kind' => 'image',
            'created_at' => now(),
        ]);

        // Run with shorter TTL override
        config(['messaging.temp_tokens.ttl_minutes' => 30]);

        $this->artisan('messaging:purge-temp-attachments')
            ->expectsOutputToContain('Purged')
            ->assertExitCode(0);

        $this->assertDatabaseCount('temp_message_attachments', 1);
        $this->assertDatabaseHas('temp_message_attachments', ['id' => $fresh->id]);
    }
}

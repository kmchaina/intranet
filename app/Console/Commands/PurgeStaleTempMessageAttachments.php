<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TempMessageAttachment;
use Illuminate\Support\Facades\Storage;

class PurgeStaleTempMessageAttachments extends Command
{
    protected $signature = 'messaging:purge-temp-attachments {--dry-run : List candidates without deleting} {--batch=500 : Max rows per iteration}';
    protected $description = 'Remove stale, unconsumed temporary message attachment tokens & files';

    public function handle(): int
    {
        $ttl = (int)config('messaging.temp_tokens.ttl_minutes', 60);
        $cutoff = now()->subMinutes($ttl);
        $batchSize = (int)$this->option('batch');
        $dry = $this->option('dry-run');
        $total = 0;
        $bytes = 0;
        $this->info("Purging temp attachments older than {$cutoff} (TTL {$ttl}m)" . ($dry ? ' [DRY RUN]' : ''));

        do {
            $rows = TempMessageAttachment::whereNull('consumed_at')
                ->where('created_at', '<', $cutoff)
                ->limit($batchSize)
                ->get();
            if ($rows->isEmpty()) break;
            foreach ($rows as $row) {
                $fullPath = $row->path;
                if (!$dry) {
                    if (str_starts_with($fullPath, 'chat/')) {
                        $size = Storage::disk('public')->exists($fullPath) ? Storage::disk('public')->size($fullPath) : 0;
                        try {
                            Storage::disk('public')->delete($fullPath);
                        } catch (\Throwable $e) {
                            $this->warn('Delete file failed: ' . $fullPath . ' ' . $e->getMessage());
                        }
                        $bytes += $size;
                    }
                    $row->delete();
                }
                $total++;
            }
        } while (true);

        $this->info(($dry ? 'Would purge' : 'Purged') . " {$total} temp attachments (" . number_format($bytes) . ' bytes)');
        return 0;
    }
}

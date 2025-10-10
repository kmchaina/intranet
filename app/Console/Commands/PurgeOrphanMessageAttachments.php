<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PurgeOrphanMessageAttachments extends Command
{
    protected $signature = 'messaging:purge-orphan-attachments {--dry-run} {--older=3}';
    protected $description = 'Delete files & rows for message attachments not linked to a message older than N hours';

    public function handle(): int
    {
        $hours = (int)$this->option('older');
        $cutoff = now()->subHours($hours);
        $orphans = DB::table('message_attachments')
            ->whereNull('message_id')
            ->where('created_at', '<', $cutoff)
            ->limit(500)
            ->get();
        if ($orphans->isEmpty()) {
            $this->info('No orphan attachments found.');
            return self::SUCCESS;
        }
        $this->info('Found ' . $orphans->count() . ' orphan(s) older than ' . $hours . 'h');
        $deleted = 0;
        foreach ($orphans as $row) {
            $path = 'public/' . $row->path; // stored with disk 'public'
            if (!$this->option('dry-run')) {
                Storage::delete($path);
                DB::table('message_attachments')->where('id', $row->id)->delete();
            }
            $deleted++;
        }
        $this->info(($this->option('dry-run') ? '[DRY RUN] ' : '') . 'Purged rows: ' . $deleted);
        return self::SUCCESS;
    }
}

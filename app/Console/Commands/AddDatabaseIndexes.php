<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Exception;

class AddDatabaseIndexes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:add-indexes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add performance indexes to database tables';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Adding performance indexes...');

        $indexes = [
            'CREATE INDEX IF NOT EXISTS idx_users_role ON users(role)' => 'users.role',
            'CREATE INDEX IF NOT EXISTS idx_users_email ON users(email)' => 'users.email',
            'CREATE INDEX IF NOT EXISTS idx_announcements_published ON announcements(published_at DESC)' => 'announcements.published_at',
            'CREATE INDEX IF NOT EXISTS idx_announcements_category ON announcements(category)' => 'announcements.category',
            'CREATE INDEX IF NOT EXISTS idx_centres_active ON centres(is_active)' => 'centres.is_active',
            'CREATE INDEX IF NOT EXISTS idx_departments_centre ON departments(centre_id, is_active)' => 'departments.centre_id',
            'CREATE INDEX IF NOT EXISTS idx_news_created ON news(created_at DESC)' => 'news.created_at',
            'CREATE INDEX IF NOT EXISTS idx_users_hierarchy ON users(headquarters_id, centre_id, station_id)' => 'users hierarchy fields',
        ];

        $successCount = 0;
        $errorCount = 0;

        foreach ($indexes as $sql => $description) {
            try {
                DB::statement($sql);
                $this->info("✅ Added index on {$description}");
                $successCount++;
            } catch (Exception $e) {
                $this->error("❌ Failed to add index on {$description}: " . $e->getMessage());
                $errorCount++;
            }
        }

        $this->info("\n📊 Summary:");
        $this->info("✅ Successful: {$successCount}");
        if ($errorCount > 0) {
            $this->warn("❌ Failed: {$errorCount}");
        }

        $this->info("\n🎉 Database optimization completed!");
        $this->info("Your queries should now be significantly faster!");

        return Command::SUCCESS;
    }
}

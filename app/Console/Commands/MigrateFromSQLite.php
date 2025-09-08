<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Exception;

class MigrateFromSQLite extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:from-sqlite {--backup} {--verify}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate data from SQLite to MySQL/PostgreSQL';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting migration from SQLite to ' . config('database.default'));

        if ($this->option('backup')) {
            $this->createBackup();
        }

        // First, create the database if it doesn't exist
        $this->createDatabase();

        // Connect to SQLite source
        $sqliteConnection = 'sqlite_source';
        config(['database.connections.sqlite_source' => [
            'driver' => 'sqlite',
            'database' => database_path('database.sqlite'),
        ]]);

        // Run fresh migrations on MySQL first
        $this->info('🔧 Running fresh migrations on MySQL...');
        $this->call('migrate:fresh', ['--force' => true]);

        $tables = [
            'users', 'headquarters', 'centres', 'stations', 'departments',
            'announcements', 'announcement_reads', 'announcement_attachments',
            'documents', 'events', 'event_rsvps', 'password_vaults',
            'todo_lists', 'training_videos', 'system_links', 'feedback',
            'news', 'news_comments', 'news_likes', 'polls', 'poll_responses'
        ];

        foreach ($tables as $table) {
            if ($this->tableExists($table, $sqliteConnection)) {
                $this->migrateTable($table, $sqliteConnection);
            } else {
                $this->warn("⚠️  Table {$table} not found in SQLite database");
            }
        }

        if ($this->option('verify')) {
            $this->verifyMigration($tables, $sqliteConnection);
        }

        $this->info('✅ Migration completed successfully!');
        $this->info('🎉 Your system is now running on MySQL with improved performance!');
    }

    private function createDatabase()
    {
        try {
            $database = config('database.connections.mysql.database');
            
            // Connect without specifying database
            $connection = config('database.connections.mysql');
            unset($connection['database']);
            config(['database.connections.temp_mysql' => $connection]);
            
            // Create database
            DB::connection('temp_mysql')->statement("CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->info("✅ Database '{$database}' created successfully");
            
        } catch (Exception $e) {
            $this->error("❌ Failed to create database: " . $e->getMessage());
            return false;
        }
        
        return true;
    }

    private function createBackup()
    {
        $this->info('💾 Creating backup...');
        $backupPath = storage_path('backups/sqlite_backup_' . date('Y-m-d_H-i-s') . '.sqlite');
        
        if (!is_dir(dirname($backupPath))) {
            mkdir(dirname($backupPath), 0755, true);
        }
        
        copy(database_path('database.sqlite'), $backupPath);
        $this->info("✅ Backup created: {$backupPath}");
    }

    private function tableExists($table, $connection)
    {
        try {
            return Schema::connection($connection)->hasTable($table);
        } catch (Exception $e) {
            return false;
        }
    }

    private function migrateTable($table, $sourceConnection)
    {
        $this->info("📋 Migrating table: {$table}");

        try {
            // Get data from SQLite
            $data = DB::connection($sourceConnection)->table($table)->get();
            $totalRecords = $data->count();

            if ($totalRecords === 0) {
                $this->warn("⚠️  No data found in table: {$table}");
                return;
            }

            // Insert data in chunks to avoid memory issues
            $chunkSize = 500;
            $chunks = $data->chunk($chunkSize);
            $processedRecords = 0;

            // Disable foreign key checks temporarily
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            foreach ($chunks as $chunk) {
                $records = $chunk->map(function ($record) {
                    return (array) $record;
                })->toArray();

                DB::table($table)->insert($records);
                $processedRecords += count($records);
                
                $this->info("   📊 Processed {$processedRecords}/{$totalRecords} records for {$table}");
            }

            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            $this->info("✅ Completed migration for table: {$table}");

        } catch (Exception $e) {
            $this->error("❌ Error migrating table {$table}: " . $e->getMessage());
        }
    }

    private function verifyMigration($tables, $sourceConnection)
    {
        $this->info('🔍 Verifying migration...');
        
        $totalErrors = 0;
        
        foreach ($tables as $table) {
            if (!$this->tableExists($table, $sourceConnection)) {
                continue;
            }

            try {
                $sourceCount = DB::connection($sourceConnection)->table($table)->count();
                $targetCount = DB::table($table)->count();

                if ($sourceCount === $targetCount) {
                    $this->info("✅ {$table}: {$sourceCount} records migrated successfully");
                } else {
                    $this->error("❌ {$table}: Source({$sourceCount}) != Target({$targetCount})");
                    $totalErrors++;
                }
            } catch (Exception $e) {
                $this->error("❌ Error verifying {$table}: " . $e->getMessage());
                $totalErrors++;
            }
        }

        if ($totalErrors === 0) {
            $this->info('🎉 All tables verified successfully!');
        } else {
            $this->warn("⚠️  {$totalErrors} tables had verification issues");
        }
    }
}

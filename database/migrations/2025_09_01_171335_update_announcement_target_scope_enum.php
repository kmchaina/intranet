<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For SQLite, we need to recreate the table to modify enum
        DB::statement("
            CREATE TABLE announcements_new (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title VARCHAR NOT NULL,
                content TEXT NOT NULL,
                category VARCHAR DEFAULT 'general',
                priority VARCHAR DEFAULT 'medium',
                created_by INTEGER NOT NULL,
                target_scope VARCHAR DEFAULT 'all' CHECK (target_scope IN (
                    'all',                  -- All NIMR staff
                    'headquarters',         -- HQ staff only
                    'my_centre',           -- My centre only
                    'my_centre_stations',  -- My centre + its stations
                    'my_station',          -- My station only
                    'all_centres',         -- All centres (no stations)
                    'all_stations',        -- All stations (no centres)
                    'specific'             -- Custom selection
                )),
                target_centres TEXT,
                target_stations TEXT,
                published_at DATETIME,
                expires_at DATETIME,
                is_published BOOLEAN DEFAULT 0,
                email_notification BOOLEAN DEFAULT 0,
                views_count INTEGER DEFAULT 0,
                created_at DATETIME,
                updated_at DATETIME,
                FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
            )
        ");

        // Copy existing data
        DB::statement("
            INSERT INTO announcements_new 
            SELECT * FROM announcements
        ");

        // Drop old table and rename new one
        DB::statement("DROP TABLE announcements");
        DB::statement("ALTER TABLE announcements_new RENAME TO announcements");

        // Recreate indexes
        DB::statement("CREATE INDEX idx_announcements_published ON announcements(is_published, published_at)");
        DB::statement("CREATE INDEX idx_announcements_target_scope ON announcements(target_scope)");
        DB::statement("CREATE INDEX idx_announcements_category_priority ON announcements(category, priority)");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("
            CREATE TABLE announcements_old (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title VARCHAR NOT NULL,
                content TEXT NOT NULL,
                category VARCHAR DEFAULT 'general',
                priority VARCHAR DEFAULT 'medium',
                created_by INTEGER NOT NULL,
                target_scope VARCHAR DEFAULT 'all' CHECK (target_scope IN (
                    'all', 'headquarters', 'centres', 'stations', 'specific'
                )),
                target_centres TEXT,
                target_stations TEXT,
                published_at DATETIME,
                expires_at DATETIME,
                is_published BOOLEAN DEFAULT 0,
                email_notification BOOLEAN DEFAULT 0,
                views_count INTEGER DEFAULT 0,
                created_at DATETIME,
                updated_at DATETIME,
                FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
            )
        ");

        DB::statement("
            INSERT INTO announcements_old 
            SELECT * FROM announcements
        ");

        DB::statement("DROP TABLE announcements");
        DB::statement("ALTER TABLE announcements_old RENAME TO announcements");
    }
};

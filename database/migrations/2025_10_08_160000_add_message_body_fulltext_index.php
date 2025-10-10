<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Attempt to add fulltext index if MySQL; otherwise skip (SQLite used in tests will ignore)
        if (Schema::hasTable('messages')) {
            try {
                Schema::table('messages', function (Blueprint $table) {
                    // Using plain index first (SQLite fallback). In MySQL production you can alter to FULLTEXT.
                    $table->index('body', 'idx_messages_body');
                });
            } catch (Throwable $e) {
                // Silently ignore if not supported (e.g., existing or SQLite limitations)
            }
        }
    }
    public function down(): void
    {
        if (Schema::hasTable('messages')) {
            try {
                Schema::table('messages', function (Blueprint $table) {
                    $table->dropIndex('idx_messages_body');
                });
            } catch (Throwable $e) {
                // ignore
            }
        }
    }
};

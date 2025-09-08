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
        Schema::table('announcements', function (Blueprint $table) {
            // Add status column with default value
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft')->after('priority');
        });

        // Update existing records: set status based on is_published
        DB::table('announcements')->where('is_published', true)->update(['status' => 'published']);
        DB::table('announcements')->where('is_published', false)->update(['status' => 'draft']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};

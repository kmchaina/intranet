<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing data first
        DB::table('feedback')->where('status', 'under_review')->update(['status' => 'reviewed']);
        DB::table('feedback')->where('status', 'in_progress')->update(['status' => 'reviewed']);
        DB::table('feedback')->where('status', 'resolved')->update(['status' => 'implemented']);
        DB::table('feedback')->where('type', 'bug_report')->update(['type' => 'suggestion']);
        DB::table('feedback')->where('type', 'complaint')->update(['type' => 'suggestion']);

        // Add new columns first
        Schema::table('feedback', function (Blueprint $table) {
            $table->boolean('is_public')->default(false)->after('is_anonymous');
            $table->integer('upvotes_count')->default(0)->after('is_public');
            $table->text('admin_notes')->nullable()->after('admin_response');
        });

        // For SQLite, we need to recreate the table to drop columns properly
        // We'll just leave the old columns for now (they won't be used)
        // In production MySQL, you can drop them safely
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feedback', function (Blueprint $table) {
            // Restore helpdesk columns
            $table->string('priority')->default('medium')->after('message');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null')->after('submitted_by');
            $table->timestamp('responded_at')->nullable()->after('admin_response');
            $table->timestamp('resolved_at')->nullable()->after('responded_at');

            // Remove suggestion box features
            $table->dropColumn(['is_public', 'upvotes_count', 'admin_notes']);

            // Drop index
            $table->dropIndex(['is_public', 'upvotes_count']);
        });
    }
};

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
        Schema::create('todo_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Task Information
            $table->string('title');
            $table->text('description')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();

            // Organization
            $table->string('category')->default('personal'); // work, personal, research, admin
            $table->string('project')->nullable(); // User-defined projects
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->string('status')->default('todo'); // todo, in_progress, blocked, completed

            // Scheduling
            $table->date('due_date')->nullable();
            $table->time('due_time')->nullable();
            $table->boolean('all_day')->default(true);
            $table->json('reminder_times')->nullable(); // Array of reminder timestamps

            // Progress & Estimation
            $table->integer('estimated_hours')->nullable();
            $table->integer('actual_hours')->nullable();
            $table->integer('progress_percentage')->default(0); // 0-100

            // Collaboration
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('is_shared')->default(false);
            $table->json('shared_with')->nullable(); // Array of user IDs
            $table->json('watchers')->nullable(); // Users watching this task

            // Dependencies & Relationships
            $table->foreignId('parent_task_id')->nullable()->constrained('todo_lists')->onDelete('cascade');
            $table->json('depends_on')->nullable(); // Array of task IDs that must be completed first
            $table->integer('sort_order')->default(0);

            // Metadata
            $table->json('tags')->nullable(); // Array of tags
            $table->json('custom_properties')->nullable(); // Notion-style custom properties
            $table->string('color')->nullable(); // Hex color for visual organization
            $table->text('notes')->nullable(); // Additional notes

            // Tracking
            $table->timestamp('started_at')->nullable();
            $table->timestamp('last_activity_at')->nullable();
            $table->integer('view_count')->default(0);

            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'is_completed']);
            $table->index(['user_id', 'category']);
            $table->index(['user_id', 'priority']);
            $table->index(['user_id', 'due_date']);
            $table->index(['assigned_to', 'is_completed']);
            $table->index(['parent_task_id']);
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('todo_lists');
    }
};

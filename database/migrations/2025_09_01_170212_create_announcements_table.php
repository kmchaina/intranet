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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->string('category')->default('general'); // general, urgent, info, event
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');

            // Author information
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');

            // Targeting system - hierarchy aware
            $table->enum('target_scope', ['all', 'headquarters', 'centres', 'stations', 'specific'])->default('all');

            // Specific targeting (JSON arrays of IDs)
            $table->json('target_centres')->nullable(); // Array of centre IDs
            $table->json('target_stations')->nullable(); // Array of station IDs

            // Publishing control
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_published')->default(false);
            $table->boolean('email_notification')->default(false);

            // Engagement tracking
            $table->integer('views_count')->default(0);

            $table->timestamps();

            // Indexes for performance
            $table->index(['is_published', 'published_at']);
            $table->index(['target_scope']);
            $table->index(['category', 'priority']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};

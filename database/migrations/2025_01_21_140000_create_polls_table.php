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
        Schema::create('polls', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['single_choice', 'multiple_choice', 'rating', 'yes_no']);
            $table->json('options')->nullable(); // For choice-based polls
            $table->integer('max_rating')->nullable(); // For rating polls (e.g., 1-5, 1-10)
            $table->boolean('anonymous')->default(false);
            $table->boolean('show_results')->default(true);
            $table->boolean('allow_comments')->default(false);
            $table->enum('visibility', ['public', 'department', 'custom'])->default('public');
            $table->json('visible_to')->nullable(); // Department IDs or user IDs for custom visibility
            $table->enum('status', ['draft', 'active', 'closed', 'archived'])->default('draft');
            $table->datetime('starts_at')->nullable();
            $table->datetime('ends_at')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->index(['status', 'starts_at', 'ends_at']);
            $table->index(['visibility', 'created_by']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('polls');
    }
};

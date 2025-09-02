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
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('suggestion'); // suggestion, bug_report, feature_request, complaint, compliment
            $table->string('category')->default('general'); // system, hr, technical, process, other
            $table->string('subject');
            $table->text('message');
            $table->string('priority')->default('medium'); // low, medium, high, urgent
            $table->string('status')->default('pending'); // pending, under_review, in_progress, resolved, closed
            $table->boolean('is_anonymous')->default(false);
            $table->foreignId('submitted_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->text('admin_response')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->json('attachments')->nullable(); // Store file paths as JSON
            $table->timestamps();

            $table->index(['status', 'priority']);
            $table->index(['type', 'category']);
            $table->index(['submitted_by', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};

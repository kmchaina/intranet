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
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer');
            $table->enum('category', ['general', 'hr', 'it', 'procedures', 'systems', 'finance', 'research']);
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->integer('view_count')->default(0);
            $table->integer('helpful_count')->default(0);
            $table->integer('not_helpful_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('keywords')->nullable(); // For better search
            $table->integer('order_position')->nullable(); // For manual ordering
            $table->timestamps();

            // Indexes for better performance
            $table->index(['status', 'category']);
            $table->index(['view_count']);
            $table->index(['is_featured', 'status']);
            // Note: Fulltext indexes not supported in SQLite, using regular indexes
            $table->index(['question']);
            $table->index(['keywords']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};

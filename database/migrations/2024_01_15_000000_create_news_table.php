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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->text('excerpt')->nullable();
            $table->string('featured_image')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->enum('priority', ['low', 'normal', 'high'])->default('normal');
            $table->json('tags')->nullable();
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->string('location')->nullable(); // Centre/Station name
            $table->string('location_type')->nullable(); // 'centre', 'station', 'headquarters'
            $table->timestamp('published_at')->nullable();
            $table->integer('views_count')->default(0);
            $table->integer('likes_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('allow_comments')->default(true);
            $table->timestamps();

            $table->index(['status', 'published_at']);
            $table->index(['location', 'location_type']);
            $table->index(['is_featured', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};

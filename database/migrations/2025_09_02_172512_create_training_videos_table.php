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
        Schema::create('training_videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('video_url'); // YouTube, Vimeo, or local file path
            $table->string('video_type')->default('youtube'); // youtube, vimeo, local
            $table->string('thumbnail_url')->nullable();
            $table->string('category')->default('general'); // general, technical, hr, safety, etc.
            $table->integer('duration_minutes')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('target_audience')->default('all'); // all, hq, centre, station
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->integer('view_count')->default(0);
            $table->timestamps();

            $table->index(['category', 'is_active']);
            $table->index(['target_audience', 'is_featured']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_videos');
    }
};

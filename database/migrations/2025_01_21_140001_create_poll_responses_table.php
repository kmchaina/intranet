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
        Schema::create('poll_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poll_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // Null for anonymous responses
            $table->json('response_data'); // Stores the actual response (option IDs, rating value, etc.)
            $table->text('comment')->nullable();
            $table->string('ip_address', 45)->nullable(); // For anonymous tracking/validation
            $table->timestamps();

            // Prevent duplicate responses (except for anonymous polls where we use IP)
            $table->unique(['poll_id', 'user_id']);
            $table->index(['poll_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poll_responses');
    }
};

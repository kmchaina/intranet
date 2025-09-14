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
        Schema::create('user_favorite_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('system_link_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Ensure a user can only favorite a link once
            $table->unique(['user_id', 'system_link_id']);
            
            // Add indexes for better performance
            $table->index(['user_id']);
            $table->index(['system_link_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_favorite_links');
    }
};

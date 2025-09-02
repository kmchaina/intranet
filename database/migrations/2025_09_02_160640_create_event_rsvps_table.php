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
        Schema::create('event_rsvps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->enum('status', ['attending', 'not_attending', 'maybe', 'pending'])->default('pending');
            $table->text('notes')->nullable(); // Optional note from attendee
            $table->dateTime('responded_at')->nullable();
            $table->boolean('attended')->nullable(); // Track actual attendance (set after event)

            $table->timestamps();

            // Ensure one RSVP per user per event
            $table->unique(['event_id', 'user_id']);
            $table->index(['event_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_rsvps');
    }
};

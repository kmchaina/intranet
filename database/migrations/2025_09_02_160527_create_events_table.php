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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();

            // Event timing
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');
            $table->boolean('all_day')->default(false);

            // Location and venue
            $table->string('location')->nullable();
            $table->string('venue')->nullable();
            $table->text('venue_details')->nullable();

            // Event properties
            $table->enum('category', [
                'meeting',
                'training',
                'conference',
                'workshop',
                'seminar',
                'fieldwork',
                'social',
                'other'
            ])->default('meeting');

            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('status', ['draft', 'published', 'cancelled', 'completed'])->default('published');

            // Recurrence
            $table->boolean('is_recurring')->default(false);
            $table->enum('recurrence_type', ['daily', 'weekly', 'monthly', 'yearly'])->nullable();
            $table->integer('recurrence_interval')->default(1); // Every X days/weeks/months
            $table->date('recurrence_end_date')->nullable();
            $table->json('recurrence_days')->nullable(); // For weekly: [1,3,5] for Mon, Wed, Fri

            // Attendance and RSVP
            $table->boolean('requires_rsvp')->default(false);
            $table->integer('max_attendees')->nullable();
            $table->dateTime('rsvp_deadline')->nullable();

            // Visibility and targeting (similar to announcements)
            $table->enum('visibility_scope', [
                'all',           // All NIMR staff
                'headquarters',  // HQ only
                'centres',       // All centres
                'stations',      // All stations
                'my_centre',     // Creator's centre only
                'my_station',    // Creator's station only
                'specific'       // Custom selection
            ])->default('all');

            $table->json('target_centres')->nullable();
            $table->json('target_stations')->nullable();

            // Creator and timestamps
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            // Indexes
            $table->index(['start_datetime', 'end_datetime']);
            $table->index(['visibility_scope', 'status']);
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};

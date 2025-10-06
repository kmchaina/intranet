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
        Schema::create('birthday_wishes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->enum('celebration_type', ['birthday', 'work_anniversary'])->default('birthday');
            $table->text('message');
            $table->boolean('is_public')->default(true); // Can others see this wish?
            $table->timestamps();

            // Indexes for performance
            $table->index(['recipient_id', 'created_at']);
            $table->index(['recipient_id', 'celebration_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('birthday_wishes');
    }
};


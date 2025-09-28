<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('conversation_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            // last_read_message_id added after messages table exists (nullable FK)
            $table->unsignedBigInteger('last_read_message_id')->nullable()->index();
            $table->timestamp('joined_at')->useCurrent();
            $table->unique(['conversation_id','user_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('conversation_participants');
    }
};

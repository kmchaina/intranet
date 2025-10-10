<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('message_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('original_name', 255);
            $table->string('path', 255);
            $table->string('mime', 120);
            $table->unsignedBigInteger('size');
            $table->string('ext', 40)->nullable();
            $table->string('kind', 40)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('linked_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->index(['message_id']);
            $table->index(['user_id']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('message_attachments');
    }
};

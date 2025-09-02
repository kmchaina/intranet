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
        Schema::create('announcement_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('announcement_id')->constrained()->onDelete('cascade');
            $table->string('original_name'); // Original filename
            $table->string('file_name'); // Stored filename
            $table->string('file_path'); // Storage path
            $table->string('mime_type');
            $table->bigInteger('file_size'); // Size in bytes
            $table->timestamps();

            // Index for performance
            $table->index('announcement_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcement_attachments');
    }
};

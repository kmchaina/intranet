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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_name');
            $table->string('original_name');
            $table->string('file_path');
            $table->string('mime_type');
            $table->unsignedBigInteger('file_size');

            // Versioning
            $table->integer('version')->default(1);
            $table->unsignedBigInteger('parent_document_id')->nullable(); // For versioning

            // Organization hierarchy targeting
            $table->enum('visibility_scope', ['all', 'headquarters', 'centres', 'stations', 'specific'])->default('all');
            $table->json('target_centres')->nullable(); // Array of centre IDs
            $table->json('target_stations')->nullable(); // Array of station IDs

            // Categories and tags
            $table->string('category')->default('general'); // general, policy, research, administrative, training
            $table->json('tags')->nullable(); // Array of tags

            // Access control
            $table->enum('access_level', ['public', 'restricted', 'confidential'])->default('public');
            $table->boolean('requires_download_permission')->default(false);

            // User relationships
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('last_accessed_at')->nullable();
            $table->unsignedInteger('download_count')->default(0);

            // Status
            $table->boolean('is_active')->default(true);
            $table->timestamp('expires_at')->nullable();

            $table->timestamps();

            // Indexes for performance
            $table->index(['category', 'is_active']);
            $table->index(['visibility_scope', 'is_active']);
            $table->index(['uploaded_by', 'created_at']);
            $table->index('parent_document_id');

            // Foreign key constraint for versioning
            $table->foreign('parent_document_id')->references('id')->on('documents')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};

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
        Schema::create('password_vaults', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Basic Information
            $table->string('title');
            $table->string('website_url')->nullable();
            $table->string('username')->nullable();
            $table->text('encrypted_password'); // Encrypted password
            $table->text('notes')->nullable();

            // Categorization
            $table->string('category')->default('general'); // work, personal, social, banking, etc.
            $table->string('folder')->nullable(); // User-defined folders
            $table->boolean('is_favorite')->default(false);

            // Security & Metadata
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('password_changed_at')->nullable();
            $table->integer('password_strength')->nullable(); // 1-5 scale
            $table->boolean('requires_2fa')->default(false);
            $table->text('encrypted_2fa_secret')->nullable(); // For TOTP secrets

            // Sharing (optional for team passwords)
            $table->boolean('is_shared')->default(false);
            $table->json('shared_with')->nullable(); // Array of user IDs
            $table->enum('share_permission', ['view', 'edit'])->default('view');

            // Additional fields
            $table->json('custom_fields')->nullable(); // For additional custom data
            $table->string('icon')->nullable(); // Font Awesome icon or URL
            $table->integer('login_count')->default(0);

            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'category']);
            $table->index(['user_id', 'is_favorite']);
            $table->index(['user_id', 'folder']);
            $table->index('last_used_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_vaults');
    }
};

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
        Schema::create('faq_suggestions', function (Blueprint $table) {
            $table->id();
            $table->string('suggested_question');
            $table->text('context')->nullable(); // Additional context from user
            $table->enum('category', ['general', 'hr', 'it', 'procedures', 'systems', 'finance', 'research']);
            $table->enum('status', ['pending', 'approved', 'rejected', 'converted'])->default('pending');
            $table->foreignId('suggested_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('converted_to_faq_id')->nullable()->constrained('faqs')->onDelete('set null');
            $table->text('admin_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'category']);
            $table->index(['suggested_by']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faq_suggestions');
    }
};

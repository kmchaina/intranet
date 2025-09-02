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
        Schema::create('system_links', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('url');
            $table->string('icon')->nullable(); // Font Awesome class or emoji
            $table->string('category')->default('general'); // hr, finance, research, technical, external
            $table->string('color_scheme')->default('blue'); // blue, green, red, purple, yellow
            $table->boolean('opens_new_tab')->default(true);
            $table->boolean('requires_vpn')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->string('access_level')->default('all'); // all, admin, hq, centre, station
            $table->integer('click_count')->default(0);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->index(['category', 'is_active']);
            $table->index(['access_level', 'is_featured']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_links');
    }
};

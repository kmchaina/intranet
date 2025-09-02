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
        // Create headquarters table
        Schema::create('headquarters', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('NIMR Headquarters');
            $table->string('code')->unique()->default('HQ');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Add headquarters_id to centres table
        Schema::table('centres', function (Blueprint $table) {
            $table->foreignId('headquarters_id')->nullable()->constrained('headquarters')->onDelete('cascade');
        });

        // Update departments table to support multiple parent types
        Schema::table('departments', function (Blueprint $table) {
            // Make station_id nullable since departments can belong to centres or headquarters too
            $table->foreignId('station_id')->nullable()->change();

            // Add optional relationships to centres and headquarters
            $table->foreignId('centre_id')->nullable()->constrained('centres')->onDelete('cascade');
            $table->foreignId('headquarters_id')->nullable()->constrained('headquarters')->onDelete('cascade');
        });

        // Add headquarters_id to users table
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('headquarters_id')->nullable()->constrained('headquarters')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove foreign keys and columns from users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['headquarters_id']);
            $table->dropColumn('headquarters_id');
        });

        // Remove foreign keys and columns from departments table
        Schema::table('departments', function (Blueprint $table) {
            $table->dropForeign(['centre_id']);
            $table->dropForeign(['headquarters_id']);
            $table->dropColumn(['centre_id', 'headquarters_id']);

            // Make station_id required again
            $table->foreignId('station_id')->nullable(false)->change();
        });

        // Remove foreign key from centres table
        Schema::table('centres', function (Blueprint $table) {
            $table->dropForeign(['headquarters_id']);
            $table->dropColumn('headquarters_id');
        });

        // Drop headquarters table
        Schema::dropIfExists('headquarters');
    }
};

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
        Schema::table('messages', function (Blueprint $table) {
            $table->boolean('is_pinned')->default(false)->after('body');
            $table->string('voice_path')->nullable()->after('is_pinned');
            $table->integer('voice_duration')->nullable()->after('voice_path'); // in seconds
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn(['is_pinned', 'voice_path', 'voice_duration']);
        });
    }
};

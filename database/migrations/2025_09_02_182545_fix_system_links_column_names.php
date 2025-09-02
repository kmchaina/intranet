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
        Schema::table('system_links', function (Blueprint $table) {
            $table->renameColumn('name', 'title');
            $table->renameColumn('created_by', 'added_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('system_links', function (Blueprint $table) {
            $table->renameColumn('title', 'name');
            $table->renameColumn('added_by', 'created_by');
        });
    }
};

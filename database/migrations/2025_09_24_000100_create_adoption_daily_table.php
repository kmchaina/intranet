<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('adoption_daily', function (Blueprint $table) {
            $table->date('date')->primary();
            $table->unsignedInteger('dau')->default(0);
            $table->unsignedInteger('wau')->default(0);
            $table->unsignedInteger('announcement_reads')->default(0);
            $table->unsignedInteger('document_views')->default(0);
            $table->unsignedInteger('document_downloads')->default(0);
            $table->unsignedInteger('poll_views')->default(0);
            $table->unsignedInteger('poll_responses')->default(0);
            $table->unsignedInteger('vault_accesses')->default(0);
            $table->unsignedInteger('vault_views')->default(0);
            $table->unsignedInteger('new_user_activation')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('adoption_daily');
    }
};

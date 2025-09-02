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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('centre_id')->nullable()->constrained('centres')->onDelete('set null');
            $table->foreignId('station_id')->nullable()->constrained('stations')->onDelete('set null');
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null');
            $table->string('employee_id')->nullable()->unique();
            $table->string('job_title')->nullable();
            $table->string('phone')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['centre_id']);
            $table->dropForeign(['station_id']);
            $table->dropForeign(['department_id']);
            $table->dropColumn(['centre_id', 'station_id', 'department_id', 'employee_id', 'job_title', 'phone']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('adoption_daily', function (Blueprint $table) {
            $table->unsignedInteger('eligible_users')->default(0)->after('new_user_activation');
            $table->unsignedInteger('mau')->default(0)->after('eligible_users');
            $table->unsignedInteger('wau_prev_week')->default(0)->after('mau');
            $table->decimal('coverage_pct',5,2)->default(0)->after('wau_prev_week');
            $table->decimal('stickiness_pct',5,2)->default(0)->after('coverage_pct');
            $table->decimal('activation_rate_pct',5,2)->default(0)->after('stickiness_pct');
            $table->decimal('top_feature_pct',5,2)->default(0)->after('activation_rate_pct');
        });
    }

    public function down(): void
    {
        Schema::table('adoption_daily', function (Blueprint $table) {
            $table->dropColumn([
                'eligible_users','mau','wau_prev_week','coverage_pct','stickiness_pct','activation_rate_pct','top_feature_pct'
            ]);
        });
    }
};

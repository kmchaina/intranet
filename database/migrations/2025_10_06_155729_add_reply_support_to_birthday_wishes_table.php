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
        Schema::table('birthday_wishes', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_wish_id')->nullable()->after('recipient_id');
            $table->json('reactions')->nullable()->after('is_public');
            $table->integer('reply_count')->default(0)->after('reactions');

            $table->foreign('parent_wish_id')->references('id')->on('birthday_wishes')->onDelete('cascade');
            $table->index(['parent_wish_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('birthday_wishes', function (Blueprint $table) {
            $table->dropForeign(['parent_wish_id']);
            $table->dropIndex(['parent_wish_id', 'created_at']);
            $table->dropColumn(['parent_wish_id', 'reactions', 'reply_count']);
        });
    }
};

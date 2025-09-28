<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('category')->nullable();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->unsignedInteger('threshold')->default(0); // generic numeric threshold
            $table->string('metric')->nullable(); // event_type or custom key
            $table->boolean('repeatable')->default(false);
            $table->timestamps();
        });

        Schema::create('user_badges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('badge_id')->constrained('badges')->cascadeOnDelete();
            $table->unsignedInteger('progress')->default(0);
            $table->unsignedInteger('level')->default(1); // for repeatable tiers
            $table->timestamp('awarded_at')->nullable();
            $table->timestamps();
            $table->unique(['user_id','badge_id','level']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('user_badges');
        Schema::dropIfExists('badges');
    }
};

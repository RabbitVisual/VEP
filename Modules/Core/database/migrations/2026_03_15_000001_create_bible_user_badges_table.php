<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('bible_user_badges')) {
            return;
        }
        Schema::create('bible_user_badges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('badge_key');
            $table->foreignId('subscription_id')->nullable()->constrained('bible_plan_subscriptions')->nullOnDelete();
            $table->timestamp('awarded_at');
            $table->index(['user_id', 'awarded_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bible_user_badges');
    }
};

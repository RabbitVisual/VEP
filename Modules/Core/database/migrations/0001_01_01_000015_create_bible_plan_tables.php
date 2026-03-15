<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bible_plans', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('type')->default('manual'); // manual, sequential, chronological
            $table->string('reading_mode')->default('digital'); // digital, physical_timer
            $table->unsignedInteger('duration_days');
            $table->string('cover_image')->nullable();
            $table->boolean('allow_back_tracking')->default(false);
            $table->boolean('is_active')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('bible_plan_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained('bible_plans')->cascadeOnDelete();
            $table->date('start_date');
            $table->unsignedInteger('current_day_number')->default(1);
            $table->date('projected_end_date')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->unsignedBigInteger('prayer_request_id')->nullable();
            $table->timestamps();
        });

        Schema::create('bible_plan_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained('bible_plans')->cascadeOnDelete();
            $table->unsignedInteger('day_number');
            $table->string('title')->nullable();
            $table->timestamps();
        });

        Schema::create('bible_plan_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_day_id')->constrained('bible_plan_days')->cascadeOnDelete();
            $table->unsignedInteger('order_index')->default(0);
            $table->string('type')->default('scripture'); // scripture, etc.
            $table->foreignId('book_id')->nullable()->constrained('bible_books')->nullOnDelete();
            $table->unsignedInteger('chapter_start')->nullable();
            $table->unsignedInteger('chapter_end')->nullable();
            $table->unsignedInteger('verse_start')->nullable();
            $table->unsignedInteger('verse_end')->nullable();
            $table->timestamps();
        });

        Schema::create('bible_user_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->constrained('bible_plan_subscriptions')->cascadeOnDelete();
            $table->foreignId('plan_day_id')->constrained('bible_plan_days')->cascadeOnDelete();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->unique(['subscription_id', 'plan_day_id']);
        });

        Schema::create('bible_reading_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subscription_id')->constrained('bible_plan_subscriptions')->cascadeOnDelete();
            $table->string('action');
            $table->json('payload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bible_reading_audit_logs');
        Schema::dropIfExists('bible_user_progress');
        Schema::dropIfExists('bible_plan_contents');
        Schema::dropIfExists('bible_plan_days');
        Schema::dropIfExists('bible_plan_subscriptions');
        Schema::dropIfExists('bible_plans');
    }
};

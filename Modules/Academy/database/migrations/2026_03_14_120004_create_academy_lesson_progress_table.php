<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academy_lesson_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained('academy_enrollments')->cascadeOnDelete();
            $table->foreignId('lesson_id')->constrained('academy_lessons')->cascadeOnDelete();
            $table->boolean('is_completed')->default(false);
            $table->unsignedInteger('watched_seconds')->default(0);
            $table->timestamps();

            $table->unique(['enrollment_id', 'lesson_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academy_lesson_progress');
    }
};

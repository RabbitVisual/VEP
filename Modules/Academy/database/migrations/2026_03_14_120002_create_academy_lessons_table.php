<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academy_lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('academy_course_modules')->cascadeOnDelete();
            $table->string('title');
            $table->string('video_url')->nullable();
            $table->longText('content')->nullable();
            $table->unsignedInteger('duration_in_minutes')->default(0);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academy_lessons');
    }
};

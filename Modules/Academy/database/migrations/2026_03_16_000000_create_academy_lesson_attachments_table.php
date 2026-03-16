<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academy_lesson_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')
                ->constrained('academy_lessons')
                ->cascadeOnDelete();
            $table->string('file_name');
            $table->string('file_path');
            $table->string('type', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academy_lesson_attachments');
    }
};


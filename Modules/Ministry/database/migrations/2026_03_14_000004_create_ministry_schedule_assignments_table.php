<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ministry_schedule_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ministry_schedule_id')->constrained('ministry_schedules')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('status', 32)->default('pending');
            $table->timestamps();

            $table->unique(['ministry_schedule_id', 'user_id'], 'ministry_schedule_assign_schedule_user_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ministry_schedule_assignments');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ministry_schedule_assignments', function (Blueprint $table) {
            $table->unique(['ministry_schedule_id', 'user_id'], 'min_sched_assign_schedule_user');
        });
    }

    public function down(): void
    {
        Schema::table('ministry_schedule_assignments', function (Blueprint $table) {
            $table->dropUnique('min_sched_assign_schedule_user');
        });
    }
};

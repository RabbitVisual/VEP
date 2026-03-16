<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('community_group_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('group_id')->constrained('community_groups')->cascadeOnDelete();
            $table->string('role', 32)->default('member'); // admin, member
            $table->timestamps();

            $table->unique(['user_id', 'group_id'], 'community_group_members_user_group_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('community_group_members');
    }
};


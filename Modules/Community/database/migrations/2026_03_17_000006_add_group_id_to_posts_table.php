<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->foreignId('group_id')
                ->nullable()
                ->after('user_id')
                ->constrained('community_groups')
                ->nullOnDelete();

            $table->index(['group_id', 'created_at'], 'posts_group_created_index');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex('posts_group_created_index');
            $table->dropConstrainedForeignId('group_id');
        });
    }
};


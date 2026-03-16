<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('post_comments', function (Blueprint $table) {
            $table->foreignId('parent_id')
                ->nullable()
                ->after('user_id')
                ->constrained('post_comments')
                ->cascadeOnDelete();

            $table->index(['post_id', 'parent_id', 'created_at'], 'post_comments_post_parent_created_index');
        });
    }

    public function down(): void
    {
        Schema::table('post_comments', function (Blueprint $table) {
            $table->dropIndex('post_comments_post_parent_created_index');
            $table->dropConstrainedForeignId('parent_id');
        });
    }
};


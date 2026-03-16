<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Migrar likes existentes de posts para reactions (tipo 'like')
        if (! DB::getSchemaBuilder()->hasTable('post_likes')) {
            return;
        }

        $postLikes = DB::table('post_likes')->select(['id', 'post_id', 'user_id', 'created_at', 'updated_at'])->get();

        if ($postLikes->isEmpty()) {
            DB::getSchemaBuilder()->dropIfExists('post_likes');

            return;
        }

        $now = now();

        $payload = $postLikes->map(function ($like) use ($now) {
            return [
                'user_id' => $like->user_id,
                'reactable_type' => \VertexSolutions\Community\Models\Post::class,
                'reactable_id' => $like->post_id,
                'type' => 'like',
                'created_at' => $like->created_at ?? $now,
                'updated_at' => $like->updated_at ?? $now,
            ];
        })->all();

        if (! empty($payload)) {
            DB::table('reactions')->insert($payload);
        }

        DB::getSchemaBuilder()->dropIfExists('post_likes');
    }

    public function down(): void
    {
        // Não recria a tabela antiga; migração é unidirecional.
    }
};


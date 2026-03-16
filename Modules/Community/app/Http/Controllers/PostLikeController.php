<?php

declare(strict_types=1);

namespace VertexSolutions\Community\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use VertexSolutions\Community\Models\Post;
use VertexSolutions\Community\Models\Reaction;

class PostLikeController extends Controller
{
    /**
     * Compat: mantém rota antiga baseada em redirect, usando reactions (type=like).
     */
    public function toggle(Request $request, Post $post): RedirectResponse
    {
        $userId = (int) $request->user()->id;

        $existing = Reaction::query()
            ->where('user_id', $userId)
            ->where('reactable_type', Post::class)
            ->where('reactable_id', $post->id)
            ->where('type', Reaction::TYPE_LIKE)
            ->first();

        if ($existing) {
            $existing->delete();
        } else {
            Reaction::create([
                'user_id' => $userId,
                'reactable_type' => Post::class,
                'reactable_id' => $post->id,
                'type' => Reaction::TYPE_LIKE,
            ]);
        }

        return back();
    }
}


<?php

declare(strict_types=1);

namespace VertexSolutions\Community\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use VertexSolutions\Community\Models\Post;
use VertexSolutions\Community\Models\PostComment;
use VertexSolutions\Community\Models\Reaction;

class ReactionController extends Controller
{
    public function toggleForPost(Request $request, Post $post): JsonResponse
    {
        $validated = $request->validate([
            'type' => 'required|string|in:like,amen,praying',
        ]);

        $userId = (int) $request->user()->id;
        $type = $validated['type'];

        $reaction = Reaction::query()
            ->where('user_id', $userId)
            ->where('reactable_type', Post::class)
            ->where('reactable_id', $post->id)
            ->where('type', $type)
            ->first();

        if ($reaction) {
            $reaction->delete();
        } else {
            Reaction::create([
                'user_id' => $userId,
                'reactable_type' => Post::class,
                'reactable_id' => $post->id,
                'type' => $type,
            ]);
        }

        $counts = [
            'like' => $post->reactions()->where('type', Reaction::TYPE_LIKE)->count(),
            'amen' => $post->reactions()->where('type', Reaction::TYPE_AMEN)->count(),
            'praying' => $post->reactions()->where('type', Reaction::TYPE_PRAYING)->count(),
        ];

        $userReaction = Reaction::query()
            ->where('user_id', $userId)
            ->where('reactable_type', Post::class)
            ->where('reactable_id', $post->id)
            ->pluck('type')
            ->all();

        return response()->json([
            'ok' => true,
            'post_id' => $post->id,
            'counts' => $counts,
            'user_reactions' => $userReaction,
        ]);
    }

    public function toggleForComment(Request $request, PostComment $comment): JsonResponse
    {
        $validated = $request->validate([
            'type' => 'required|string|in:like,amen,praying',
        ]);

        $userId = (int) $request->user()->id;
        $type = $validated['type'];

        $reaction = Reaction::query()
            ->where('user_id', $userId)
            ->where('reactable_type', PostComment::class)
            ->where('reactable_id', $comment->id)
            ->where('type', $type)
            ->first();

        if ($reaction) {
            $reaction->delete();
        } else {
            Reaction::create([
                'user_id' => $userId,
                'reactable_type' => PostComment::class,
                'reactable_id' => $comment->id,
                'type' => $type,
            ]);
        }

        $counts = [
            'like' => $comment->reactions()->where('type', Reaction::TYPE_LIKE)->count(),
            'amen' => $comment->reactions()->where('type', Reaction::TYPE_AMEN)->count(),
            'praying' => $comment->reactions()->where('type', Reaction::TYPE_PRAYING)->count(),
        ];

        $userReaction = Reaction::query()
            ->where('user_id', $userId)
            ->where('reactable_type', PostComment::class)
            ->where('reactable_id', $comment->id)
            ->pluck('type')
            ->all();

        return response()->json([
            'ok' => true,
            'comment_id' => $comment->id,
            'counts' => $counts,
            'user_reactions' => $userReaction,
        ]);
    }
}


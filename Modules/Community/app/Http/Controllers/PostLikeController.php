<?php

declare(strict_types=1);

namespace VertexSolutions\Community\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use VertexSolutions\Community\Models\Post;
use VertexSolutions\Community\Models\PostLike;

class PostLikeController extends Controller
{
    public function toggle(Request $request, Post $post): RedirectResponse
    {
        $userId = $request->user()->id;

        $existing = PostLike::where('post_id', $post->id)
            ->where('user_id', $userId)
            ->first();

        if ($existing) {
            $existing->delete();
        } else {
            PostLike::create([
                'post_id' => $post->id,
                'user_id' => $userId,
            ]);
        }

        return back();
    }
}


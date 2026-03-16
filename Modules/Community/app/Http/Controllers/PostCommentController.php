<?php

declare(strict_types=1);

namespace VertexSolutions\Community\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\TheologicalMarkdownConverter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use VertexSolutions\Community\Models\Post;
use VertexSolutions\Community\Models\PostComment;

class PostCommentController extends Controller
{
    public function store(Request $request, Post $post): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|integer|exists:post_comments,id',
        ]);

        $comment = PostComment::create([
            'post_id' => $post->id,
            'user_id' => $request->user()->id,
            'parent_id' => $validated['parent_id'] ?? null,
            'content' => $validated['content'],
        ]);

        if ($request->wantsJson()) {
            $comment->load('user');

            return response()->json([
                'ok' => true,
                'post_id' => $post->id,
                'comment' => [
                    'id' => $comment->id,
                    'post_id' => $comment->post_id,
                    'parent_id' => $comment->parent_id,
                    'user_name' => $comment->user->name ?? 'Membro',
                    'created_at_human' => $comment->created_at?->diffForHumans(),
                    'content_html' => TheologicalMarkdownConverter::convert($comment->content ?? ''),
                ],
            ]);
        }

        return back()->withFragment('post-'.$post->id);
    }
}


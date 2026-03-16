<?php

declare(strict_types=1);

namespace VertexSolutions\Community\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use VertexSolutions\Community\Models\Post;

class PostController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'content' => 'required|string|max:10000',
            'type' => 'nullable|in:update,question,testimony',
        ]);

        Post::create([
            'user_id' => $request->user()->id,
            'content' => $validated['content'],
            'type' => $validated['type'] ?? Post::TYPE_UPDATE,
        ]);

        return redirect()->route('painel.community.feed.index')->with('success', 'Post publicado no feed.');
    }
}

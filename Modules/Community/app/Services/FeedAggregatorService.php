<?php

declare(strict_types=1);

namespace VertexSolutions\Community\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use VertexSolutions\Academy\Models\Certificate;
use VertexSolutions\Community\Models\Post;
use VertexSolutions\Sermons\Models\Sermon;

final class FeedAggregatorService
{
    private const PER_PAGE = 15;

    /**
     * Aggregated feed item for polymorphic rendering.
     *
     * @param  'post'|'sermon'|'certificate'  $type
     */
    public function getPaginatedFeed(int $page = 1): LengthAwarePaginator
    {
        $posts = Post::with(['user'])
            ->withCount(['likes', 'comments'])
            ->orderByDesc('created_at')
            ->limit(50)
            ->get()
            ->map(fn (Post $p) => [
                'type' => 'post',
                'date' => $p->created_at,
                'item' => $p,
            ]);

        $sermons = Sermon::query()
            ->where('visibility', Sermon::VISIBILITY_PUBLIC)
            ->where('status', Sermon::STATUS_PUBLISHED)
            ->with('user')
            ->orderByDesc('published_at')
            ->limit(50)
            ->get()
            ->map(fn (Sermon $s) => [
                'type' => 'sermon',
                'date' => $s->published_at ?? $s->created_at,
                'item' => $s,
            ]);

        $certificates = Certificate::with(['user', 'course'])
            ->orderByDesc('issued_at')
            ->limit(50)
            ->get()
            ->map(fn (Certificate $c) => [
                'type' => 'certificate',
                'date' => $c->issued_at ?? $c->created_at,
                'item' => $c,
            ]);

        $merged = $posts->concat($sermons)->concat($certificates)
            ->sortByDesc(fn (array $row) => $row['date']->getTimestamp())
            ->values();

        $total = $merged->count();
        $slice = $merged->slice(($page - 1) * self::PER_PAGE, self::PER_PAGE)->values();

        return new LengthAwarePaginator(
            $slice,
            $total,
            self::PER_PAGE,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }
}

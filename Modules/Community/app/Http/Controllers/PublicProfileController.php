<?php

declare(strict_types=1);

namespace VertexSolutions\Community\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;
use VertexSolutions\Community\Models\Post;
use VertexSolutions\Community\Services\FeedAggregatorService;
use VertexSolutions\Sermons\Models\Sermon;

class PublicProfileController extends Controller
{
    public function show(User $user): View
    {
        $currentUser = request()->user();

        $posts = Post::where('user_id', $user->id)
            ->with('user')
            ->orderByDesc('created_at')
            ->limit(20)
            ->get()
            ->map(fn (Post $p) => [
                'type' => 'post',
                'date' => $p->created_at,
                'item' => $p,
            ]);

        $sermons = Sermon::where('user_id', $user->id)
            ->where('visibility', Sermon::VISIBILITY_PUBLIC)
            ->where('status', Sermon::STATUS_PUBLISHED)
            ->with('user')
            ->orderByDesc('published_at')
            ->limit(20)
            ->get()
            ->map(fn (Sermon $s) => [
                'type' => 'sermon',
                'date' => $s->published_at ?? $s->created_at,
                'item' => $s,
            ]);

        $activityFeed = $posts->concat($sermons)
            ->sortByDesc(fn (array $row) => $row['date']->getTimestamp())
            ->values()
            ->take(15);

        $badges = [];
        if (class_exists(\VertexSolutions\Core\Models\BibleUserBadge::class)) {
            $badges = \VertexSolutions\Core\Models\BibleUserBadge::where('user_id', $user->id)
                ->orderByDesc('awarded_at')
                ->get();
        }

        $isFollowing = false;
        if ($currentUser && $currentUser->id !== $user->id) {
            $isFollowing = \VertexSolutions\Community\Models\UserFollow::where('follower_id', $currentUser->id)
                ->where('following_id', $user->id)
                ->exists();
        }

        return view('community::profile', [
            'profileUser' => $user,
            'activityFeed' => $activityFeed,
            'badges' => $badges,
            'isFollowing' => $isFollowing,
        ]);
    }
}

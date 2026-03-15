<?php

namespace VertexSolutions\PastoralPanel\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use VertexSolutions\Ministry\Models\Ministry;
use VertexSolutions\Ministry\Models\MinistrySchedule;
use VertexSolutions\Sermons\Models\Sermon;

class PastoralDashboardService
{
    /**
     * Count active members (users with is_active = true).
     */
    public function activeMembersCount(): int
    {
        return User::where('is_active', true)->count();
    }

    /**
     * Count draft sermons for the current user (pastor).
     */
    public function draftSermonsCount(): int
    {
        $userId = auth()->id();
        if (! $userId) {
            return 0;
        }

        return Sermon::where('status', Sermon::STATUS_DRAFT)
            ->where('user_id', $userId)
            ->count();
    }

    /**
     * Upcoming schedules (next 7 days) for ministries the pastor leads or collaborates in.
     */
    public function schedulesThisWeek(): Collection
    {
        $user = auth()->user();
        if (! $user) {
            return collect();
        }

        $ministryIds = Ministry::where('leader_id', $user->id)
            ->orWhereHas('members', fn ($q) => $q->where('user_id', $user->id))
            ->pluck('id');

        if ($ministryIds->isEmpty()) {
            return collect();
        }

        $now = Carbon::now();
        $nextWeek = $now->copy()->addDays(7);

        return MinistrySchedule::with(['ministry'])
            ->whereIn('ministry_id', $ministryIds)
            ->whereBetween('scheduled_at', [$now, $nextWeek])
            ->orderBy('scheduled_at')
            ->get();
    }

    /**
     * Recent sermons (last edited drafts) for the pastor.
     */
    public function recentSermons(int $limit = 5): Collection
    {
        $userId = auth()->id();
        if (! $userId) {
            return collect();
        }

        return Sermon::where('user_id', $userId)
            ->with(['category'])
            ->orderBy('updated_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Count of members who completed at least one reading plan (EAD engagement).
     * Returns 0 if Bible module is not present.
     */
    public function eadEngagementCount(): int
    {
        if (! class_exists(\Modules\Bible\App\Models\BiblePlanSubscription::class)) {
            return 0;
        }

        return \Modules\Bible\App\Models\BiblePlanSubscription::where('is_completed', true)
            ->distinct('user_id')
            ->count('user_id');
    }

    /**
     * Number of schedules this week (for stats card).
     */
    public function schedulesThisWeekCount(): int
    {
        return $this->schedulesThisWeek()->count();
    }
}

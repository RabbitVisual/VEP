<?php

namespace VertexSolutions\PastoralPanel\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use VertexSolutions\PastoralPanel\Services\PastoralDashboardService;

class DashboardController extends Controller
{
    public function __construct(
        protected PastoralDashboardService $dashboardService
    ) {}

    /**
     * Pastoral cockpit: stats, upcoming schedules, recent sermons, EAD engagement.
     */
    public function index(): View
    {
        $activeMembers = $this->dashboardService->activeMembersCount();
        $draftSermons = $this->dashboardService->draftSermonsCount();
        $schedulesCount = $this->dashboardService->schedulesThisWeekCount();
        $upcomingSchedules = $this->dashboardService->schedulesThisWeek();
        $recentSermons = $this->dashboardService->recentSermons(5);
        $eadEngagement = $this->dashboardService->eadEngagementCount();

        return view('pastoralpanel::dashboard', [
            'activeMembers' => $activeMembers,
            'draftSermons' => $draftSermons,
            'schedulesCount' => $schedulesCount,
            'upcomingSchedules' => $upcomingSchedules,
            'recentSermons' => $recentSermons,
            'eadEngagement' => $eadEngagement,
        ]);
    }
}

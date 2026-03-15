<?php

namespace VertexSolutions\Ministry\Http\Controllers\MemberPanel;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\View\View;
use VertexSolutions\Ministry\Models\Ministry;

class MinistryDashboardController extends Controller
{
    /**
     * Dashboard for a ministry (leader view: volunteers count, next 7 days schedules, recent materials).
     */
    public function dashboard(Ministry $ministry): View
    {
        $this->authorize('view', $ministry);

        $ministry->load(['members', 'schedules', 'materials']);

        $volunteersCount = $ministry->members()->count();
        $now = Carbon::now();
        $nextWeek = $now->copy()->addDays(7);
        $upcomingSchedules = $ministry->schedules()
            ->whereBetween('scheduled_at', [$now, $nextWeek])
            ->orderBy('scheduled_at')
            ->get();
        $recentMaterials = $ministry->materials()->latest()->limit(5)->get();

        return view('ministry::memberpanel.dashboard', [
            'ministry' => $ministry,
            'volunteersCount' => $volunteersCount,
            'upcomingSchedules' => $upcomingSchedules,
            'recentMaterials' => $recentMaterials,
        ]);
    }
}

<?php

namespace VertexSolutions\MemberPanel\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use VertexSolutions\Ministry\Models\MinistryScheduleAssignment;
use VertexSolutions\Sermons\Models\Sermon;

class DashboardController extends Controller
{
    /**
     * Vertex Hub – Cockpit central: widgets de progresso, próxima escala e feed de sermões.
     */
    public function index(): View
    {
        $subscriptions = $this->getReadingPlanSubscriptions();
        $nextSchedule = $this->getNextSchedule();
        $latestSermons = $this->getLatestSermons();

        return view('memberpanel::dashboard', [
            'subscriptions' => $subscriptions,
            'nextSchedule' => $nextSchedule,
            'latestSermons' => $latestSermons,
        ]);
    }

    /**
     * Planos de leitura ativos do usuário com percentual (lógica alinhada ao ReadingPlanController).
     */
    protected function getReadingPlanSubscriptions(): Collection
    {
        if (! class_exists(\Modules\Bible\App\Models\BiblePlanSubscription::class)) {
            return collect();
        }

        $user = auth()->user();
        $subscriptions = \Modules\Bible\App\Models\BiblePlanSubscription::with(['plan', 'progress'])
            ->where('user_id', $user->id)
            ->latest()
            ->get()
            ->filter(fn ($sub) => $sub->plan !== null);

        $catchUpService = app()->make(\Modules\Bible\App\Services\ReadingCatchUpService::class);

        foreach ($subscriptions as $sub) {
            $total = $sub->plan->days()->count();
            $sub->total_days = $total ?: ($sub->plan->duration_days ?? 0);
            $completed = $sub->progress()->count();
            $sub->percent = $sub->total_days > 0 ? (int) round(($completed / $sub->total_days) * 100) : 0;
            $sub->offer_recalculate = $catchUpService->shouldOfferRecalculate($sub);
        }

        return $subscriptions;
    }

    /**
     * Próxima escala do membro (MinistryScheduleAssignment com scheduled_at >= now).
     */
    protected function getNextSchedule(): ?MinistryScheduleAssignment
    {
        return MinistryScheduleAssignment::with(['ministrySchedule.ministry'])
            ->where('user_id', auth()->id())
            ->whereHas('ministrySchedule', fn ($q) => $q->where('scheduled_at', '>=', now()))
            ->join('ministry_schedules', 'ministry_schedules.id', '=', 'ministry_schedule_assignments.ministry_schedule_id')
            ->orderBy('ministry_schedules.scheduled_at')
            ->select('ministry_schedule_assignments.*')
            ->first();
    }

    /**
     * Últimos 5 sermões publicados (visíveis ao usuário).
     */
    protected function getLatestSermons(): Collection
    {
        return Sermon::visible(auth()->user())
            ->published()
            ->with(['user', 'category'])
            ->orderBy('published_at', 'desc')
            ->limit(5)
            ->get();
    }
}

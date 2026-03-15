<?php

namespace VertexSolutions\PastoralPanel\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use VertexSolutions\Ministry\Models\Ministry;
use VertexSolutions\Ministry\Models\MinistryScheduleAssignment;
use VertexSolutions\PastoralPanel\Models\PastoralNote;

class MemberManagementController extends Controller
{
    /**
     * Data table: members with search, filters (ministry, status), pagination.
     */
    public function index(Request $request): View
    {
        $query = User::query()->withCount('ministryMemberships');

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('first_name', 'like', "%{$term}%")
                    ->orWhere('last_name', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%");
            });
        }

        if ($request->filled('ministry_id')) {
            $query->whereHas('ministryMemberships', fn ($q) => $q->where('ministry_id', $request->ministry_id));
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $members = $query->orderBy('first_name')->orderBy('last_name')->paginate(15)->withQueryString();
        $ministries = Ministry::orderBy('name')->get(['id', 'name']);

        return view('pastoralpanel::members.index', [
            'members' => $members,
            'ministries' => $ministries,
        ]);
    }

    /**
     * Perfil da ovelha: progresso leitura, ministérios, escalas, anotações pastorais.
     */
    public function show(Request $request, User $user): View|RedirectResponse
    {
        $user->load(['ministryMemberships.ministry']);

        $scheduleAssignments = MinistryScheduleAssignment::with(['ministrySchedule.ministry'])
            ->where('user_id', $user->id)
            ->join('ministry_schedules', 'ministry_schedules.id', '=', 'ministry_schedule_assignments.ministry_schedule_id')
            ->orderByDesc('ministry_schedules.scheduled_at')
            ->select('ministry_schedule_assignments.*')
            ->limit(20)
            ->get();

        $readingProgress = $this->getReadingProgressForUser($user);
        $pastoralNotes = PastoralNote::where('user_id', $user->id)
            ->with('author')
            ->orderByDesc('created_at')
            ->get();

        return view('pastoralpanel::members.show', [
            'member' => $user,
            'scheduleAssignments' => $scheduleAssignments,
            'readingProgress' => $readingProgress,
            'pastoralNotes' => $pastoralNotes,
        ]);
    }

    /**
     * Store a new pastoral note (AJAX or form post).
     */
    public function storeNote(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'content' => ['required', 'string', 'max:10000'],
        ]);

        PastoralNote::create([
            'user_id' => $user->id,
            'author_id' => auth()->id(),
            'content' => $validated['content'],
        ]);

        return redirect()->route('pastoral.members.show', $user)->with('success', 'Anotação adicionada.');
    }

    protected function getReadingProgressForUser(User $user): array
    {
        if (! class_exists(\Modules\Bible\App\Models\BiblePlanSubscription::class)) {
            return ['subscriptions' => [], 'completed_count' => 0];
        }

        $subscriptions = \Modules\Bible\App\Models\BiblePlanSubscription::with(['plan'])
            ->where('user_id', $user->id)
            ->latest()
            ->get()
            ->filter(fn ($sub) => $sub->plan !== null);

        $completedCount = \Modules\Bible\App\Models\BiblePlanSubscription::where('user_id', $user->id)
            ->where('is_completed', true)
            ->count();

        $result = [];
        foreach ($subscriptions as $sub) {
            $total = $sub->plan->days()->count();
            $completed = method_exists($sub, 'progress') ? $sub->progress()->count() : 0;
            $result[] = [
                'plan' => $sub->plan,
                'total_days' => $total ?: ($sub->plan->duration_days ?? 0),
                'completed' => $completed,
                'percent' => $total > 0 ? (int) round(($completed / $total) * 100) : 0,
                'is_completed' => $sub->is_completed ?? false,
            ];
        }

        return ['subscriptions' => $result, 'completed_count' => $completedCount];
    }
}

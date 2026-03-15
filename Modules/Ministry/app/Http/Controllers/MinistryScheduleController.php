<?php

namespace VertexSolutions\Ministry\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use VertexSolutions\Ministry\Models\Ministry;
use VertexSolutions\Ministry\Models\MinistrySchedule;
use VertexSolutions\Ministry\Models\MinistryScheduleAssignment;

class MinistryScheduleController extends Controller
{
    /**
     * List schedules for a ministry (optionally "week" view).
     */
    public function index(Ministry $ministry, Request $request): View
    {
        $query = $ministry->schedules()->orderBy('scheduled_at');
        if ($request->filled('from')) {
            $query->where('scheduled_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->where('scheduled_at', '<=', $request->to);
        }
        $schedules = $query->withCount('assignments')->get();

        return view('ministry::pastoralpanel.schedules.index', compact('ministry', 'schedules'));
    }

    /**
     * Show create schedule form.
     */
    public function create(Ministry $ministry): View
    {
        return view('ministry::pastoralpanel.schedules.create', compact('ministry'));
    }

    /**
     * Store a new schedule.
     */
    public function store(Request $request, Ministry $ministry): RedirectResponse
    {
        $validated = $request->validate([
            'activity_name' => ['required', 'string', 'max:255'],
            'scheduled_at' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);
        $validated['ministry_id'] = $ministry->id;
        $schedule = MinistrySchedule::create($validated);

        if (class_exists(\VertexSolutions\Notifications\App\Services\InAppNotificationService::class)) {
            $service = app(\VertexSolutions\Notifications\App\Services\InAppNotificationService::class);
            foreach ($ministry->members()->with('user')->get() as $member) {
                if ($member->user_id) {
                    $service->sendToUser(
                        $member->user,
                        'Nova escala publicada',
                        $ministry->name . ': ' . $schedule->activity_name . ' em ' . $schedule->scheduled_at?->format('d/m/Y H:i'),
                        [
                            'action_url' => route('ministry.schedules.show', [$ministry, $schedule]),
                            'action_text' => 'Ver escala',
                            'notification_type' => 'ministry_schedule',
                        ]
                    );
                }
            }
        }

        return redirect()
            ->route('ministry.schedules.index', $ministry)
            ->with('success', __('Escala criada com sucesso.'));
    }

    /**
     * Show a schedule and its assignments.
     */
    public function show(Ministry $ministry, MinistrySchedule $schedule): View
    {
        if ($schedule->ministry_id !== $ministry->id) {
            abort(404);
        }
        $schedule->load(['assignments.user:id,name,email']);
        $ministry->load('members.user:id,name,email');
        $assignableUserIds = $schedule->assignments->pluck('user_id')->all();
        $availableMembers = $ministry->members()
            ->with('user:id,name,email')
            ->whereNotIn('user_id', $assignableUserIds)
            ->get();

        return view('ministry::schedules.show', [
            'ministry' => $ministry,
            'schedule' => $schedule,
            'availableMembers' => $availableMembers,
        ]);
    }

    /**
     * Show edit schedule form.
     */
    public function edit(Ministry $ministry, MinistrySchedule $schedule): View
    {
        if ($schedule->ministry_id !== $ministry->id) {
            abort(404);
        }
        return view('ministry::pastoralpanel.schedules.edit', compact('ministry', 'schedule'));
    }

    /**
     * Update a schedule.
     */
    public function update(Request $request, Ministry $ministry, MinistrySchedule $schedule): RedirectResponse
    {
        if ($schedule->ministry_id !== $ministry->id) {
            abort(404);
        }
        $validated = $request->validate([
            'activity_name' => ['required', 'string', 'max:255'],
            'scheduled_at' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);
        $schedule->update($validated);

        return redirect()
            ->route('ministry.schedules.show', [$ministry, $schedule])
            ->with('success', __('Escala atualizada com sucesso.'));
    }

    /**
     * Delete a schedule.
     */
    public function destroy(Ministry $ministry, MinistrySchedule $schedule): RedirectResponse
    {
        if ($schedule->ministry_id !== $ministry->id) {
            abort(404);
        }
        $schedule->delete();
        return redirect()
            ->route('ministry.schedules.index', $ministry)
            ->with('success', __('Escala excluída com sucesso.'));
    }

    /**
     * Assign a user to a schedule (store assignment).
     */
    public function assign(Request $request, Ministry $ministry, MinistrySchedule $schedule): RedirectResponse
    {
        if ($schedule->ministry_id !== $ministry->id) {
            abort(404);
        }
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'status' => ['nullable', 'string', 'in:pending,confirmed'],
        ]);
        $validated['status'] = $validated['status'] ?? MinistryScheduleAssignment::STATUS_PENDING;

        $exists = MinistryScheduleAssignment::where('ministry_schedule_id', $schedule->id)
            ->where('user_id', $validated['user_id'])
            ->exists();
        if ($exists) {
            return back()->with('error', __('Esta pessoa já está na escala.'));
        }

        MinistryScheduleAssignment::create([
            'ministry_schedule_id' => $schedule->id,
            'user_id' => $validated['user_id'],
            'status' => $validated['status'],
        ]);

        if (class_exists(\VertexSolutions\Notifications\App\Services\InAppNotificationService::class)) {
            $user = User::find($validated['user_id']);
            if ($user) {
                app(\VertexSolutions\Notifications\App\Services\InAppNotificationService::class)->sendToUser(
                    $user,
                    'Você foi escalado',
                    $ministry->name . ': ' . $schedule->activity_name . ' em ' . $schedule->scheduled_at?->format('d/m/Y H:i'),
                    [
                        'action_url' => route('ministry.schedules.show', [$ministry, $schedule]),
                        'action_text' => 'Ver escala',
                        'notification_type' => 'ministry_schedule_assignment',
                    ]
                );
            }
        }

        return back()->with('success', __('Pessoa adicionada à escala.'));
    }

    /**
     * Update assignment status (e.g. confirm).
     */
    public function updateAssignment(Request $request, Ministry $ministry, MinistrySchedule $schedule, int $assignment): RedirectResponse
    {
        if ($schedule->ministry_id !== $ministry->id) {
            abort(404);
        }
        $assignmentModel = $schedule->assignments()->findOrFail($assignment);
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending,confirmed'],
        ]);
        $assignmentModel->update($validated);
        return back()->with('success', __('Status atualizado.'));
    }

    /**
     * Remove assignment from schedule.
     */
    public function unassign(Ministry $ministry, MinistrySchedule $schedule, int $assignment): RedirectResponse
    {
        if ($schedule->ministry_id !== $ministry->id) {
            abort(404);
        }
        $assignmentModel = $schedule->assignments()->findOrFail($assignment);
        $assignmentModel->delete();
        return back()->with('success', __('Pessoa removida da escala.'));
    }
}

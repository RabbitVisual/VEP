<?php

namespace VertexSolutions\Ministry\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use VertexSolutions\Ministry\Models\Ministry;

class MinistryController extends Controller
{
    /**
     * Display a listing of ministries.
     */
    public function index(): View
    {
        $ministries = Ministry::with('leader:id,name,email')
            ->orderBy('name')
            ->get();

        return view('ministry::pastoralpanel.ministries.index', compact('ministries'));
    }

    /**
     * Show the form for creating a new ministry.
     */
    public function create(): View
    {
        $users = User::orderBy('name')->get(['id', 'name', 'email']);
        $iconOptions = self::iconOptions();
        $colorOptions = self::colorOptions();

        return view('ministry::pastoralpanel.ministries.create', compact('users', 'iconOptions', 'colorOptions'));
    }

    /**
     * Store a newly created ministry.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'leader_id' => ['nullable', 'exists:users,id'],
            'icon' => ['required', 'string', 'max:64', 'in:' . implode(',', array_column(self::iconOptions(), 'value'))],
            'color' => ['required', 'string', 'max:64', 'in:' . implode(',', array_column(self::colorOptions(), 'value'))],
        ]);

        $ministry = Ministry::create($validated);

        return redirect()
            ->route('ministry.show', $ministry)
            ->with('success', __('Ministério criado com sucesso.'));
    }

    /**
     * Display the specified ministry.
     */
    public function show(Ministry $ministry): View
    {
        $ministry->load(['leader:id,name,email', 'members.user:id,name,email', 'schedules' => fn ($q) => $q->orderBy('scheduled_at')->limit(5), 'materials' => fn ($q) => $q->latest()->limit(5)]);

        return view('ministry::pastoralpanel.ministries.show', compact('ministry'));
    }

    /**
     * Show the form for editing the specified ministry.
     */
    public function edit(Ministry $ministry): View
    {
        $users = User::orderBy('name')->get(['id', 'name', 'email']);
        $iconOptions = self::iconOptions();
        $colorOptions = self::colorOptions();

        return view('ministry::pastoralpanel.ministries.edit', compact('ministry', 'users', 'iconOptions', 'colorOptions'));
    }

    /**
     * Update the specified ministry.
     */
    public function update(Request $request, Ministry $ministry): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'leader_id' => ['nullable', 'exists:users,id'],
            'icon' => ['required', 'string', 'max:64', 'in:' . implode(',', array_column(self::iconOptions(), 'value'))],
            'color' => ['required', 'string', 'max:64', 'in:' . implode(',', array_column(self::colorOptions(), 'value'))],
        ]);

        $ministry->update($validated);

        return redirect()
            ->route('ministry.show', $ministry)
            ->with('success', __('Ministério atualizado com sucesso.'));
    }

    /**
     * Remove the specified ministry.
     */
    public function destroy(Ministry $ministry): RedirectResponse
    {
        $ministry->delete();

        return redirect()
            ->route('ministry.index')
            ->with('success', __('Ministério excluído com sucesso.'));
    }

    protected static function iconOptions(): array
    {
        return [
            ['value' => 'fa-music', 'label' => 'Música / Louvor'],
            ['value' => 'fa-child-reaching', 'label' => 'Infantil'],
            ['value' => 'fa-earth-americas', 'label' => 'Missões'],
            ['value' => 'fa-users', 'label' => 'Jovens / Geral'],
            ['value' => 'fa-users-viewfinder', 'label' => 'Ministérios'],
            ['value' => 'fa-hands-praying', 'label' => 'Oração'],
            ['value' => 'fa-book-bible', 'label' => 'Escola Bíblica'],
        ];
    }

    protected static function colorOptions(): array
    {
        return [
            ['value' => 'amber-500', 'label' => 'Âmbar'],
            ['value' => 'blue-500', 'label' => 'Azul'],
            ['value' => 'emerald-500', 'label' => 'Esmeralda'],
            ['value' => 'violet-500', 'label' => 'Violeta'],
            ['value' => 'rose-500', 'label' => 'Rosa'],
            ['value' => 'sky-500', 'label' => 'Céu'],
            ['value' => 'indigo-500', 'label' => 'Índigo'],
        ];
    }
}

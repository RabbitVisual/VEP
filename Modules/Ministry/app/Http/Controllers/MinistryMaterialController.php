<?php

namespace VertexSolutions\Ministry\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use VertexSolutions\Ministry\Models\Ministry;
use VertexSolutions\Ministry\Models\MinistryMaterial;

class MinistryMaterialController extends Controller
{
    /**
     * List materials for a ministry (optional filter by type).
     */
    public function index(Request $request, Ministry $ministry): View
    {
        $query = $ministry->materials()->with('creator:id,name')->orderBy('created_at', 'desc');
        if ($request->filled('type') && array_key_exists($request->type, MinistryMaterial::types())) {
            $query->where('type', $request->type);
        }
        $materials = $query->get();

        return view('ministry::pastoralpanel.materials.index', [
            'ministry' => $ministry,
            'materials' => $materials,
            'typeFilter' => $request->type,
            'typeOptions' => MinistryMaterial::types(),
        ]);
    }

    /**
     * Show create material form.
     */
    public function create(Ministry $ministry): View
    {
        return view('ministry::pastoralpanel.materials.create', [
            'ministry' => $ministry,
            'typeOptions' => MinistryMaterial::types(),
        ]);
    }

    /**
     * Store a new material (with optional file upload).
     */
    public function store(Request $request, Ministry $ministry): RedirectResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'string', 'max:32', 'in:' . implode(',', array_keys(MinistryMaterial::types()))],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'file' => ['nullable', 'file', 'max:10240'],
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filePath = $file->store('ministry_materials/' . $ministry->id, 'public');
        }

        $material = $ministry->materials()->create([
            'type' => $validated['type'],
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'file_path' => $filePath,
            'created_by' => $request->user()?->id,
        ]);

        if (class_exists(\VertexSolutions\Notifications\App\Services\InAppNotificationService::class)) {
            $service = app(\VertexSolutions\Notifications\App\Services\InAppNotificationService::class);
            foreach ($ministry->members()->with('user')->get() as $member) {
                if ($member->user_id) {
                    $service->sendToUser(
                        $member->user,
                        'Novo material no ministério',
                        $ministry->name . ': ' . $material->title,
                        [
                            'action_url' => route('ministry.materials.show', [$ministry, $material]),
                            'action_text' => 'Ver material',
                            'notification_type' => 'ministry_material',
                        ]
                    );
                }
            }
        }

        return redirect()
            ->route('ministry.materials.index', $ministry)
            ->with('success', __('Material criado com sucesso.'));
    }

    /**
     * Show a single material.
     */
    public function show(Ministry $ministry, MinistryMaterial $material): View
    {
        if ($material->ministry_id !== $ministry->id) {
            abort(404);
        }
        $material->load('creator:id,name');

        return view('ministry::pastoralpanel.materials.show', compact('ministry', 'material'));
    }

    /**
     * Show edit material form.
     */
    public function edit(Ministry $ministry, MinistryMaterial $material): View
    {
        if ($material->ministry_id !== $ministry->id) {
            abort(404);
        }
        return view('ministry::pastoralpanel.materials.edit', [
            'ministry' => $ministry,
            'material' => $material,
            'typeOptions' => MinistryMaterial::types(),
        ]);
    }

    /**
     * Update a material.
     */
    public function update(Request $request, Ministry $ministry, MinistryMaterial $material): RedirectResponse
    {
        if ($material->ministry_id !== $ministry->id) {
            abort(404);
        }
        $validated = $request->validate([
            'type' => ['required', 'string', 'max:32', 'in:' . implode(',', array_keys(MinistryMaterial::types()))],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'file' => ['nullable', 'file', 'max:10240'],
        ]);

        $filePath = $material->file_path;
        if ($request->hasFile('file')) {
            if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
                Storage::disk('public')->delete($material->file_path);
            }
            $filePath = $request->file('file')->store('ministry_materials/' . $ministry->id, 'public');
        }

        $material->update([
            'type' => $validated['type'],
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'file_path' => $filePath,
        ]);

        return redirect()
            ->route('ministry.materials.show', [$ministry, $material])
            ->with('success', __('Material atualizado com sucesso.'));
    }

    /**
     * Delete a material.
     */
    public function destroy(Ministry $ministry, MinistryMaterial $material): RedirectResponse
    {
        if ($material->ministry_id !== $ministry->id) {
            abort(404);
        }
        if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
            Storage::disk('public')->delete($material->file_path);
        }
        $material->delete();
        return redirect()
            ->route('ministry.materials.index', $ministry)
            ->with('success', __('Material excluído com sucesso.'));
    }
}

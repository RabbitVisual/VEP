<?php

namespace VertexSolutions\Ministry\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use VertexSolutions\Ministry\Models\Ministry;
use VertexSolutions\Ministry\Models\MinistryMember;

class MinistryMemberController extends Controller
{
    /**
     * List members of a ministry.
     */
    public function index(Ministry $ministry): View
    {
        $ministry->load('members.user:id,name,email');
        $availableUsers = User::orderBy('name')
            ->whereNotIn('id', $ministry->members->pluck('user_id'))
            ->get(['id', 'name', 'email']);

        return view('ministry::pastoralpanel.members.index', [
            'ministry' => $ministry,
            'members' => $ministry->members,
            'availableUsers' => $availableUsers,
            'roleOptions' => MinistryMember::roles(),
        ]);
    }

    /**
     * Add a member to the ministry.
     */
    public function store(Request $request, Ministry $ministry): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'role' => ['required', 'string', 'in:leader,collaborator,volunteer'],
        ]);

        $exists = MinistryMember::where('ministry_id', $ministry->id)
            ->where('user_id', $validated['user_id'])
            ->exists();
        if ($exists) {
            return back()->with('error', __('Este usuário já é membro do ministério.'));
        }

        $ministry->members()->create($validated);

        return back()->with('success', __('Membro adicionado com sucesso.'));
    }

    /**
     * Update member role.
     */
    public function update(Request $request, Ministry $ministry, int $member): RedirectResponse
    {
        $memberModel = $ministry->members()->findOrFail($member);

        $validated = $request->validate([
            'role' => ['required', 'string', 'in:leader,collaborator,volunteer'],
        ]);

        $memberModel->update($validated);

        return back()->with('success', __('Função atualizada com sucesso.'));
    }

    /**
     * Remove a member from the ministry.
     */
    public function destroy(Ministry $ministry, int $member): RedirectResponse
    {
        $memberModel = $ministry->members()->findOrFail($member);
        $memberModel->delete();
        return back()->with('success', __('Membro removido do ministério.'));
    }
}

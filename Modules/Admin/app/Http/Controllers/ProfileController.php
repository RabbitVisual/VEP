<?php

namespace VertexSolutions\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(): View
    {
        $user = auth()->user();
        return view('admin::admin.profile.show', ['user' => $user]);
    }

    public function edit(): View
    {
        $user = auth()->user();
        return view('admin::admin.profile.edit', ['user' => $user]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'cpf' => 'nullable|string|max:14',
            'birth_date' => 'nullable|date',
            'phone' => 'nullable|string|max:20',
            'church' => 'nullable|string|max:255',
            'ministry' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'avatar' => 'nullable|string|max:500',
        ]);
        $user->update($validated);
        return redirect()->route('admin.profile.show')->with('success', 'Perfil atualizado.');
    }
}

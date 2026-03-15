<?php

namespace VertexSolutions\MemberPanel\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SensitiveFieldChangeRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(): View
    {
        $user = auth()->user();
        $user->loadMissing([]);
        $sensitiveRequests = SensitiveFieldChangeRequest::where('user_id', $user->id)->get()->keyBy('field_name');
        $canRequestField = fn (string $field) => ! $sensitiveRequests->has($field);

        return view('memberpanel::profile.show', [
            'user' => $user,
            'sensitiveRequests' => $sensitiveRequests,
            'canRequestField' => $canRequestField,
        ]);
    }

    public function edit(): View
    {
        $user = auth()->user();
        return view('memberpanel::profile.edit', ['user' => $user]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'nullable|date',
            'church' => 'nullable|string|max:255',
            'ministry' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);
        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        } else {
            unset($validated['avatar']);
        }
        $user->update(array_filter($validated));
        return redirect()->route('painel.profile.show')->with('success', 'Perfil atualizado.');
    }

    public function requestSensitiveChange(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'field_name' => 'required|string|in:cpf,email,phone',
            'requested_value' => 'required|string|max:255',
        ]);
        $user = auth()->user();
        $exists = SensitiveFieldChangeRequest::where('user_id', $user->id)
            ->where('field_name', $validated['field_name'])
            ->exists();
        if ($exists) {
            return redirect()->route('painel.profile.show')->with('error', 'Este campo já possui uma solicitação (uma única vez por campo).');
        }
        if ($validated['field_name'] === 'email') {
            $request->validate(['requested_value' => 'required|email']);
        }
        SensitiveFieldChangeRequest::create([
            'user_id' => $user->id,
            'field_name' => $validated['field_name'],
            'requested_value' => $validated['requested_value'],
            'status' => SensitiveFieldChangeRequest::STATUS_PENDING,
        ]);
        return redirect()->route('painel.profile.show')->with('success', 'Solicitação de alteração enviada. Aguarde aprovação do administrador.');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\CpfRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginCpfController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'cpf' => ['required', 'string', new CpfRule],
            'password' => ['required', 'string'],
        ]);

        $cpf = preg_replace('/\D/', '', $request->input('cpf'));
        $user = User::where('cpf', $cpf)->first();

        if (! $user || ! Auth::guard('web')->attempt(['email' => $user->email, 'password' => $request->input('password')], $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'cpf' => [__('auth.failed')],
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(config('fortify.home', '/dashboard'));
    }
}

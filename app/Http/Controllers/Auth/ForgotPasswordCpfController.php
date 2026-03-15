<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\CpfRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class ForgotPasswordCpfController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'cpf' => ['required', 'string', new CpfRule],
            'birth_date' => ['required', 'date', 'before:today'],
        ]);

        $cpf = preg_replace('/\D/', '', $request->input('cpf'));
        $birthDate = $request->date('birth_date')->format('Y-m-d');

        $user = User::where('cpf', $cpf)->where('birth_date', $birthDate)->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'cpf' => ['Não encontramos um cadastro com este CPF e data de nascimento.'],
            ]);
        }

        $status = Password::sendResetLink(['email' => $user->email]);

        if ($status !== Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        return back()->with('status', __('Enviamos o link de recuperação para o e-mail cadastrado.'));
    }
}

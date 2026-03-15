<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Database\Seeders\DemoUsersSeeder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/**
 * Login automático em modo desenvolvimento para testes dos painéis.
 * Só disponível quando APP_DEBUG=true ou APP_ENV=local.
 */
class DevLoginController extends Controller
{
    public function __construct()
    {
        if (! $this->isDev()) {
            abort(404);
        }
    }

    /**
     * Logar como usuário demo e redirecionar para o painel correspondente.
     */
    public function store(Request $request): RedirectResponse
    {
        if (! $this->isDev()) {
            abort(404);
        }

        $request->validate([
            'email' => 'required|string|in:'.implode(',', [
                DemoUsersSeeder::EMAIL_ADMIN,
                DemoUsersSeeder::EMAIL_PASTOR,
                DemoUsersSeeder::EMAIL_ALUNO,
            ]),
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();
        if (! $user) {
            return redirect()->route('login')
                ->with('error', 'Usuário demo não encontrado. Execute: php artisan db:seed --class=DemoUsersSeeder');
        }

        Auth::login($user, true);

        $redirectRoute = match ($request->email) {
            DemoUsersSeeder::EMAIL_ADMIN => 'admin.index',
            DemoUsersSeeder::EMAIL_PASTOR => (Route::has('pastoral.dashboard') ? 'pastoral.dashboard' : 'pastoralpanel.index'),
            default => 'painel.dashboard',
        };

        if (! Route::has($redirectRoute)) {
            $redirectRoute = 'painel.dashboard';
        }

        return redirect()->intended(route($redirectRoute))
            ->with('status', 'Entrou como '.$user->name.' (modo demo).');
    }

    private function isDev(): bool
    {
        return config('app.debug') === true || app()->environment('local');
    }
}

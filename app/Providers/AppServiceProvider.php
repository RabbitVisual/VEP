<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->configureExegesisRateLimiting();

        // Registrar namespace de erro para garantir carregamento premium
        $this->app['view']->addNamespace('errors', resource_path('views/errors'));
    }

    /**
     * Rate limiting teológico: exegesis/chat por minuto (proteção de custo API).
     */
    protected function configureExegesisRateLimiting(): void
    {
        $maxPerMinute = config('core.ai.rate_limit.exegesis_per_minute', 10);
        if ($maxPerMinute <= 0) {
            return;
        }

        RateLimiter::for('exegesis', function (Request $request) use ($maxPerMinute) {
            $key = 'exegesis:' . ($request->user()?->id ?? $request->ip());

            return Limit::perMinute($maxPerMinute)->by($key)->response(function () {
                return response()->json([
                    'error' => 'rate_limited',
                    'message' => 'Calma, pastor! Vamos estudar um versículo por vez? Aguarde um minuto antes de enviar outra pergunta.',
                ], 429);
            });
        });
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}

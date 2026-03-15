@extends('homepage::components.layouts.auth')

@section('title', 'Entrar')

@section('authBranding')
    <h2 class="text-2xl font-bold tracking-tight text-foreground lg:text-3xl">Entre na sua conta</h2>
    <p class="mt-3 text-muted-foreground">Acesse a plataforma EAD e todos os recursos.</p>
@endsection

@section('content')
    <h1 class="text-2xl font-bold text-foreground">Entrar</h1>
    @if (isset($status))
        <p class="mt-2 text-sm text-green-600 dark:text-green-400">{{ $status }}</p>
    @endif
    @if ($errors->any())
        <ul class="mt-2 list-inside list-disc text-sm text-red-600 dark:text-red-400">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <div x-data="{ tab: 'email' }" class="mt-6">
        <div class="flex rounded-xl border border-border bg-muted/30 p-1">
            <button type="button" @click="tab = 'email'" :class="tab === 'email' ? 'bg-background shadow text-foreground' : 'text-muted-foreground'" class="flex-1 rounded-lg px-4 py-2.5 text-sm font-medium transition-all duration-200">
                E-mail
            </button>
            <button type="button" @click="tab = 'cpf'" :class="tab === 'cpf' ? 'bg-background shadow text-foreground' : 'text-muted-foreground'" class="flex-1 rounded-lg px-4 py-2.5 text-sm font-medium transition-all duration-200">
                CPF
            </button>
        </div>

        <form method="POST" action="{{ route('login') }}" x-show="tab === 'email'" class="mt-6 space-y-4" x-cloak>
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-foreground">E-mail</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="mt-1.5 block w-full rounded-xl border border-input bg-background px-4 py-2.5 text-foreground shadow-sm transition-colors focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-foreground">Senha</label>
                <input id="password" type="password" name="password" required autocomplete="current-password" class="mt-1.5 block w-full rounded-xl border border-input bg-background px-4 py-2.5 text-foreground shadow-sm transition-colors focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="remember" class="rounded border-input text-primary focus:ring-primary">
                    <span class="text-sm text-muted-foreground">Lembrar de mim</span>
                </label>
                @if ($canResetPassword ?? true)
                    <a href="{{ route('password.request') }}" class="text-sm font-medium text-primary hover:underline">Esqueci a senha</a>
                @endif
            </div>
            <button type="submit" class="w-full rounded-xl bg-primary px-4 py-3 font-semibold text-primary-foreground shadow-sm transition-all duration-200 hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">Entrar</button>
        </form>

        <form method="POST" action="{{ route('login.cpf') }}" x-show="tab === 'cpf'" class="mt-6 space-y-4" x-cloak>
            @csrf
            <div>
                <label for="cpf" class="block text-sm font-medium text-foreground">CPF</label>
                <input id="cpf" type="text" name="cpf" value="{{ old('cpf') }}" inputmode="numeric" maxlength="14" placeholder="000.000.000-00" class="mt-1.5 block w-full rounded-xl border border-input bg-background px-4 py-2.5 text-foreground shadow-sm transition-colors focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>
            <div>
                <label for="password_cpf" class="block text-sm font-medium text-foreground">Senha</label>
                <input id="password_cpf" type="password" name="password" required autocomplete="current-password" class="mt-1.5 block w-full rounded-xl border border-input bg-background px-4 py-2.5 text-foreground shadow-sm transition-colors focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>
            <button type="submit" class="w-full rounded-xl bg-primary px-4 py-3 font-semibold text-primary-foreground shadow-sm transition-all duration-200 hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">Entrar com CPF</button>
        </form>
    </div>

    @if ($canRegister ?? true)
        <p class="mt-8 text-center text-sm text-muted-foreground">
            Não tem conta? <a href="{{ route('register') }}" class="font-semibold text-primary hover:underline">Cadastre-se</a>
        </p>
    @endif

    @if ((config('app.debug') || app()->environment('local')) && Route::has('dev-login.store'))
        <div class="mt-8 rounded-xl border border-amber-500/40 bg-amber-500/10 p-4 dark:border-amber-400/30 dark:bg-amber-500/5">
            <p class="text-center text-xs font-semibold uppercase tracking-wider text-amber-700 dark:text-amber-400">Modo desenvolvimento – login automático</p>
            <p class="mt-1 text-center text-sm text-muted-foreground">Use os botões abaixo para testar os painéis (execute <code class="rounded bg-muted px-1 text-xs">php artisan db:seed --class=DemoUsersSeeder</code> se necessário).</p>
            <div class="mt-4 flex flex-col gap-2 sm:flex-row sm:justify-center">
                <form method="POST" action="{{ route('dev-login.store') }}" class="inline">
                    @csrf
                    <input type="hidden" name="email" value="{{ \Database\Seeders\DemoUsersSeeder::EMAIL_ALUNO }}">
                    <button type="submit" class="w-full rounded-lg border border-slate-300 bg-slate-100 px-4 py-2.5 text-sm font-medium text-slate-800 transition-colors hover:bg-slate-200 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700 sm:w-auto">
                        Entrar como Aluno Demo
                    </button>
                </form>
                <form method="POST" action="{{ route('dev-login.store') }}" class="inline">
                    @csrf
                    <input type="hidden" name="email" value="{{ \Database\Seeders\DemoUsersSeeder::EMAIL_PASTOR }}">
                    <button type="submit" class="w-full rounded-lg border border-indigo-300 bg-indigo-100 px-4 py-2.5 text-sm font-medium text-indigo-800 transition-colors hover:bg-indigo-200 dark:border-indigo-600 dark:bg-indigo-900/40 dark:text-indigo-200 dark:hover:bg-indigo-800/40 sm:w-auto">
                        Entrar como Pastor Demo
                    </button>
                </form>
                <form method="POST" action="{{ route('dev-login.store') }}" class="inline">
                    @csrf
                    <input type="hidden" name="email" value="{{ \Database\Seeders\DemoUsersSeeder::EMAIL_ADMIN }}">
                    <button type="submit" class="w-full rounded-lg border border-rose-300 bg-rose-100 px-4 py-2.5 text-sm font-medium text-rose-800 transition-colors hover:bg-rose-200 dark:border-rose-600 dark:bg-rose-900/40 dark:text-rose-200 dark:hover:bg-rose-800/40 sm:w-auto">
                        Entrar como Admin Demo
                    </button>
                </form>
            </div>
        </div>
    @endif
@endsection

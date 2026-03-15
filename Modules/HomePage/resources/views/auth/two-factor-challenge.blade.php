@extends('homepage::components.layouts.auth')

@section('title', 'Autenticação em duas etapas')

@section('authBranding')
    <h2 class="text-2xl font-bold tracking-tight text-foreground lg:text-3xl">Autenticação em duas etapas</h2>
    <p class="mt-3 text-muted-foreground">Digite o código do seu aplicativo ou use um código de recuperação.</p>
@endsection

@section('content')
    <h1 class="text-2xl font-bold text-foreground">Verificação 2FA</h1>
    @if ($errors->any())
        <ul class="mt-2 list-inside list-disc text-sm text-red-600 dark:text-red-400">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <div x-data="{ useRecovery: false }" class="mt-6">
        <div class="flex rounded-xl border border-border bg-muted/30 p-1 mb-6">
            <button type="button" @click="useRecovery = false" :class="!useRecovery ? 'bg-background shadow text-foreground' : 'text-muted-foreground'" class="flex-1 rounded-lg px-4 py-2.5 text-sm font-medium transition-all duration-200">
                Código do app
            </button>
            <button type="button" @click="useRecovery = true" :class="useRecovery ? 'bg-background shadow text-foreground' : 'text-muted-foreground'" class="flex-1 rounded-lg px-4 py-2.5 text-sm font-medium transition-all duration-200">
                Código de recuperação
            </button>
        </div>

        <form method="POST" action="{{ url('/two-factor-challenge') }}" x-show="!useRecovery" class="space-y-4" x-cloak>
            @csrf
            <div>
                <label for="code" class="block text-sm font-medium text-foreground">Código</label>
                <input id="code" type="text" name="code" inputmode="numeric" autocomplete="one-time-code" placeholder="000000" class="mt-1.5 block w-full rounded-xl border border-input bg-background px-4 py-2.5 text-foreground shadow-sm transition-colors focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>
            <button type="submit" class="w-full rounded-xl bg-primary px-4 py-3 font-semibold text-primary-foreground shadow-sm transition-all duration-200 hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">Verificar</button>
        </form>
        <form method="POST" action="{{ url('/two-factor-challenge') }}" x-show="useRecovery" x-cloak class="space-y-4">
            @csrf
            <div>
                <label for="recovery_code" class="block text-sm font-medium text-foreground">Código de recuperação</label>
                <input id="recovery_code" type="text" name="recovery_code" class="mt-1.5 block w-full rounded-xl border border-input bg-background px-4 py-2.5 text-foreground shadow-sm transition-colors focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>
            <button type="submit" class="w-full rounded-xl bg-primary px-4 py-3 font-semibold text-primary-foreground shadow-sm transition-all duration-200 hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">Verificar</button>
        </form>
    </div>
@endsection

@extends('homepage::components.layouts.auth')

@section('title', 'Confirmar senha')

@section('authBranding')
    <h2 class="text-2xl font-bold tracking-tight text-foreground lg:text-3xl">Confirmação de senha</h2>
    <p class="mt-3 text-muted-foreground">Esta área é protegida. Confirme sua senha para continuar.</p>
@endsection

@section('content')
    <h1 class="text-2xl font-bold text-foreground">Confirmar senha</h1>
    <p class="mt-2 text-sm text-muted-foreground">Digite sua senha atual para acessar esta área.</p>
    @if ($errors->any())
        <ul class="mt-2 list-inside list-disc text-sm text-red-600 dark:text-red-400">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('password.confirm') }}" class="mt-6 space-y-4">
        @csrf
        <div>
            <label for="password" class="block text-sm font-medium text-foreground">Senha</label>
            <input id="password" type="password" name="password" required autocomplete="current-password" class="mt-1.5 block w-full rounded-xl border border-input bg-background px-4 py-2.5 text-foreground shadow-sm transition-colors focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
        </div>
        <button type="submit" class="w-full rounded-xl bg-primary px-4 py-3 font-semibold text-primary-foreground shadow-sm transition-all duration-200 hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">Confirmar</button>
    </form>
@endsection

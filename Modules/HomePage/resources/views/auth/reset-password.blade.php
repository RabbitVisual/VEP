@extends('homepage::components.layouts.auth')

@section('title', 'Nova senha')

@section('authBranding')
    <h2 class="text-2xl font-bold tracking-tight text-foreground lg:text-3xl">Definir nova senha</h2>
    <p class="mt-3 text-muted-foreground">Use o link que enviamos por e-mail para criar uma nova senha.</p>
@endsection

@section('content')
    <h1 class="text-2xl font-bold text-foreground">Nova senha</h1>
    @if ($errors->any())
        <ul class="mt-2 list-inside list-disc text-sm text-red-600 dark:text-red-400">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('password.store') }}" class="mt-6 space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div>
            <label for="email" class="block text-sm font-medium text-foreground">E-mail</label>
            <input id="email" type="email" name="email" value="{{ $email }}" required readonly class="mt-1.5 block w-full rounded-xl border border-input bg-muted/50 px-4 py-2.5 text-foreground">
        </div>
        <div>
            <label for="password" class="block text-sm font-medium text-foreground">Nova senha</label>
            <input id="password" type="password" name="password" required autocomplete="new-password" class="mt-1.5 block w-full rounded-xl border border-input bg-background px-4 py-2.5 text-foreground shadow-sm transition-colors focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
        </div>
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-foreground">Confirmar senha</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="mt-1.5 block w-full rounded-xl border border-input bg-background px-4 py-2.5 text-foreground shadow-sm transition-colors focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
        </div>
        <button type="submit" class="w-full rounded-xl bg-primary px-4 py-3 font-semibold text-primary-foreground shadow-sm transition-all duration-200 hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">Redefinir senha</button>
    </form>
@endsection

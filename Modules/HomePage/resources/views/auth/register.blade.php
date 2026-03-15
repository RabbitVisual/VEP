@extends('homepage::components.layouts.auth')

@section('title', 'Criar conta')

@section('authBranding')
    <h2 class="text-2xl font-bold tracking-tight text-foreground lg:text-3xl">Criar sua conta</h2>
    <p class="mt-3 text-muted-foreground">Preencha os dados e comece a usar a plataforma.</p>
@endsection

@section('content')
    <h1 class="text-2xl font-bold text-foreground">Cadastro</h1>
    @if ($errors->any())
        <ul class="mt-2 list-inside list-disc text-sm text-red-600 dark:text-red-400">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="first_name" class="block text-sm font-medium text-foreground">Nome</label>
                <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" required autofocus class="mt-1.5 block w-full rounded-xl border border-input bg-background px-4 py-2.5 text-foreground shadow-sm transition-colors focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>
            <div>
                <label for="last_name" class="block text-sm font-medium text-foreground">Sobrenome</label>
                <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" required class="mt-1.5 block w-full rounded-xl border border-input bg-background px-4 py-2.5 text-foreground shadow-sm transition-colors focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>
        </div>
        <div>
            <label for="email" class="block text-sm font-medium text-foreground">E-mail</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" class="mt-1.5 block w-full rounded-xl border border-input bg-background px-4 py-2.5 text-foreground shadow-sm transition-colors focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
        </div>
        <div>
            <label for="cpf" class="block text-sm font-medium text-foreground">CPF</label>
            <input id="cpf" type="text" name="cpf" value="{{ old('cpf') }}" maxlength="14" placeholder="000.000.000-00" class="mt-1.5 block w-full rounded-xl border border-input bg-background px-4 py-2.5 text-foreground shadow-sm transition-colors focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
        </div>
        <div>
            <label for="phone" class="block text-sm font-medium text-foreground">Celular</label>
            <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" class="mt-1.5 block w-full rounded-xl border border-input bg-background px-4 py-2.5 text-foreground shadow-sm transition-colors focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
        </div>
        <div>
            <label for="password" class="block text-sm font-medium text-foreground">Senha</label>
            <input id="password" type="password" name="password" required autocomplete="new-password" class="mt-1.5 block w-full rounded-xl border border-input bg-background px-4 py-2.5 text-foreground shadow-sm transition-colors focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
        </div>
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-foreground">Confirmar senha</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="mt-1.5 block w-full rounded-xl border border-input bg-background px-4 py-2.5 text-foreground shadow-sm transition-colors focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
        </div>
        <button type="submit" class="w-full rounded-xl bg-primary px-4 py-3 font-semibold text-primary-foreground shadow-sm transition-all duration-200 hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">Cadastrar</button>
    </form>

    <p class="mt-8 text-center text-sm text-muted-foreground">
        Já tem conta? <a href="{{ route('login') }}" class="font-semibold text-primary hover:underline">Entrar</a>
    </p>
@endsection

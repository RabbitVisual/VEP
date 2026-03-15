@extends('homepage::components.layouts.auth')

@section('title', 'Recuperar senha')

@section('authBranding')
    <h2 class="text-2xl font-bold tracking-tight text-foreground lg:text-3xl">Recuperar senha</h2>
    <p class="mt-3 text-muted-foreground">Informe seu e-mail ou CPF e data de nascimento para receber o link.</p>
@endsection

@section('content')
    <h1 class="text-2xl font-bold text-foreground">Recuperar senha</h1>
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
                Por e-mail
            </button>
            <button type="button" @click="tab = 'cpf'" :class="tab === 'cpf' ? 'bg-background shadow text-foreground' : 'text-muted-foreground'" class="flex-1 rounded-lg px-4 py-2.5 text-sm font-medium transition-all duration-200">
                Por CPF + Data nasc.
            </button>
        </div>

        <form method="POST" action="{{ route('password.email') }}" x-show="tab === 'email'" class="mt-6 space-y-4" x-cloak>
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-foreground">E-mail</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="mt-1.5 block w-full rounded-xl border border-input bg-background px-4 py-2.5 text-foreground shadow-sm transition-colors focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>
            <button type="submit" class="w-full rounded-xl bg-primary px-4 py-3 font-semibold text-primary-foreground shadow-sm transition-all duration-200 hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">Enviar link de recuperação</button>
        </form>

        <form method="POST" action="{{ route('password.email.cpf') }}" x-show="tab === 'cpf'" class="mt-6 space-y-4" x-cloak>
            @csrf
            <div>
                <label for="cpf_recovery" class="block text-sm font-medium text-foreground">CPF</label>
                <input id="cpf_recovery" type="text" name="cpf" value="{{ old('cpf') }}" maxlength="14" placeholder="000.000.000-00" class="mt-1.5 block w-full rounded-xl border border-input bg-background px-4 py-2.5 text-foreground shadow-sm transition-colors focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>
            <div>
                <label for="birth_date" class="block text-sm font-medium text-foreground">Data de nascimento</label>
                <input id="birth_date" type="date" name="birth_date" value="{{ old('birth_date') }}" required class="mt-1.5 block w-full rounded-xl border border-input bg-background px-4 py-2.5 text-foreground shadow-sm transition-colors focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>
            <button type="submit" class="w-full rounded-xl bg-primary px-4 py-3 font-semibold text-primary-foreground shadow-sm transition-all duration-200 hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">Enviar link por e-mail</button>
        </form>
    </div>

    <p class="mt-8 text-center text-sm text-muted-foreground">
        <a href="{{ route('login') }}" class="font-semibold text-primary hover:underline">Voltar ao login</a>
    </p>
@endsection

@extends('homepage::components.layouts.auth')

@section('title', 'Verificar e-mail')

@section('authBranding')
    <h2 class="text-2xl font-bold tracking-tight text-foreground lg:text-3xl">Verificação de e-mail</h2>
    <p class="mt-3 text-muted-foreground">Enviamos um link para o seu e-mail. Clique para ativar sua conta.</p>
@endsection

@section('content')
    <h1 class="text-2xl font-bold text-foreground">Verifique seu e-mail</h1>
    @if (isset($status))
        <p class="mt-2 text-sm text-green-600 dark:text-green-400">{{ $status }}</p>
    @else
        <p class="mt-2 text-muted-foreground">Enviamos um link de verificação para o seu e-mail.</p>
    @endif
    <form method="POST" action="{{ route('verification.send') }}" class="mt-6">
        @csrf
        <button type="submit" class="w-full rounded-xl bg-primary px-4 py-3 font-semibold text-primary-foreground shadow-sm transition-all duration-200 hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">Reenviar e-mail</button>
    </form>
    <p class="mt-8 text-center text-sm text-muted-foreground">
        <a href="{{ route('login') }}" class="font-semibold text-primary hover:underline">Voltar ao login</a>
    </p>
@endsection

@extends('admin::components.layouts.master')

@section('title', 'Meu perfil')

@section('content')
    <div class="p-6 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="space-y-1">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight flex items-center gap-2">
                    <x-icon name="user" style="duotone" class="w-9 h-9 text-indigo-500" />
                    Meu perfil (Admin)
                </h1>
                <p class="text-gray-600 dark:text-gray-400">Como administrador, você pode alterar todos os dados diretamente, incluindo CPF, e-mail e telefone.</p>
            </div>
            <a href="{{ route('admin.profile.edit') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700">
                <x-icon name="pen-to-square" style="duotone" class="w-4 h-4" />
                Editar perfil
            </a>
        </div>

        @if (session('success'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 dark:border-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-200" role="alert">{{ session('success') }}</div>
        @endif

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800/50 p-6">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Dados pessoais</h2>
                <dl class="space-y-3">
                    <div><dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Nome</dt><dd class="text-gray-900 dark:text-white">{{ $user->first_name }} {{ $user->last_name }}</dd></div>
                    <div><dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Data de nascimento</dt><dd class="text-gray-900 dark:text-white">{{ $user->birth_date?->format('d/m/Y') ?? '—' }}</dd></div>
                    <div><dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Igreja / Ministério</dt><dd class="text-gray-900 dark:text-white">{{ $user->church ?? '—' }} {{ $user->ministry ? ' · ' . $user->ministry : '' }}</dd></div>
                </dl>
            </div>
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800/50 p-6">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Dados sensíveis</h2>
                <dl class="space-y-3">
                    <div><dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">CPF</dt><dd class="text-gray-900 dark:text-white">{{ $user->cpf ?? '—' }}</dd></div>
                    <div><dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">E-mail</dt><dd class="text-gray-900 dark:text-white">{{ $user->email }}</dd></div>
                    <div><dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Telefone</dt><dd class="text-gray-900 dark:text-white">{{ $user->phone ?? '—' }}</dd></div>
                </dl>
            </div>
        </div>

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.change-requests.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <x-icon name="clipboard-list" style="duotone" class="w-4 h-4" />
                Gerenciar solicitações de alteração
            </a>
            <a href="{{ route('admin.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">Voltar ao painel</a>
        </div>
    </div>
@endsection

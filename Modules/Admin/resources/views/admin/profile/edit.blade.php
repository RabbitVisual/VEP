@extends('admin::components.layouts.master')

@section('title', 'Editar perfil')

@section('content')
    <div class="p-6 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="space-y-1">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight flex items-center gap-2">
                    <x-icon name="pen-to-square" style="duotone" class="w-9 h-9 text-indigo-500" />
                    Editar perfil (Admin)
                </h1>
                <p class="text-gray-600 dark:text-gray-400">Altere qualquer dado, incluindo CPF, e-mail e telefone. Apenas o admin pode editar dados sensíveis diretamente.</p>
            </div>
            <a href="{{ route('admin.profile.show') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800">Voltar</a>
        </div>

        @if ($errors->any())
            <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800 dark:border-red-800 dark:bg-red-900/20 dark:text-red-200" role="alert">
                <ul class="list-disc list-inside space-y-1">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('admin.profile.update') }}" method="POST" class="max-w-2xl space-y-6">
            @csrf
            @method('PUT')
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800/50 p-6 space-y-4">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Dados pessoais</h2>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome</label>
                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $user->first_name) }}" required class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                    </div>
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sobrenome</label>
                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->last_name) }}" required class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                    </div>
                </div>
                <div>
                    <label for="birth_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data de nascimento</label>
                    <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', $user->birth_date?->format('Y-m-d')) }}" class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                </div>
                <div>
                    <label for="church" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Igreja</label>
                    <input type="text" name="church" id="church" value="{{ old('church', $user->church) }}" class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                </div>
                <div>
                    <label for="ministry" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ministério</label>
                    <input type="text" name="ministry" id="ministry" value="{{ old('ministry', $user->ministry) }}" class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                </div>
            </div>
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800/50 p-6 space-y-4">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Dados sensíveis (somente admin)</h2>
                <div>
                    <label for="cpf" class="block text-sm font-medium text-gray-700 dark:text-gray-300">CPF</label>
                    <input type="text" name="cpf" id="cpf" value="{{ old('cpf', $user->cpf) }}" maxlength="14" class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white" placeholder="000.000.000-00">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">E-mail</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                </div>
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Telefone</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" maxlength="20" class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                </div>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700">Salvar</button>
                <a href="{{ route('admin.profile.show') }}" class="rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800">Cancelar</a>
            </div>
        </form>
    </div>
@endsection

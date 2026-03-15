@extends('pastoralpanel::components.layouts.master')

@section('title', 'Equipe – ' . $ministry->name)

@section('content')
    <div class="p-6 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <a href="{{ route('ministry.show', $ministry) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline mb-2 inline-block">
                    ← {{ $ministry->name }}
                </a>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <x-icon name="users" style="duotone" class="w-8 h-8 text-indigo-500" />
                    Equipe
                </h1>
            </div>
        </div>

        @if (session('success'))
            <div class="p-4 text-sm text-green-800 bg-green-50 border border-green-200 rounded-lg dark:bg-green-900/20 dark:text-green-300 flex items-center" role="alert">
                <x-icon name="circle-check" class="w-5 h-5 mr-3 flex-shrink-0" />
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="p-4 text-sm text-red-800 bg-red-50 border border-red-200 rounded-lg dark:bg-red-900/20 dark:text-red-300 flex items-center" role="alert">
                <x-icon name="circle-exclamation" class="w-5 h-5 mr-3 flex-shrink-0" />
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="backdrop-blur-xl bg-white/70 dark:bg-white/10 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Adicionar membro</h2>
            <form action="{{ route('ministry.members.store', $ministry) }}" method="POST" class="flex flex-wrap items-end gap-3">
                @csrf
                <div class="min-w-[200px]">
                    <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Usuário</label>
                    <select name="user_id" id="user_id" required class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                        <option value="">Selecione</option>
                        @foreach ($availableUsers as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="min-w-[140px]">
                    <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Função</label>
                    <select name="role" id="role" class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                        @foreach ($roleOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg">
                    <x-icon name="plus" class="w-4 h-4 mr-2" />
                    Adicionar
                </button>
            </form>
            @if ($availableUsers->isEmpty())
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Todos os usuários já estão neste ministério ou não há outros usuários.</p>
            @endif
        </div>

        <div class="backdrop-blur-xl bg-white/70 dark:bg-white/10 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Membros ({{ $members->count() }})</h2>
            </div>
            @if ($members->count() > 0)
                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($members as $member)
                        <li class="px-6 py-4 flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $member->user->name ?? '—' }}</span>
                                <span class="text-gray-500 dark:text-gray-400 text-sm ml-2">{{ $member->user->email ?? '' }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <form action="{{ route('ministry.members.update', [$ministry, $member->id]) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <select name="role" onchange="this.form.submit()" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                                        @foreach ($roleOptions as $value => $label)
                                            <option value="{{ $value }}" {{ $member->role === $value ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </form>
                                <form action="{{ route('ministry.members.destroy', [$ministry, $member->id]) }}" method="POST" class="inline" onsubmit="return confirm('Remover este membro do ministério?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 dark:text-red-400 hover:underline text-sm">Remover</button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="px-6 py-8 text-gray-500 dark:text-gray-400 text-center">Nenhum membro na equipe.</p>
            @endif
        </div>
    </div>
@endsection

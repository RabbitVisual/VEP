@extends('pastoralpanel::components.layouts.master')

@section('title', 'Ministérios')

@section('content')
    <div class="p-6 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="space-y-1">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight flex items-center gap-2">
                    <x-icon name="users-viewfinder" style="duotone" class="w-9 h-9 text-indigo-500" />
                    Ministérios
                </h1>
                <p class="text-gray-600 dark:text-gray-400">Gerencie os departamentos da igreja (Louvor, Infantil, Missões, Jovens e outros).</p>
            </div>
            <a href="{{ route('ministry.create') }}"
                class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 focus:ring-4 focus:ring-indigo-300 dark:focus:ring-indigo-800">
                <x-icon name="plus" class="w-5 h-5 mr-2" />
                Novo ministério
            </a>
        </div>

        @if (session('success'))
            <div class="p-4 text-sm text-green-800 bg-green-50 border border-green-200 rounded-lg dark:bg-green-900/20 dark:text-green-300 dark:border-green-800 flex items-center" role="alert">
                <x-icon name="circle-check" class="w-5 h-5 mr-3 flex-shrink-0" />
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($ministries->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                    $colorBg = [
                        'amber-500' => 'bg-amber-500/20',
                        'blue-500' => 'bg-blue-500/20',
                        'emerald-500' => 'bg-emerald-500/20',
                        'violet-500' => 'bg-violet-500/20',
                        'rose-500' => 'bg-rose-500/20',
                        'sky-500' => 'bg-sky-500/20',
                        'indigo-500' => 'bg-indigo-500/20',
                    ];
                    $colorText = [
                        'amber-500' => 'text-amber-600 dark:text-amber-400',
                        'blue-500' => 'text-blue-600 dark:text-blue-400',
                        'emerald-500' => 'text-emerald-600 dark:text-emerald-400',
                        'violet-500' => 'text-violet-600 dark:text-violet-400',
                        'rose-500' => 'text-rose-600 dark:text-rose-400',
                        'sky-500' => 'text-sky-600 dark:text-sky-400',
                        'indigo-500' => 'text-indigo-600 dark:text-indigo-400',
                    ];
                @endphp
                @foreach ($ministries as $ministry)
                    <div class="backdrop-blur-xl bg-white/70 dark:bg-white/10 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg transition-all duration-300">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 rounded-xl flex items-center justify-center {{ $colorBg[$ministry->color] ?? 'bg-gray-500/20' }}">
                                <x-icon name="{{ str_replace('fa-', '', $ministry->icon) }}" style="duotone" class="w-6 h-6 {{ $colorText[$ministry->color] ?? 'text-gray-600' }}" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white truncate">{{ $ministry->name }}</h3>
                                @if ($ministry->leader)
                                    <p class="text-sm text-gray-600 dark:text-gray-400 truncate">{{ $ministry->leader->name }}</p>
                                @else
                                    <p class="text-sm text-gray-500 dark:text-gray-500">Sem líder definido</p>
                                @endif
                            </div>
                        </div>
                        @if ($ministry->description)
                            <div class="px-6 py-3">
                                <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">{{ $ministry->description }}</p>
                            </div>
                        @endif
                        <div class="px-6 py-4 bg-gray-50/50 dark:bg-gray-800/30 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
                            <a href="{{ route('ministry.show', $ministry) }}"
                                class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-indigo-700 bg-indigo-100 rounded-lg hover:bg-indigo-200 dark:bg-indigo-900/30 dark:text-indigo-300">
                                <x-icon name="eye" style="duotone" class="w-4 h-4 mr-1.5" />
                                Ver
                            </a>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('ministry.edit', $ministry) }}"
                                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300">
                                    <x-icon name="pen-to-square" style="duotone" class="w-4 h-4 mr-1.5" />
                                    Editar
                                </a>
                                <form action="{{ route('ministry.destroy', $ministry) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Tem certeza que deseja excluir este ministério?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-red-700 bg-red-100 rounded-lg hover:bg-red-200 dark:bg-red-900/30 dark:text-red-300">
                                        <x-icon name="trash-can" style="duotone" class="w-4 h-4" />
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-16 px-4 bg-white/70 dark:bg-white/10 backdrop-blur-xl rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-700">
                <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                    <x-icon name="users-viewfinder" style="duotone" class="w-10 h-10 text-gray-400 dark:text-gray-500" />
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Nenhum ministério cadastrado</h3>
                <p class="text-gray-600 dark:text-gray-400 text-center max-w-md mb-6">Comece criando um ministério (Louvor, Infantil, Missões, Jovens, etc.).</p>
                <a href="{{ route('ministry.create') }}"
                    class="inline-flex items-center px-6 py-3 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                    <x-icon name="plus" class="w-5 h-5 mr-2" />
                    Criar primeiro ministério
                </a>
            </div>
        @endif
    </div>
@endsection

@extends('pastoralpanel::components.layouts.master')

@section('title', 'Materiais – ' . $ministry->name)

@section('content')
    <div class="p-6 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <a href="{{ route('ministry.show', $ministry) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline mb-2 inline-block">
                    ← {{ $ministry->name }}
                </a>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <x-icon name="folder-open" style="duotone" class="w-8 h-8 text-indigo-500" />
                    Materiais
                </h1>
            </div>
            <a href="{{ route('ministry.materials.create', $ministry) }}"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg">
                <x-icon name="plus" class="w-5 h-5 mr-2" />
                Novo material
            </a>
        </div>

        @if (session('success'))
            <div class="p-4 text-sm text-green-800 bg-green-50 border border-green-200 rounded-lg dark:bg-green-900/20 dark:text-green-300 flex items-center" role="alert">
                <x-icon name="circle-check" class="w-5 h-5 mr-3 flex-shrink-0" />
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="flex flex-wrap gap-2 mb-4">
            <a href="{{ route('ministry.materials.index', $ministry) }}"
                class="inline-flex px-3 py-1.5 text-sm font-medium rounded-lg {{ !$typeFilter ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                Todos
            </a>
            @foreach ($typeOptions as $value => $label)
                <a href="{{ route('ministry.materials.index', [$ministry, 'type' => $value]) }}"
                    class="inline-flex px-3 py-1.5 text-sm font-medium rounded-lg {{ $typeFilter === $value ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        @if ($materials->count() > 0)
            <div class="space-y-3">
                @foreach ($materials as $material)
                    <div class="backdrop-blur-xl bg-white/70 dark:bg-white/10 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 flex flex-wrap items-center justify-between gap-3">
                        <div class="min-w-0">
                            <h3 class="font-semibold text-gray-900 dark:text-white truncate">{{ $material->title }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $typeOptions[$material->type] ?? $material->type }}
                                @if ($material->creator)
                                    · {{ $material->creator->name }}
                                @endif
                                · {{ $material->created_at->format('d/m/Y') }}
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('ministry.materials.show', [$ministry, $material]) }}"
                                class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-indigo-700 bg-indigo-100 rounded-lg hover:bg-indigo-200 dark:bg-indigo-900/30 dark:text-indigo-300">
                                Ver
                            </a>
                            <a href="{{ route('ministry.materials.edit', [$ministry, $material]) }}"
                                class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg dark:bg-gray-700 dark:text-gray-300">
                                Editar
                            </a>
                            <form action="{{ route('ministry.materials.destroy', [$ministry, $material]) }}" method="POST" class="inline"
                                onsubmit="return confirm('Excluir este material?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-red-700 bg-red-100 rounded-lg dark:bg-red-900/30 dark:text-red-300">
                                    Excluir
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="backdrop-blur-xl bg-white/70 dark:bg-white/10 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-700 p-12 text-center">
                <x-icon name="folder-open" style="duotone" class="w-12 h-12 text-gray-400 dark:text-gray-500 mx-auto mb-3" />
                <p class="text-gray-600 dark:text-gray-400 mb-4">Nenhum material cadastrado.</p>
                <a href="{{ route('ministry.materials.create', $ministry) }}"
                    class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg">
                    <x-icon name="plus" class="w-5 h-5 mr-2" />
                    Criar primeiro material
                </a>
            </div>
        @endif
    </div>
@endsection

@extends('pastoralpanel::components.layouts.master')

@section('title', $material->title . ' – ' . $ministry->name)

@section('content')
    <div class="p-6 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <a href="{{ route('ministry.materials.index', $ministry) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline mb-2 inline-block">
                    ← Materiais
                </a>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $material->title }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-0.5">
                    {{ \VertexSolutions\Ministry\Models\MinistryMaterial::types()[$material->type] ?? $material->type }}
                    @if ($material->creator)
                        · {{ $material->creator->name }}
                    @endif
                    · {{ $material->created_at->format('d/m/Y H:i') }}
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('ministry.materials.edit', [$ministry, $material]) }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-indigo-700 bg-indigo-100 rounded-lg hover:bg-indigo-200 dark:bg-indigo-900/30 dark:text-indigo-300">
                    Editar
                </a>
                <form action="{{ route('ministry.materials.destroy', [$ministry, $material]) }}" method="POST" class="inline"
                    onsubmit="return confirm('Excluir este material?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-red-700 bg-red-100 rounded-lg dark:bg-red-900/30 dark:text-red-300">
                        Excluir
                    </button>
                </form>
            </div>
        </div>

        @if (session('success'))
            <div class="p-4 text-sm text-green-800 bg-green-50 border border-green-200 rounded-lg dark:bg-green-900/20 dark:text-green-300 flex items-center" role="alert">
                <x-icon name="circle-check" class="w-5 h-5 mr-3 flex-shrink-0" />
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="backdrop-blur-xl bg-white/70 dark:bg-white/10 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            @if ($material->content)
                <div class="prose dark:prose-invert max-w-none">
                    <div class="whitespace-pre-wrap text-gray-700 dark:text-gray-300">{{ $material->content }}</div>
                </div>
            @endif
            @if ($material->file_path)
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ asset('storage/' . $material->file_path) }}" target="_blank" rel="noopener"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-indigo-700 bg-indigo-100 rounded-lg hover:bg-indigo-200 dark:bg-indigo-900/30 dark:text-indigo-300">
                        <x-icon name="file-arrow-down" style="duotone" class="w-5 h-5 mr-2" />
                        Baixar anexo: {{ basename($material->file_path) }}
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection

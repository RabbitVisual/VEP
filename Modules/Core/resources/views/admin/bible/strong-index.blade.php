@extends('admin::components.layouts.master')

@section('title', 'Concordância Strong – Admin')

@section('content')
    <div class="p-6 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Concordância Strong</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Refine explicações e contexto (lemma_br, descrição, DITAT/TWOT, Gematria). Campos originais preservados; edição livre do equivalente em português.</p>
            </div>
            <a href="{{ route('admin.bible.index') }}"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors">
                <x-icon name="arrow-left" style="duotone" class="w-4 h-4 mr-2" />
                Voltar à Bíblia Digital
            </a>
        </div>

        @if (session('success'))
            <div class="p-4 text-sm text-green-800 bg-green-50 border border-green-200 rounded-lg dark:bg-green-900/20 dark:text-green-300 dark:border-green-800 flex items-center" role="alert">
                <x-icon name="circle-check" class="w-5 h-5 mr-3 flex-shrink-0" />
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <form method="GET" action="{{ route('admin.bible.strong.index') }}" class="flex flex-wrap items-center gap-3">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Número, lemma, lemma_br, transliteração..."
                        class="flex-1 min-w-[200px] px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                    <select name="language" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                        <option value="">Todos (H/G)</option>
                        <option value="H" {{ request('language') === 'H' ? 'selected' : '' }}>Hebraico (H)</option>
                        <option value="G" {{ request('language') === 'G' ? 'selected' : '' }}>Grego (G)</option>
                    </select>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                        Pesquisar
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Número</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Lemma</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Lemma (BR)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Transliteração</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Classe</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ação</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($strongs as $s)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                <td class="px-6 py-3 whitespace-nowrap text-sm font-mono font-semibold text-gray-900 dark:text-white">{{ $s->number }}</td>
                                <td class="px-6 py-3 text-sm text-gray-700 dark:text-gray-300" dir="rtl">{{ $s->lemma }}</td>
                                <td class="px-6 py-3 text-sm text-gray-700 dark:text-gray-300 max-w-xs truncate" title="{{ $s->lemma_br }}">{{ Str::limit($s->lemma_br, 40) }}</td>
                                <td class="px-6 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $s->transliteration }}</td>
                                <td class="px-6 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $s->part_of_speech ?? '—' }}</td>
                                <td class="px-6 py-3 text-right whitespace-nowrap">
                                    <a href="{{ route('admin.bible.strong.edit', $s) }}"
                                        class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-indigo-700 bg-indigo-100 rounded-lg hover:bg-indigo-200 dark:bg-indigo-900/30 dark:text-indigo-300 dark:hover:bg-indigo-900/50 transition-colors">
                                        <x-icon name="pen-to-square" style="duotone" class="w-4 h-4 mr-1.5" />
                                        Editar
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">Nenhum registro Strong encontrado. Execute <code class="px-1.5 py-0.5 bg-gray-100 dark:bg-gray-700 rounded">php artisan bible:import-interlinear --strongs</code> para importar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($strongs->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $strongs->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

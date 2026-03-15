@extends('memberpanel::components.layouts.master')

@section('title', $book->name . ' - ' . $version->name)

@include('bible::memberpanel.bible.partials.bible-header-assets')
@section('content')
    <div class="min-h-screen bible-page-wrapper bg-gray-50 dark:bg-slate-950 transition-colors pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 sm:pt-6 space-y-6">

            @include('bible::memberpanel.bible.partials.bible-reader-bar', [
                'title' => $book->name,
                'subtitle' => ($book->testament == 'old' ? 'Antigo Testamento' : 'Novo Testamento') . ' · ' . $chapters->count() . ' capítulos',
                'showBack' => true,
                'backUrl' => route('painel.bible.read', $version->abbreviation),
                'showVersionSelector' => false,
                'showSearch' => true,
                'showFavorites' => true,
                'showInterlinear' => true,
                'showLeitura' => false,
            ])

            <!-- Info Card -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 p-6 shadow-sm">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="flex-1">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Capítulos</h2>
                        <p class="text-sm text-gray-500 dark:text-slate-400">
                            Selecione um capítulo abaixo para iniciar sua leitura na versão <strong>{{ $version->abbreviation }}</strong>.
                        </p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="text-center bg-gray-50 dark:bg-slate-800 rounded-2xl px-6 py-4 border border-gray-100 dark:border-slate-700">
                            <span class="block text-2xl font-black text-gray-900 dark:text-white">{{ $chapters->count() }}</span>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-gray-500 dark:text-slate-400">Capítulos</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chapters Grid -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 p-6 shadow-sm">
                @if($chapters->isEmpty())
                     <div class="text-center py-20">
                        <div class="w-20 h-20 bg-gray-50 dark:bg-slate-900 rounded-full flex items-center justify-center mx-auto mb-6">
                            <x-icon name="triangle-exclamation" class="w-8 h-8 text-gray-300 dark:text-slate-700" />
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Sem Capítulos</h3>
                        <p class="text-gray-500 dark:text-slate-400">Não há capítulos disponíveis para este livro nesta versão.</p>
                    </div>
                @else
                    <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 lg:grid-cols-10 xl:grid-cols-12 gap-3">
                        @foreach($chapters as $chapter)
                            <a href="{{ route('painel.bible.chapter', ['versionAbbr' => $version->abbreviation, 'bookNumber' => $book->book_number, 'chapterNumber' => $chapter->chapter_number]) }}"
                                class="group flex flex-col items-center justify-center aspect-square rounded-2xl border border-gray-100 dark:border-slate-700 bg-white dark:bg-slate-800 hover:border-indigo-500 dark:hover:border-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 hover:shadow-lg hover:shadow-indigo-500/10 transition-all duration-200">
                                <span class="text-lg font-black text-gray-700 dark:text-slate-300 group-hover:text-indigo-600 dark:group-hover:text-white transition-colors">
                                    {{ $chapter->chapter_number }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection


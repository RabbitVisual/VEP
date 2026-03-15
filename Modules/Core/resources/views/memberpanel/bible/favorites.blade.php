@extends('memberpanel::components.layouts.master')

@section('title', 'Versículos Favoritos')

@include('bible::memberpanel.bible.partials.bible-header-assets')
@section('content')
    <div class="min-h-screen bible-page-wrapper bg-gray-50 dark:bg-slate-950 transition-colors pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 sm:pt-6 space-y-6">

            @include('bible::memberpanel.bible.partials.bible-reader-bar', [
                'title' => 'Meus Favoritos',
                'subtitle' => 'Versículos salvos · atalhos rápidos',
                'showBack' => false,
                'showVersionSelector' => false,
                'showSearch' => true,
                'showFavorites' => true,
                'showInterlinear' => true,
                'showLeitura' => true,
                'version' => \Modules\Bible\App\Models\BibleVersion::first(),
            ])

            <!-- Stats Card -->
            @if($favorites->count() > 0)
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 p-6 shadow-sm">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Total Salvo</h2>
                            <p class="text-sm text-gray-500 dark:text-slate-400">Versículos que você marcou como favoritos.</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="text-center bg-red-50 dark:bg-red-900/20 rounded-2xl px-6 py-4 border border-red-100 dark:border-red-900/50">
                                <span class="block text-2xl font-black text-red-600 dark:text-red-400">{{ $favorites->count() }}</span>
                                <span class="text-[10px] font-bold uppercase tracking-widest text-red-500 dark:text-red-400">Favoritos</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- List -->
            <div class="space-y-4">
                @forelse($favorites as $verse)
                    @php
                        $chapter = $verse->chapter;
                        $book = $chapter->book;
                        $version = $book->bibleVersion;
                    @endphp
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 p-6 shadow-sm hover:shadow-lg hover:border-indigo-200 dark:hover:border-indigo-900/50 transition-all duration-300 group relative">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-4">
                            <a href="{{ route('painel.bible.chapter', ['versionAbbr' => $version->abbreviation, 'bookNumber' => $book->book_number, 'chapterNumber' => $chapter->chapter_number]) }}#verse-{{ $verse->verse_number }}"
                               class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-gray-100 dark:bg-slate-800 text-gray-900 dark:text-white font-bold text-xs hover:bg-indigo-50 hover:text-indigo-600 dark:hover:bg-indigo-900/30 dark:hover:text-indigo-400 transition-colors">
                                <x-icon name="bookmark" class="w-3 h-3" />
                                {{ $verse->full_reference }}
                            </a>
                            <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider bg-gray-50 dark:bg-slate-800 text-gray-400 dark:text-slate-500 border border-gray-100 dark:border-slate-700">
                                {{ $version->abbreviation }}
                            </span>
                        </div>

                        <!-- Content -->
                        <p class="text-lg text-gray-800 dark:text-slate-200 font-serif leading-relaxed mb-4">
                            "{{ $verse->text }}"
                        </p>

                        <!-- Actions -->
                        <div class="flex justify-end pt-4 border-t border-gray-50 dark:border-slate-800/50">
                            <button onclick="removeFavorite({{ $verse->id }})"
                                class="inline-flex items-center text-xs font-bold text-gray-400 hover:text-red-500 transition-colors uppercase tracking-wider group/btn">
                                <span class="group-hover/btn:hidden">Remover</span>
                                <span class="hidden group-hover/btn:inline">Confirmar Remoção?</span>
                                <x-icon name="trash" class="w-3 h-3 ml-2" />
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-24">
                        <div class="w-24 h-24 bg-gray-100 dark:bg-slate-900 rounded-full flex items-center justify-center mx-auto mb-6">
                            <x-icon name="heart-crack" class="w-10 h-10 text-gray-300 dark:text-slate-700" />
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Nenhum Favorito</h3>
                        <p class="text-gray-500 dark:text-slate-400 max-w-sm mx-auto mb-8">
                            Você ainda não salvou nenhum versículo. Enquanto lê a Bíblia, clique no ícone de coração para salvar aqui.
                        </p>
                        <a href="{{ route('painel.bible.read', \Modules\Bible\App\Models\BibleVersion::first()->abbreviation ?? 'NVI') }}"
                            class="inline-flex items-center justify-center px-6 py-3 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-500/20 transition-all">
                            <x-icon name="book-open" style="duotone" class="w-4 h-4 mr-2" />
                            Ir para Leitura
                        </a>
                    </div>
                @endforelse
            </div>

        </div>
    </div>

    <script>
        function removeFavorite(verseId) {
            const button = event.target.closest('button');

            // Simple visual confirmation before fetch (optimistic UI could be used, but safety first here)
            // The button text changes on hover to "Confirmar Remoção?", acting as a soft confirmation.
            // But let's add a real confirm for safety.
            if(!confirm('Tem certeza que deseja remover este versículo dos favoritos?')) return;

            button.disabled = true;
            button.classList.add('opacity-50', 'cursor-not-allowed');

            fetch(`{{ url('/social/bible/favorites') }}/${verseId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Animate removal
                    const card = button.closest('.relative');
                    card.style.transform = 'scale(0.95)';
                    card.style.opacity = '0';
                    setTimeout(() => {
                        location.reload(); // Reload to refresh count/layout cleanly
                    }, 200);
                } else {
                    button.disabled = false;
                    button.classList.remove('opacity-50', 'cursor-not-allowed');
                    alert('Erro ao remover. Tente novamente.');
                }
            })
            .catch(error => {
                button.disabled = false;
                button.classList.remove('opacity-50', 'cursor-not-allowed');
                console.error(error);
            });
        }
    </script>
@endsection


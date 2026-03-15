@extends('homepage::components.layouts.master')

@section('title', $book->name . ' ' . $chapter->chapter_number . ' – Bíblia ' . $version->abbreviation)

@php
    $chapterTheme = $chapter->theme ?? null;
    $bibleChapterConfig = [
        'apiBase' => url('api/v1/bible'),
        'versionAbbr' => $version->abbreviation,
        'bookNumber' => $book->book_number,
        'chapterNumber' => $chapter->chapter_number,
        'bookName' => $book->name,
        'versions' => $versions->map(fn($v) => ['id' => $v->id, 'abbreviation' => $v->abbreviation, 'name' => $v->name])->values()->toArray(),
        'chapterAudioUrl' => $chapterAudioUrl ?? null,
        'versionName' => $version->name,
    ];
@endphp
@push('styles')
@include('bible::public.partials.bible-public-styles')
@endpush

@section('content')
<script>
window.__bibleChapterConfig = @json($bibleChapterConfig);
document.addEventListener('alpine:init', function() {
    Alpine.data('bibleChapter', function() {
        var config = Object.assign({}, window.__bibleChapterConfig);
        return Object.assign(config, {
            booksOpen: false,
            searchOpen: false,
            searchQuery: '',
            chaptersBarOpen: (function(){ try { var v = localStorage.getItem('bible_chapters_bar_open'); return v !== null ? v === 'true' : true; } catch(e) { return true; } })(),
            searchResults: null,
            searchLoading: false,
            searchDebounce: null,
            compareMode: false,
            compareVersion2: '',
            compareData: null,
            compareLoading: false,
            readingMode: false,
            fontSize: 100,
            fontPanelOpen: false,
            fullscreenActive: false,
            fetchCompare: function() {
                var self = this;
                if (!self.compareVersion2) return;
                self.compareLoading = true;
                self.compareData = null;
                fetch(self.apiBase + '/compare?v1=' + encodeURIComponent(self.versionAbbr) + '&v2=' + encodeURIComponent(self.compareVersion2) + '&book_number=' + self.bookNumber + '&chapter=' + self.chapterNumber)
                    .then(function(r) { return r.json(); })
                    .then(function(json) { if (json.data) self.compareData = json.data; })
                    .catch(function(e) { console.error(e); })
                    .finally(function() { self.compareLoading = false; });
            },
            doSearch: function() {
                var self = this;
                clearTimeout(self.searchDebounce);
                if (self.searchQuery.trim().length < 2) { self.searchResults = null; return; }
                self.searchDebounce = setTimeout(function() {
                    self.searchLoading = true;
                    self.searchResults = null;
                    fetch(self.apiBase + '/search?q=' + encodeURIComponent(self.searchQuery.trim()))
                        .then(function(r) { return r.json(); })
                        .then(function(json) { if (json.data) self.searchResults = json.data; })
                        .catch(function(e) { console.error(e); })
                        .finally(function() { self.searchLoading = false; });
                }, 300);
            },
            saveLastReading: function() {
                try {
                    localStorage.setItem('bible_public_last', JSON.stringify({
                        versionAbbr: this.versionAbbr,
                        book_number: this.bookNumber,
                        chapter_number: this.chapterNumber,
                        book_name: this.bookName
                    }));
                } catch (e) {}
            },
            fontSizeDown: function() {
                this.fontSize = Math.max(80, this.fontSize - 10);
            },
            fontSizeUp: function() {
                this.fontSize = Math.min(140, this.fontSize + 10);
            },
            toggleReadingFullscreen: function() {
                var self = this;
                var el = document.getElementById('bible-reading-mode-container');
                if (!el) return;
                if (!self.fullscreenActive) {
                    if (el.requestFullscreen) el.requestFullscreen();
                    else if (el.webkitRequestFullscreen) el.webkitRequestFullscreen();
                    self.fullscreenActive = true;
                } else {
                    if (document.exitFullscreen) document.exitFullscreen();
                    else if (document.webkitExitFullscreen) document.webkitExitFullscreen();
                    self.fullscreenActive = false;
                }
            },
            exitReadingMode: function() {
                this.readingMode = false;
                this.fullscreenActive = false;
                if (document.fullscreenElement || document.webkitFullscreenElement) {
                    if (document.exitFullscreen) document.exitFullscreen();
                    else if (document.webkitExitFullscreen) document.webkitExitFullscreen();
                }
            },
            saveChaptersBarPreference: function() {
                try { localStorage.setItem('bible_chapters_bar_open', this.chaptersBarOpen); } catch (e) {}
            }
        });
    });
});
</script>
<div class="bible-public-container min-h-screen pb-28"
     id="bible-public-chapter"
     x-data="bibleChapter()"
     x-init="saveLastReading(); $watch('readingMode', function(v) { document.body.classList.toggle('bible-reading-mode', v); })"
     x-effect="$el.style.setProperty('--bible-font-size', fontSize + '%')"
     :style="'--bible-font-size: ' + fontSize + '%'">

    {{-- Cabeçalho: breadcrumb + barra de ferramentas --}}
    <nav class="sticky top-0 z-40 bible-public-header transition-all"
         x-show="!readingMode"
         x-transition>
        <div class="max-w-4xl mx-auto px-3 sm:px-6 py-2.5 sm:py-3">
            <nav class="flex items-center gap-1.5 text-xs text-[var(--bible-muted)] mb-2" aria-label="Navegação">
                <a href="{{ route('bible.public.index') }}" class="hover:text-[var(--bible-accent)] transition-colors">Bíblia</a>
                <x-icon name="chevron-right" class="w-3 h-3 opacity-70" />
                <a href="{{ route('bible.public.read', $version->abbreviation) }}" class="hover:text-[var(--bible-accent)] transition-colors">{{ $version->abbreviation }}</a>
                <x-icon name="chevron-right" class="w-3 h-3 opacity-70" />
                <a href="{{ route('bible.public.book', [$version->abbreviation, $book->book_number]) }}" class="hover:text-[var(--bible-accent)] transition-colors">{{ $book->name }}</a>
                <x-icon name="chevron-right" class="w-3 h-3 opacity-70" />
                <span class="text-[var(--bible-text)] font-semibold">Capítulo {{ $chapter->chapter_number }}</span>
            </nav>
            <div class="flex items-center justify-between gap-2">
                <div class="flex items-center gap-1 sm:gap-2 shrink-0">
                    <button @click="booksOpen = true"
                            type="button"
                            class="flex items-center gap-1.5 p-2 sm:px-3 sm:py-2 rounded-xl bible-btn-back hover:border-[var(--bible-accent)] text-[var(--bible-text)] transition-colors"
                            aria-label="Abrir lista de livros">
                        <x-icon name="book-open" class="w-5 h-5" />
                        <span class="hidden sm:inline text-sm font-bold">Livros</span>
                    </button>
                    <a href="{{ route('bible.public.book', [$version->abbreviation, $book->book_number]) }}"
                       class="p-2 rounded-xl bible-btn-back transition-colors"
                       aria-label="Voltar ao livro">
                        <x-icon name="chevron-left" class="w-5 h-5" />
                    </a>
                </div>
                <div class="flex-1 min-w-0 flex flex-col sm:flex-row items-center justify-center gap-0.5 sm:gap-2 text-center">
                    <h1 class="text-sm sm:text-base font-black text-[var(--bible-text)] truncate w-full sm:w-auto" style="font-family: var(--bible-serif);">
                        {{ $book->name }} <span class="bible-accent">{{ $chapter->chapter_number }}</span>
                    </h1>
                    <label for="pub-version" class="sr-only">Versão</label>
                    <select id="pub-version"
                            onchange="window.location.href = '{{ url('biblia-online/versao') }}/' + this.value + '/livro/{{ $book->book_number }}/capitulo/{{ $chapter->chapter_number }}'"
                            class="text-xs sm:text-sm font-bold bible-accent bg-transparent border-0 py-1 pr-6 focus:ring-0 cursor-pointer">
                        @foreach($versions as $v)
                            <option value="{{ $v->abbreviation }}" {{ $v->id === $version->id ? 'selected' : '' }}>{{ $v->abbreviation }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-1 shrink-0">
                    <button @click="searchOpen = true; searchResults = null; searchQuery = ''"
                            type="button"
                            class="p-2 rounded-xl bible-btn-back hover:border-[var(--bible-accent)] bible-accent transition-colors"
                            aria-label="Busca">
                        <x-icon name="magnifying-glass" class="w-5 h-5" />
                    </button>
                    <button @click="compareMode = !compareMode; if(compareMode && compareVersion2) fetchCompare()"
                            :class="compareMode ? 'bg-[var(--bible-accent-soft)] border-[var(--bible-accent)] bible-accent' : 'bible-btn-back'"
                            class="p-2 rounded-xl border transition-colors"
                            type="button"
                            aria-label="Comparar versões">
                        <x-icon name="columns-3" class="w-5 h-5" />
                    </button>
                    <button @click="readingMode = !readingMode"
                            type="button"
                            class="p-2 rounded-xl bible-btn-back hover:border-[var(--bible-accent)] bible-accent transition-colors"
                            :aria-pressed="readingMode"
                            aria-label="Modo leitura">
                        <x-icon name="book-open-reader" class="w-5 h-5" />
                    </button>
                </div>
            </div>
        </div>
    </nav>

    {{-- Barra de capítulos: recolher/expandir para não atrapalhar a leitura --}}
    <div class="sticky top-[52px] sm:top-[56px] z-30 bible-public-header border-b border-[var(--bible-border)] py-2 px-4"
         x-show="!readingMode"
         x-transition>
        <div class="max-w-4xl mx-auto">
            <button type="button"
                    @click="chaptersBarOpen = !chaptersBarOpen; saveChaptersBarPreference()"
                    class="w-full flex items-center justify-between gap-2 py-2 text-left rounded-lg hover:bg-[var(--bible-bg)] transition-colors"
                    :aria-expanded="chaptersBarOpen"
                    aria-controls="bible-chapters-list">
                <span class="text-xs font-bold uppercase tracking-wider text-[var(--bible-muted)]">Capítulos</span>
                <span class="flex items-center gap-1 text-[var(--bible-muted)]" aria-hidden="true">
                    <span class="text-xs font-semibold" x-text="chaptersBarOpen ? 'Recolher' : 'Expandir'"></span>
                    <span class="inline-block w-4 h-4 transition-transform duration-200" :class="chaptersBarOpen ? 'rotate-180' : ''"><x-icon name="chevron-down" class="w-4 h-4" /></span>
                </span>
            </button>
            <div id="bible-chapters-list"
                 x-show="chaptersBarOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-end="opacity-0"
                 class="overflow-x-auto scrollbar-hide -mx-1 px-1">
                <div class="flex gap-2 justify-center flex-nowrap sm:flex-wrap min-w-0 py-0.5 pb-1">
                    @for($i = 1; $i <= ($totalChapters ?? 0); $i++)
                        <a href="{{ route('bible.public.chapter', [$version->abbreviation, $book->book_number, $i]) }}"
                           class="bible-chapter-pill {{ $i == $chapter->chapter_number ? 'current' : '' }}">
                            {{ $i }}
                        </a>
                    @endfor
                </div>
            </div>
        </div>
    </div>

    {{-- Font size control --}}
    <div class="fixed right-3 bottom-24 sm:bottom-28 z-20 flex flex-col gap-1 bible-card rounded-xl shadow-lg border-2 border-[var(--bible-border)] p-1.5"
         x-show="!readingMode"
         x-transition>
        <button @click="fontPanelOpen = !fontPanelOpen" type="button" class="p-2 rounded-lg text-[var(--bible-muted)] hover:bg-[var(--bible-bg)] transition-colors" aria-label="Tamanho da fonte">
            <x-icon name="font" class="w-5 h-5" />
        </button>
        <template x-if="fontPanelOpen">
            <div class="flex items-center gap-1 border-t border-[var(--bible-border)] pt-2 mt-1">
                <button @click="fontSizeDown()" type="button" class="w-8 h-8 rounded-lg bg-[var(--bible-bg)] text-sm font-bold text-[var(--bible-text)] border border-[var(--bible-border)]" aria-label="Diminuir fonte">A-</button>
                <span class="text-xs font-bold text-[var(--bible-muted)] min-w-[2.5rem]" x-text="fontSize + '%'"></span>
                <button @click="fontSizeUp()" type="button" class="w-8 h-8 rounded-lg bg-[var(--bible-bg)] text-sm font-bold text-[var(--bible-text)] border border-[var(--bible-border)]" aria-label="Aumentar fonte">A+</button>
            </div>
        </template>
    </div>

    {{-- Modo leitura: tela cheia acima do navbar (z-[60]) + opção fullscreen API --}}
    <div id="bible-reading-mode-container"
         x-show="readingMode"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-[60] overflow-y-auto bible-public-container"
         :class="{ 'flex flex-col': readingMode }"
         @fullscreenchange.window="fullscreenActive = !!(document.fullscreenElement || document.webkitFullscreenElement)"
         @webkitfullscreenchange.window="fullscreenActive = !!(document.fullscreenElement || document.webkitFullscreenElement)">
        <template x-if="readingMode">
            <div class="flex-1 flex flex-col min-h-full">
                {{-- Barra mínima modo leitura: sair + tela cheia --}}
                <div class="sticky top-0 z-10 flex items-center justify-between px-3 py-2 bible-public-header border-b border-[var(--bible-border)]">
                    <span class="text-sm font-bold text-[var(--bible-muted)] truncate" x-text="bookName + ' ' + chapterNumber"></span>
                    <div class="flex items-center gap-1">
                        <button @click="toggleReadingFullscreen()" type="button" class="p-2 rounded-xl bible-btn-back" :aria-pressed="fullscreenActive" aria-label="Tela cheia">
                            <x-icon name="expand" class="w-5 h-5" />
                        </button>
                        <button @click="exitReadingMode()" type="button" class="p-2 rounded-xl font-bold text-sm text-white border-0" style="background: var(--bible-verse-num-bg);" aria-label="Sair do modo leitura">Sair</button>
                    </div>
                </div>
                {{-- Controle de fonte dentro do modo leitura --}}
                <div class="flex justify-center gap-2 py-2 border-b border-[var(--bible-border)]">
                    <button @click="fontSizeDown()" type="button" class="w-9 h-9 rounded-lg border border-[var(--bible-border)] bg-[var(--bible-bg)] text-sm font-bold text-[var(--bible-text)]" aria-label="Diminuir fonte">A-</button>
                    <span class="text-sm font-bold text-[var(--bible-muted)] min-w-[3rem] flex items-center justify-center" x-text="fontSize + '%'"></span>
                    <button @click="fontSizeUp()" type="button" class="w-9 h-9 rounded-lg border border-[var(--bible-border)] bg-[var(--bible-bg)] text-sm font-bold text-[var(--bible-text)]" aria-label="Aumentar fonte">A+</button>
                </div>
                {{-- Conteúdo de leitura (mesmo bloco que o main abaixo, duplicado aqui para modo leitura) --}}
                <div class="flex-1 max-w-3xl w-full mx-auto px-4 sm:px-6 py-6 bible-reading-column" :style="'font-size: ' + (fontSize / 100) + 'rem'">
                    @if(!empty($chapterAudioUrl))
                        <div class="mb-6 p-4 rounded-xl bible-card border-2 border-[var(--bible-border)]" aria-label="Ouvir capítulo em áudio">
                            <p class="text-sm font-bold text-[var(--bible-muted)] mb-3 flex items-center gap-2">
                                <x-icon name="volume-high" class="w-4 h-4 bible-accent" />
                                Áudio deste capítulo ({{ $version->name }})
                            </p>
                            <audio controls class="w-full max-w-md" preload="metadata" src="{{ $chapterAudioUrl }}">
                                Seu navegador não suporta o elemento de áudio.
                            </audio>
                        </div>
                    @endif
                    @if(!$verses->isEmpty())
                        <div class="bible-page">
                            <h2 class="bible-chapter-heading">{{ strtoupper($book->name) }} {{ $chapter->chapter_number }}</h2>
                            @if(!empty($chapterTheme))
                                <p class="bible-chapter-theme">{{ $chapterTheme }}</p>
                            @endif
                            <div class="space-y-0">
                                @foreach($verses as $verse)
                                    <div class="bible-verse-line" id="v{{ $verse->verse_number }}">
                                        <span class="verse-num">{{ $verse->verse_number }}</span>
                                        <span class="verse-text">{{ $verse->text }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @if(isset($previousChapter) || isset($nextChapter))
                            <footer class="mt-10 pt-6 border-t border-[var(--bible-border)] flex flex-wrap items-center justify-between gap-4">
                                @if(isset($previousChapter))
                                    <a href="{{ route('bible.public.chapter', [$version->abbreviation, $previousChapter->book->book_number, $previousChapter->chapter_number]) }}"
                                       class="flex items-center gap-2 px-4 py-2.5 rounded-xl bible-btn-back font-bold text-sm transition-colors">
                                        <x-icon name="chevron-left" class="w-5 h-5" />
                                        <span class="hidden sm:inline">Cap. {{ $previousChapter->chapter_number }}</span>
                                    </a>
                                @else
                                    <span aria-hidden="true"></span>
                                @endif
                                <span class="text-xs font-bold text-[var(--bible-muted)] uppercase tracking-wider">{{ $book->name }} {{ $chapter->chapter_number }}</span>
                                @if(isset($nextChapter))
                                    <a href="{{ route('bible.public.chapter', [$version->abbreviation, $nextChapter->book->book_number, $nextChapter->chapter_number]) }}"
                                       class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-white font-bold text-sm border-0 transition-opacity hover:opacity-90"
                                       style="background: var(--bible-verse-num-bg);">
                                        <span class="hidden sm:inline">Cap. {{ $nextChapter->chapter_number }}</span>
                                        <x-icon name="chevron-right" class="w-5 h-5" />
                                    </a>
                                @else
                                    <span aria-hidden="true"></span>
                                @endif
                            </footer>
                        @endif
                    @endif
                </div>
            </div>
        </template>
    </div>

    {{-- Main content (hidden in reading mode; shown when not reading) --}}
    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-6 sm:py-10"
         x-show="!readingMode"
         x-transition>
        <div class="bible-reading-column mx-auto" :style="'font-size: ' + (fontSize / 100) + 'rem'">

            {{-- Compare mode: choose version then show 1-1, 2-2 --}}
            <template x-if="compareMode && !compareData && !compareLoading">
                <div class="mb-8 p-6 rounded-2xl bible-card border-2 border-[var(--bible-border)] text-center" style="background: var(--bible-accent-soft);">
                    <p class="text-sm font-bold bible-accent mb-3">Escolha a segunda versão para comparar</p>
                    <div class="flex flex-wrap justify-center gap-2">
                        @foreach($versions as $v)
                            @if($v->id !== $version->id)
                                <button type="button"
                                        @click="compareVersion2 = '{{ $v->abbreviation }}'; fetchCompare()"
                                        class="px-4 py-2 rounded-xl bible-card border-2 border-[var(--bible-border)] text-sm font-bold hover:border-[var(--bible-accent)] transition-colors text-[var(--bible-text)]"
                                        x-text="'{{ $v->abbreviation }}'"></button>
                            @endif
                        @endforeach
                    </div>
                </div>
            </template>

            <template x-if="compareMode && compareLoading">
                <div class="flex flex-col items-center justify-center py-12">
                    <svg class="animate-spin h-10 w-10 bible-accent mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span class="text-sm font-bold text-[var(--bible-muted)]">Carregando comparação...</span>
                </div>
            </template>

            {{-- Compare: duas colunas claras — sua versão (cor A) vs comparação (cor B) --}}
            <template x-if="compareMode && compareData && !compareLoading">
                <div class="space-y-6">
                    <div class="flex flex-wrap items-center gap-4 py-2 border-b border-[var(--bible-border)]">
                        <span class="text-xs font-semibold uppercase tracking-wider text-[var(--bible-muted)]">Legenda:</span>
                        <span class="inline-flex items-center gap-2 text-sm bible-compare-current font-semibold">
                            <span class="w-2.5 h-2.5 rounded-sm bible-compare-current-bg border border-[var(--bible-accent)]"></span>
                            <span x-text="'Sua versão (' + compareData.v1.abbreviation + ')'"></span>
                        </span>
                        <span class="inline-flex items-center gap-2 text-sm bible-compare-other font-semibold">
                            <span class="w-2.5 h-2.5 rounded-sm bible-compare-other-bg border border-[var(--bible-compare-other)]"></span>
                            <span x-text="'Comparação (' + compareData.v2.abbreviation + ')'"></span>
                        </span>
                    </div>
                    <template x-for="(v1, idx) in compareData.v1.verses" :key="idx">
                        <div class="bible-page py-4">
                            <div class="flex gap-2 mb-3">
                                <span class="bible-verse-num" x-text="v1.verse_number"></span>
                            </div>
                            <div class="grid sm:grid-cols-2 gap-4">
                                <div class="bible-compare-current-bg rounded px-4 py-3 border-l-2 border-[var(--bible-accent)]">
                                    <span class="text-[10px] font-semibold uppercase tracking-wider bible-accent" x-text="compareData.v1.abbreviation"></span>
                                    <p class="bible-compare-current font-serif leading-relaxed mt-1 text-[1em]" x-text="v1.text"></p>
                                </div>
                                <div class="bible-compare-other-bg rounded px-4 py-3 border-l-2 border-[var(--bible-compare-other)]">
                                    <span class="text-[10px] font-semibold uppercase tracking-wider bible-compare-other" x-text="compareData.v2.abbreviation"></span>
                                    <p class="bible-compare-other font-serif leading-relaxed mt-1 text-[1em]"
                                       x-text="(compareData.v2.verses[idx] || {}).text || '—'"></p>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </template>

            {{-- Chapter audio (when available) --}}
            @if(!empty($chapterAudioUrl))
                <div x-show="!compareMode || !compareData" class="mb-8 p-4 rounded-xl bible-card border-2 border-[var(--bible-border)]" aria-label="Ouvir capítulo em áudio">
                    <p class="text-sm font-bold text-[var(--bible-muted)] mb-3 flex items-center gap-2">
                        <x-icon name="volume-high" class="w-4 h-4 bible-accent" />
                        Áudio deste capítulo ({{ $version->name }})
                    </p>
                    <audio controls class="w-full max-w-md" preload="metadata" src="{{ $chapterAudioUrl }}">
                        Seu navegador não suporta o elemento de áudio.
                    </audio>
                </div>
            @endif

            {{-- Normal reading (or when not compare) --}}
            @if($verses->isEmpty())
                <div class="text-center py-16">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bible-card border-2 border-[var(--bible-border)] flex items-center justify-center text-[var(--bible-muted)]">
                        <x-icon name="triangle-exclamation" class="w-8 h-8" style="--fa-primary-opacity: 0.6;" />
                    </div>
                    <p class="text-[var(--bible-muted)]">Este capítulo ainda não está disponível nesta versão.</p>
                </div>
            @else
                <div x-show="!compareMode || !compareData" class="bible-page">
                    <h2 class="bible-chapter-heading">{{ strtoupper($book->name) }} {{ $chapter->chapter_number }}</h2>
                    @if(!empty($chapterTheme))
                        <p class="bible-chapter-theme">{{ $chapterTheme }}</p>
                    @endif
                    <div class="space-y-0">
                        @foreach($verses as $verse)
                            <div class="bible-verse-line" id="v{{ $verse->verse_number }}">
                                <span class="verse-num">{{ $verse->verse_number }}</span>
                                <span class="verse-text">{{ $verse->text }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </main>

    {{-- Footer prev/next --}}
    @if($verses->isNotEmpty())
        <footer class="fixed bottom-0 left-0 right-0 z-30 bible-public-header border-t border-[var(--bible-border)] safe-area-pb">
            <div class="max-w-3xl mx-auto px-4 py-3 flex items-center justify-between gap-4">
                @if($previousChapter)
                    <a href="{{ route('bible.public.chapter', [$version->abbreviation, $previousChapter->book->book_number, $previousChapter->chapter_number]) }}"
                       class="flex items-center gap-2 px-4 py-2.5 rounded-xl bible-btn-back font-bold text-sm transition-colors">
                        <x-icon name="chevron-left" class="w-5 h-5" />
                        <span class="hidden sm:inline">Cap. {{ $previousChapter->chapter_number }}</span>
                    </a>
                @else
                    <span aria-hidden="true"></span>
                @endif
                <span class="text-xs font-bold text-[var(--bible-muted)] uppercase tracking-wider">{{ $book->name }} {{ $chapter->chapter_number }}</span>
                @if($nextChapter)
                    <a href="{{ route('bible.public.chapter', [$version->abbreviation, $nextChapter->book->book_number, $nextChapter->chapter_number]) }}"
                       class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-white font-bold text-sm border-0 hover:opacity-90 transition-opacity"
                       style="background: var(--bible-verse-num-bg);">
                        <span class="hidden sm:inline">Cap. {{ $nextChapter->chapter_number }}</span>
                        <x-icon name="chevron-right" class="w-5 h-5" />
                    </a>
                @else
                    <span aria-hidden="true"></span>
                @endif
            </div>
        </footer>
    @endif

    {{-- Modal Livros --}}
    <div x-show="booksOpen"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-modal="true"
         @books-open.window="booksOpen = true">
        <div class="flex min-h-full items-center justify-center p-4">
            <div x-show="booksOpen" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="booksOpen = false"></div>
            <div x-show="booksOpen" x-transition
                 class="relative rounded-2xl shadow-xl w-full max-w-2xl max-h-[85vh] overflow-hidden bible-card border-2 border-[var(--bible-border)]">
                <div class="p-4 sm:p-6 border-b border-[var(--bible-border)] flex items-center justify-between">
                    <h2 class="text-lg font-bold text-[var(--bible-text)] flex items-center gap-2" style="font-family: var(--bible-serif);">
                        <x-icon name="book-open" class="w-5 h-5 bible-accent" />
                        Livros
                    </h2>
                    <button @click="booksOpen = false" type="button" class="p-2 rounded-xl bible-btn-back">
                        <x-icon name="xmark" class="w-5 h-5" />
                    </button>
                </div>
                <div class="p-4 sm:p-6 overflow-y-auto max-h-[70vh] space-y-6">
                    <div>
                        <h3 class="text-xs font-black uppercase tracking-widest bible-at-accent mb-3 flex items-center gap-2">
                            <x-icon name="book-open" class="w-4 h-4" />
                            Antigo Testamento
                        </h3>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                            @foreach($oldTestament ?? [] as $b)
                                <a href="{{ route('bible.public.book', [$version->abbreviation, $b->book_number]) }}"
                                   @click="booksOpen = false"
                                   class="p-3 rounded-xl bible-card border-2 border-[var(--bible-border)] text-sm font-bold text-[var(--bible-text)] hover:border-[var(--bible-at)] transition-colors text-center"
                                   style="font-family: var(--bible-serif);">
                                    {{ $b->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <h3 class="text-xs font-black uppercase tracking-widest bible-nt-accent mb-3 flex items-center gap-2">
                            <x-icon name="book-open" class="w-4 h-4" />
                            Novo Testamento
                        </h3>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                            @foreach($newTestament ?? [] as $b)
                                <a href="{{ route('bible.public.book', [$version->abbreviation, $b->book_number]) }}"
                                   @click="booksOpen = false"
                                   class="p-3 rounded-xl bible-card border-2 border-[var(--bible-border)] text-sm font-bold text-[var(--bible-text)] hover:border-[var(--bible-nt)] transition-colors text-center"
                                   style="font-family: var(--bible-serif);">
                                    {{ $b->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Drawer Busca --}}
    <div x-show="searchOpen"
         x-cloak
         class="fixed inset-0 z-50 overflow-hidden"
         aria-modal="true">
        <div x-show="searchOpen" x-transition class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="searchOpen = false"></div>
        <div x-show="searchOpen" x-transition:enter="transform transition ease-out duration-200" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
             class="absolute bottom-0 left-0 right-0 rounded-t-2xl shadow-2xl max-h-[85vh] flex flex-col bible-card border-t-2 border-[var(--bible-border)]">
            <div class="p-4 border-b border-[var(--bible-border)] flex items-center gap-3">
                <label for="bible-search-input" class="sr-only">Buscar na Bíblia</label>
                <input id="bible-search-input"
                       type="search"
                       x-model="searchQuery"
                       @input.debounce.300ms="doSearch()"
                       placeholder="Referência (ex: João 3:16) ou palavra..."
                       class="flex-1 px-4 py-3 rounded-xl border-2 border-[var(--bible-border)] bg-[var(--bible-bg)] text-[var(--bible-text)] placeholder-[var(--bible-muted)] focus:ring-2 focus:ring-[var(--bible-accent)] focus:border-[var(--bible-accent)]">
                <button @click="searchOpen = false" type="button" class="p-2 rounded-xl bible-btn-back">
                    <x-icon name="xmark" class="w-5 h-5" />
                </button>
            </div>
            <div class="flex-1 overflow-y-auto p-4">
                <template x-if="searchLoading">
                    <div class="flex flex-col items-center justify-center py-12">
                        <svg class="animate-spin h-8 w-8 bible-accent mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span class="text-sm text-[var(--bible-muted)]">Buscando...</span>
                    </div>
                </template>
                <template x-if="!searchLoading && searchResults && Array.isArray(searchResults)">
                    <ul class="space-y-2">
                        <template x-for="(item, i) in searchResults" :key="i">
                            <li>
                                <a :href="item.reference ? ('/biblia-online/versao/' + versionAbbr + '/livro/' + (item.book_number || '') + '/capitulo/' + (item.chapter_number || '') + (item.verse_number ? '#v' + item.verse_number : '')) : '#'"
                                   @click="searchOpen = false"
                                   class="block p-4 rounded-xl bible-card border-2 border-[var(--bible-border)] hover:border-[var(--bible-accent)] transition-colors">
                                    <span class="text-xs font-bold bible-accent" x-text="item.reference || item.reference"></span>
                                    <p class="text-sm text-[var(--bible-text)] mt-1 line-clamp-2" x-text="item.text || ''"></p>
                                </a>
                            </li>
                        </template>
                    </ul>
                </template>
                <template x-if="!searchLoading && searchResults && searchResults.type === 'exact'">
                    <div class="space-y-2">
                        <p class="text-sm font-bold bible-accent" x-text="searchResults.reference"></p>
                        <template x-for="(v, i) in (searchResults.verses || [])" :key="i">
                            <a :href="'/biblia-online/versao/' + versionAbbr + '/livro/' + (searchResults.book_number || bookNumber) + '/capitulo/' + (searchResults.chapter_number || chapterNumber) + '#v' + v.verse_number"
                               @click="searchOpen = false"
                               class="block p-4 rounded-xl bible-card border-2 border-[var(--bible-border)] hover:border-[var(--bible-accent)] transition-colors flex items-start gap-3">
                                <span class="bible-verse-num shrink-0" x-text="v.verse_number"></span>
                                <span class="text-[var(--bible-text)] font-serif flex-1" x-text="v.text"></span>
                            </a>
                        </template>
                    </div>
                </template>
                <template x-if="!searchLoading && searchQuery.length >= 2 && searchResults && !searchResults.length && searchResults.type !== 'exact'">
                    <p class="text-center text-[var(--bible-muted)] py-8">Nenhum resultado encontrado.</p>
                </template>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function() {
    function scrollToVerseFromHash() {
        var hash = window.location.hash;
        if (!/^#v\d+$/.test(hash)) return;
        var id = hash.slice(1);
        var el = document.getElementById(id);
        if (el) {
            el.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
    if (document.readyState === 'complete') {
        setTimeout(scrollToVerseFromHash, 100);
    } else {
        window.addEventListener('load', function() { setTimeout(scrollToVerseFromHash, 100); });
    }
})();
</script>
@endpush
@endsection

@extends('homepage::components.layouts.master')

@section('title', 'Buscar na Bíblia – Bíblia Online')

@push('styles')
@include('bible::public.partials.bible-public-styles')
<style>
    /* Campo de busca: ícone dentro da área do input, alinhado com flex (evita ícone fora do espaço) */
    .bible-search-field {
        display: flex;
        align-items: center;
        gap: 0;
        border: 2px solid var(--bible-border);
        background: var(--bible-bg);
        border-radius: 1rem;
        overflow: hidden;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .bible-search-field:focus-within {
        border-color: var(--bible-accent);
        box-shadow: 0 0 0 3px var(--bible-accent-soft);
    }
    .bible-search-field .icon-wrap {
        flex-shrink: 0;
        width: 3rem;
        height: 3rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--bible-muted);
    }
    .bible-search-field input {
        flex: 1;
        min-width: 0;
        height: 3rem;
        padding: 0 1rem 0 0;
        border: none;
        background: transparent;
        color: var(--bible-text);
        font-size: 1rem;
    }
    .bible-search-field input::placeholder {
        color: var(--bible-muted);
    }
    .bible-search-field input:focus {
        outline: none;
    }
</style>
@endpush

@php
    $bibleSearchConfig = [
        'apiBase' => $apiBase,
        'defaultVersionAbbr' => $versions->isNotEmpty() ? $versions->first()->abbreviation : '',
        'versions' => $versions->map(fn($v) => ['abbreviation' => $v->abbreviation, 'name' => $v->name])->values()->toArray(),
    ];
@endphp
<script>
window.__bibleSearchConfig = @json($bibleSearchConfig);
document.addEventListener('alpine:init', function() {
    Alpine.data('bibleSearch', function() {
        var c = window.__bibleSearchConfig;
        var versionAbbr = '';
        try { versionAbbr = localStorage.getItem('bible_public_version') || c.defaultVersionAbbr; } catch(e) { versionAbbr = c.defaultVersionAbbr; }
        return {
            searchQuery: '',
            searchResults: null,
            searchLoading: false,
            searchDebounce: null,
            versionAbbr: versionAbbr,
            apiBase: c.apiBase,
            versions: c.versions,
            saveVersionToStorage: function() {
                try { localStorage.setItem('bible_public_version', this.versionAbbr); } catch(e) {}
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
            }
        };
    });
});
</script>

@section('content')
<div class="bible-public-container min-h-screen pb-24"
     x-data="bibleSearch()">
    <header class="sticky top-0 z-30 bible-public-header">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 py-4">
            <div class="flex items-center justify-between gap-4 mb-4">
                <a href="{{ route('bible.public.index') }}"
                   class="flex items-center gap-2 bible-btn-back rounded-xl px-3 py-2.5 transition-colors shrink-0">
                    <x-icon name="chevron-left" class="w-5 h-5" />
                    <span class="hidden sm:inline text-sm font-bold">Voltar</span>
                </a>
                <h1 class="text-lg sm:text-xl font-black text-[var(--bible-text)]" style="font-family: var(--bible-serif);">
                    Buscar na Bíblia
                </h1>
                <div class="w-[calc(theme(spacing.9)+theme(spacing.3))] shrink-0" aria-hidden="true"></div>
            </div>
            {{-- Campo de busca com ícone dentro do espaço (flex, não absolute) --}}
            <label for="search-input" class="sr-only">Buscar por referência ou texto</label>
            <div class="bible-search-field mb-3">
                <span class="icon-wrap" aria-hidden="true">
                    <x-icon name="magnifying-glass" class="w-5 h-5" style="--fa-primary-opacity: 0.9; --fa-secondary-opacity: 0.5;" />
                </span>
                <input id="search-input"
                       type="search"
                       x-model="searchQuery"
                       @input.debounce.300ms="doSearch()"
                       placeholder="Ex.: João 3:16 ou palavra..."
                       autofocus>
            </div>
            <div>
                <label for="search-version" class="sr-only">Versão para abrir os links</label>
                <select id="search-version"
                        x-model="versionAbbr"
                        @change="saveVersionToStorage()"
                        class="w-full appearance-none pl-4 pr-10 py-2.5 rounded-xl border-2 border-[var(--bible-border)] bg-[var(--bible-bg)] text-[var(--bible-text)] font-bold text-sm focus:ring-2 focus:ring-[var(--bible-accent)] focus:border-[var(--bible-accent)] cursor-pointer">
                    <option value="">Selecione a versão para abrir os links</option>
                    <template x-for="v in versions" :key="v.abbreviation">
                        <option :value="v.abbreviation" x-text="v.name + ' (' + v.abbreviation + ')'"></option>
                    </template>
                </select>
            </div>
        </div>
    </header>

    <main class="max-w-2xl mx-auto px-4 sm:px-6 py-6">
        <template x-if="searchLoading">
            <div class="flex flex-col items-center justify-center py-12">
                <svg class="animate-spin h-10 w-10 bible-accent mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                <span class="text-sm font-bold text-[var(--bible-muted)]">Buscando...</span>
            </div>
        </template>
        <template x-if="!searchLoading && searchResults && Array.isArray(searchResults)">
            <ul class="space-y-3">
                <template x-for="(item, i) in searchResults" :key="i">
                    <li>
                        <a :href="versionAbbr && item.book_number && item.chapter_number ? ('/biblia-online/versao/' + versionAbbr + '/livro/' + item.book_number + '/capitulo/' + item.chapter_number + (item.verse_number ? '#v' + item.verse_number : '')) : '#'"
                           class="block p-4 rounded-xl bible-card border-2 border-[var(--bible-border)] hover:border-[var(--bible-accent)] transition-colors">
                            <span class="text-xs font-bold bible-accent" x-text="item.reference || ''"></span>
                            <p class="text-sm text-[var(--bible-text)] mt-1 line-clamp-2" x-text="item.text || ''"></p>
                        </a>
                    </li>
                </template>
            </ul>
        </template>
        <template x-if="!searchLoading && searchResults && searchResults.type === 'exact'">
            <div class="space-y-3">
                <p class="text-sm font-bold bible-accent" x-text="searchResults.reference"></p>
                <template x-for="(v, i) in (searchResults.verses || [])" :key="i">
                    <a :href="versionAbbr && searchResults.book_number && searchResults.chapter_number ? ('/biblia-online/versao/' + versionAbbr + '/livro/' + searchResults.book_number + '/capitulo/' + searchResults.chapter_number + '#v' + v.verse_number) : '#'"
                       class="block p-4 rounded-xl bible-card border-2 border-[var(--bible-border)] hover:border-[var(--bible-accent)] transition-colors flex items-start gap-3">
                        <span class="bible-verse-num shrink-0" x-text="v.verse_number"></span>
                        <span class="text-[var(--bible-text)] font-serif flex-1" x-text="v.text"></span>
                    </a>
                </template>
            </div>
        </template>
        <template x-if="!searchLoading && searchQuery.length >= 2 && searchResults && !Array.isArray(searchResults) && searchResults.type !== 'exact'">
            <div class="text-center py-12 rounded-2xl bible-card border-2 border-[var(--bible-border)]">
                <x-icon name="magnifying-glass" class="w-12 h-12 text-[var(--bible-muted)] mx-auto mb-3" style="--fa-primary-opacity: 0.4;" />
                <p class="text-[var(--bible-muted)]">Nenhum resultado encontrado.</p>
            </div>
        </template>
        <template x-if="!searchLoading && searchQuery.length < 2 && !searchResults">
            <div class="text-center py-12 rounded-2xl bible-card border-2 border-[var(--bible-border)]">
                <x-icon name="book-bible" class="w-12 h-12 bible-accent mx-auto mb-3" style="--fa-primary-opacity: 0.6; --fa-secondary-opacity: 0.3;" />
                <p class="text-[var(--bible-muted)] text-sm max-w-sm mx-auto">Digite ao menos 2 caracteres para buscar por referência (ex.: João 3:16) ou por texto.</p>
            </div>
        </template>
    </main>
</div>
@endsection

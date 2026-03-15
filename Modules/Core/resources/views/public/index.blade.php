@extends('homepage::components.layouts.master')

@section('title', 'Bíblia Online – Leia a Bíblia Sagrada')

@push('styles')
@include('bible::public.partials.bible-public-styles')
<style>
    .bible-index-hero {
        background: linear-gradient(160deg, var(--bible-bg) 0%, var(--bible-bg-end) 50%);
        border-bottom: 2px solid var(--bible-border);
    }
    .bible-version-card {
        aspect-ratio: 1;
        min-height: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 1rem;
        border-radius: 1.25rem;
        border: 2px solid var(--bible-border);
        background: var(--bible-card);
        transition: border-color 0.2s, box-shadow 0.2s, transform 0.15s;
    }
    .bible-version-card:hover {
        border-color: var(--bible-accent);
        box-shadow: 0 8px 24px rgba(0,0,0,0.06);
    }
    .dark .bible-version-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.2); }
    .bible-version-card:active { transform: scale(0.98); }
    .bible-version-card .abbr {
        width: 3rem;
        height: 3rem;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        font-weight: 800;
        color: #fff;
        flex-shrink: 0;
        background: var(--bible-verse-num-bg);
    }
    .bible-version-card .name {
        font-family: var(--bible-serif);
        font-weight: 700;
        color: var(--bible-text);
        text-align: center;
        line-height: 1.25;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .bible-version-card .sub {
        font-size: 0.75rem;
        color: var(--bible-muted);
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="bible-public-container min-h-screen"
     x-data="{ last: (function(){ try { const s = localStorage.getItem('bible_public_last'); return s ? JSON.parse(s) : null; } catch(e) { return null; } })() }">
    {{-- Hero --}}
    <section class="bible-index-hero px-4 sm:px-6 pt-8 sm:pt-10 pb-8">
        <div class="max-w-3xl mx-auto text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bible-card border-2 border-[var(--bible-border)] shadow-lg mb-4 sm:mb-5 text-[var(--bible-accent)]">
                <x-icon name="book-bible" class="w-10 h-10 sm:w-12 sm:h-12" style="--fa-primary-opacity: 1; --fa-secondary-opacity: 0.5;" />
            </div>
            <h1 class="text-3xl sm:text-4xl font-black text-[var(--bible-text)] tracking-tight mb-2" style="font-family: var(--bible-serif);">Bíblia Online</h1>
            <p class="text-[var(--bible-muted)] text-sm sm:text-base max-w-md mx-auto mb-6">A Palavra de Deus para leitura gratuita. Escolha uma versão abaixo e leia em qualquer dispositivo.</p>
            <a href="{{ route('bible.public.search') }}"
               class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bible-card border-2 border-[var(--bible-border)] hover:border-[var(--bible-accent)] text-[var(--bible-accent)] font-bold text-sm transition-colors">
                <x-icon name="magnifying-glass" class="w-4 h-4" />
                Buscar na Bíblia
            </a>
        </div>
    </section>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-8 sm:py-10">
        {{-- Continuar lendo --}}
        <template x-if="last && last.versionAbbr && last.book_number && last.chapter_number">
            <a :href="'/biblia-online/versao/' + last.versionAbbr + '/livro/' + last.book_number + '/capitulo/' + last.chapter_number"
               class="mb-8 flex items-center gap-4 p-4 sm:p-5 rounded-2xl bible-card border-2 border-[var(--bible-border)] hover:border-[var(--bible-accent)] transition-all group shadow-sm">
                <span class="flex-shrink-0 w-12 h-12 rounded-xl flex items-center justify-center text-white shadow-md"
                      style="background: var(--bible-verse-num-bg);">
                    <x-icon name="book-open" class="w-6 h-6" style="--fa-primary-opacity: 1; --fa-secondary-opacity: 0.4;" />
                </span>
                <div class="min-w-0 flex-1 text-left">
                    <span class="text-xs font-bold uppercase tracking-wider bible-accent">Continuar lendo</span>
                    <p class="font-bold text-[var(--bible-text)] truncate mt-0.5" style="font-family: var(--bible-serif);" x-text="(last.book_name || '') + ' ' + (last.chapter_number || '')"></p>
                </div>
                <x-icon name="chevron-right" class="w-5 h-5 text-[var(--bible-muted)] group-hover:text-[var(--bible-accent)] group-hover:translate-x-0.5 transition-all flex-shrink-0" />
            </a>
        </template>

        {{-- Selecione a versão – grid de cards (não lista) --}}
        <section>
            <h2 class="text-sm font-bold uppercase tracking-widest text-[var(--bible-muted)] mb-4 flex items-center justify-center gap-2">
                <x-icon name="book-open-reader" class="w-4 h-4 bible-accent" />
                Selecione a versão
            </h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4">
                @foreach($versions as $v)
                    <a href="{{ route('bible.public.read', $v->abbreviation) }}"
                       class="bible-version-card group/card">
                        <span class="abbr">{{ strtoupper(substr($v->abbreviation, 0, 2)) }}</span>
                        <span class="name" title="{{ $v->name }}">{{ $v->name }}</span>
                        <span class="sub">{{ $v->abbreviation }}</span>
                    </a>
                @endforeach
            </div>
        </section>

        <p class="mt-12 text-center text-xs text-[var(--bible-muted)]">
            Leitura gratuita. Não é necessário cadastro.
        </p>
    </div>
</div>
@endsection

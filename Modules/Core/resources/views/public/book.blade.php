@extends('homepage::components.layouts.master')

@section('title', $book->name . ' – Bíblia ' . $version->abbreviation)

@push('styles')
@include('bible::public.partials.bible-public-styles')
<style>
    .bible-book-chapter-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.875rem 1rem;
        border-radius: 0.75rem;
        border: 2px solid var(--bible-border);
        background: var(--bible-card);
        color: var(--bible-text);
        font-family: var(--bible-serif);
        font-weight: 700;
        font-size: 1rem;
        transition: border-color 0.2s, background 0.2s, box-shadow 0.2s, transform 0.1s;
    }
    .bible-book-chapter-btn:hover {
        border-color: var(--bible-accent);
        background: var(--bible-accent-soft);
        box-shadow: 0 4px 12px rgba(0,0,0,0.04);
    }
    .dark .bible-book-chapter-btn:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
    .bible-book-chapter-btn:active { transform: scale(0.98); }
    .bible-book-chapter-btn .num {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 2rem;
        height: 2rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 800;
        color: #fff;
        background: var(--bible-verse-num-bg);
    }
    .bible-book-chapter-btn .label { font-size: 0.9375rem; }
    .bible-book-section-title {
        font-size: 0.6875rem;
        font-weight: 800;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--bible-muted);
        margin-bottom: 0.75rem;
        padding-left: 0.25rem;
    }
</style>
@endpush

@section('content')
<div class="bible-public-container min-h-screen pb-24">
    <header class="sticky top-0 z-30 bible-public-header">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-4">
            {{-- Breadcrumb + título --}}
            <nav class="flex items-center gap-2 text-sm text-[var(--bible-muted)] mb-2" aria-label="Navegação">
                <a href="{{ route('bible.public.index') }}" class="hover:text-[var(--bible-accent)] transition-colors">Bíblia</a>
                <x-icon name="chevron-right" class="w-3.5 h-3.5 opacity-70" />
                <a href="{{ route('bible.public.read', $version->abbreviation) }}" class="hover:text-[var(--bible-accent)] transition-colors">{{ $version->abbreviation }}</a>
                <x-icon name="chevron-right" class="w-3.5 h-3.5 opacity-70" />
                <span class="text-[var(--bible-text)] font-bold">{{ $book->name }}</span>
            </nav>
            <div class="flex items-center justify-between gap-4">
                <a href="{{ route('bible.public.read', $version->abbreviation) }}"
                   class="flex items-center gap-2 bible-btn-back rounded-xl px-3 py-2.5 transition-colors shrink-0">
                    <x-icon name="chevron-left" class="w-5 h-5" />
                    <span class="hidden sm:inline text-sm font-bold">Livros</span>
                </a>
                <h1 class="flex-1 min-w-0 text-xl sm:text-2xl font-black text-[var(--bible-text)] text-center truncate px-2" style="font-family: var(--bible-serif);">{{ $book->name }}</h1>
                <div class="w-[calc(theme(spacing.9)+theme(spacing.3))] shrink-0" aria-hidden="true"></div>
            </div>
            <p class="text-center text-sm text-[var(--bible-muted)] mt-1 flex items-center justify-center gap-2">
                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 border border-[var(--bible-border)] bg-[var(--bible-bg)]">
                    <x-icon name="book-open" class="w-3.5 h-3.5 bible-accent" />
                    {{ $book->testament === 'old' ? 'Antigo Testamento' : 'Novo Testamento' }}
                </span>
                <span>{{ $chapters->count() }} capítulos</span>
            </p>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-8 sm:py-10">
        @if(session('error'))
            <div class="mb-6 rounded-xl border-2 border-red-300 dark:border-red-700 bg-red-50 dark:bg-red-900/20 px-4 py-3 text-sm text-red-800 dark:text-red-200">
                {{ session('error') }}
            </div>
        @endif
        @if($chapters->isEmpty())
            <div class="text-center py-16 rounded-2xl bible-card border-2 border-[var(--bible-border)]">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl border-2 border-[var(--bible-border)] flex items-center justify-center text-[var(--bible-muted)]" style="background: var(--bible-bg);">
                    <x-icon name="triangle-exclamation" class="w-8 h-8" style="--fa-primary-opacity: 0.6;" />
                </div>
                <p class="text-[var(--bible-muted)]">Nenhum capítulo disponível para este livro.</p>
            </div>
        @else
            <p class="text-sm font-bold text-[var(--bible-muted)] mb-5 text-center sm:text-left">Selecione o capítulo para ler</p>

            @php
                $chaptersArray = $chapters->all();
                $groupBy = 10;
                $total = count($chaptersArray);
            @endphp
            @if($total <= 20)
                {{-- Livros com poucos capítulos: grid único --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                    @foreach($chapters as $ch)
                        <a href="{{ route('bible.public.chapter', [$version->abbreviation, $book->book_number, $ch->chapter_number]) }}"
                           class="bible-book-chapter-btn">
                            <span class="num">{{ $ch->chapter_number }}</span>
                            <span class="label">Cap. {{ $ch->chapter_number }}</span>
                        </a>
                    @endforeach
                </div>
            @else
                {{-- Livros com muitos capítulos: agrupados de 10 em 10 --}}
                <div class="space-y-8">
                    @for($start = 0; $start < $total; $start += $groupBy)
                        @php
                            $end = min($start + $groupBy, $total);
                            $firstNum = $chaptersArray[$start]->chapter_number;
                            $lastNum = $chaptersArray[$end - 1]->chapter_number;
                        @endphp
                        <section aria-label="Capítulos {{ $firstNum }} a {{ $lastNum }}">
                            <h2 class="bible-book-section-title">Capítulos {{ $firstNum }} – {{ $lastNum }}</h2>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                                @foreach(array_slice($chaptersArray, $start, $end - $start) as $ch)
                                    <a href="{{ route('bible.public.chapter', [$version->abbreviation, $book->book_number, $ch->chapter_number]) }}"
                                       class="bible-book-chapter-btn">
                                        <span class="num">{{ $ch->chapter_number }}</span>
                                        <span class="label">Cap. {{ $ch->chapter_number }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </section>
                    @endfor
                </div>
            @endif
        @endif
    </main>
</div>
@endsection

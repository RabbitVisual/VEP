@extends('homepage::components.layouts.master')

@section('title', 'Bíblia ' . $version->abbreviation . ' – Livros')

@push('styles')
@include('bible::public.partials.bible-public-styles')
@endpush

@section('content')
<div class="bible-public-container min-h-screen pb-24">
    <header class="sticky top-0 z-30 bible-public-header">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-4">
            <div class="flex items-center justify-between gap-4">
                <a href="{{ route('bible.public.index') }}"
                   class="flex items-center gap-2 bible-btn-back rounded-xl px-3 py-2.5 transition-colors shrink-0">
                    <x-icon name="chevron-left" class="w-5 h-5" />
                    <span class="hidden sm:inline text-sm font-bold">Voltar</span>
                </a>
                <div class="flex-1 min-w-0 flex items-center justify-center gap-2">
                    <x-icon name="book-bible" class="w-6 h-6 bible-accent shrink-0" />
                    <h1 class="text-lg sm:text-xl font-black text-[var(--bible-text)] truncate" style="font-family: var(--bible-serif);">Bíblia {{ $version->abbreviation }}</h1>
                </div>
                <div class="w-[calc(theme(spacing.9)+theme(spacing.3))] shrink-0" aria-hidden="true"></div>
            </div>
            <div class="mt-3 pt-3 border-t border-[var(--bible-border)]">
                <label for="version-select" class="sr-only">Trocar versão</label>
                <select id="version-select"
                        onchange="window.location.href = '{{ url('biblia-online/versao') }}/' + this.value"
                        class="w-full appearance-none pl-4 pr-10 py-2.5 rounded-xl border border-[var(--bible-border)] bg-[var(--bible-bg)] text-[var(--bible-text)] font-bold text-sm focus:ring-2 focus:ring-[var(--bible-accent)] focus:border-[var(--bible-accent)] cursor-pointer">
                    @foreach($versions as $v)
                        <option value="{{ $v->abbreviation }}" {{ $v->id === $version->id ? 'selected' : '' }}>{{ $v->name }} ({{ $v->abbreviation }})</option>
                    @endforeach
                </select>
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
        @if(session('error'))
            <div class="mb-6 rounded-xl border-2 border-red-300 dark:border-red-700 bg-red-50 dark:bg-red-900/20 px-4 py-3 text-sm text-red-800 dark:text-red-200">
                {{ session('error') }}
            </div>
        @endif

        <section class="mb-10">
            <h2 class="flex items-center gap-2 text-xs font-black uppercase tracking-widest bible-at-accent mb-4">
                <x-icon name="book-open" class="w-4 h-4" />
                Antigo Testamento
            </h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2 sm:gap-3">
                @foreach($oldTestament as $b)
                    <a href="{{ route('bible.public.book', [$version->abbreviation, $b->book_number]) }}"
                       class="flex items-center justify-center p-3 sm:p-4 rounded-xl bible-card border-2 border-[var(--bible-border)] hover:border-[var(--bible-at)] text-[var(--bible-text)] font-bold text-sm sm:text-base hover:shadow-md active:scale-[0.98] transition-all"
                       style="font-family: var(--bible-serif);">
                        {{ $b->name }}
                    </a>
                @endforeach
            </div>
        </section>

        <section>
            <h2 class="flex items-center gap-2 text-xs font-black uppercase tracking-widest bible-nt-accent mb-4">
                <x-icon name="book-open" class="w-4 h-4" />
                Novo Testamento
            </h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2 sm:gap-3">
                @foreach($newTestament as $b)
                    <a href="{{ route('bible.public.book', [$version->abbreviation, $b->book_number]) }}"
                       class="flex items-center justify-center p-3 sm:p-4 rounded-xl bible-card border-2 border-[var(--bible-border)] hover:border-[var(--bible-nt)] text-[var(--bible-text)] font-bold text-sm sm:text-base hover:shadow-md active:scale-[0.98] transition-all"
                       style="font-family: var(--bible-serif);">
                        {{ $b->name }}
                    </a>
                @endforeach
            </div>
        </section>
    </main>
</div>
@endsection

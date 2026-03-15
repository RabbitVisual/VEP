@extends('homepage::components.layouts.master')

@section('title', 'Bíblia Online')

@push('styles')
@include('bible::public.partials.bible-public-styles')
@endpush

@section('content')
<div class="bible-public-container min-h-[70vh] flex items-center justify-center px-4 py-16">
    <div class="max-w-md w-full text-center">
        <div class="w-24 h-24 mx-auto mb-6 rounded-2xl bible-card border-2 border-[var(--bible-border)] flex items-center justify-center text-[var(--bible-muted)]">
            <x-icon name="book-bible" class="w-12 h-12" style="--fa-primary-opacity: 0.5; --fa-secondary-opacity: 0.3;" />
        </div>
        <h1 class="text-2xl font-bold text-[var(--bible-text)] mb-2" style="font-family: var(--bible-serif);">Bíblia Online</h1>
        <p class="text-[var(--bible-muted)] mb-8">Nenhuma versão da Bíblia está disponível no momento. Tente novamente mais tarde.</p>
        <a href="{{ route('homepage.index') }}"
           class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bible-card border-2 border-[var(--bible-border-strong)] text-[var(--bible-text)] font-bold hover:border-[var(--bible-accent)] transition-colors">
            <x-icon name="arrow-left" class="w-5 h-5" />
            Voltar ao início
        </a>
    </div>
</div>
@endsection

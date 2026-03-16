@extends('memberpanel::components.layouts.master')

@section('title', 'Feed')
@section('page-title', 'Feed')

@section('content')
<div class="max-w-3xl mx-auto">
  <div class="mb-6 flex items-center gap-3">
    <i class="fa-duotone fa-house-user text-2xl text-slate-600 dark:text-slate-400"></i>
    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Feed da Comunidade</h1>
  </div>

  <form action="{{ route('painel.community.posts.store') }}" method="POST" class="mb-8 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 bg-white/70 dark:bg-slate-900/50 backdrop-blur-sm shadow-sm p-5">
    @csrf
    <label for="post-content" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Compartilhe com a comunidade</label>
    <textarea name="content" id="post-content" rows="3" required maxlength="10000" placeholder="Escreva uma atualização, pergunta ou testemunho... Use @ para referências bíblicas (ex: @João 3:16)." class="w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500 px-4 py-3 text-sm" data-mention-editor="true"></textarea>
    <input type="hidden" name="type" value="update">
    <div class="mt-3 flex justify-end">
      <button type="submit" class="px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium transition-colors">Publicar</button>
    </div>
  </form>

  <div class="space-y-6" id="feed-container">
    @forelse($feed as $entry)
      @include('community::components.feed-card', ['entry' => $entry])
    @empty
      <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white/70 dark:bg-slate-900/50 backdrop-blur-sm p-8 text-center text-slate-500 dark:text-slate-400">
        <i class="fa-duotone fa-house-user text-4xl mb-3 opacity-50"></i>
        <p>Nenhuma atividade no feed ainda. Compartilhe um post ou acompanhe sermões e conquistas da comunidade.</p>
      </div>
    @endforelse
  </div>

  @if($feed->hasPages())
    <div class="mt-8 flex justify-center">
      <nav class="flex items-center gap-2">
        @if($feed->onFirstPage())
          <span class="px-4 py-2 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 cursor-not-allowed text-sm">Anterior</span>
        @else
          <a href="{{ $feed->previousPageUrl() }}" class="px-4 py-2 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 text-sm font-medium transition-colors">Anterior</a>
        @endif
        <span class="px-4 py-2 text-sm text-slate-600 dark:text-slate-400">Página {{ $feed->currentPage() }} de {{ $feed->lastPage() }}</span>
        @if($feed->hasMorePages())
          <a href="{{ $feed->nextPageUrl() }}" class="px-4 py-2 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 text-sm font-medium transition-colors">Próxima</a>
        @else
          <span class="px-4 py-2 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 cursor-not-allowed text-sm">Próxima</span>
        @endif
      </nav>
    </div>
  @endif
</div>
@endsection

@extends('memberpanel::components.layouts.master')

@section('title', 'Comunidade')
@section('page-title', 'Comunidade')

@section('content')
<div class="space-y-8 pb-12">
  {{-- Hero --}}
  <div class="relative overflow-hidden bg-slate-900 rounded-3xl shadow-2xl border border-slate-800">
    <div class="absolute inset-0 opacity-40 pointer-events-none">
      <div class="absolute -top-24 -left-20 w-96 h-96 bg-blue-600 rounded-full blur-[100px]"></div>
      <div class="absolute top-1/2 right-40 w-80 h-80 bg-amber-500 rounded-full blur-[100px]"></div>
    </div>
    <div class="relative px-8 py-8 flex flex-col md:flex-row items-center justify-between gap-6 z-10">
      <div class="flex-1 space-y-2">
        <p class="text-amber-200/80 font-bold uppercase tracking-widest text-xs">Vida em Comunidade</p>
        <h1 class="text-3xl md:text-4xl font-black text-white tracking-tight">Feed da Comunidade</h1>
        <p class="text-slate-300 font-medium max-w-xl">
          Partilhe atualizações, dúvidas e testemunhos. Use @ para mencionar textos bíblicos e fortalecer a edificação mútua.
        </p>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    {{-- Coluna principal: composer + feed --}}
    <div class="lg:col-span-3 space-y-6">
      <form action="{{ route('painel.community.posts.store') }}" method="POST" class="rounded-2xl border border-slate-200/80 dark:border-slate-700/80 bg-white/70 dark:bg-slate-900/50 backdrop-blur-sm shadow-sm p-5">
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
        <div class="mt-4 flex justify-center">
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

    {{-- Coluna lateral: atalhos --}}
    <aside class="lg:col-span-1 space-y-4">
      <div class="rounded-2xl border border-slate-200/80 dark:border-slate-700/80 bg-white/70 dark:bg-slate-900/60 backdrop-blur-sm shadow-sm p-5">
        <h2 class="text-sm font-semibold text-slate-800 dark:text-slate-100 mb-3">Atalhos rápidos</h2>
        <ul class="space-y-2 text-sm">
          <li><a href="{{ route('painel.community.prayers.index') }}" class="flex items-center gap-2 text-slate-600 dark:text-slate-300 hover:text-amber-600 dark:hover:text-amber-400"><i class="fa-duotone fa-hands-praying text-xs"></i> Mural de Intercessão</a></li>
          <li><a href="{{ route('painel.sermons.index') }}" class="flex items-center gap-2 text-slate-600 dark:text-slate-300 hover:text-amber-600 dark:hover:text-amber-400"><i class="fa-duotone fa-podium text-xs"></i> Sermões</a></li>
        </ul>
      </div>
    </aside>
  </div>
</div>
@endsection

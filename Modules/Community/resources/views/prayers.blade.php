@extends('memberpanel::components.layouts.master')

@section('title', 'Mural de Intercessão')
@section('page-title', 'Mural de Intercessão')

@section('content')
<div class="max-w-4xl mx-auto">
  <div class="mb-6 flex items-center gap-3">
    <i class="fa-duotone fa-hands-praying text-2xl text-slate-600 dark:text-slate-400"></i>
    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Mural de Intercessão</h1>
  </div>

  <p class="mb-6 text-slate-600 dark:text-slate-400">Compartilhe fardos e ore pelos irmãos. Clique em "Estou orando" para apoiar.</p>

  <div x-data="{ showForm: false }" class="mb-8">
    <button type="button" @click="showForm = !showForm" class="rounded-xl border border-amber-500 text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/20 px-4 py-2 text-sm font-medium transition-colors">
      <i class="fa-duotone fa-hands-praying mr-2"></i> Pedir oração
    </button>
    <form x-show="showForm" x-cloak action="{{ route('painel.community.prayers.store') }}" method="POST" class="mt-4 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white/70 dark:bg-slate-900/50 space-y-3">
      @csrf
      <input type="text" name="title" required maxlength="255" placeholder="Título do pedido" class="w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white px-4 py-2 text-sm">
      <textarea name="content" required maxlength="5000" rows="4" placeholder="Descreva seu pedido de oração..." class="w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white px-4 py-2 text-sm"></textarea>
      <button type="submit" class="px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium">Publicar pedido</button>
    </form>
  </div>

  <div class="space-y-6">
    @forelse($prayerRequests as $pr)
      <div
        x-data="{
          praysCount: {{ $pr->prays_count }},
          hasPrayed: {{ isset($userPrayedIds[$pr->id]) ? 'true' : 'false' }},
          loading: false,
          async pray() {
            if (this.hasPrayed) return;
            this.loading = true;
            try {
              const res = await fetch('{{ route('painel.community.prayers.pray', $pr) }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                body: JSON.stringify({})
              });
              const data = await res.json();
              if (res.ok) {
                this.praysCount = data.prays_count;
                this.hasPrayed = true;
              }
            } finally {
              this.loading = false;
            }
          }
        }"
        class="rounded-2xl border border-slate-200/80 dark:border-slate-700/80 bg-white/70 dark:bg-slate-900/50 backdrop-blur-sm shadow-sm overflow-hidden"
      >
        <div class="p-5">
          <div class="flex items-start gap-4">
            @if($pr->user)
              <div class="shrink-0">
                @if($pr->user->avatar_url ?? null)
                  <img src="{{ $pr->user->avatar_url }}" alt="" class="w-12 h-12 rounded-full object-cover ring-2 ring-slate-200 dark:ring-slate-600">
                @else
                  <div class="w-12 h-12 rounded-full flex items-center justify-center bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-semibold text-lg">{{ strtoupper(substr($pr->user->first_name ?? 'U', 0, 1)) }}</div>
                @endif
              </div>
            @endif
            <div class="flex-1 min-w-0">
              <h2 class="font-semibold text-slate-900 dark:text-white">{{ $pr->title }}</h2>
              <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">{{ $pr->user->name ?? 'Membro' }} · {{ $pr->created_at->diffForHumans() }}</p>
              <div class="mt-3 text-slate-700 dark:text-slate-300 whitespace-pre-wrap">{{ $pr->content }}</div>
              @if($pr->status === 'answered')
                <p class="mt-2 text-sm font-medium text-emerald-600 dark:text-emerald-400"><i class="fa-duotone fa-check-circle"></i> Oração respondida</p>
              @endif
            </div>
          </div>
          <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-700/50 flex items-center justify-between">
            <button
              type="button"
              @click="pray()"
              :disabled="hasPrayed || loading"
              class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium transition-colors disabled:opacity-60 disabled:cursor-not-allowed"
              :class="hasPrayed ? 'bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-200' : 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 hover:bg-amber-100 dark:hover:bg-amber-900/30 hover:text-amber-800 dark:hover:text-amber-200'"
            >
              <i class="fa-duotone fa-hands-praying"></i>
              <span x-text="hasPrayed ? 'Você está orando' : 'Estou orando'"></span>
              <span x-show="praysCount > 0" x-text="'(' + praysCount + ')'"></span>
            </button>
          </div>
        </div>
      </div>
    @empty
      <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white/70 dark:bg-slate-900/50 backdrop-blur-sm p-8 text-center text-slate-500 dark:text-slate-400">
        <i class="fa-duotone fa-hands-praying text-4xl mb-3 opacity-50"></i>
        <p>Nenhum pedido de oração no mural. Seja o primeiro a compartilhar.</p>
      </div>
    @endforelse
  </div>

  @if($prayerRequests->hasPages())
    <div class="mt-8 flex justify-center">
      {{ $prayerRequests->links() }}
    </div>
  @endif
</div>
@endsection

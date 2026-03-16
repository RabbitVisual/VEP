@extends('memberpanel::components.layouts.master')

@section('title', $profileUser->name ?? 'Perfil')
@section('page-title', $profileUser->name ?? 'Perfil')

@section('content')
<div class="max-w-4xl mx-auto" x-data="{ tab: 'activity' }">
  {{-- Header --}}
  <div class="rounded-2xl border border-slate-200/80 dark:border-slate-700/80 bg-white/70 dark:bg-slate-900/50 backdrop-blur-sm shadow-sm overflow-hidden mb-6">
    <div class="p-6 flex flex-col sm:flex-row items-center sm:items-start gap-4">
      @if($profileUser->avatar_url ?? null)
        <img src="{{ $profileUser->avatar_url }}" alt="" class="w-24 h-24 rounded-full object-cover ring-4 ring-slate-200 dark:ring-slate-600 shrink-0">
      @else
        <div class="w-24 h-24 rounded-full flex items-center justify-center bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold text-3xl shrink-0">{{ strtoupper(substr($profileUser->first_name ?? 'U', 0, 1)) }}</div>
      @endif
      <div class="flex-1 text-center sm:text-left">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $profileUser->name ?? 'Membro' }}</h1>
        @if(!empty($profileUser->ministry))
          <p class="mt-1 text-slate-600 dark:text-slate-400"><i class="fa-duotone fa-church"></i> {{ $profileUser->ministry }}</p>
        @endif
        @if($profileUser->id !== auth()->id())
          <div class="mt-4">
            <form id="follow-form" method="POST" action="{{ route('painel.community.follow.toggle', ['user' => $profileUser]) }}" class="inline">
              @csrf
              @if($isFollowing)
                <button type="submit" name="action" value="unfollow" class="px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 text-sm font-medium transition-colors">Deixar de seguir</button>
              @else
                <button type="submit" name="action" value="follow" class="px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium transition-colors">Seguir</button>
              @endif
            </form>
          </div>
        @endif
      </div>
    </div>
  </div>

  {{-- Tabs --}}
  <div class="flex gap-2 border-b border-slate-200 dark:border-slate-700 mb-6">
    <button type="button" @click="tab = 'activity'" :class="tab === 'activity' ? 'border-amber-500 text-amber-600 dark:text-amber-400 font-medium' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300'" class="px-4 py-2 border-b-2 transition-colors flex items-center gap-2">
      <i class="fa-duotone fa-house-user"></i>
      Atividade
    </button>
    <button type="button" @click="tab = 'badges'" :class="tab === 'badges' ? 'border-amber-500 text-amber-600 dark:text-amber-400 font-medium' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300'" class="px-4 py-2 border-b-2 transition-colors flex items-center gap-2">
      <i class="fa-duotone fa-medal"></i>
      Conquistas
    </button>
  </div>

  {{-- Tab: Atividade --}}
  <div x-show="tab === 'activity'" x-cloak class="space-y-6">
    @forelse($activityFeed as $entry)
      @include('community::components.feed-card', ['entry' => $entry])
    @empty
      <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white/70 dark:bg-slate-900/50 p-8 text-center text-slate-500 dark:text-slate-400">
        <i class="fa-duotone fa-house-user text-4xl mb-3 opacity-50"></i>
        <p>Nenhuma atividade pública ainda.</p>
      </div>
    @endforelse
  </div>

  {{-- Tab: Conquistas --}}
  <div x-show="tab === 'badges'" x-cloak class="space-y-6">
    @if(count($badges) > 0)
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($badges as $badge)
          @php
            $label = class_exists(\VertexSolutions\Core\Models\BibleUserBadge::class) ? \VertexSolutions\Core\Models\BibleUserBadge::getLabel($badge->badge_key) : $badge->badge_key;
          @endphp
          <div class="rounded-2xl border border-slate-200/80 dark:border-slate-700/80 bg-white/70 dark:bg-slate-900/50 backdrop-blur-sm shadow-sm p-5 flex items-center gap-4 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
            <div class="w-14 h-14 rounded-full flex items-center justify-center bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 shrink-0">
              <i class="fa-duotone fa-medal text-2xl"></i>
            </div>
            <div class="min-w-0">
              <p class="font-semibold text-slate-900 dark:text-white">{{ $label }}</p>
              <p class="text-sm text-slate-500 dark:text-slate-400">{{ $badge->awarded_at->translatedFormat('d/m/Y') }}</p>
            </div>
          </div>
        @endforeach
      </div>
    @else
      <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white/70 dark:bg-slate-900/50 p-8 text-center text-slate-500 dark:text-slate-400">
        <i class="fa-duotone fa-medal text-4xl mb-3 opacity-50"></i>
        <p>Nenhuma conquista ainda. Complete planos de leitura e cursos para desbloquear medalhas.</p>
      </div>
    @endif
  </div>
</div>
@endsection

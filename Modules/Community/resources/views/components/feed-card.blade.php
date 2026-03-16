@props(['entry'])
@php
  $type = $entry['type'] ?? 'post';
  $date = $entry['date'] ?? now();
  $item = $entry['item'] ?? null;
  if (!$item) return;
  $user = $item->user ?? null;
  $contentHtml = $type === 'post' ? \App\Services\TheologicalMarkdownConverter::convert($item->content ?? '') : null;
@endphp
<article class="rounded-2xl border border-slate-200/80 dark:border-slate-700/80 bg-white/70 dark:bg-slate-900/50 backdrop-blur-sm shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 overflow-hidden">
  <div class="p-5">
    <div class="flex items-start gap-4">
      @if($user)
        <a href="{{ route('painel.community.profile.show', $user) }}" class="shrink-0">
          @if($user->avatar_url ?? null)
            <img src="{{ $user->avatar_url }}" alt="" class="w-12 h-12 rounded-full object-cover ring-2 ring-slate-200 dark:ring-slate-600">
          @else
            <div class="w-12 h-12 rounded-full flex items-center justify-center bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-semibold text-lg">{{ strtoupper(substr($user->first_name ?? 'U', 0, 1)) }}</div>
          @endif
        </a>
        <div class="flex-1 min-w-0">
          <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('painel.community.profile.show', $user) }}" class="font-semibold text-slate-900 dark:text-white hover:underline">{{ $user->name ?? 'Membro' }}</a>
            <span class="text-slate-500 dark:text-slate-400 text-sm">{{ $date->diffForHumans() }}</span>
            @if($type === 'post' && isset($item->type))
              <span class="text-xs px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300">{{ match($item->type) { 'update' => 'Atualização', 'question' => 'Pergunta', 'testimony' => 'Testemunho', default => $item->type } }}</span>
            @endif
          </div>
          @if($type === 'post')
            <div class="mt-2 prose prose-slate dark:prose-invert max-w-none text-sm prose-p:my-1 prose-a:text-amber-600 dark:prose-a:text-amber-400">{!! $contentHtml !!}</div>
          @elseif($type === 'sermon')
            <h3 class="mt-1 font-medium text-slate-900 dark:text-white">{{ $item->title }}</h3>
            @if(!empty($item->description))
              <p class="mt-1 text-sm text-slate-600 dark:text-slate-400 line-clamp-2">{{ Str::limit(strip_tags($item->description), 120) }}</p>
            @endif
            <a href="{{ route('painel.sermons.show', $item) }}" class="inline-flex items-center gap-1 mt-2 text-sm font-medium text-amber-600 dark:text-amber-400 hover:underline">
              <i class="fa-duotone fa-book-bible"></i> Ver sermão
            </a>
          @elseif($type === 'certificate')
            <p class="mt-1 text-slate-700 dark:text-slate-300">
              <i class="fa-duotone fa-medal text-amber-500 dark:text-amber-400"></i>
              Concluiu o curso <strong>{{ $item->course->title ?? 'Curso' }}</strong>
            </p>
            <span class="text-xs text-slate-500 dark:text-slate-400">{{ $date->translatedFormat('d/m/Y') }}</span>
          @endif
        </div>
      @endif
    </div>
    <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-700/50 flex items-center gap-4 text-slate-500 dark:text-slate-400 text-sm">
      <button type="button" class="flex items-center gap-1.5 hover:text-amber-600 dark:hover:text-amber-400 transition-colors" aria-label="Curtir">
        <i class="fa-duotone fa-heart"></i>
        <span>Curtir</span>
      </button>
      <button type="button" class="flex items-center gap-1.5 hover:text-amber-600 dark:hover:text-amber-400 transition-colors" aria-label="Comentar">
        <i class="fa-duotone fa-comment"></i>
        <span>Comentar</span>
      </button>
    </div>
  </div>
</article>

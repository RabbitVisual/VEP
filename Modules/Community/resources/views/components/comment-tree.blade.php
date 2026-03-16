@props(['comments', 'post' => null])
@php
  /** @var \Illuminate\Support\Collection|\VertexSolutions\Community\Models\PostComment[] $comments */
@endphp
@if($comments && $comments->isNotEmpty())
  <div class="space-y-2">
    @foreach($comments as $comment)
      @php
        $user = $comment->user ?? null;
        $contentHtml = \App\Services\TheologicalMarkdownConverter::convert($comment->content ?? '');
        $reactions = method_exists($comment, 'reactions') ? [
            'like' => $comment->reactions()->where('type', \VertexSolutions\Community\Models\Reaction::TYPE_LIKE)->count(),
            'amen' => $comment->reactions()->where('type', \VertexSolutions\Community\Models\Reaction::TYPE_AMEN)->count(),
            'praying' => $comment->reactions()->where('type', \VertexSolutions\Community\Models\Reaction::TYPE_PRAYING)->count(),
        ] : ['like' => 0, 'amen' => 0, 'praying' => 0];
      @endphp
      <div
        class="flex items-start gap-2 text-xs"
        x-data="communityCommentNode({
          commentId: {{ (int) $comment->id }},
          reactions: @js($reactions),
        })"
      >
        <div class="mt-0.5">
          <i class="fa-duotone fa-comment text-[11px]"></i>
        </div>
        <div class="flex-1 min-w-0">
          <p class="font-semibold text-slate-700 dark:text-slate-200">
            {{ $user->name ?? 'Membro' }}
            <span class="ml-1 text-[10px] font-normal text-slate-400">{{ $comment->created_at?->diffForHumans() }}</span>
          </p>
          <div class="text-slate-600 dark:text-slate-300 prose prose-slate dark:prose-invert max-w-none text-xs">
            {!! $contentHtml !!}
          </div>
          <div class="mt-1 flex items-center gap-3 text-[11px] text-slate-400 dark:text-slate-500">
            <button
              type="button"
              @click.prevent="toggleReaction('like')"
              class="inline-flex items-center gap-1 hover:text-amber-600 dark:hover:text-amber-400 transition-all duration-200"
              :class="{ 'scale-110 text-amber-600 dark:text-amber-400': hasReaction('like') }"
            >
              <i class="fa-duotone fa-heart text-[10px]"></i>
              <span>Curtir</span>
              <span x-show="reactions.like > 0" x-text="reactions.like" class="ml-0.5"></span>
            </button>
            <button
              type="button"
              @click.prevent="toggleReaction('amen')"
              class="inline-flex items-center gap-1 hover:text-amber-600 dark:hover:text-amber-400 transition-all duration-200"
              :class="{ 'scale-110 text-amber-600 dark:text-amber-400': hasReaction('amen') }"
            >
              <i class="fa-duotone fa-hands-praying text-[10px]"></i>
              <span>Amém</span>
              <span x-show="reactions.amen > 0" x-text="reactions.amen" class="ml-0.5"></span>
            </button>
            <button
              type="button"
              @click.prevent="toggleReaction('praying')"
              class="inline-flex items-center gap-1 hover:text-amber-600 dark:hover:text-amber-400 transition-all duration-200"
              :class="{ 'scale-110 text-amber-600 dark:text-amber-400': hasReaction('praying') }"
            >
              <i class="fa-duotone fa-hands-holding text-[10px]"></i>
              <span>Orando</span>
              <span x-show="reactions.praying > 0" x-text="reactions.praying" class="ml-0.5"></span>
            </button>
            <button
              type="button"
              @click="toggleReply"
              class="inline-flex items-center gap-1 hover:text-amber-600 dark:hover:text-amber-400 transition-colors"
            >
              <i class="fa-duotone fa-reply text-[10px]"></i>
              <span>Responder</span>
            </button>
          </div>

          <div x-show="showReply" x-cloak x-transition class="mt-2">
            <form @submit.prevent="submitReply({{ (int) ($post?->id ?? 0) }})">
              <label :for="'reply-' + commentId" class="sr-only">Responder comentário</label>
              <textarea
                :id="'reply-' + commentId"
                x-ref="replyInput"
                name="content"
                rows="2"
                maxlength="1000"
                required
                class="w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white px-3 py-1.5 text-xs focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500"
                placeholder="Escreva uma resposta..."
                data-mention-editor="true"
              ></textarea>
              <div class="mt-1 flex justify-end gap-2">
                <button type="button" @click="toggleReply" class="px-2 py-1 rounded-lg border border-slate-300 dark:border-slate-600 text-[11px] text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800">
                  Cancelar
                </button>
                <button type="submit" class="px-3 py-1 rounded-lg bg-amber-500 hover:bg-amber-600 text-white text-[11px] font-medium transition-colors">
                  Responder
                </button>
              </div>
            </form>
          </div>

          @php
            $replies = $comment->relationLoaded('replies') ? $comment->replies : $comment->replies()->with('user')->get();
          @endphp
          @if($replies->isNotEmpty())
            <div class="mt-2 pl-3 border-l border-slate-200 dark:border-slate-700">
              <x-community::comment-tree :comments="$replies" :post="$post" />
            </div>
          @endif
        </div>
      </div>
    @endforeach
  </div>
@endif


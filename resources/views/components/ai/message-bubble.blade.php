@props([
    'role' => 'assistant',
    'content' => '',
    'markdown' => true,
])

@php
    $isUser = $role === 'user';
    if ($markdown && $content !== '' && ! $isUser) {
        $html = \App\Services\TheologicalMarkdownConverter::convert($content);
    } else {
        $html = $isUser ? e($content) : $content;
    }
@endphp

<div
    @class([
        'flex gap-3 px-4 py-3',
        'justify-end' => $isUser,
        'justify-start' => ! $isUser,
    ])
>
    @if (! $isUser)
        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary" aria-hidden="true">
            <x-icon name="microchip-ai" style="duotone" class="text-sm" />
        </div>
    @endif
    <div
        @class([
            'max-w-[85%] rounded-xl px-4 py-3 text-sm',
            'bg-primary text-primary-foreground' => $isUser,
            'bg-muted/60 dark:bg-muted/40 text-foreground border border-border/60' => ! $isUser,
        ])
    >
        @if ($isUser)
            <p class="whitespace-pre-wrap">{{ $content }}</p>
        @else
            <div class="prose prose-sm dark:prose-invert max-w-none prose-p:my-1.5 prose-ul:my-1.5 prose-li:my-0.5 prose-a:text-primary prose-a:no-underline hover:prose-a:underline">
                {!! $html !!}
            </div>
        @endif
    </div>
    @if ($isUser)
        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-muted text-muted-foreground" aria-hidden="true">
            <x-icon name="user" style="duotone" class="text-sm" />
        </div>
    @endif
</div>

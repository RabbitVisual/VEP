@props([
    'title' => 'Chat Teológico',
    'subtitle' => null,
])

{{-- Container do chat: glassmorphism, paleta Vertex, FA Duotone --}}
<div
    {{ $attributes->merge([
        'class' => 'rounded-2xl border border-border/80 bg-card/80 dark:bg-card/90 backdrop-blur-xl shadow-lg overflow-hidden',
    ]) }}
>
    <div class="flex items-center gap-3 px-5 py-4 border-b border-border/80 bg-[var(--color-vertex-glass)]">
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
            <x-icon name="sparkles" style="duotone" class="text-xl" />
        </div>
        <div class="min-w-0 flex-1">
            <h3 class="font-semibold text-card-foreground truncate">{{ $title }}</h3>
            @if ($subtitle)
                <p class="text-xs text-muted-foreground truncate">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
    <div class="min-h-[200px] flex flex-col">
        {{ $slot }}
    </div>
</div>

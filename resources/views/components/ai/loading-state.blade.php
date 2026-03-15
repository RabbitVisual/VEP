@props([
    'message' => 'Processando...',
])

<div
    {{ $attributes->merge([
        'class' => 'flex items-center gap-3 px-4 py-4 text-muted-foreground',
    ]) }}
    role="status"
    aria-live="polite"
    aria-label="{{ $message }}"
>
    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary">
        <x-icon name="microchip-ai" style="duotone" class="text-sm animate-pulse" />
    </div>
    <div class="flex flex-col gap-2 min-w-0">
        <p class="text-sm font-medium">{{ $message }}</p>
        <div class="flex gap-1.5">
            <span class="h-2 w-2 rounded-full bg-primary/50 animate-[bounce_1s_ease-in-out_infinite]" style="animation-delay: 0ms"></span>
            <span class="h-2 w-2 rounded-full bg-primary/50 animate-[bounce_1s_ease-in-out_infinite]" style="animation-delay: 150ms"></span>
            <span class="h-2 w-2 rounded-full bg-primary/50 animate-[bounce_1s_ease-in-out_infinite]" style="animation-delay: 300ms"></span>
        </div>
    </div>
</div>

{{--
    Skeleton – Vertex Escola de Pastores (EAD)
    Placeholders de carregamento para conteúdo assíncrono (listas, cards, vídeo, aulas).
    A11y: role="status", sr-only "Carregando…". Respeita prefers-reduced-motion.
    Uso: <x-skeleton variant="card" /> | <x-skeleton variant="video" /> | <x-skeleton variant="course-card" :rows="3" />
--}}
@props([
    'variant' => 'text',
    'rows' => null,
    'shimmer' => true,
])

@php
    $count = $rows ?? match ($variant) {
        'list' => 4,
        'table' => 5,
        'course-card' => 3,
        'lesson-row' => 5,
        default => 1,
    };
    $count = max(1, min((int) $count, 12));
    $baseClass = 'vertex-skeleton-bg rounded';
    $shimmerClass = $shimmer ? 'vertex-skeleton-shimmer' : '';
@endphp

<div
    role="status"
    aria-label="{{ __('Carregando...') }}"
    {{ $attributes->merge(['class' => 'vertex-skeleton max-w-full']) }}
>
    @if ($variant === 'text')
        <div class="space-y-3 {{ $shimmerClass }}">
            <div class="h-4 {{ $baseClass }} rounded-full w-4/5 max-w-sm"></div>
            <div class="h-3 {{ $baseClass }} rounded-full max-w-[340px]"></div>
            <div class="h-3 {{ $baseClass }} rounded-full max-w-[300px]"></div>
            <div class="h-3 {{ $baseClass }} rounded-full max-w-[280px]"></div>
        </div>

    @elseif ($variant === 'card')
        <div class="p-5 border border-gray-200 dark:border-slate-600/50 rounded-xl shadow-sm space-y-4 bg-white/50 dark:bg-slate-900/30 {{ $shimmerClass }}">
            <div class="h-44 {{ $baseClass }} rounded-lg w-full"></div>
            <div class="h-4 {{ $baseClass }} rounded-full w-1/2"></div>
            <div class="h-3 {{ $baseClass }} rounded-full"></div>
            <div class="h-3 {{ $baseClass }} rounded-full max-w-[85%]"></div>
            <div class="flex items-center gap-3 mt-4 pt-4 border-t border-gray-200 dark:border-slate-600/50">
                <div class="w-10 h-10 {{ $baseClass }} rounded-full shrink-0"></div>
                <div class="flex-1 space-y-2 min-w-0">
                    <div class="h-3 {{ $baseClass }} rounded-full w-28"></div>
                    <div class="h-2.5 {{ $baseClass }} rounded-full w-36"></div>
                </div>
            </div>
        </div>

    @elseif ($variant === 'list')
        <div class="divide-y divide-gray-200 dark:divide-slate-600/50 rounded-xl border border-gray-200 dark:border-slate-600/50 overflow-hidden bg-white/50 dark:bg-slate-900/30 {{ $shimmerClass }}">
            @foreach (range(1, $count) as $i)
                <div class="flex items-center justify-between gap-4 p-4">
                    <div class="flex items-center gap-3 min-w-0 flex-1">
                        <div class="w-10 h-10 {{ $baseClass }} rounded-full shrink-0"></div>
                        <div class="space-y-2 min-w-0">
                            <div class="h-3 {{ $baseClass }} rounded-full w-32"></div>
                            <div class="h-2.5 {{ $baseClass }} rounded-full w-24"></div>
                        </div>
                    </div>
                    <div class="h-3 {{ $baseClass }} rounded-full w-14 shrink-0"></div>
                </div>
            @endforeach
        </div>

    @elseif ($variant === 'table')
        <div class="rounded-xl border border-gray-200 dark:border-slate-600/50 overflow-hidden bg-white/50 dark:bg-slate-900/30 {{ $shimmerClass }}">
            <div class="border-b border-gray-200 dark:border-slate-600/50 p-4 flex gap-4">
                @foreach (range(1, 4) as $c)
                    <div class="h-3 {{ $baseClass }} rounded-full flex-1 max-w-[120px]"></div>
                @endforeach
            </div>
            @foreach (range(1, $count) as $i)
                <div class="border-b border-gray-200 dark:border-slate-600/50 last:border-0 p-4 flex gap-4">
                    @foreach (range(1, 4) as $c)
                        <div class="h-3 {{ $baseClass }} rounded-full flex-1"></div>
                    @endforeach
                </div>
            @endforeach
        </div>

    @elseif ($variant === 'video')
        {{-- Player EAD: 16/9 + barra de controles + título --}}
        <div class="rounded-xl border border-gray-200 dark:border-slate-600/50 overflow-hidden bg-white/50 dark:bg-slate-900/30 {{ $shimmerClass }}">
            <div class="aspect-video w-full {{ $baseClass }} flex items-center justify-center">
                <x-icon name="circle-play" style="duotone" class="w-16 h-16 text-gray-400 dark:text-slate-500" />
            </div>
            <div class="p-4 space-y-2">
                <div class="h-4 {{ $baseClass }} rounded-full w-2/3"></div>
                <div class="h-3 {{ $baseClass }} rounded-full w-1/2"></div>
            </div>
            <div class="flex gap-2 p-4 pt-0">
                <div class="h-9 {{ $baseClass }} rounded-lg w-20"></div>
                <div class="h-9 {{ $baseClass }} rounded-lg flex-1 max-w-[200px]"></div>
            </div>
        </div>

    @elseif ($variant === 'course-card')
        {{-- Grid de cards de curso (thumbnail + título + meta) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 {{ $shimmerClass }}">
            @foreach (range(1, $count) as $i)
                <div class="rounded-xl border border-gray-200 dark:border-slate-600/50 overflow-hidden bg-white/50 dark:bg-slate-900/30 shadow-sm">
                    <div class="aspect-[16/10] {{ $baseClass }}"></div>
                    <div class="p-4 space-y-3">
                        <div class="h-4 {{ $baseClass }} rounded-full w-3/4"></div>
                        <div class="h-3 {{ $baseClass }} rounded-full w-full"></div>
                        <div class="h-3 {{ $baseClass }} rounded-full w-1/2"></div>
                        <div class="flex items-center gap-2 pt-2">
                            <div class="w-8 h-8 {{ $baseClass }} rounded-full"></div>
                            <div class="h-3 {{ $baseClass }} rounded-full w-24"></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    @elseif ($variant === 'lesson-row')
        {{-- Lista de aulas (ícone + título + duração) --}}
        <div class="space-y-1 {{ $shimmerClass }}">
            @foreach (range(1, $count) as $i)
                <div class="flex items-center gap-4 p-3 rounded-lg border border-gray-200 dark:border-slate-600/50 bg-white/30 dark:bg-slate-800/30">
                    <div class="w-10 h-10 {{ $baseClass }} rounded-lg shrink-0 flex items-center justify-center">
                        <x-icon name="play" style="solid" class="w-4 h-4 text-gray-400 dark:text-slate-500" />
                    </div>
                    <div class="flex-1 min-w-0 space-y-2">
                        <div class="h-3.5 {{ $baseClass }} rounded-full w-full max-w-[240px]"></div>
                        <div class="h-2.5 {{ $baseClass }} rounded-full w-16"></div>
                    </div>
                    <div class="h-3 {{ $baseClass }} rounded-full w-12 shrink-0"></div>
                </div>
            @endforeach
        </div>

    @elseif ($variant === 'avatar-text')
        <div class="flex items-center gap-4 {{ $shimmerClass }}">
            <div class="w-12 h-12 {{ $baseClass }} rounded-full shrink-0"></div>
            <div class="flex-1 space-y-2 min-w-0">
                <div class="h-4 {{ $baseClass }} rounded-full w-32"></div>
                <div class="h-3 {{ $baseClass }} rounded-full w-48"></div>
            </div>
        </div>

    @else
        {{ $slot }}
    @endif

    <span class="sr-only">{{ __('Carregando...') }}</span>
</div>

<style>
    .vertex-skeleton-bg {
        background-color: oklch(0.92 0.01 260 / 0.7);
    }
    .dark .vertex-skeleton-bg {
        background-color: oklch(0.28 0.02 260 / 0.5);
    }
    .vertex-skeleton-shimmer .vertex-skeleton-bg {
        position: relative;
        overflow: hidden;
    }
    .vertex-skeleton-shimmer .vertex-skeleton-bg::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(
            105deg,
            transparent 0%,
            oklch(1 0 0 / 0.08) 45%,
            oklch(1 0 0 / 0.12) 50%,
            oklch(1 0 0 / 0.08) 55%,
            transparent 100%
        );
        transform: translateX(-100%);
        animation: vertex-skeleton-shine 1.8s ease-in-out infinite;
    }
    .dark .vertex-skeleton-shimmer .vertex-skeleton-bg::after {
        background: linear-gradient(
            105deg,
            transparent 0%,
            oklch(1 0 0 / 0.06) 45%,
            oklch(1 0 0 / 0.1) 50%,
            oklch(1 0 0 / 0.06) 55%,
            transparent 100%
        );
    }
    @keyframes vertex-skeleton-shine {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
    @media (prefers-reduced-motion: reduce) {
        .vertex-skeleton-shimmer .vertex-skeleton-bg::after {
            animation: none;
        }
        .vertex-skeleton-bg {
            animation: vertex-skeleton-pulse 2s ease-in-out infinite;
        }
        @keyframes vertex-skeleton-pulse {
            0%, 100% { opacity: 0.85; }
            50% { opacity: 1; }
        }
    }
</style>

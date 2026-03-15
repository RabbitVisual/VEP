@props([
    'title',
    'value',
    'icon' => 'chart-simple',
    'growth' => null,
    'href' => null,
])

@php
    $tag = $href ? 'a' : 'div';
    $hrefAttr = $href ? ['href' => $href] : [];
@endphp

<{{ $tag }}
    {{ $attributes->merge(['class' => 'rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/50 p-5 shadow-sm transition-all duration-300 hover:shadow-md dark:bg-slate-800/50']) }}
    @foreach ($hrefAttr as $k => $v) {{ $k }}="{{ $v }}" @endforeach
>
    <div class="flex items-start justify-between gap-4">
        <div class="min-w-0 flex-1">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $title }}</p>
            <p class="mt-1 text-2xl font-semibold text-slate-900 dark:text-slate-100">{{ $value }}</p>
            @if ($growth !== null)
                <p class="mt-1 text-xs font-medium {{ $growth >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                    {{ $growth >= 0 ? '+' : '' }}{{ $growth }}% em relação ao mês anterior
                </p>
            @endif
        </div>
        <div class="flex size-12 shrink-0 items-center justify-center rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400" aria-hidden="true">
            <x-icon :name="$icon" style="duotone" class="size-6" />
        </div>
    </div>
</{{ $tag }}>

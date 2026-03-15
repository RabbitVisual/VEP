@props([
    'message' => 'Nenhum registro encontrado.',
    'actionUrl' => null,
    'actionLabel' => 'Ver mais',
])

<div class="flex flex-col items-center justify-center py-8 text-center">
    <p class="text-sm text-slate-500 dark:text-slate-400">{{ $message }}</p>
    @if ($actionUrl)
        <a href="{{ $actionUrl }}" class="mt-3 inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition-all duration-300 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-slate-900">
            {{ $actionLabel }}
        </a>
    @endif
</div>

@php
    $nav = [
        [
            'label' => 'Dashboard',
            'icon' => 'grid-2',
            'route' => 'painel.dashboard',
            'routeMatch' => 'painel.dashboard',
        ],
        [
            'label' => 'Bíblia',
            'icon' => 'book-bible',
            'routeMatch' => 'painel.bible.',
            'children' => [
                ['label' => 'Leitura', 'icon' => 'book-open', 'route' => 'painel.bible.read', 'routeMatch' => 'painel.bible.read'],
                ['label' => 'Meus Planos', 'icon' => 'list-check', 'route' => 'painel.bible.plans.index', 'routeMatch' => 'painel.bible.plans.index'],
                ['label' => 'Catálogo de Planos', 'icon' => 'books', 'route' => 'painel.bible.plans.catalog', 'routeMatch' => 'painel.bible.plans.catalog'],
                ['label' => 'Favoritos', 'icon' => 'heart', 'route' => 'painel.bible.favorites', 'routeMatch' => 'painel.bible.favorites'],
            ],
        ],
        [
            'label' => 'Verse Explainer',
            'icon' => 'brain-circuit',
            'route' => 'painel.verse-explainer',
            'routeMatch' => 'painel.verse-explainer',
        ],
        [
            'label' => 'Sermões Sociais',
            'icon' => 'podium',
            'route' => 'painel.sermons.index',
            'routeMatch' => 'painel.sermons.',
        ],
        [
            'label' => 'Academia',
            'icon' => 'graduation-cap',
            'route' => 'painel.academy.catalog',
            'routeMatch' => 'painel.academy.',
        ],
        [
            'label' => 'Meus Ministérios',
            'icon' => 'users-viewfinder',
            'route' => 'ministry.index',
            'routeMatch' => 'ministry.',
        ],
    ];
@endphp
{{-- Sidebar: fixa em lg (w-64), drawer em mobile; tema claro/escuro Tailwind v4.1 --}}
<aside x-show="sidebarOpen || isLg" x-cloak
    class="fixed inset-y-0 left-0 z-50 w-64 flex flex-col border-r border-gray-200 bg-white backdrop-blur-xl dark:border-slate-800/80 dark:bg-slate-900/95 lg:static lg:z-auto"
    aria-label="Navegação principal">
    {{-- Logo --}}
    <div class="flex h-16 shrink-0 items-center gap-3 border-b border-gray-200 px-4 dark:border-slate-800/80 lg:px-5">
        <a href="{{ route('painel.dashboard') }}"
            class="flex items-center gap-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-slate-900">
            <div
                class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600 dark:bg-indigo-500/20 dark:text-indigo-400">
                <x-icon name="graduation-cap" style="duotone" class="text-lg" />
            </div>
            <span class="truncate text-sm font-semibold text-gray-900 dark:text-slate-100">Vertex Hub</span>
        </a>
    </div>

    {{-- Links (Vertical Menu com ícones Duotone; categorias com subitens) --}}
    <nav class="flex-1 space-y-1 overflow-y-auto p-3 lg:p-4" aria-label="Menu">
        @foreach ($nav as $item)
            @if (!empty($item['children']))
                @php
                    $groupActive = collect($item['children'])->contains(fn ($c) => request()->routeIs($c['routeMatch'] ?? $c['route']));
                @endphp
                <div class="space-y-0.5">
                    <div @class([
                        'flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-semibold transition-colors',
                        'text-indigo-700 dark:text-indigo-300' => $groupActive,
                        'text-gray-700 dark:text-slate-300' => !$groupActive,
                    ])>
                        <x-icon name="{{ $item['icon'] }}" style="duotone" class="size-5 shrink-0" />
                        <span class="truncate">{{ $item['label'] }}</span>
                    </div>
                    @foreach ($item['children'] as $child)
                        @php
                            $childActive = request()->routeIs(($child['routeMatch'] ?? $child['route']) . '*') || request()->routeIs($child['routeMatch'] ?? $child['route']);
                        @endphp
                        <a href="{{ route($child['route']) }}" @class([
                            'flex items-center gap-3 rounded-lg px-3 py-2 pl-11 text-sm font-medium transition-colors',
                            'border-l-4 border-indigo-500 bg-indigo-50 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300' => $childActive,
                            'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-slate-400 dark:hover:bg-slate-800/60 dark:hover:text-slate-200' => !$childActive,
                        ])
                            @if ($childActive) aria-current="page" @endif>
                            <x-icon name="{{ $child['icon'] }}" style="duotone" class="size-4 shrink-0" />
                            <span class="truncate">{{ $child['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            @else
                @php
                    $active = $item['routeMatch'] && request()->routeIs($item['routeMatch'] . '*');
                @endphp
                <a href="{{ route($item['route']) }}" @class([
                    'flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors',
                    'border-l-4 border-indigo-500 bg-indigo-50 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300' => $active,
                    'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-slate-400 dark:hover:bg-slate-800/60 dark:hover:text-slate-200' => !$active,
                ])
                    @if ($active) aria-current="page" @endif>
                    <x-icon name="{{ $item['icon'] }}" style="duotone" class="size-5 shrink-0" />
                    <span class="truncate">{{ $item['label'] }}</span>
                </a>
            @endif
        @endforeach
    </nav>

    {{-- Perfil (clicável) / Logout --}}
    <div class="border-t border-gray-200 p-3 dark:border-slate-800/80 lg:p-4">
        <a href="{{ route('painel.profile.show') }}"
            class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-700 dark:text-slate-400 dark:hover:bg-slate-800/60 dark:hover:text-slate-200"
            aria-label="Ir para meu perfil">
            @if (auth()->user()->avatar_url)
                <img src="{{ auth()->user()->avatar_url }}" alt=""
                    class="size-9 shrink-0 rounded-full object-cover ring-2 ring-gray-200 dark:ring-slate-700">
            @else
                <div
                    class="flex size-9 shrink-0 items-center justify-center rounded-full bg-gray-200 text-gray-600 dark:bg-slate-700/80 dark:text-slate-300">
                    <x-icon name="user" style="duotone" class="text-sm" />
                </div>
            @endif
            <div class="min-w-0 flex-1">
                <p class="truncate text-sm font-medium text-gray-900 dark:text-slate-200">
                    {{ auth()->user()->name ?? 'Membro' }}</p>
                <p class="truncate text-xs text-gray-500 dark:text-slate-500">Área do membro · Clique para perfil</p>
            </div>
        </a>
        <form method="POST" action="{{ route('logout') }}" class="mt-2">
            @csrf
            <button type="submit"
                class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-gray-600 transition-colors hover:bg-gray-100 hover:text-amber-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-white dark:text-slate-400 dark:hover:bg-slate-800/60 dark:hover:text-amber-400 dark:focus:ring-offset-slate-900">
                <x-icon name="right-from-bracket" style="duotone" class="size-5 shrink-0" />
                <span>Sair</span>
            </button>
        </form>
    </div>
</aside>

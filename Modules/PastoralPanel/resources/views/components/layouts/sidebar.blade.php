@php
    $nav = [
        [
            'label' => 'Dashboard',
            'icon' => 'chart-network',
            'route' => 'pastoral.dashboard',
            'routeMatch' => 'pastoral.dashboard',
        ],
        [
            'label' => 'Gestão do Rebanho',
            'icon' => 'users-medical',
            'route' => 'pastoral.members.index',
            'routeMatch' => 'pastoral.members.',
        ],
        [
            'label' => 'Sermon Studio',
            'icon' => 'books',
            'route' => 'pastor.sermoes.sermons.index',
            'routeMatch' => 'pastor.sermoes.sermons.',
        ],
        [
            'label' => 'Visão de Ministérios',
            'icon' => 'sitemap',
            'route' => 'ministry.index',
            'routeMatch' => 'ministry.',
        ],
        [
            'label' => 'Assistente de Exegese',
            'icon' => 'microscope',
            'route' => 'pastoral.exegesis-assistant',
            'routeMatch' => 'pastoral.exegesis-assistant',
        ],
        [
            'label' => 'Academia',
            'icon' => 'list-tree',
            'route' => 'pastoral.academy.courses.index',
            'routeMatch' => 'pastoral.academy.',
        ],
    ];
@endphp
<aside
    x-show="sidebarOpen || isLg"
    x-cloak
    class="fixed inset-y-0 left-0 z-50 w-64 flex flex-col border-r border-slate-800/80 bg-slate-900/95 backdrop-blur-xl lg:static lg:z-auto transition-all duration-300"
    aria-label="Navegação principal"
>
    <div class="flex h-16 shrink-0 items-center gap-3 border-b border-slate-800/80 px-4 lg:px-5">
        <a href="{{ route('pastoral.dashboard') }}" class="flex items-center gap-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-slate-900">
            <div class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-indigo-500/20 text-indigo-400">
                <x-icon name="user-tie" style="duotone" class="text-lg" />
            </div>
            <span class="truncate text-sm font-semibold text-slate-100">Área Pastoral</span>
        </a>
    </div>

    <nav class="flex-1 space-y-1 overflow-y-auto p-3 lg:p-4" aria-label="Menu">
        @foreach ($nav as $item)
            @php
                $active = $item['routeMatch'] && request()->routeIs($item['routeMatch'] . '*');
            @endphp
            <a href="{{ route($item['route']) }}"
               @class([
                   'flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all duration-300',
                   'border-l-4 border-indigo-500 bg-indigo-500/20 text-indigo-300' => $active,
                   'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200' => !$active,
               ])
               @if ($active) aria-current="page" @endif>
                <x-icon name="{{ $item['icon'] }}" style="duotone" class="size-5 shrink-0" />
                <span class="truncate">{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

    <div class="border-t border-slate-800/80 p-3 lg:p-4">
        <a href="{{ route('pastoral.profile.show') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-400 transition-colors hover:bg-slate-800/60 hover:text-slate-200" aria-label="Ir para meu perfil">
            @if (auth()->user()->avatar_url)
                <img src="{{ auth()->user()->avatar_url }}" alt="" class="size-9 shrink-0 rounded-full object-cover ring-2 ring-slate-700">
            @else
                <div class="flex size-9 shrink-0 items-center justify-center rounded-full bg-slate-700/80 text-slate-300">
                    <x-icon name="user" style="duotone" class="text-sm" />
                </div>
            @endif
            <div class="min-w-0 flex-1">
                <p class="truncate text-sm font-medium text-slate-200">{{ auth()->user()->name ?? 'Pastor' }}</p>
                <p class="truncate text-xs text-slate-500">Área pastoral · Clique para perfil</p>
            </div>
        </a>
        <form method="POST" action="{{ route('logout') }}" class="mt-2">
            @csrf
            <button type="submit"
                    class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-slate-400 transition-colors hover:bg-slate-800/60 hover:text-amber-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-slate-900"
                    aria-label="Sair">
                <x-icon name="right-from-bracket" style="duotone" class="size-5 shrink-0" />
                <span>Sair</span>
            </button>
        </form>
    </div>
</aside>

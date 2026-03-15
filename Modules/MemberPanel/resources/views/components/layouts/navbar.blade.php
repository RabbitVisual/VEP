@props([
    'title' => null,
    'breadcrumb' => [],
])

<header class="sticky top-0 z-30 flex h-16 shrink-0 items-center gap-4 border-b border-gray-200 bg-white/90 px-4 backdrop-blur-xl dark:border-slate-800/80 dark:bg-slate-900/80 md:px-6 lg:px-8">
    {{-- Toggle sidebar (mobile) --}}
    <button type="button"
            @click="sidebarOpen = true"
            class="flex size-10 items-center justify-center rounded-lg text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-white dark:text-slate-400 dark:hover:bg-slate-800/60 dark:hover:text-slate-200 dark:focus:ring-offset-slate-900 lg:hidden"
            aria-label="Abrir menu">
        <x-icon name="bars" style="duotone" class="size-6" />
    </button>

    {{-- Breadcrumb ou título --}}
    <div class="min-w-0 flex-1">
        @if (count($breadcrumb) > 0)
            <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-sm">
                @foreach ($breadcrumb as $i => $item)
                    @if ($i > 0)
                        <x-icon name="chevron-right" style="solid" class="size-3.5 shrink-0 text-gray-400 dark:text-slate-500" />
                    @endif
                    @if (!empty($item['url']))
                        <a href="{{ $item['url'] }}" class="truncate text-gray-500 transition-colors hover:text-gray-900 dark:text-slate-400 dark:hover:text-slate-200">{{ $item['label'] }}</a>
                    @else
                        <span class="truncate font-medium text-gray-900 dark:text-slate-200">{{ $item['label'] }}</span>
                    @endif
                @endforeach
            </nav>
        @else
            <h1 class="truncate text-lg font-semibold text-gray-900 dark:text-slate-100">{{ $title ?? 'Painel' }}</h1>
        @endif
    </div>

    {{-- Perfil: dropdown (Meu perfil + Sair do sistema) + toggle tema dentro --}}
    <div class="relative flex items-center gap-2" x-data="{ open: false, dark: document.documentElement.classList.contains('dark') }" x-init="dark = document.documentElement.classList.contains('dark')" @click.outside="open = false">
        <button type="button"
                @click="open = !open"
                class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-gray-600 transition-colors hover:bg-gray-100 hover:text-gray-900 dark:text-slate-400 dark:hover:bg-slate-800/60 dark:hover:text-slate-200"
                aria-expanded="false"
                aria-haspopup="true"
                :aria-expanded="{{ 'open' }}"
                aria-label="Menu do perfil">
            @if (auth()->user()->avatar_url)
                <img src="{{ auth()->user()->avatar_url }}" alt="" class="size-8 shrink-0 rounded-full object-cover ring-1 ring-gray-200 dark:ring-slate-700">
            @else
                <div class="flex size-8 shrink-0 items-center justify-center rounded-full bg-gray-200 text-gray-500 dark:bg-slate-700 dark:text-slate-400">
                    <x-icon name="user" style="duotone" class="size-4" />
                </div>
            @endif
            <span class="hidden text-sm font-medium sm:inline">{{ auth()->user()->name ?? 'Perfil' }}</span>
            <x-icon name="chevron-down" style="solid" class="size-4 shrink-0 transition-transform" :class="{{ "open ? 'rotate-180' : ''" }}" />
        </button>
        <div x-show="open"
             x-cloak
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="absolute right-0 top-full z-50 mt-2 w-56 origin-top-right rounded-xl border border-gray-200 bg-white py-1 shadow-lg dark:border-slate-700 dark:bg-slate-800"
             role="menu">
            <a href="{{ route('painel.profile.show') }}" role="menuitem" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 dark:text-slate-200 dark:hover:bg-slate-700">
                <x-icon name="user" style="duotone" class="size-4 shrink-0" />
                Meu perfil
            </a>
            <button type="button"
                    @click="dark = !dark; document.documentElement.classList.toggle('dark', dark); localStorage.setItem('painel-dark', dark);"
                    role="menuitem"
                    class="flex w-full items-center gap-2 px-4 py-2.5 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-slate-200 dark:hover:bg-slate-700">
                <span x-show="dark"><x-icon name="sun" style="duotone" class="size-4 shrink-0" /></span>
                <span x-show="!dark" x-cloak><x-icon name="moon" style="duotone" class="size-4 shrink-0" /></span>
                <span x-text="dark ? 'Modo claro' : 'Modo escuro'"></span>
            </button>
            <div class="border-t border-gray-200 dark:border-slate-700">
                <form method="POST" action="{{ route('logout') }}" class="block">
                    @csrf
                    <button type="submit" role="menuitem" class="flex w-full items-center gap-2 px-4 py-2.5 text-left text-sm text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20">
                        <x-icon name="right-from-bracket" style="duotone" class="size-4 shrink-0" />
                        Sair do sistema
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

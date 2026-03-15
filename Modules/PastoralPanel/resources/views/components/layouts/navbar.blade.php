@props([
    'title' => null,
    'breadcrumb' => [],
])

<header class="sticky top-0 z-30 flex h-16 shrink-0 items-center gap-4 border-b border-slate-800/80 bg-slate-900/80 px-4 backdrop-blur-xl md:px-6 lg:px-8" role="banner">
    <button type="button"
            @click="sidebarOpen = true"
            class="flex size-10 items-center justify-center rounded-lg text-slate-400 transition-colors hover:bg-slate-800/60 hover:text-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-slate-900 lg:hidden"
            aria-label="Abrir menu">
        <x-icon name="bars" style="duotone" class="size-6" />
    </button>

    <div class="min-w-0 flex-1">
        @if (count($breadcrumb) > 0)
            <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-sm">
                @foreach ($breadcrumb as $i => $item)
                    @if ($i > 0)
                        <x-icon name="chevron-right" style="solid" class="size-3.5 shrink-0 text-slate-500" />
                    @endif
                    @if (!empty($item['url']))
                        <a href="{{ $item['url'] }}" class="truncate text-slate-400 transition-colors hover:text-slate-200">{{ $item['label'] }}</a>
                    @else
                        <span class="truncate font-medium text-slate-200">{{ $item['label'] }}</span>
                    @endif
                @endforeach
            </nav>
        @else
            <h1 class="truncate text-lg font-semibold text-slate-100">{{ $title ?? 'Área Pastoral' }}</h1>
        @endif
    </div>

    <div class="flex items-center gap-2">
        @if (Route::has('pastor.sermoes.sermons.create'))
            <a href="{{ route('pastor.sermoes.sermons.create') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-all duration-300 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-slate-900"
               aria-label="Novo sermão">
                <x-icon name="plus" style="solid" class="size-4" />
                <span class="hidden sm:inline">Novo Sermão</span>
            </a>
        @endif

        {{-- Perfil: dropdown (Meu perfil + Sair do sistema) + toggle tema dentro --}}
        <div class="relative" x-data="{ open: false, dark: document.documentElement.classList.contains('dark') }" x-init="dark = document.documentElement.classList.contains('dark')" @click.outside="open = false">
            <button type="button"
                    @click="open = !open"
                    class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-slate-400 transition-colors hover:bg-slate-800/60 hover:text-slate-200"
                    :aria-expanded="{{ 'open' }}"
                    aria-haspopup="true"
                    aria-label="Menu do perfil">
                @if (auth()->user()->avatar_url)
                    <img src="{{ auth()->user()->avatar_url }}" alt="" class="size-8 shrink-0 rounded-full object-cover ring-1 ring-slate-700">
                @else
                    <div class="flex size-8 shrink-0 items-center justify-center rounded-full bg-slate-700 text-slate-400">
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
                 class="absolute right-0 top-full z-50 mt-2 w-56 origin-top-right rounded-xl border border-slate-700 bg-slate-800 py-1 shadow-lg"
                 role="menu">
                <a href="{{ route('pastoral.profile.show') }}" role="menuitem" class="flex items-center gap-2 px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700">
                    <x-icon name="user" style="duotone" class="size-4 shrink-0" />
                    Meu perfil
                </a>
                <button type="button"
                        @click="dark = !dark; document.documentElement.classList.toggle('dark', dark); localStorage.setItem('pastoral-dark', dark);"
                        role="menuitem"
                        class="flex w-full items-center gap-2 px-4 py-2.5 text-left text-sm text-slate-200 hover:bg-slate-700">
                    <span x-show="dark"><x-icon name="sun" style="duotone" class="size-4 shrink-0" /></span>
                    <span x-show="!dark" x-cloak><x-icon name="moon" style="duotone" class="size-4 shrink-0" /></span>
                    <span x-text="dark ? 'Modo claro' : 'Modo escuro'"></span>
                </button>
                <div class="border-t border-slate-700">
                    <form method="POST" action="{{ route('logout') }}" class="block">
                        @csrf
                        <button type="submit" role="menuitem" class="flex w-full items-center gap-2 px-4 py-2.5 text-left text-sm text-red-400 hover:bg-red-900/20">
                            <x-icon name="right-from-bracket" style="duotone" class="size-4 shrink-0" />
                            Sair do sistema
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<style>[x-cloak] { display: none !important; }</style>

<!-- Sidebar: duas colunas (ícones w-16 + texto w-64), design CBAV -->
<div id="sidebar-container"
    class="flex h-screen overflow-hidden fixed left-0 top-0 z-30 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
    <!-- Coluna 1: Ícones -->
    <div class="flex h-screen w-16 flex-col justify-between border-e border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800">
        <div>
            <div class="inline-flex size-16 items-center justify-center border-b border-gray-100 dark:border-gray-700">
                <a href="{{ route('painel.dashboard') }}" class="group">
                    <img src="{{ asset('storage/image/logo_icon.png') }}" alt="Logo"
                        class="size-10 object-contain transition-transform group-hover:scale-110 rounded-lg bg-gray-100 dark:bg-gray-700 p-1"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='grid';">
                    <span class="hidden size-10 place-content-center rounded-lg bg-purple-100 dark:bg-purple-900 text-xs font-bold text-purple-600 dark:text-purple-400">
                        {{ strtoupper(substr(config('app.name', 'V'), 0, 1)) }}
                    </span>
                </a>
            </div>
            <div class="border-t border-gray-100 dark:border-gray-700">
                <div class="px-2 py-4 space-y-1">
                    <a href="{{ route('painel.dashboard') }}"
                        class="group relative flex justify-center rounded-sm px-2 py-1.5 {{ request()->routeIs('painel.dashboard') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                        <x-icon name="gauge-high" class="size-5" />
                        <span class="invisible absolute start-full top-1/2 ms-4 -translate-y-1/2 rounded-sm bg-gray-900 dark:bg-gray-700 px-2 py-1.5 text-xs font-medium text-white whitespace-nowrap group-hover:visible z-50">Dashboard</span>
                    </a>
                </div>
                <ul class="space-y-1 border-t border-gray-100 dark:border-gray-700 pt-4 px-2">
                    <li>
                        <a href="{{ route('painel.bible.read') }}"
                            class="group relative flex justify-center rounded-sm px-2 py-1.5 {{ request()->routeIs('painel.bible.*') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                            <x-icon name="book-bible" class="size-5" />
                            <span class="invisible absolute start-full top-1/2 ms-4 -translate-y-1/2 rounded-sm bg-gray-900 dark:bg-gray-700 px-2 py-1.5 text-xs font-medium text-white whitespace-nowrap group-hover:visible z-50">Bíblia</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('painel.verse-explainer') }}"
                            class="group relative flex justify-center rounded-sm px-2 py-1.5 {{ request()->routeIs('painel.verse-explainer*') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                            <x-icon name="brain-circuit" class="size-5" />
                            <span class="invisible absolute start-full top-1/2 ms-4 -translate-y-1/2 rounded-sm bg-gray-900 dark:bg-gray-700 px-2 py-1.5 text-xs font-medium text-white whitespace-nowrap group-hover:visible z-50">Verse Explainer</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('painel.sermons.index') }}"
                            class="group relative flex justify-center rounded-sm px-2 py-1.5 {{ request()->routeIs('painel.sermons.*') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                            <x-icon name="podium" class="size-5" />
                            <span class="invisible absolute start-full top-1/2 ms-4 -translate-y-1/2 rounded-sm bg-gray-900 dark:bg-gray-700 px-2 py-1.5 text-xs font-medium text-white whitespace-nowrap group-hover:visible z-50">Sermões</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('painel.academy.catalog') }}"
                            class="group relative flex justify-center rounded-sm px-2 py-1.5 {{ request()->routeIs('painel.academy.*') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                            <x-icon name="graduation-cap" class="size-5" />
                            <span class="invisible absolute start-full top-1/2 ms-4 -translate-y-1/2 rounded-sm bg-gray-900 dark:bg-gray-700 px-2 py-1.5 text-xs font-medium text-white whitespace-nowrap group-hover:visible z-50">Academia</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('painel.community.feed.index') }}"
                            class="group relative flex justify-center rounded-sm px-2 py-1.5 {{ request()->routeIs('painel.community.*') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                            <i class="fa-duotone fa-house-user w-5 h-5 inline-block"></i>
                            <span class="invisible absolute start-full top-1/2 ms-4 -translate-y-1/2 rounded-sm bg-gray-900 dark:bg-gray-700 px-2 py-1.5 text-xs font-medium text-white whitespace-nowrap group-hover:visible z-50">Comunidade</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('ministry.index') }}"
                            class="group relative flex justify-center rounded-sm px-2 py-1.5 {{ request()->routeIs('painel.ministries.*') || request()->routeIs('ministry.*') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                            <x-icon name="users-viewfinder" class="size-5" />
                            <span class="invisible absolute start-full top-1/2 ms-4 -translate-y-1/2 rounded-sm bg-gray-900 dark:bg-gray-700 px-2 py-1.5 text-xs font-medium text-white whitespace-nowrap group-hover:visible z-50">Ministérios</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="sticky inset-x-0 bottom-0 border-t border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 p-2">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="group relative flex w-full justify-center rounded-lg px-2 py-1.5 text-sm text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300">
                    <x-icon name="right-from-bracket" class="size-5" />
                    <span class="invisible absolute start-full top-1/2 ms-4 -translate-y-1/2 rounded-sm bg-gray-900 dark:bg-gray-700 px-2 py-1.5 text-xs font-medium text-white whitespace-nowrap group-hover:visible z-50">Sair</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Coluna 2: Texto + usuário -->
    <div class="flex h-screen w-64 flex-col justify-between border-e border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800">
        <div class="flex-1 overflow-y-auto px-4 py-6">
            <div class="mb-6 pb-6 border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-center space-x-3">
                    <img class="h-12 w-12 rounded-full object-cover ring-2 ring-purple-500 dark:ring-purple-400"
                        src="{{ Auth::user()->avatar_url ?? '' }}" alt="{{ Auth::user()->name }}"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="h-12 w-12 rounded-full flex items-center justify-center text-white font-bold text-lg ring-2 ring-purple-500 dark:ring-purple-400 bg-gradient-to-br from-purple-400 to-purple-600"
                        style="display: none;">
                        {{ strtoupper(substr(Auth::user()->first_name ?? Auth::user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ Auth::user()->name ?? 'Membro' }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>
            <ul class="mt-4 space-y-1">
                <li>
                    <a href="{{ route('painel.dashboard') }}"
                        class="block rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('painel.dashboard') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                        <span class="flex items-center gap-2"><x-icon name="gauge-high" class="size-5" /> Dashboard</span>
                    </a>
                </li>
                <li x-data="{ open: {{ request()->routeIs('painel.bible.*') ? 'true' : 'false' }} }">
                    <div class="flex flex-col">
                        <button @click="open = !open" type="button"
                            class="flex w-full cursor-pointer items-center justify-between rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('painel.bible.*') ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                            <span class="flex items-center gap-2"><x-icon name="book-bible" class="size-5" /> Bíblia</span>
                            <x-icon name="chevron-down" class="size-5 shrink-0 transition duration-300" :class="'open ? \'-rotate-180\' : \'\''" />
                        </button>
                        <ul class="mt-2 space-y-1 px-4 border-l-2 border-gray-100 dark:border-gray-700 ml-6" x-show="open" x-cloak x-transition>
                            <li><a href="{{ route('painel.bible.read') }}" class="block rounded-lg px-4 py-2 text-xs font-medium {{ request()->routeIs('painel.bible.read') ? 'text-purple-600 dark:text-purple-400 font-bold' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">Leitura</a></li>
                            <li><a href="{{ route('painel.bible.plans.index') }}" class="block rounded-lg px-4 py-2 text-xs font-medium {{ request()->routeIs('painel.bible.plans.index') ? 'text-purple-600 dark:text-purple-400 font-bold' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">Meus Planos</a></li>
                            <li><a href="{{ route('painel.bible.plans.catalog') }}" class="block rounded-lg px-4 py-2 text-xs font-medium {{ request()->routeIs('painel.bible.plans.catalog') ? 'text-purple-600 dark:text-purple-400 font-bold' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">Catálogo de Planos</a></li>
                            <li><a href="{{ route('painel.bible.favorites') }}" class="block rounded-lg px-4 py-2 text-xs font-medium {{ request()->routeIs('painel.bible.favorites') ? 'text-purple-600 dark:text-purple-400 font-bold' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">Favoritos</a></li>
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="{{ route('painel.verse-explainer') }}"
                        class="block rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('painel.verse-explainer*') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                        <span class="flex items-center gap-2"><x-icon name="brain-circuit" class="size-5" /> Verse Explainer</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('painel.sermons.index') }}"
                        class="block rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('painel.sermons.*') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                        <span class="flex items-center gap-2"><x-icon name="podium" class="size-5" /> Sermões</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('painel.academy.catalog') }}"
                        class="block rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('painel.academy.*') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                        <span class="flex items-center gap-2"><x-icon name="graduation-cap" class="size-5" /> Academia</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('ministry.index') }}"
                        class="block rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('painel.ministries.*') || request()->routeIs('ministry.*') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                        <span class="flex items-center gap-2"><x-icon name="users-viewfinder" class="size-5" /> Meus Ministérios</span>
                    </a>
                </li>
                <li x-data="{ open: {{ request()->routeIs('painel.community.*') ? 'true' : 'false' }} }">
                    <div class="flex flex-col">
                        <button @click="open = !open" type="button"
                            class="flex w-full cursor-pointer items-center justify-between rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('painel.community.*') ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                            <span class="flex items-center gap-2"><i class="fa-duotone fa-house-user w-5 h-5 inline-block"></i> Comunidade</span>
                            <x-icon name="chevron-down" class="size-5 shrink-0 transition duration-300" :class="'open ? \'-rotate-180\' : \'\''" />
                        </button>
                        <ul class="mt-2 space-y-1 px-4 border-l-2 border-gray-100 dark:border-gray-700 ml-6" x-show="open" x-cloak x-transition>
                            <li><a href="{{ route('painel.community.feed.index') }}" class="block rounded-lg px-4 py-2 text-xs font-medium {{ request()->routeIs('painel.community.feed.*') ? 'text-purple-600 dark:text-purple-400 font-bold' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">Feed</a></li>
                            <li><a href="{{ route('painel.community.prayers.index') }}" class="block rounded-lg px-4 py-2 text-xs font-medium {{ request()->routeIs('painel.community.prayers.*') ? 'text-purple-600 dark:text-purple-400 font-bold' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">Mural de Intercessão</a></li>
                        </ul>
                    </div>
                </li>
                <li x-data="{ open: {{ request()->routeIs('painel.profile.*') ? 'true' : 'false' }} }">
                    <div class="flex flex-col">
                        <button @click="open = !open" type="button"
                            class="flex w-full cursor-pointer items-center justify-between rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('painel.profile.*') ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                            <span class="flex items-center gap-2"><x-icon name="user" class="size-5" /> Conta</span>
                            <x-icon name="chevron-down" class="size-5 shrink-0 transition duration-300" :class="'open ? \'-rotate-180\' : \'\''" />
                        </button>
                        <ul class="mt-2 space-y-1 px-4" x-show="open" x-cloak x-transition>
                            <li>
                                <a href="{{ route('painel.profile.show') }}"
                                    class="block rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('painel.profile.show') && !request()->routeIs('painel.community.*') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">Configurações</a>
                            </li>
                            <li>
                                <a href="{{ route('painel.community.profile.show', Auth::user()) }}"
                                    class="block rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('painel.community.profile.show') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">Meu Perfil Público</a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
        <div class="sticky inset-x-0 bottom-0 border-t border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 p-4">
            <div class="text-xs text-gray-500 dark:text-gray-400 text-center">
                <p class="font-semibold">{{ config('app.name', 'Vertex') }}</p>
                <p>© {{ date('Y') }}</p>
            </div>
        </div>
    </div>
</div>

<div id="sidebar-overlay" class="hidden fixed inset-0 bg-black/50 z-20 lg:hidden" aria-hidden="true"></div>

@props(['pageTitle' => null])

@php
    $user = Auth::user();
    $userAvatarUrl = $user->avatar_url ?? '';
    $userInitial = strtoupper(substr($user->first_name ?? $user->name ?? 'U', 0, 1));
@endphp
<nav class="sticky top-0 z-40 backdrop-blur-xl bg-white/80 dark:bg-gray-900/90 border-b border-gray-200/50 dark:border-gray-700/50 px-6 py-4 shadow-sm transition-all duration-300">
    <div class="flex items-center justify-between">
        <button id="sidebar-toggle" type="button"
            class="lg:hidden text-gray-500 dark:text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800"
            aria-label="Abrir menu">
            <x-icon name="bars" class="h-6 w-6" />
        </button>

        <div class="flex-1 lg:ml-0">
            <h1 class="text-xl font-black tracking-tight text-gray-900 dark:text-white">
                @yield('page-title', $pageTitle ?? 'Dashboard')
            </h1>
        </div>

        <div class="flex items-center space-x-4">
            <button id="theme-toggle" type="button"
                class="focus:outline-none rounded-full text-sm p-2.5 transition-all duration-200 flex items-center justify-center hover:bg-gray-100 dark:hover:bg-gray-800 active:scale-95"
                aria-label="Alternar tema">
                <span class="hidden dark:block">
                    <x-icon name="sun" class="w-6 h-6 text-yellow-400" />
                </span>
                <span class="block dark:hidden">
                    <x-icon name="moon" class="w-6 h-6 text-gray-500" />
                </span>
            </button>

            <div class="relative" x-data="{ open: false }" id="user-menu-dropdown">
                <button @click="open = !open" type="button" id="user-menu-toggle"
                    class="flex items-center space-x-2 text-sm focus:outline-none group transition-transform hover:scale-105 active:scale-95">
                    <div class="relative">
                        <img src="{{ $userAvatarUrl }}"
                             alt="{{ $user->name }}"
                             class="w-9 h-9 rounded-full object-cover border-2 border-white dark:border-gray-800 shadow-md ring-2 ring-purple-500/20 dark:ring-purple-500/40"
                             onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden'); this.nextElementSibling.classList.add('flex');">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xs shadow-md border-2 border-white dark:border-gray-800 hidden">
                            {{ $userInitial }}
                        </div>
                    </div>
                    <div class="hidden md:flex flex-col items-start px-1">
                        <span class="text-xs font-bold text-gray-700 dark:text-gray-200 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">{{ Str::limit($user->first_name ?? $user->name ?? 'Membro', 15) }}</span>
                    </div>
                    <x-icon name="chevron-down" class="w-4 h-4 text-gray-400 dark:text-gray-500 group-hover:text-purple-500 transition-colors" />
                </button>

                <div x-show="open" @click.away="open = false" x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    id="user-menu"
                    class="absolute right-0 mt-4 w-56 bg-white/95 dark:bg-gray-800/95 backdrop-blur-xl rounded-2xl shadow-xl shadow-purple-500/10 py-2 z-50 border border-gray-100 dark:border-gray-700">

                    <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 mb-1 lg:hidden">
                        <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $user->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $user->email }}</p>
                    </div>

                    <a href="{{ route('painel.profile.show') }}"
                        class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-purple-600 dark:hover:text-purple-400 transition-colors mx-2 rounded-xl">
                        <x-icon name="user" class="w-4 h-4" />
                        Meu Perfil
                    </a>

                    <div class="border-t border-gray-100 dark:border-gray-700 my-1 mx-2"></div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="flex w-full items-center gap-3 px-4 py-2.5 text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/10 transition-colors mx-2 rounded-xl text-left">
                            <x-icon name="right-from-bracket" class="w-4 h-4" />
                            Sair
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

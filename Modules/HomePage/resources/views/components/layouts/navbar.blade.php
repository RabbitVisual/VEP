<nav x-data="{ menuOpen: false }"
     x-effect="document.body.classList.toggle('overflow-hidden', menuOpen)"
     role="navigation"
     class="sticky top-0 z-50 w-full border-b border-border/40 bg-background/80 backdrop-blur-xl transition-all duration-300">
    <div class="mx-auto flex h-14 sm:h-16 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
        <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-2 font-semibold text-foreground transition-opacity hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 rounded-lg" aria-label="Vertex Escola de Pastores – Início">
            <img src="{{ asset('storage/logo/vertex-escola-de-pastores.svg') }}" alt="" class="h-8 max-w-full dark:hidden" width="160" height="36" />
            <img src="{{ asset('storage/logo/vertex-escola-de-pastores-dark.svg') }}" alt="" class="hidden h-8 max-w-full dark:block" width="160" height="36" />
        </a>

        {{-- Links desktop (hidden on mobile) --}}
        <div class="hidden md:flex items-center gap-1">
            <a href="{{ route('faq') }}" class="rounded-lg px-3 py-2 text-sm font-medium text-muted-foreground transition-colors hover:bg-muted hover:text-foreground focus:ring-2 focus:ring-primary focus:ring-offset-2">FAQ</a>
            <a href="{{ route('about') }}" class="rounded-lg px-3 py-2 text-sm font-medium text-muted-foreground transition-colors hover:bg-muted hover:text-foreground focus:ring-2 focus:ring-primary focus:ring-offset-2">Sobre</a>
            <a href="{{ route('pricing') }}" class="rounded-lg px-3 py-2 text-sm font-medium text-muted-foreground transition-colors hover:bg-muted hover:text-foreground focus:ring-2 focus:ring-primary focus:ring-offset-2">Preços</a>
            @if(Route::has('bible.public.index'))
                <a href="{{ route('bible.public.index') }}" class="rounded-lg px-3 py-2 text-sm font-medium text-muted-foreground transition-colors hover:bg-muted hover:text-foreground flex items-center gap-1.5 focus:ring-2 focus:ring-primary focus:ring-offset-2">
                    <x-icon name="book-bible" class="h-4 w-4 shrink-0" />
                    Bíblia
                </a>
            @endif
        </div>

        {{-- Theme toggle + Auth (always visible) --}}
        <div class="flex items-center gap-2">
            <button type="button" data-theme-toggle class="inline-flex min-w-[48px] min-h-[48px] items-center justify-center rounded-lg text-muted-foreground transition-colors hover:bg-muted hover:text-foreground focus:ring-2 focus:ring-primary focus:ring-offset-2" aria-label="Alternar tema claro/escuro">
                <span class="theme-icon-light inline-flex items-center justify-center h-5 w-5" aria-hidden="true"><x-icon name="sun" class="h-5 w-5" /></span>
                <span class="theme-icon-dark inline-flex items-center justify-center h-5 w-5" aria-hidden="true"><x-icon name="moon" class="h-5 w-5" /></span>
            </button>
            @auth
                <a href="{{ config('fortify.home', '/dashboard') }}" class="hidden sm:inline-flex items-center min-h-[48px] rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-primary-foreground shadow-sm transition-all duration-300 hover:opacity-90 focus:ring-2 focus:ring-primary focus:ring-offset-2">
                    Área restrita
                </a>
            @else
                <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center rounded-lg px-3 py-2 text-sm font-medium text-muted-foreground transition-colors hover:bg-muted hover:text-foreground min-h-[48px] focus:ring-2 focus:ring-primary focus:ring-offset-2">Entrar</a>
                <a href="{{ route('register') }}" class="hidden sm:inline-flex items-center min-h-[48px] rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-primary-foreground shadow-sm transition-all duration-300 hover:opacity-90 focus:ring-2 focus:ring-primary focus:ring-offset-2">
                    Começar agora
                </a>
            @endauth
            {{-- Hamburger (mobile only) --}}
            <button type="button"
                    @click="menuOpen = !menuOpen"
                    :aria-expanded="menuOpen"
                    :aria-label="menuOpen ? 'Fechar menu de navegação' : 'Abrir menu de navegação'"
                    class="md:hidden inline-flex min-w-[48px] min-h-[48px] items-center justify-center rounded-lg text-muted-foreground transition-colors hover:bg-muted hover:text-foreground focus:ring-2 focus:ring-primary focus:ring-offset-2">
                <x-icon name="bars" class="h-6 w-6" />
            </button>
        </div>
    </div>

    {{-- Mobile menu: teleport no body para cobrir toda a tela e evitar bugs de stacking --}}
    <template x-teleport="body">
        <div x-show="menuOpen"
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="menuOpen = false"
             @keydown.escape.window="menuOpen = false"
             class="md:hidden fixed inset-0 z-[100] bg-black/50">
            <div @click.stop
                 class="flex h-full w-full max-w-sm flex-col border-r border-border/40 bg-background shadow-xl">
            {{-- Cabeçalho do menu: título + botão Fechar --}}
            <div class="flex min-h-[56px] shrink-0 items-center justify-between border-b border-border/40 px-4">
                <span class="text-sm font-semibold text-foreground">Menu</span>
                <button type="button"
                        @click="menuOpen = false"
                        class="inline-flex min-w-[48px] min-h-[48px] -mr-2 items-center justify-center rounded-lg text-muted-foreground transition-colors hover:bg-muted hover:text-foreground"
                        aria-label="Fechar menu">
                    <x-icon name="xmark" class="h-6 w-6" />
                </button>
            </div>
            <div class="flex-1 overflow-y-auto py-4 px-4 space-y-1">
            <a href="{{ route('faq') }}" class="flex min-h-[48px] items-center rounded-lg px-4 py-3 text-base font-medium text-foreground hover:bg-muted" @click="menuOpen = false">FAQ</a>
            <a href="{{ route('about') }}" class="flex min-h-[48px] items-center rounded-lg px-4 py-3 text-base font-medium text-foreground hover:bg-muted" @click="menuOpen = false">Sobre</a>
            <a href="{{ route('pricing') }}" class="flex min-h-[48px] items-center rounded-lg px-4 py-3 text-base font-medium text-foreground hover:bg-muted" @click="menuOpen = false">Preços</a>
            @if(Route::has('bible.public.index'))
                <a href="{{ route('bible.public.index') }}" class="flex min-h-[48px] items-center gap-2 rounded-lg px-4 py-3 text-base font-medium text-foreground hover:bg-muted" @click="menuOpen = false">
                    <x-icon name="book-bible" class="h-5 w-5 shrink-0" />
                    Bíblia
                </a>
            @endif
            <div class="border-t border-border/40 pt-4 mt-4">
                @auth
                    <a href="{{ config('fortify.home', '/dashboard') }}" class="flex min-h-[48px] items-center rounded-lg bg-primary px-4 py-3 text-base font-semibold text-primary-foreground" @click="menuOpen = false">Área restrita</a>
                @else
                    <a href="{{ route('login') }}" class="flex min-h-[48px] items-center rounded-lg px-4 py-3 text-base font-medium text-foreground hover:bg-muted" @click="menuOpen = false">Entrar</a>
                    <a href="{{ route('register') }}" class="flex min-h-[48px] items-center rounded-lg bg-primary px-4 py-3 text-base font-semibold text-primary-foreground mt-2" @click="menuOpen = false">Começar agora</a>
                @endauth
            </div>
            </div>
        </div>
        </div>
    </template>
</nav>

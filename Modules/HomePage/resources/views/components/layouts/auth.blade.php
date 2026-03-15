<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Acesso') – VEP</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml" sizes="any">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <script>
        (function(){
            var s=localStorage.getItem('theme'),d=window.matchMedia('(prefers-color-scheme: dark)').matches;
            document.documentElement.classList.toggle('dark',s==='dark'||(s!=='light'&&d));
            document.documentElement.setAttribute('data-theme',document.documentElement.classList.contains('dark')?'dark':'light');
        })();
    </script>
    <style>[x-cloak]{display:none!important}</style>
</head>
<body class="min-h-screen overflow-x-hidden bg-background text-foreground antialiased">
    <div class="flex min-h-screen flex-col lg:flex-row">
        {{-- Painel esquerdo: mobile primeiro (imagem em cima), desktop 50% --}}
        <div class="relative flex w-full flex-1 flex-col justify-between overflow-hidden lg:w-1/2">
            {{-- Imagem de fundo (estudo/biblioteca) --}}
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" style="background-image: url('{{ asset('storage/auth/split-unsplash.jpg') }}');"></div>
            {{-- Overlay: sombra para texto legível --}}
            <div class="absolute inset-0 bg-gradient-to-br from-slate-900/85 via-slate-900/75 to-slate-900/65"></div>
            <div class="absolute inset-0 bg-primary/20 mix-blend-multiply dark:bg-primary/30" aria-hidden="true"></div>

            <div class="relative z-10 flex flex-col justify-between p-6 min-h-[200px] sm:min-h-[280px] lg:min-h-0 sm:p-8 lg:p-12 xl:p-16">
                <div class="flex items-center justify-between gap-4">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 transition-opacity hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-white/50 focus:ring-offset-2 focus:ring-offset-transparent rounded-lg" aria-label="Voltar ao início">
                        <img src="{{ asset('storage/logo/vertex-escola-de-pastores-dark.svg') }}" alt="VEP" class="h-9 max-w-full sm:h-10" width="180" height="45">
                    </a>
                    <button type="button" data-theme-toggle class="inline-flex min-w-[48px] min-h-[48px] items-center justify-center rounded-lg text-white/80 transition-colors hover:bg-white/10 hover:text-white focus:ring-2 focus:ring-white/50 focus:ring-offset-2 focus:ring-offset-transparent" aria-label="Alternar tema claro/escuro">
                        <span class="theme-icon-light inline-flex items-center justify-center h-5 w-5" aria-hidden="true"><x-icon name="sun" class="h-5 w-5" /></span>
                        <span class="theme-icon-dark inline-flex items-center justify-center h-5 w-5" aria-hidden="true"><x-icon name="moon" class="h-5 w-5" /></span>
                    </button>
                </div>
                <div class="mt-8 lg:mt-0">
                    <div class="text-white [&_h2]:text-white [&_p]:text-white/90">
                        @yield('authBranding')
                    </div>
                </div>
                <div class="mt-6 hidden text-sm text-white/70 lg:block">
                    Vertex Escola de Pastores · Formação de pastores e líderes
                </div>
            </div>
        </div>

        {{-- Painel direito: formulário (responsivo) --}}
        <div class="flex w-full flex-1 flex-col justify-center px-4 py-8 sm:py-12 sm:px-6 md:px-8 lg:w-1/2 lg:px-12 xl:px-16">
            <div class="mx-auto w-full max-w-md">
                @yield('content')
            </div>
        </div>
    </div>
    @stack('scripts')
</body>
</html>

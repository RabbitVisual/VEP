@props([
    'title' => 'Painel',
    'description' => '',
    'keywords' => '',
    'author' => '',
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ dark: true }"
      x-init="
        dark = localStorage.getItem('painel-dark') !== 'false';
        $watch('dark', v => { document.documentElement.classList.toggle('dark', v); localStorage.setItem('painel-dark', v); });
        document.documentElement.classList.toggle('dark', dark);
      "
      :class="{ 'dark': dark }"
      class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>@yield('title', $title) – {{ config('app.name', 'Vertex') }}</title>

    @if ($description)
        <meta name="description" content="{{ $description }}">
    @endif
    @if ($keywords)
        <meta name="keywords" content="{{ $keywords }}">
    @endif
    @if ($author)
        <meta name="author" content="{{ $author }}">
    @endif

    {{-- Fonts: system stack (sem CDN) – app.css já define --font-sans --}}
    {{-- Tailwind v4 + Font Awesome Pro local (duotone, solid, etc.) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-gray-50 text-gray-900 dark:bg-slate-950 dark:text-slate-100 antialiased"
      x-data="{ sidebarOpen: false, isLg: window.innerWidth >= 1024 }"
      x-init="
        const update = () => { isLg = window.innerWidth >= 1024; if (isLg) sidebarOpen = false; };
        window.addEventListener('resize', update);
      ">
    <x-loading-overlay />

    <div class="flex min-h-screen">
        {{-- Sidebar: fixa em lg+, drawer em mobile --}}
        <x-memberpanel::layouts.sidebar />

        {{-- Overlay mobile quando sidebar aberta --}}
        <div x-show="sidebarOpen"
             x-cloak
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false"
             class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden"
             aria-hidden="true"></div>

        {{-- Área principal --}}
        <div class="flex flex-1 flex-col min-w-0 lg:pl-0">
            <x-memberpanel::layouts.navbar />

            <main class="flex-1 p-4 md:p-6 lg:p-8 bg-gray-50 dark:bg-slate-950">
                <div class="mx-auto max-w-7xl">
                    @hasSection('content')
                        @yield('content')
                    @else
                        {{ $slot ?? '' }}
                    @endif
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $title ?? config('app.name')) – Vertex Escola de Pastores</title>
    <meta name="description" content="{{ $description ?? 'Plataforma EAD para formação de pastores e líderes.' }}">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml" sizes="any">
    <link rel="apple-touch-icon" href="{{ asset('storage/logo/vertex-escola-de-pastores-icon.svg') }}">
    {{-- Tailwind 4.1 + Font Awesome Pro (100% local, no CDN) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <script>
        (function() {
            var stored = localStorage.getItem('theme');
            var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            var dark = stored === 'dark' || (stored !== 'light' && prefersDark);
            document.documentElement.classList.toggle('dark', dark);
            document.documentElement.setAttribute('data-theme', dark ? 'dark' : 'light');
        })();
    </script>
</head>
<body class="min-h-screen overflow-x-hidden bg-background text-foreground antialiased transition-colors duration-300">
    <x-loading-overlay />

    @include('homepage::components.layouts.navbar')

    <main class="flex-1">
        @hasSection('content')
            @yield('content')
        @else
            {{ $slot ?? '' }}
        @endif
    </main>

    @include('homepage::components.layouts.footer')

    @stack('scripts')
</body>
</html>

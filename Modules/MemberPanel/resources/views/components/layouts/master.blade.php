@props([
    'title' => 'Painel',
    'description' => '',
    'keywords' => '',
    'author' => '',
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>@yield('title', $title) | {{ config('app.name', 'Vertex') }}</title>

    @if ($description)
        <meta name="description" content="{{ $description }}">
    @endif
    @if ($keywords)
        <meta name="keywords" content="{{ $keywords }}">
    @endif
    @if ($author)
        <meta name="author" content="{{ $author }}">
    @endif

    <!-- Favicon (ícone 100% local) -->
    <link rel="icon" type="image/png" href="{{ asset('storage/image/logo_icon.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('storage/image/logo_icon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('storage/image/logo_icon.png') }}">

    @stack('head_scripts')

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @if (file_exists(public_path('vendor/fontawesome-pro/css/all.css')))
    <link href="{{ asset('vendor/fontawesome-pro/css/all.css') }}" rel="stylesheet">
    @endif

    @stack('styles')
</head>
<body class="antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    <x-loading-overlay />
    <div class="flex h-screen overflow-hidden">
        <x-memberpanel::layouts.sidebar />

        <div class="flex-1 flex flex-col overflow-hidden lg:ml-80 transition-all duration-300" id="main-content">
            <x-memberpanel::layouts.navbar :pageTitle="$title ?? null" />

            <main class="flex-1 overflow-y-auto bg-gray-50 dark:bg-gray-900 p-6">
                @if (session('success'))
                    <div role="alert" class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 dark:border-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-200">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div role="alert" class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-800 dark:border-red-800 dark:bg-red-900/20 dark:text-red-200">{{ session('error') }}</div>
                @endif
                @if (session('warning'))
                    <div role="alert" class="mb-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-medium text-amber-800 dark:border-amber-800 dark:bg-amber-900/20 dark:text-amber-200">{{ session('warning') }}</div>
                @endif
                @if (session('info'))
                    <div role="alert" class="mb-4 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm font-medium text-blue-800 dark:border-blue-800 dark:bg-blue-900/20 dark:text-blue-200">{{ session('info') }}</div>
                @endif

                @hasSection('content')
                    @yield('content')
                @else
                    {{ $slot ?? '' }}
                @endif
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebarContainer = document.getElementById('sidebar-container');
            const sidebarOverlay = document.getElementById('sidebar-overlay');

            if (sidebarToggle && sidebarContainer) {
                sidebarToggle.addEventListener('click', function() {
                    sidebarContainer.classList.toggle('-translate-x-full');
                    if (sidebarOverlay) {
                        sidebarOverlay.classList.toggle('hidden');
                    }
                });
            }

            if (sidebarOverlay && sidebarContainer) {
                sidebarOverlay.addEventListener('click', function() {
                    sidebarContainer.classList.add('-translate-x-full');
                    sidebarOverlay.classList.add('hidden');
                });
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const userMenuToggle = document.getElementById('user-menu-toggle');
            const userMenu = document.getElementById('user-menu');

            if (userMenuToggle && userMenu) {
                setTimeout(function() {
                    if (typeof window.Alpine === 'undefined' || !window.Alpine) {
                        userMenuToggle.addEventListener('click', function(e) {
                            e.stopPropagation();
                            const isHidden = userMenu.style.display === 'none' || !userMenu.style.display;
                            userMenu.style.display = isHidden ? 'block' : 'none';
                        });
                        document.addEventListener('click', function(e) {
                            if (userMenu && userMenuToggle &&
                                !userMenuToggle.contains(e.target) &&
                                !userMenu.contains(e.target)) {
                                userMenu.style.display = 'none';
                            }
                        });
                    }
                }, 100);
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('theme-toggle');
            if (themeToggle) {
                themeToggle.addEventListener('click', function() {
                    const isDark = document.documentElement.classList.contains('dark');
                    document.documentElement.classList.toggle('dark', !isDark);
                    localStorage.setItem('theme', isDark ? 'light' : 'dark');
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>

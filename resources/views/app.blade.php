<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VertexShop | Preset</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-vertex-dark text-white min-h-screen flex items-center justify-center">

    <div class="text-center space-y-6 p-8 rounded-2xl bg-vertex-glass border border-white/10 backdrop-blur-xl max-w-lg w-full">
        <div class="flex justify-center">
            <div class="w-20 h-20 rounded-full bg-vertex-blue flex items-center justify-center shadow-[0_0_30px_rgba(96,165,250,0.5)]">
                <i class="fa-duotone fa-solid fa-rocket-launch text-4xl text-white"></i>
            </div>
        </div>

        <div>
            <h1 class="text-3xl font-bold tracking-tight">VertexShop Preset</h1>
            <p class="text-white/60 mt-2">Laravel 12 + Tailwind 4.1 + FontAwesome 7.1 Pro</p>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="p-4 rounded-xl bg-white/5 border border-white/5 text-left">
                <i class="fa-solid fa-box-open text-vertex-blue mb-2"></i>
                <h3 class="font-semibold text-sm">Modular</h3>
                <p class="text-xs text-white/40">Estrutura pronta para Modules/</p>
            </div>
            <div class="p-4 rounded-xl bg-white/5 border border-white/5 text-left">
                <i class="fa-solid fa-code text-vertex-blue mb-2"></i>
                <h3 class="font-semibold text-sm">Clean Blade</h3>
                <p class="text-xs text-white/40">Sem React/Inertia</p>
            </div>
        </div>

        <p class="text-[10px] uppercase tracking-[0.2em] text-white/30 pt-4">Pronto para iniciar sua loja</p>
    </div>

</body>
</html>

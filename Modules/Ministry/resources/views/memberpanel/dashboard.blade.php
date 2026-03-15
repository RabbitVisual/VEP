@extends('memberpanel::components.layouts.master')

@section('title', 'Dashboard – ' . $ministry->name)

@section('content')
    @php
        $colorBg = [
            'amber-500' => 'bg-amber-500/20',
            'blue-500' => 'bg-blue-500/20',
            'emerald-500' => 'bg-emerald-500/20',
            'violet-500' => 'bg-violet-500/20',
            'rose-500' => 'bg-rose-500/20',
            'sky-500' => 'bg-sky-500/20',
            'indigo-500' => 'bg-indigo-500/20',
        ];
        $colorText = [
            'amber-500' => 'text-amber-600 dark:text-amber-400',
            'blue-500' => 'text-blue-600 dark:text-blue-400',
            'emerald-500' => 'text-emerald-600 dark:text-emerald-400',
            'violet-500' => 'text-violet-600 dark:text-violet-400',
            'rose-500' => 'text-rose-600 dark:text-rose-400',
            'sky-500' => 'text-sky-600 dark:text-sky-400',
            'indigo-500' => 'text-indigo-600 dark:text-indigo-400',
        ];
    @endphp
    <div class="p-6 space-y-6">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-xl flex items-center justify-center {{ $colorBg[$ministry->color] ?? 'bg-gray-500/20' }}">
                <x-icon name="{{ str_replace('fa-', '', $ministry->icon) }}" style="duotone" class="w-8 h-8 {{ $colorText[$ministry->color] ?? 'text-gray-600' }}" />
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $ministry->name }}</h1>
                <p class="text-gray-600 dark:text-gray-400">Dashboard do ministério</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="backdrop-blur-xl bg-white/70 dark:bg-white/10 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
                        <x-icon name="users" style="duotone" class="w-6 h-6 text-indigo-600 dark:text-indigo-400" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Voluntários ativos</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $volunteersCount }}</p>
                    </div>
                </div>
                <a href="{{ route('ministry.members.index', $ministry) }}" class="mt-3 text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                    Ver equipe →
                </a>
            </div>

            <div class="backdrop-blur-xl bg-white/70 dark:bg-white/10 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                        <x-icon name="calendar-days" style="duotone" class="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Próximas escalas (7 dias)</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $upcomingSchedules->count() }}</p>
                    </div>
                </div>
                <a href="{{ route('ministry.schedules.index', $ministry) }}" class="mt-3 text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                    Ver escalas →
                </a>
            </div>

            <div class="backdrop-blur-xl bg-white/70 dark:bg-white/10 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                        <x-icon name="folder-open" style="duotone" class="w-6 h-6 text-amber-600 dark:text-amber-400" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Materiais recentes</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $recentMaterials->count() }}</p>
                    </div>
                </div>
                <a href="{{ route('ministry.materials.index', $ministry) }}" class="mt-3 text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                    Ver materiais →
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="backdrop-blur-xl bg-white/70 dark:bg-white/10 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Próximas escalas</h2>
                @if ($upcomingSchedules->count() > 0)
                    <ul class="space-y-3">
                        @foreach ($upcomingSchedules as $schedule)
                            <li class="flex items-center justify-between text-sm">
                                <span class="text-gray-700 dark:text-gray-300">{{ $schedule->activity_name }}</span>
                                <span class="text-gray-500 dark:text-gray-400">{{ $schedule->scheduled_at?->format('d/m H:i') }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Nenhuma escala nos próximos 7 dias.</p>
                @endif
            </div>

            <div class="backdrop-blur-xl bg-white/70 dark:bg-white/10 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Materiais recentes</h2>
                @if ($recentMaterials->count() > 0)
                    <ul class="space-y-2">
                        @foreach ($recentMaterials as $material)
                            <li>
                                <a href="{{ route('ministry.materials.show', [$ministry, $material]) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                                    {{ $material->title }}
                                </a>
                                <span class="text-gray-500 dark:text-gray-400 text-xs ml-1">{{ $material->created_at->format('d/m') }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Nenhum material recente.</p>
                @endif
            </div>
        </div>
    </div>
@endsection

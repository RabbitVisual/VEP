@extends('pastoralpanel::components.layouts.master')

@section('title', $ministry->name)

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
        @if (session('success'))
            <div class="p-4 text-sm text-green-800 bg-green-50 border border-green-200 rounded-lg dark:bg-green-900/20 dark:text-green-300 dark:border-green-800 flex items-center" role="alert">
                <x-icon name="circle-check" class="w-5 h-5 mr-3 flex-shrink-0" />
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl flex items-center justify-center {{ $colorBg[$ministry->color] ?? 'bg-gray-500/20' }}">
                    <x-icon name="{{ str_replace('fa-', '', $ministry->icon) }}" style="duotone" class="w-8 h-8 {{ $colorText[$ministry->color] ?? 'text-gray-600' }}" />
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $ministry->name }}</h1>
                    @if ($ministry->leader)
                        <p class="text-gray-600 dark:text-gray-400 mt-0.5">Líder: {{ $ministry->leader->name }}</p>
                    @endif
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('ministry.edit', $ministry) }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-indigo-700 bg-indigo-100 rounded-lg hover:bg-indigo-200 dark:bg-indigo-900/30 dark:text-indigo-300">
                    <x-icon name="pen-to-square" style="duotone" class="w-5 h-5 mr-2" />
                    Editar
                </a>
                <form action="{{ route('ministry.destroy', $ministry) }}" method="POST" class="inline"
                    onsubmit="return confirm('Tem certeza que deseja excluir este ministério?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-red-700 bg-red-100 rounded-lg hover:bg-red-200 dark:bg-red-900/30 dark:text-red-300">
                        <x-icon name="trash-can" style="duotone" class="w-5 h-5 mr-2" />
                        Excluir
                    </button>
                </form>
            </div>
        </div>

        @if ($ministry->description)
            <div class="backdrop-blur-xl bg-white/70 dark:bg-white/10 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Descrição</h2>
                <p class="text-gray-600 dark:text-gray-400 whitespace-pre-line">{{ $ministry->description }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="backdrop-blur-xl bg-white/70 dark:bg-white/10 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <x-icon name="users" style="duotone" class="w-5 h-5 text-indigo-500" />
                        Membros ({{ $ministry->members->count() }})
                    </h2>
                    <a href="{{ route('ministry.members.index', $ministry) }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">Gerenciar equipe</a>
                </div>
                @if ($ministry->members->count() > 0)
                    <ul class="space-y-2">
                        @foreach ($ministry->members as $member)
                            <li class="flex items-center justify-between text-sm">
                                <span class="text-gray-700 dark:text-gray-300">{{ $member->user->name ?? '—' }}</span>
                                <span class="text-gray-500 dark:text-gray-400">{{ $member->role }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Nenhum membro cadastrado.</p>
                @endif
            </div>

            <div class="backdrop-blur-xl bg-white/70 dark:bg-white/10 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <x-icon name="calendar-days" style="duotone" class="w-5 h-5 text-indigo-500" />
                        Próximas escalas
                    </h2>
                    <a href="{{ route('ministry.schedules.index', $ministry) }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">Ver todas</a>
                </div>
                @if ($ministry->schedules->count() > 0)
                    <ul class="space-y-2">
                        @foreach ($ministry->schedules as $schedule)
                            <li class="text-sm">
                                <a href="{{ route('ministry.schedules.show', [$ministry, $schedule]) }}" class="text-gray-700 dark:text-gray-300 hover:underline">
                                    {{ $schedule->scheduled_at?->format('d/m/Y H:i') }} — {{ $schedule->activity_name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Nenhuma escala cadastrada.</p>
                @endif
            </div>
        </div>

        <div class="backdrop-blur-xl bg-white/70 dark:bg-white/10 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <x-icon name="folder-open" style="duotone" class="w-5 h-5 text-indigo-500" />
                    Materiais recentes
                </h2>
                <a href="{{ route('ministry.materials.index', $ministry) }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">Ver todos</a>
            </div>
            @if ($ministry->materials->count() > 0)
                <ul class="space-y-2">
                    @foreach ($ministry->materials as $material)
                        <li class="text-sm">
                            <a href="{{ route('ministry.materials.show', [$ministry, $material]) }}" class="text-gray-700 dark:text-gray-300 hover:underline">{{ $material->title }}</a>
                            <span class="text-gray-500 dark:text-gray-400">({{ $material->type }})</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500 dark:text-gray-400 text-sm">Nenhum material cadastrado.</p>
            @endif
        </div>
    </div>
@endsection

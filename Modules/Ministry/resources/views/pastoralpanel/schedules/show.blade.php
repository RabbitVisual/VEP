@extends('pastoralpanel::components.layouts.master')

@section('title', $schedule->activity_name . ' – ' . $ministry->name)

@section('content')
    <div class="p-6 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <a href="{{ route('ministry.schedules.index', $ministry) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline mb-2 inline-block">
                    ← Escalas
                </a>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $schedule->activity_name }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-0.5">{{ $schedule->scheduled_at?->format('d/m/Y H:i') }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('ministry.schedules.edit', [$ministry, $schedule]) }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-indigo-700 bg-indigo-100 rounded-lg hover:bg-indigo-200 dark:bg-indigo-900/30 dark:text-indigo-300">
                    Editar escala
                </a>
                <form action="{{ route('ministry.schedules.destroy', [$ministry, $schedule]) }}" method="POST" class="inline"
                    onsubmit="return confirm('Excluir esta escala?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-red-700 bg-red-100 rounded-lg dark:bg-red-900/30 dark:text-red-300">
                        Excluir
                    </button>
                </form>
            </div>
        </div>

        @if (session('success'))
            <div class="p-4 text-sm text-green-800 bg-green-50 border border-green-200 rounded-lg dark:bg-green-900/20 dark:text-green-300 flex items-center" role="alert">
                <x-icon name="circle-check" class="w-5 h-5 mr-3 flex-shrink-0" />
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="p-4 text-sm text-red-800 bg-red-50 border border-red-200 rounded-lg dark:bg-red-900/20 dark:text-red-300 flex items-center" role="alert">
                <x-icon name="circle-exclamation" class="w-5 h-5 mr-3 flex-shrink-0" />
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if ($schedule->notes)
            <div class="backdrop-blur-xl bg-white/70 dark:bg-white/10 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Observações</h2>
                <p class="text-gray-600 dark:text-gray-400 whitespace-pre-line">{{ $schedule->notes }}</p>
            </div>
        @endif

        <div class="backdrop-blur-xl bg-white/70 dark:bg-white/10 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Pessoas na escala</h2>

            <form action="{{ route('ministry.schedules.assign', [$ministry, $schedule]) }}" method="POST" class="flex flex-wrap items-end gap-3 mb-6">
                @csrf
                <div class="min-w-[200px]">
                    <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Adicionar membro da equipe</label>
                    <select name="user_id" id="user_id" class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                        <option value="">Selecione</option>
                        @foreach ($availableMembers as $m)
                            <option value="{{ $m->user_id }}">{{ $m->user->name ?? '—' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="min-w-[120px]">
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                    <select name="status" id="status" class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                        <option value="pending">Pendente</option>
                        <option value="confirmed">Confirmado</option>
                    </select>
                </div>
                <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg" {{ $availableMembers->isEmpty() ? 'disabled' : '' }}>
                    Adicionar
                </button>
            </form>

            @if ($schedule->assignments->count() > 0)
                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($schedule->assignments as $assignment)
                        <li class="py-3 flex flex-wrap items-center justify-between gap-3">
                            <span class="font-medium text-gray-900 dark:text-white">{{ $assignment->user->name ?? '—' }}</span>
                            <div class="flex items-center gap-2">
                                <form action="{{ route('ministry.schedules.assignments.update', [$ministry, $schedule, $assignment->id]) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" onchange="this.form.submit()" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                                        <option value="pending" {{ $assignment->status === 'pending' ? 'selected' : '' }}>Pendente</option>
                                        <option value="confirmed" {{ $assignment->status === 'confirmed' ? 'selected' : '' }}>Confirmado</option>
                                    </select>
                                </form>
                                <form action="{{ route('ministry.schedules.assignments.destroy', [$ministry, $schedule, $assignment->id]) }}" method="POST" class="inline" onsubmit="return confirm('Remover desta escala?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 dark:text-red-400 hover:underline text-sm">Remover</button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500 dark:text-gray-400">Nenhuma pessoa atribuída. Adicione membros da equipe acima.</p>
            @endif
        </div>
    </div>
@endsection

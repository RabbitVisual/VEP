@extends('pastoralpanel::components.layouts.master')

@section('title', 'Editar escala – ' . $ministry->name)

@section('content')
    <div class="p-6 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('ministry.schedules.show', [$ministry, $schedule]) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline mb-2 inline-block">
                    ← Voltar à escala
                </a>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editar escala</h1>
            </div>
        </div>

        <div class="backdrop-blur-xl bg-white/70 dark:bg-white/10 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 max-w-2xl">
            <form action="{{ route('ministry.schedules.update', [$ministry, $schedule]) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label for="activity_name" class="block text-sm font-bold text-gray-700 dark:text-gray-300">Nome da atividade <span class="text-red-500">*</span></label>
                    <input type="text" name="activity_name" id="activity_name" value="{{ old('activity_name', $schedule->activity_name) }}" required
                        class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('activity_name') border-red-500 @enderror">
                    @error('activity_name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="scheduled_at" class="block text-sm font-bold text-gray-700 dark:text-gray-300">Data e hora <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="scheduled_at" id="scheduled_at" value="{{ old('scheduled_at', $schedule->scheduled_at?->format('Y-m-d\TH:i')) }}" required
                        class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('scheduled_at') border-red-500 @enderror">
                    @error('scheduled_at')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="notes" class="block text-sm font-bold text-gray-700 dark:text-gray-300">Observações</label>
                    <textarea name="notes" id="notes" rows="3"
                        class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm">{{ old('notes', $schedule->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <a href="{{ route('ministry.schedules.show', [$ministry, $schedule]) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800">
                        Cancelar
                    </a>
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg">
                        <x-icon name="check" class="w-5 h-5 mr-2" />
                        Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

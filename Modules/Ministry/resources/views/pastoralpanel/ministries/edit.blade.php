@extends('pastoralpanel::components.layouts.master')

@section('title', 'Editar ministério')

@section('content')
    <div class="p-6 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <x-icon name="pen-to-square" style="duotone" class="w-8 h-8 text-indigo-500" />
                    Editar ministério
                </h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $ministry->name }}</p>
            </div>
            <a href="{{ route('ministry.show', $ministry) }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                <x-icon name="arrow-left" class="w-5 h-5 mr-2" />
                Voltar
            </a>
        </div>

        <div class="backdrop-blur-xl bg-white/70 dark:bg-white/10 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <form action="{{ route('ministry.update', $ministry) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-bold text-gray-700 dark:text-gray-300">Nome <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $ministry->name) }}" required
                            class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('name') border-red-500 @enderror"
                            placeholder="Ex: Louvor, Infantil, Missões">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-bold text-gray-700 dark:text-gray-300">Descrição</label>
                        <textarea name="description" id="description" rows="3"
                            class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('description') border-red-500 @enderror"
                            placeholder="Breve descrição do ministério">{{ old('description', $ministry->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="leader_id" class="block text-sm font-bold text-gray-700 dark:text-gray-300">Líder</label>
                        <select name="leader_id" id="leader_id"
                            class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                            <option value="">— Sem líder —</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ old('leader_id', $ministry->leader_id) == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                        @error('leader_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="icon" class="block text-sm font-bold text-gray-700 dark:text-gray-300">Ícone <span class="text-red-500">*</span></label>
                        <select name="icon" id="icon" required
                            class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                            @foreach ($iconOptions as $opt)
                                <option value="{{ $opt['value'] }}" {{ old('icon', $ministry->icon) === $opt['value'] ? 'selected' : '' }}>{{ $opt['label'] }}</option>
                            @endforeach
                        </select>
                        @error('icon')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="color" class="block text-sm font-bold text-gray-700 dark:text-gray-300">Cor <span class="text-red-500">*</span></label>
                        <select name="color" id="color" required
                            class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                            @foreach ($colorOptions as $opt)
                                <option value="{{ $opt['value'] }}" {{ old('color', $ministry->color) === $opt['value'] ? 'selected' : '' }}>{{ $opt['label'] }}</option>
                            @endforeach
                        </select>
                        @error('color')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('ministry.show', $ministry) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                        Cancelar
                    </a>
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm focus:ring-4 focus:ring-indigo-300 dark:focus:ring-indigo-800">
                        <x-icon name="check" class="w-5 h-5 mr-2" />
                        Salvar alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

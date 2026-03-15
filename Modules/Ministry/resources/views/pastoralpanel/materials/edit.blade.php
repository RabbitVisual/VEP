@extends('pastoralpanel::components.layouts.master')

@section('title', 'Editar material – ' . $ministry->name)

@section('content')
    <div class="p-6 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('ministry.materials.show', [$ministry, $material]) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline mb-2 inline-block">
                    ← Voltar ao material
                </a>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editar material</h1>
            </div>
        </div>

        <div class="backdrop-blur-xl bg-white/70 dark:bg-white/10 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 max-w-3xl">
            <form action="{{ route('ministry.materials.update', [$ministry, $material]) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="type" class="block text-sm font-bold text-gray-700 dark:text-gray-300">Tipo <span class="text-red-500">*</span></label>
                        <select name="type" id="type" required
                            class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                            @foreach ($typeOptions as $value => $label)
                                <option value="{{ $value }}" {{ old('type', $material->type) === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="title" class="block text-sm font-bold text-gray-700 dark:text-gray-300">Título <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="title" value="{{ old('title', $material->title) }}" required
                            class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('title') border-red-500 @enderror">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div>
                    <label for="content" class="block text-sm font-bold text-gray-700 dark:text-gray-300">Conteúdo</label>
                    <textarea name="content" id="content" rows="8" data-mention-editor="true"
                        class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('content') border-red-500 @enderror">{{ old('content', $material->content) }}</textarea>
                    @error('content')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="file" class="block text-sm font-bold text-gray-700 dark:text-gray-300">Anexo (opcional)</label>
                    @if ($material->file_path)
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Arquivo atual: {{ basename($material->file_path) }}</p>
                    @endif
                    <input type="file" name="file" id="file"
                        class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                    @error('file')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <a href="{{ route('ministry.materials.show', [$ministry, $material]) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800">
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

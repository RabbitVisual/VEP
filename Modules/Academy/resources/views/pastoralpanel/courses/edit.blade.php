@extends('pastoralpanel::components.layouts.master')

@section('title', 'Editar curso – Academia')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('pastoral.academy.courses.index') }}" class="text-slate-400 hover:text-slate-200">
            <x-icon name="arrow-left" style="solid" class="size-5" />
        </a>
        <h2 class="text-lg font-semibold text-slate-100">Editar curso</h2>
    </div>

    <form action="{{ route('pastoral.academy.courses.update', $course) }}" method="POST" enctype="multipart/form-data" class="max-w-2xl space-y-6 rounded-xl border border-slate-700 bg-slate-800/50 p-6">
        @csrf
        @method('PUT')

        <div>
            <label for="title" class="block text-sm font-medium text-slate-300">Título <span class="text-red-400">*</span></label>
            <input type="text" name="title" id="title" value="{{ old('title', $course->title) }}" required
                   class="mt-1 block w-full rounded-lg border-slate-600 bg-slate-800 text-slate-100 shadow-sm focus:ring-indigo-500">
            @error('title')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="slug" class="block text-sm font-medium text-slate-300">Slug</label>
            <input type="text" name="slug" id="slug" value="{{ old('slug', $course->slug) }}"
                   class="mt-1 block w-full rounded-lg border-slate-600 bg-slate-800 text-slate-100 shadow-sm focus:ring-indigo-500">
            @error('slug')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-slate-300">Descrição</label>
            <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-lg border-slate-600 bg-slate-800 text-slate-100 shadow-sm focus:ring-indigo-500">{{ old('description', $course->description) }}</textarea>
        </div>

        <div>
            <label for="cover_image" class="block text-sm font-medium text-slate-300">Capa</label>
            @if ($course->cover_image)
                <p class="mt-1 text-xs text-slate-500">Atual: {{ $course->cover_image }}</p>
            @endif
            <input type="file" name="cover_image" id="cover_image" accept="image/*"
                   class="mt-1 block w-full text-sm text-slate-400 file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-600 file:px-4 file:py-2 file:text-white">
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label for="level" class="block text-sm font-medium text-slate-300">Nível</label>
                <select name="level" id="level" class="mt-1 block w-full rounded-lg border-slate-600 bg-slate-800 text-slate-100">
                    <option value="iniciante" @selected(old('level', $course->level) === 'iniciante')>Iniciante</option>
                    <option value="intermediário" @selected(old('level', $course->level) === 'intermediário')>Intermediário</option>
                    <option value="avançado" @selected(old('level', $course->level) === 'avançado')>Avançado</option>
                </select>
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-slate-300">Status</label>
                <select name="status" id="status" class="mt-1 block w-full rounded-lg border-slate-600 bg-slate-800 text-slate-100">
                    <option value="draft" @selected(old('status', $course->status) === 'draft')>Rascunho</option>
                    <option value="published" @selected(old('status', $course->status) === 'published')>Publicado</option>
                </select>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Salvar</button>
            <a href="{{ route('pastoral.academy.courses.show', $course) }}" class="rounded-lg border border-slate-600 px-4 py-2 text-sm font-medium text-slate-300 hover:bg-slate-700">Ver curso</a>
        </div>
    </form>
</div>
@endsection

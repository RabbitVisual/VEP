@extends('pastoralpanel::components.layouts.master')

@section('title', 'Editar módulo – Academia')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('pastoral.academy.courses.show', $module->course) }}" class="text-slate-400 hover:text-slate-200">
            <x-icon name="arrow-left" style="solid" class="size-5" />
        </a>
        <h2 class="text-lg font-semibold text-slate-100">Editar módulo</h2>
    </div>

    <form action="{{ route('pastoral.academy.modules.update', $module) }}" method="POST" class="max-w-xl space-y-6 rounded-xl border border-slate-700 bg-slate-800/50 p-6">
        @csrf
        @method('PUT')

        <div>
            <label for="title" class="block text-sm font-medium text-slate-300">Título <span class="text-red-400">*</span></label>
            <input type="text" name="title" id="title" value="{{ old('title', $module->title) }}" required
                   class="mt-1 block w-full rounded-lg border-slate-600 bg-slate-800 text-slate-100 shadow-sm focus:ring-indigo-500">
            @error('title')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="order" class="block text-sm font-medium text-slate-300">Ordem</label>
            <input type="number" name="order" id="order" value="{{ old('order', $module->order) }}" min="0"
                   class="mt-1 block w-full rounded-lg border-slate-600 bg-slate-800 text-slate-100 shadow-sm focus:ring-indigo-500">
        </div>

        <div class="flex gap-3">
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Salvar</button>
            <a href="{{ route('pastoral.academy.courses.show', $module->course) }}" class="rounded-lg border border-slate-600 px-4 py-2 text-sm font-medium text-slate-300 hover:bg-slate-700">Voltar</a>
        </div>
    </form>
</div>
@endsection

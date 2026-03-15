@extends('memberpanel::components.layouts.master')

@section('title', 'Academia')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-lg font-semibold text-slate-100">Academia</h2>
        <p class="mt-1 text-sm text-slate-400">Cursos de formação teológica e liderança.</p>
    </div>

    <form method="GET" action="{{ route('painel.academy.catalog') }}" class="flex flex-wrap items-center gap-4">
        <input type="search" name="search" value="{{ request('search') }}" placeholder="Buscar cursos..."
               class="rounded-lg border-slate-600 bg-slate-800 text-slate-100 shadow-sm focus:ring-indigo-500 sm:w-56">
        <select name="level" class="rounded-lg border-slate-600 bg-slate-800 text-slate-100">
            <option value="">Todos os níveis</option>
            <option value="iniciante" @selected(request('level') === 'iniciante')>Iniciante</option>
            <option value="intermediário" @selected(request('level') === 'intermediário')>Intermediário</option>
            <option value="avançado" @selected(request('level') === 'avançado')>Avançado</option>
        </select>
        <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Filtrar</button>
    </form>

    @if ($courses->isEmpty())
        <div class="rounded-xl border border-slate-700 bg-slate-800/50 p-12 text-center">
            <x-icon name="graduation-cap" style="duotone" class="mx-auto size-12 text-slate-500" />
            <p class="mt-4 text-slate-400">Nenhum curso disponível no momento.</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($courses as $course)
                <a href="{{ route('painel.academy.courses.show', $course) }}" class="group block overflow-hidden rounded-xl border border-slate-700 bg-slate-800/50 transition-all duration-300 hover:border-indigo-500/50 hover:shadow-lg">
                    @if ($course->cover_image)
                        <img src="{{ asset('storage/' . $course->cover_image) }}" alt="" class="aspect-video w-full object-cover">
                    @else
                        <div class="flex aspect-video w-full items-center justify-center bg-slate-700/50">
                            <x-icon name="graduation-cap" style="duotone" class="size-16 text-slate-500" />
                        </div>
                    @endif
                    <div class="p-4">
                        <h3 class="font-semibold text-slate-100 group-hover:text-indigo-400">{{ $course->title }}</h3>
                        <p class="mt-1 text-xs text-slate-400">{{ $course->level }} · {{ $course->modules_count }} módulo(s)</p>
                        @if ($course->description)
                            <p class="mt-2 line-clamp-2 text-sm text-slate-500">{{ Str::limit(strip_tags($course->description), 100) }}</p>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
        @if ($courses->hasPages())
            <div class="mt-6">{{ $courses->links() }}</div>
        @endif
    @endif
</div>
@endsection

@extends('pastoralpanel::components.layouts.master')

@section('title', $course->title . ' – Construtor')

@section('content')
<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('pastoral.academy.courses.index') }}" class="text-slate-400 hover:text-slate-200">
                <x-icon name="arrow-left" style="solid" class="size-5" />
            </a>
            <div>
                <h2 class="text-lg font-semibold text-slate-100">{{ $course->title }}</h2>
                <p class="text-sm text-slate-400">{{ $course->modules->count() }} módulo(s)</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('pastoral.academy.courses.edit', $course) }}" class="rounded-lg border border-slate-600 px-4 py-2 text-sm font-medium text-slate-300 hover:bg-slate-700">Editar curso</a>
            <a href="{{ route('pastoral.academy.courses.modules.create', $course) }}" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                <x-icon name="list-tree" style="duotone" class="size-4" />
                Adicionar módulo
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="rounded-lg bg-emerald-500/20 px-4 py-3 text-sm text-emerald-200" role="alert">{{ session('success') }}</div>
    @endif

    <div class="space-y-6">
        @forelse ($course->modules as $module)
            <section class="rounded-xl border border-slate-700 bg-slate-800/50 p-5" aria-labelledby="module-{{ $module->id }}">
                <div class="flex items-center justify-between border-b border-slate-600 pb-3">
                    <h3 id="module-{{ $module->id }}" class="flex items-center gap-2 text-base font-semibold text-slate-100">
                        <x-icon name="list-tree" style="duotone" class="size-5 text-indigo-400" />
                        {{ $module->title }}
                    </h3>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('pastoral.academy.modules.edit', $module) }}" class="text-sm text-slate-400 hover:text-slate-200">Editar</a>
                        <form action="{{ route('pastoral.academy.modules.destroy', $module) }}" method="POST" class="inline" onsubmit="return confirm('Remover este módulo e todas as aulas?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm text-red-400 hover:text-red-300">Remover</button>
                        </form>
                        <a href="{{ route('pastoral.academy.lessons.create', $module) }}" class="inline-flex items-center gap-1 rounded-lg bg-slate-600 px-3 py-1.5 text-sm text-slate-200 hover:bg-slate-500">
                            <x-icon name="plus" style="solid" class="size-3" /> Aula
                        </a>
                    </div>
                </div>
                <ul class="mt-4 space-y-2" role="list">
                    @forelse ($module->lessons as $lesson)
                        <li class="flex items-center justify-between rounded-lg border border-slate-600/50 bg-slate-800/30 px-4 py-3">
                            <div class="flex items-center gap-3">
                                <x-icon name="play-circle" style="duotone" class="size-5 text-slate-400" />
                                <span class="text-slate-200">{{ $lesson->title }}</span>
                                @if ($lesson->duration_in_minutes > 0)
                                    <span class="text-xs text-slate-500">{{ $lesson->duration_in_minutes }} min</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('pastoral.academy.lessons.edit', $lesson) }}" class="text-sm text-indigo-400 hover:text-indigo-300">Editar</a>
                                <form action="{{ route('pastoral.academy.lessons.destroy', $lesson) }}" method="POST" class="inline" onsubmit="return confirm('Remover esta aula?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-400 hover:text-red-300">Remover</button>
                                </form>
                            </div>
                        </li>
                    @empty
                        <li class="py-4 text-center text-sm text-slate-500">Nenhuma aula. <a href="{{ route('pastoral.academy.lessons.create', $module) }}" class="text-indigo-400 hover:underline">Adicionar primeira aula</a></li>
                    @endforelse
                </ul>
            </section>
        @empty
            <div class="rounded-xl border border-dashed border-slate-600 bg-slate-800/30 p-8 text-center">
                <p class="text-slate-400">Nenhum módulo ainda.</p>
                <a href="{{ route('pastoral.academy.courses.modules.create', $course) }}" class="mt-3 inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Criar primeiro módulo</a>
            </div>
        @endforelse
    </div>
</div>
@endsection

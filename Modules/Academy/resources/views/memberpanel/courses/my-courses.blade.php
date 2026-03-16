@extends('memberpanel::components.layouts.master')

@section('title', 'Meus Cursos')

@section('content')
<div class="min-h-screen bg-slate-950/60 py-6">
    <div class="mx-auto max-w-6xl px-4 space-y-6">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500">Meu Campus</p>
                <h1 class="text-2xl font-bold text-slate-100">Meus Cursos</h1>
                <p class="text-sm text-slate-400">Continue suas trilhas de formação pastoral de onde parou.</p>
            </div>
            <a href="{{ route('painel.academy.catalog') }}" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                <x-icon name="books" class="size-4" />
                Ver catálogo de cursos
            </a>
        </div>

        @if ($enrollments->isEmpty())
            <div class="rounded-2xl border border-slate-800 bg-slate-900/70 px-6 py-10 text-center">
                <p class="text-sm font-medium text-slate-200">Você ainda não está matriculado em nenhum curso.</p>
                <p class="mt-1 text-sm text-slate-500">Explore o catálogo e inicie sua jornada de formação.</p>
                <a href="{{ route('painel.academy.catalog') }}" class="mt-4 inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                    <x-icon name="graduation-cap" class="size-4" />
                    Ir para o catálogo
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                @foreach ($enrollments as $enrollment)
                    @php $course = $enrollment->course; @endphp
                    <a href="{{ route('painel.academy.courses.show', $course) }}" class="group flex flex-col rounded-2xl border border-slate-800 bg-slate-900/70 p-4 transition-all hover:border-indigo-500/60 hover:bg-slate-900">
                        <div class="flex items-start gap-3">
                            <div class="flex size-10 items-center justify-center rounded-lg bg-slate-800">
                                <x-icon name="graduation-cap" style="duotone" class="size-5 text-slate-300 group-hover:text-indigo-300" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Curso</p>
                                <h2 class="truncate text-sm font-semibold text-slate-100">{{ $course->title }}</h2>
                                <p class="mt-1 line-clamp-2 text-xs text-slate-400">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($course->description ?? ''), 120) }}
                                </p>
                            </div>
                        </div>
                        <div class="mt-4 space-y-1">
                            <div class="flex items-center justify-between text-xs text-slate-400">
                                <span>Progresso</span>
                                <span class="font-semibold text-indigo-400">{{ $enrollment->progress_percent }}%</span>
                            </div>
                            <div class="h-2 w-full overflow-hidden rounded-full bg-slate-800">
                                <div class="h-full rounded-full bg-indigo-600 transition-all duration-300" style="width: {{ $enrollment->progress_percent }}%"></div>
                            </div>
                        </div>
                        <span class="mt-3 inline-flex items-center gap-1 text-xs font-semibold text-indigo-400">
                            Continuar trilha
                            <x-icon name="arrow-right" class="size-3.5" />
                        </span>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection


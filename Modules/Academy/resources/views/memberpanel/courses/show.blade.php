@extends('memberpanel::components.layouts.master')

@section('title', $course->title)

@section('content')
<div class="space-y-6">
    @if (session('success'))
        <div class="rounded-lg bg-emerald-500/20 px-4 py-3 text-sm text-emerald-200" role="alert">{{ session('success') }}</div>
    @endif

    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div class="flex gap-4">
            @if ($course->cover_image)
                <img src="{{ asset('storage/' . $course->cover_image) }}" alt="" class="size-24 rounded-lg object-cover sm:size-32">
            @else
                <div class="flex size-24 shrink-0 items-center justify-center rounded-lg bg-slate-700 sm:size-32">
                    <x-icon name="graduation-cap" style="duotone" class="size-12 text-slate-400" />
                </div>
            @endif
            <div>
                <h2 class="text-xl font-semibold text-slate-100">{{ $course->title }}</h2>
                <p class="text-sm text-slate-400">{{ $course->level }}</p>
                @if ($course->description)
                    <p class="mt-2 text-sm text-slate-500">{{ Str::limit(strip_tags($course->description), 200) }}</p>
                @endif
            </div>
        </div>
        @if (! $enrollment)
            <form action="{{ route('painel.academy.enroll', $course) }}" method="POST">
                @csrf
                <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700">
                    Fazer matrícula
                </button>
            </form>
        @endif
    </div>

    @if ($enrollment)
        <div class="rounded-xl border border-slate-700 bg-slate-800/50 p-4">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-slate-300">Seu progresso</span>
                <span class="text-sm font-semibold text-indigo-400">{{ $progressPercent }}%</span>
            </div>
            <div class="mt-2 h-2 w-full overflow-hidden rounded-full bg-slate-700">
                <div class="h-full rounded-full bg-indigo-600 transition-all duration-300" style="width: {{ $progressPercent }}%"></div>
            </div>
        </div>

        <div class="space-y-6">
            @foreach ($course->modules as $module)
                <section class="rounded-xl border border-slate-700 bg-slate-800/50 p-5" aria-labelledby="mod-{{ $module->id }}">
                    <h3 id="mod-{{ $module->id }}" class="text-base font-semibold text-slate-100">{{ $module->title }}</h3>
                    <ul class="mt-4 space-y-2" role="list">
                        @foreach ($module->lessons as $lesson)
                            @php $unlocked = $unlockedLessons[$lesson->id] ?? false; @endphp
                            @php $completed = $enrollment->lessonProgress()->where('lesson_id', $lesson->id)->where('is_completed', true)->exists(); @endphp
                            <li>
                                @if ($unlocked)
                                    <a href="{{ route('painel.academy.player', ['enrollment' => $enrollment, 'lesson' => $lesson]) }}" class="flex items-center gap-3 rounded-lg border border-slate-600/50 bg-slate-800/30 px-4 py-3 transition-colors hover:bg-slate-700/50">
                                        @if ($completed)
                                            <x-icon name="circle-check" style="solid" class="size-5 text-emerald-400" aria-label="Concluída" />
                                        @else
                                            <x-icon name="play-circle" style="duotone" class="size-5 text-indigo-400" />
                                        @endif
                                        <span class="text-slate-200">{{ $lesson->title }}</span>
                                        @if ($lesson->duration_in_minutes > 0)
                                            <span class="ml-auto text-xs text-slate-500">{{ $lesson->duration_in_minutes }} min</span>
                                        @endif
                                    </a>
                                @else
                                    <div class="flex items-center gap-3 rounded-lg border border-slate-700 bg-slate-800/20 px-4 py-3 text-slate-500">
                                        <x-icon name="lock" style="solid" class="size-5" aria-label="Bloqueada" />
                                        <span>{{ $lesson->title }}</span>
                                        @if ($lesson->duration_in_minutes > 0)
                                            <span class="ml-auto text-xs">{{ $lesson->duration_in_minutes }} min</span>
                                        @endif
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endforeach
        </div>
    @endif
</div>
@endsection

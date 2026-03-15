@extends('memberpanel::components.layouts.master')

@section('title', $lesson->title)

@section('content')
@php
    $isCompleted = $enrollment->lessonProgress()->where('lesson_id', $lesson->id)->where('is_completed', true)->exists();
@endphp
<div class="min-h-screen bg-slate-950" x-data="lessonPlayer({{ $isCompleted ? 'true' : 'false' }})">
    <div class="mx-auto max-w-7xl px-4 py-6">
        <div class="flex flex-col gap-6 lg:flex-row">
            <div class="min-w-0 flex-1 space-y-6">
                {{-- Video --}}
                @if ($lesson->video_url)
                    <div class="overflow-hidden rounded-xl bg-black">
                        <div class="aspect-video w-full">
                            @php
                                $url = $lesson->video_url;
                                $embed = $url;
                                if (preg_match('#(?:youtube\.com/watch\?v=|youtu\.be/)([a-zA-Z0-9_-]+)#', $url, $m)) {
                                    $embed = 'https://www.youtube.com/embed/' . $m[1];
                                } elseif (preg_match('#vimeo\.com/(?:video/)?(\d+)#', $url, $m)) {
                                    $embed = 'https://player.vimeo.com/video/' . $m[1];
                                }
                            @endphp
                            <iframe src="{{ $embed }}" title="Vídeo da aula" class="size-full" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                        </div>
                    </div>
                @else
                    <div class="flex aspect-video w-full items-center justify-center rounded-xl bg-slate-800">
                        <x-icon name="play-circle" style="duotone" class="size-24 text-slate-600" />
                    </div>
                @endif

                <div class="rounded-xl border border-slate-700 bg-slate-800/50 p-5">
                    <h1 class="text-xl font-semibold text-slate-100">{{ $lesson->title }}</h1>
                    @if ($lesson->duration_in_minutes > 0)
                        <p class="mt-1 text-sm text-slate-400">{{ $lesson->duration_in_minutes }} min</p>
                    @endif
                </div>

                @if ($contentHtml)
                    <div class="prose prose-invert max-w-none rounded-xl border border-slate-700 bg-slate-800/50 p-5 prose-p:text-slate-300 prose-a:text-indigo-400">
                        {!! $contentHtml !!}
                    </div>
                @endif

                <div class="flex flex-wrap items-center gap-4">
                    <button type="button"
                            @click="completeLesson()"
                            :disabled="completed"
                            class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition-all duration-300 hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-50">
                        <x-icon name="circle-check" style="solid" class="size-5" />
                        <span x-text="completed ? 'Concluída' : 'Marcar como concluída'"></span>
                    </button>
                    @if ($nextLesson)
                        <a href="{{ route('painel.academy.player', ['enrollment' => $enrollment, 'lesson' => $nextLesson]) }}" class="text-sm font-medium text-indigo-400 hover:text-indigo-300">
                            Próxima aula →
                        </a>
                    @else
                        <a href="{{ route('painel.academy.courses.show', $enrollment->course) }}" class="text-sm font-medium text-slate-400 hover:text-slate-300">Voltar ao curso</a>
                    @endif
                </div>
            </div>

            <aside class="w-full shrink-0 lg:w-72">
                <div class="sticky top-4 rounded-xl border border-slate-700 bg-slate-800/50 p-4">
                    <h3 class="text-sm font-semibold text-slate-200">Aulas deste módulo</h3>
                    <ul class="mt-3 space-y-1" role="list">
                        @foreach ($lesson->module->lessons as $l)
                            @php $isCurrent = $l->id === $lesson->id; @endphp
                            <li>
                                @if ($isCurrent)
                                    <span class="flex items-center gap-2 rounded-lg bg-indigo-500/20 px-3 py-2 text-sm font-medium text-indigo-300">{{ $l->title }}</span>
                                @else
                                    <a href="{{ route('painel.academy.player', ['enrollment' => $enrollment, 'lesson' => $l]) }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-slate-400 hover:bg-slate-700/50 hover:text-slate-200">{{ $l->title }}</a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('painel.academy.courses.show', $enrollment->course) }}" class="mt-4 block text-center text-sm text-slate-400 hover:text-slate-200">Voltar ao curso</a>
                </div>
            </aside>
        </div>
    </div>
</div>

<script>
function lessonPlayer(initialCompleted = false) {
    return {
        completed: !!initialCompleted,
        completeLesson() {
            if (this.completed) return;
            window.dispatchEvent(new CustomEvent('loading-overlay:show', { detail: { message: 'Salvando...' } }));
            fetch('{{ route("painel.academy.lessons.complete", ["enrollment" => $enrollment, "lesson" => $lesson]) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ watched_seconds: 0 })
            })
            .then(r => r.json())
            .then(data => {
                window.dispatchEvent(new CustomEvent('loading-overlay:hide'));
                if (data.success) {
                    this.completed = true;
                    if (data.certificate_issued && data.certificate_download_url) {
                        if (confirm('Parabéns! Você concluiu o curso. Deseja baixar o certificado?')) {
                            window.open(data.certificate_download_url, '_blank');
                        }
                    }
                    if (data.next_lesson_url) {
                        window.location.href = data.next_lesson_url;
                    }
                }
            })
            .catch(() => window.dispatchEvent(new CustomEvent('loading-overlay:hide')));
        }
    };
}
</script>
@endsection

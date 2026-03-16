@extends('memberpanel::components.layouts.master')

@section('title', 'Meus Certificados')

@section('content')
<div class="min-h-screen bg-slate-950/60 py-6">
    <div class="mx-auto max-w-6xl px-4 space-y-6">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500">Meu Campus</p>
                <h1 class="text-2xl font-bold text-slate-100">Meus Certificados</h1>
                <p class="text-sm text-slate-400">Seus diplomas oficiais emitidos pela Vertex Academy.</p>
            </div>
        </div>

        @if ($certificates->isEmpty())
            <div class="rounded-2xl border border-slate-800 bg-slate-900/70 px-6 py-10 text-center">
                <i class="fa-duotone fa-certificate text-4xl text-slate-600 mb-3"></i>
                <p class="text-sm font-medium text-slate-200">Nenhum certificado disponível ainda.</p>
                <p class="mt-1 text-sm text-slate-500">Conclua cursos para desbloquear seus diplomas colecionáveis.</p>
            </div>
        @else
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                @foreach ($certificates as $certificate)
                    @php
                        $course = $certificate->course;
                    @endphp
                    <div class="relative overflow-hidden rounded-2xl border border-slate-800 bg-slate-900/80 p-5 shadow-lg shadow-slate-950/40">
                        <div class="absolute inset-0 pointer-events-none opacity-30">
                            <div class="absolute -right-20 -top-10 h-40 w-40 rounded-full border border-amber-400/40"></div>
                            <div class="absolute -left-10 bottom-0 h-32 w-32 rounded-full border border-slate-600/40"></div>
                        </div>
                        <div class="relative flex items-start gap-3">
                            <div class="flex size-11 items-center justify-center rounded-full bg-amber-500/10 ring-1 ring-amber-400/40">
                                <i class="fa-duotone fa-medal text-xl text-amber-300"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-amber-300">Certificado de Conclusão</p>
                                <h2 class="mt-1 line-clamp-2 text-sm font-semibold text-slate-50">{{ $course->title }}</h2>
                                <p class="mt-1 text-xs text-slate-400">
                                    Emitido em {{ $certificate->issued_at?->format('d/m/Y') ?? '-' }}
                                </p>
                                <p class="mt-1 text-[11px] text-slate-500">
                                    Código de validação: <span class="font-mono text-slate-300">{{ $certificate->validation_code }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="relative mt-4 flex items-center justify-between">
                            <a href="{{ route('painel.academy.courses.show', $course) }}" class="text-xs font-medium text-slate-400 hover:text-slate-200">
                                Ver curso
                            </a>
                            <a href="{{ route('painel.academy.certificates.download', $certificate) }}" class="inline-flex items-center gap-2 rounded-lg bg-amber-500 px-3 py-1.5 text-xs font-semibold text-slate-900 hover:bg-amber-400">
                                <i class="fa-duotone fa-download"></i>
                                Baixar diploma
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection


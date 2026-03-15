@extends('pastoralpanel::components.layouts.master')

@section('title', 'Cursos – Academia')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-lg font-semibold text-slate-100">Cursos</h2>
            <p class="mt-1 text-sm text-slate-400">Gerencie os cursos da Academia.</p>
        </div>
        <a href="{{ route('pastoral.academy.courses.create') }}"
           class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition-all duration-300 hover:bg-indigo-700">
            <x-icon name="plus" style="solid" class="size-4" />
            Novo curso
        </a>
    </div>

    @if (session('success'))
        <div class="rounded-lg bg-emerald-500/20 px-4 py-3 text-sm text-emerald-200" role="alert">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ route('pastoral.academy.courses.index') }}" class="flex flex-wrap items-center gap-4">
        <input type="search" name="search" value="{{ request('search') }}" placeholder="Buscar..."
               class="rounded-lg border-slate-600 bg-slate-800 text-slate-100 shadow-sm focus:ring-indigo-500 sm:w-48">
        <select name="status" class="rounded-lg border-slate-600 bg-slate-800 text-slate-100">
            <option value="">Todos os status</option>
            <option value="draft" @selected(request('status') === 'draft')>Rascunho</option>
            <option value="published" @selected(request('status') === 'published')>Publicado</option>
        </select>
        <select name="level" class="rounded-lg border-slate-600 bg-slate-800 text-slate-100">
            <option value="">Todos os níveis</option>
            <option value="iniciante" @selected(request('level') === 'iniciante')>Iniciante</option>
            <option value="intermediário" @selected(request('level') === 'intermediário')>Intermediário</option>
            <option value="avançado" @selected(request('level') === 'avançado')>Avançado</option>
        </select>
        <button type="submit" class="rounded-lg bg-slate-700 px-4 py-2 text-sm font-medium text-slate-200 hover:bg-slate-600">Filtrar</button>
    </form>

    <div class="overflow-hidden rounded-xl border border-slate-700 bg-slate-800/50">
        @if ($courses->isEmpty())
            <p class="p-8 text-center text-slate-400">Nenhum curso encontrado.</p>
        @else
            <table class="min-w-full divide-y divide-slate-600">
                <thead class="bg-slate-800/80">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase text-slate-400">Curso</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase text-slate-400">Nível</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase text-slate-400">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase text-slate-400">Módulos</th>
                        <th class="relative px-4 py-3"><span class="sr-only">Ações</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-600">
                    @foreach ($courses as $course)
                        <tr class="hover:bg-slate-700/30">
                            <td class="px-4 py-3">
                                <a href="{{ route('pastoral.academy.courses.show', $course) }}" class="font-medium text-indigo-400 hover:text-indigo-300">{{ $course->title }}</a>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-300">{{ $course->level }}</td>
                            <td class="px-4 py-3">
                                <span class="rounded-full px-2.5 py-0.5 text-xs font-medium {{ $course->status === 'published' ? 'bg-emerald-500/20 text-emerald-300' : 'bg-slate-600 text-slate-300' }}">{{ $course->status === 'published' ? 'Publicado' : 'Rascunho' }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-400">{{ $course->modules_count }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('pastoral.academy.courses.edit', $course) }}" class="text-sm text-slate-400 hover:text-slate-200">Editar</a>
                                <form action="{{ route('pastoral.academy.courses.destroy', $course) }}" method="POST" class="inline ml-2" onsubmit="return confirm('Remover este curso?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-400 hover:text-red-300">Remover</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if ($courses->hasPages())
                <div class="border-t border-slate-600 px-4 py-3">{{ $courses->links() }}</div>
            @endif
        @endif
    </div>
</div>
@endsection

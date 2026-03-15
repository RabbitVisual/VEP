@extends('memberpanel::components.layouts.master')

@section('title', 'Catálogo de Planos de Leitura')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-slate-950 transition-colors pb-12">

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            {{-- Cabeçalho: título + ícone padrão e link voltar --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
                <div class="flex items-center gap-3">
                    <a href="{{ route('painel.bible.plans.index') }}"
                        class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 transition-colors"
                        aria-label="Voltar para Meus Planos">
                        <x-icon name="arrow-left" style="duotone" class="size-5" />
                    </a>
                    <div class="flex items-center gap-3">
                        <div class="flex size-12 shrink-0 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400">
                            <x-icon name="books" style="duotone" class="size-6" />
                        </div>
                        <div>
                            <h1 class="text-xl font-black text-gray-900 dark:text-white tracking-tight">Catálogo de Planos</h1>
                            <p class="text-sm text-gray-500 dark:text-slate-400 mt-0.5">Escolha um plano e comece a ler a Bíblia.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Busca com ícone --}}
            <form method="get" action="{{ route('painel.bible.plans.catalog') }}" class="mb-8">
                <div class="relative flex items-center">
                    <span class="absolute left-4 flex size-9 items-center justify-center rounded-lg bg-gray-100 dark:bg-slate-800 text-gray-500 dark:text-slate-400 pointer-events-none">
                        <x-icon name="magnifying-glass" style="duotone" class="size-5" />
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Buscar por título ou descrição..."
                        class="w-full pl-14 pr-4 py-3 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <button type="submit"
                        class="absolute right-2 inline-flex items-center gap-2 px-4 py-2 text-sm font-bold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">
                        <x-icon name="magnifying-glass" style="duotone" class="size-4" />
                        Buscar
                    </button>
                </div>
            </form>

            {{-- Destaques --}}
            @if($featuredPlans->isNotEmpty())
                <section class="mb-10">
                    <h2 class="flex items-center gap-2 text-sm font-bold uppercase tracking-wider text-gray-500 dark:text-slate-400 mb-4">
                        <x-icon name="star" style="duotone" class="size-4" />
                        Destaques
                    </h2>
                    <div class="grid sm:grid-cols-3 gap-4">
                        @foreach($featuredPlans as $plan)
                            <article class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                                <div class="p-5">
                                    <div class="flex items-start gap-3">
                                        <div class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400">
                                            <x-icon name="book-bible" style="duotone" class="size-5" />
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <h3 class="font-bold text-gray-900 dark:text-white">{{ $plan->title }}</h3>
                                            <p class="text-sm text-gray-500 dark:text-slate-400 mt-1 line-clamp-2">{{ Str::limit($plan->description, 80) }}</p>
                                            <p class="flex items-center gap-1.5 text-xs text-gray-400 dark:text-slate-500 mt-2">
                                                <x-icon name="calendar-days" style="duotone" class="size-3.5" />
                                                {{ $plan->duration_days }} dias
                                            </p>
                                        </div>
                                    </div>
                                    <div class="mt-4 flex gap-2">
                                        <a href="{{ route('painel.bible.plans.preview', $plan->id) }}"
                                            class="flex-1 inline-flex items-center justify-center gap-2 py-2.5 text-sm font-bold text-indigo-600 dark:text-indigo-400 rounded-xl hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors">
                                            <x-icon name="eye" style="duotone" class="size-4" />
                                            Ver
                                        </a>
                                        <form action="{{ route('painel.bible.plans.subscribe', $plan->id) }}" method="post" class="flex-1">
                                            @csrf
                                            <button type="submit"
                                                class="w-full inline-flex items-center justify-center gap-2 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition-colors">
                                                <x-icon name="plus" style="duotone" class="size-4" />
                                                Inscrever
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- Todos os planos --}}
            <section>
                <h2 class="flex items-center gap-2 text-sm font-bold uppercase tracking-wider text-gray-500 dark:text-slate-400 mb-4">
                    <x-icon name="list" style="duotone" class="size-4" />
                    {{ request('search') ? 'Resultados da busca' : 'Todos os planos' }}
                </h2>

                @if($allPlans->isEmpty())
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 p-10 text-center">
                        <div class="flex justify-center mb-4">
                            <span class="flex size-14 items-center justify-center rounded-2xl bg-gray-100 dark:bg-slate-800 text-gray-400 dark:text-slate-500">
                                <x-icon name="books" style="duotone" class="size-7" />
                            </span>
                        </div>
                        <p class="text-gray-500 dark:text-slate-400">
                            @if(request('search'))
                                Nenhum plano encontrado para "{{ request('search') }}".
                            @else
                                Nenhum plano disponível no momento.
                            @endif
                        </p>
                        @if(request('search'))
                            <a href="{{ route('painel.bible.plans.catalog') }}"
                                class="mt-4 inline-flex items-center gap-2 text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                                <x-icon name="arrow-rotate-left" style="duotone" class="size-4" />
                                Limpar busca
                            </a>
                        @endif
                    </div>
                @else
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($allPlans as $plan)
                            <article class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                                <div class="p-5">
                                    <div class="flex items-start gap-3">
                                        <div class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400">
                                            <x-icon name="book-bible" style="duotone" class="size-5" />
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <h3 class="font-bold text-gray-900 dark:text-white">{{ $plan->title }}</h3>
                                            <p class="text-sm text-gray-500 dark:text-slate-400 mt-1 line-clamp-2">{{ Str::limit($plan->description, 80) }}</p>
                                            <p class="flex items-center gap-1.5 text-xs text-gray-400 dark:text-slate-500 mt-2">
                                                <x-icon name="calendar-days" style="duotone" class="size-3.5" />
                                                {{ $plan->duration_days }} dias
                                            </p>
                                        </div>
                                    </div>
                                    <div class="mt-4 flex gap-2">
                                        <a href="{{ route('painel.bible.plans.preview', $plan->id) }}"
                                            class="flex-1 inline-flex items-center justify-center gap-2 py-2.5 text-sm font-bold text-indigo-600 dark:text-indigo-400 rounded-xl hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors">
                                            <x-icon name="eye" style="duotone" class="size-4" />
                                            Ver
                                        </a>
                                        <form action="{{ route('painel.bible.plans.subscribe', $plan->id) }}" method="post" class="flex-1">
                                            @csrf
                                            <button type="submit"
                                                class="w-full inline-flex items-center justify-center gap-2 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition-colors">
                                                <x-icon name="plus" style="duotone" class="size-4" />
                                                Inscrever
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    @if($allPlans->hasPages())
                        <div class="mt-8 flex justify-center">
                            {{ $allPlans->withQueryString()->links() }}
                        </div>
                    @endif
                @endif
            </section>
        </div>
    </div>
@endsection

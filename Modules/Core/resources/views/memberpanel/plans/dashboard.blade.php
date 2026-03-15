@extends('memberpanel::components.layouts.master')

@section('title', 'Meus Planos de Leitura')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-slate-950 transition-colors pb-12">

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
                <div class="flex items-center gap-3">
                    <div class="flex size-12 shrink-0 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400">
                        <x-icon name="list-check" style="duotone" class="size-6" />
                    </div>
                    <div>
                        <h1 class="text-xl font-black text-gray-900 dark:text-white tracking-tight">Meus Planos de Leitura</h1>
                        <p class="text-sm text-gray-500 dark:text-slate-400 mt-0.5">Acompanhe seu progresso e continue de onde parou.</p>
                    </div>
                </div>
                <a href="{{ route('painel.bible.plans.catalog') }}"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-bold text-white bg-indigo-600 border border-transparent rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-500/20 transition-all">
                    <x-icon name="plus" style="duotone" class="size-4" />
                    Novo plano
                </a>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('info'))
                <div class="mb-6 p-4 rounded-xl bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-200 text-sm font-medium">
                    {{ session('info') }}
                </div>
            @endif

            @if($subscriptions->isEmpty())
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 p-10 text-center shadow-sm">
                    <div class="flex justify-center mb-4">
                        <span class="flex size-14 items-center justify-center rounded-2xl bg-gray-100 dark:bg-slate-800 text-gray-400 dark:text-slate-500">
                            <x-icon name="books" style="duotone" class="size-7" />
                        </span>
                    </div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Nenhum plano ativo</h2>
                    <p class="text-gray-500 dark:text-slate-400 text-sm max-w-sm mx-auto mb-6">
                        Escolha um plano de leitura da Bíblia e comece a acompanhar seu progresso dia a dia.
                    </p>
                    <a href="{{ route('painel.bible.plans.catalog') }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition-colors">
                        <x-icon name="books" style="duotone" class="size-4" />
                        Ver catálogo de planos
                    </a>
                </div>
            @else
                <ul class="space-y-4">
                    @foreach($subscriptions as $sub)
                        <li class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                            <div class="p-5 sm:p-6">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-base font-bold text-gray-900 dark:text-white truncate">{{ $sub->plan->title ?? 'Plano' }}</h3>
                                        <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">
                                            {{ $sub->percent ?? 0 }}% concluído
                                            · {{ $sub->total_days ?? 0 }} dias
                                        </p>
                                        <div class="mt-3 h-2 bg-gray-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                            <div class="h-full bg-indigo-600 rounded-full transition-all" style="width: {{ min(100, $sub->percent ?? 0) }}%"></div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        <a href="{{ route('painel.bible.reader', ['subscriptionId' => $sub->id, 'day' => $sub->current_day_number ?? 1]) }}"
                                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/30 rounded-xl hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition-colors">
                                            <x-icon name="book-open" style="duotone" class="size-4" />
                                            Continuar
                                        </a>
                                        @if(!empty($sub->offer_recalculate))
                                            <form action="{{ route('painel.bible.plans.recalculate', $sub->id) }}" method="post" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center gap-2 px-3 py-2 text-xs font-bold text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/30 rounded-lg hover:bg-amber-100 dark:hover:bg-amber-900/50 transition-colors">
                                                    <x-icon name="arrow-rotate-right" style="duotone" class="size-3.5" />
                                                    Recalcular
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('painel.bible.plans.pdf', $sub->id) }}"
                                            class="inline-flex items-center justify-center size-9 text-gray-500 hover:text-gray-700 dark:text-slate-400 dark:hover:text-white rounded-lg hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors"
                                            title="Baixar PDF">
                                            <x-icon name="arrow-down-tray" style="duotone" class="size-5" />
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <div class="mt-6 text-center">
                    <a href="{{ route('painel.bible.plans.catalog') }}" class="inline-flex items-center gap-2 text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                        <x-icon name="plus" style="duotone" class="size-4" />
                        Adicionar outro plano
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection

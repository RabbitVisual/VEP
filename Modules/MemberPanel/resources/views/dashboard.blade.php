@extends('memberpanel::components.layouts.master')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
@php
    $user = auth()->user();
    $firstName = $user->first_name ?? explode(' ', $user->name ?? '')[0] ?? 'Membro';
@endphp
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-200 pb-12">
    <div class="max-w-7xl mx-auto space-y-8 px-4 sm:px-6 pt-6">

        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Visão Geral</h1>
                <p class="text-gray-500 dark:text-slate-400 mt-1 max-w-md">Bem-vindo ao seu painel. Acompanhe seus planos de leitura, escalas e sermões.</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="px-4 py-2 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl shadow-sm flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                    <span class="text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider">Sistema Online</span>
                </div>
            </div>
        </div>

        <div class="relative overflow-hidden bg-white dark:bg-slate-900 rounded-3xl shadow-xl dark:shadow-2xl border border-gray-100 dark:border-slate-800 transition-colors duration-200">
            <div class="absolute inset-0 opacity-20 dark:opacity-40 pointer-events-none">
                <div class="absolute -top-24 -left-20 w-96 h-96 bg-blue-400 dark:bg-blue-600 rounded-full blur-[100px]"></div>
                <div class="absolute top-1/2 -right-20 w-80 h-80 bg-purple-400 dark:bg-purple-600 rounded-full blur-[100px]"></div>
                <div class="absolute bottom-0 left-1/2 w-64 h-64 bg-indigo-300 dark:bg-indigo-500 rounded-full blur-[80px]"></div>
            </div>
            <div class="relative px-6 sm:px-8 py-8 flex flex-col md:flex-row items-center gap-8 z-10">
                <div class="relative group shrink-0">
                    <div class="w-24 h-24 rounded-full p-[3px] bg-gradient-to-br from-blue-500 via-purple-500 to-indigo-500 shadow-xl shadow-blue-500/20">
                        <div class="w-full h-full rounded-full overflow-hidden border-4 border-white dark:border-slate-900 bg-gray-100 dark:bg-slate-800">
                            @if ($user->avatar_url ?? null)
                                <img src="{{ $user->avatar_url }}" alt="" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-3xl font-black text-gray-300 dark:text-slate-600 bg-gray-50 dark:bg-slate-900">
                                    {{ strtoupper(substr($firstName, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex-1 text-center md:text-left space-y-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-900/30 border border-blue-100 dark:border-blue-800 mb-2">
                        <x-icon name="sparkles" class="w-3 h-3 text-blue-600 dark:text-blue-400" />
                        <span class="text-[10px] font-black uppercase tracking-widest text-blue-600 dark:text-blue-400">Painel do Membro</span>
                    </div>
                    <h2 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">Olá, {{ $firstName }}!</h2>
                    <p class="text-gray-500 dark:text-slate-300 font-medium max-w-xl">Acompanhe sua jornada de estudo, escalas e novidades.</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <div class="xl:col-span-2 space-y-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="group relative bg-white dark:bg-slate-900 rounded-3xl p-6 border border-gray-100 dark:border-slate-800 shadow-sm hover:shadow-xl hover:shadow-blue-500/5 transition-all duration-300">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-slate-500 mb-1">Progresso de estudo</p>
                                <h3 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">
                                    @if ($subscriptions->isNotEmpty())
                                        {{ $subscriptions->first()->percent ?? 0 }}%
                                    @else
                                        —
                                    @endif
                                </h3>
                            </div>
                            <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 dark:text-blue-400">
                                <x-icon name="book-sparkles" class="w-6 h-6" />
                            </div>
                        </div>
                        @if ($subscriptions->isNotEmpty())
                            @php $first = $subscriptions->first(); @endphp
                            <div class="relative h-2 w-full bg-gray-100 dark:bg-slate-800 rounded-full overflow-hidden mb-3">
                                <div class="absolute h-full bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full transition-all duration-1000" style="width: {{ min($first->percent ?? 0, 100) }}%"></div>
                            </div>
                            <a href="{{ route('painel.bible.reader', ['subscriptionId' => $first->id, 'day' => $first->current_day_number ?? 1]) }}"
                                class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline">
                                Continuar leitura <x-icon name="arrow-right" class="w-3.5 h-3.5" />
                            </a>
                        @else
                            <p class="text-xs text-gray-500 dark:text-slate-400">Nenhum plano ativo.</p>
                            <a href="{{ route('painel.bible.plans.catalog') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline mt-1">Ver catálogo</a>
                        @endif
                    </div>

                    <div class="group relative bg-white dark:bg-slate-900 rounded-3xl p-6 border border-gray-100 dark:border-slate-800 shadow-sm hover:shadow-xl hover:shadow-amber-500/5 transition-all duration-300">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-slate-500 mb-1">Próxima escala</p>
                                <h3 class="text-lg font-black text-gray-900 dark:text-white tracking-tight truncate">
                                    @if ($nextSchedule && $nextSchedule->ministrySchedule)
                                        {{ $nextSchedule->ministrySchedule->activity_name ?? 'Escala' }}
                                    @else
                                        Nenhuma
                                    @endif
                                </h3>
                            </div>
                            <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-900/20 flex items-center justify-center text-amber-600 dark:text-amber-400">
                                <x-icon name="calendar-days" class="w-6 h-6" />
                            </div>
                        </div>
                        @if ($nextSchedule && $nextSchedule->ministrySchedule)
                            @php $schedule = $nextSchedule->ministrySchedule; $ministry = $schedule->ministry ?? null; @endphp
                            <p class="text-xs text-gray-500 dark:text-slate-400">{{ $schedule->scheduled_at?->format('d/m/Y H:i') }}</p>
                            @if ($ministry)
                                <a href="{{ route('painel.ministries.dashboard', $ministry) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-amber-600 dark:text-amber-400 hover:underline mt-1">{{ $ministry->name }}</a>
                            @endif
                        @else
                            <p class="text-xs text-gray-500 dark:text-slate-400">Nenhuma escala próxima.</p>
                            <a href="{{ route('ministry.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-gray-600 dark:text-gray-400 hover:underline mt-1">Ver ministérios</a>
                        @endif
                    </div>

                    <div class="group relative bg-white dark:bg-slate-900 rounded-3xl p-6 border border-gray-100 dark:border-slate-800 shadow-sm hover:shadow-xl hover:shadow-emerald-500/5 transition-all duration-300">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-slate-500 mb-1">Sermões recentes</p>
                                <h3 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">{{ $latestSermons->count() }}</h3>
                            </div>
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                                <x-icon name="podium" class="w-6 h-6" />
                            </div>
                        </div>
                        @if ($latestSermons->isNotEmpty())
                            <ul class="space-y-1.5">
                                @foreach ($latestSermons->take(2) as $sermon)
                                    <li><a href="{{ route('painel.sermons.show', $sermon) }}" class="text-xs font-medium text-gray-700 dark:text-slate-300 hover:text-purple-600 dark:hover:text-purple-400 truncate block">{{ $sermon->title }}</a></li>
                                @endforeach
                            </ul>
                            <a href="{{ route('painel.sermons.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:underline mt-2">Ver todos</a>
                        @else
                            <p class="text-xs text-gray-500 dark:text-slate-400">Nenhum sermão recente.</p>
                            <a href="{{ route('painel.sermons.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-gray-600 dark:text-gray-400 hover:underline mt-1">Explorar sermões</a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="space-y-8">
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-800 flex items-center gap-3">
                        <x-icon name="bolt" class="w-4 h-4 text-gray-400 dark:text-slate-500" />
                        <h3 class="text-xs font-black uppercase tracking-widest text-gray-500 dark:text-slate-400">Acesso Rápido</h3>
                    </div>
                    <div class="p-4 grid grid-cols-2 gap-3">
                        <a href="{{ route('painel.profile.show') }}" class="flex flex-col items-center justify-center gap-2 p-4 rounded-2xl bg-blue-50 dark:bg-blue-900/10 hover:bg-blue-100 dark:hover:bg-blue-900/20 border border-blue-100 dark:border-blue-900/20 transition-all text-center">
                            <div class="w-10 h-10 rounded-full bg-white dark:bg-blue-900/30 flex items-center justify-center shadow-sm"><x-icon name="user" class="w-5 h-5 text-blue-600 dark:text-blue-400" /></div>
                            <span class="text-xs font-bold text-gray-900 dark:text-white">Meu Perfil</span>
                        </a>
                        <a href="{{ route('painel.bible.read') }}" class="flex flex-col items-center justify-center gap-2 p-4 rounded-2xl bg-purple-50 dark:bg-purple-900/10 hover:bg-purple-100 dark:hover:bg-purple-900/20 border border-purple-100 dark:border-purple-900/20 transition-all text-center">
                            <div class="w-10 h-10 rounded-full bg-white dark:bg-purple-900/30 flex items-center justify-center shadow-sm"><x-icon name="book-bible" class="w-5 h-5 text-purple-600 dark:text-purple-400" /></div>
                            <span class="text-xs font-bold text-gray-900 dark:text-white">Bíblia</span>
                        </a>
                        <a href="{{ route('painel.bible.plans.index') }}" class="flex flex-col items-center justify-center gap-2 p-4 rounded-2xl bg-indigo-50 dark:bg-indigo-900/10 hover:bg-indigo-100 dark:hover:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-900/20 transition-all text-center">
                            <div class="w-10 h-10 rounded-full bg-white dark:bg-indigo-900/30 flex items-center justify-center shadow-sm"><x-icon name="list-check" class="w-5 h-5 text-indigo-600 dark:text-indigo-400" /></div>
                            <span class="text-xs font-bold text-gray-900 dark:text-white">Planos</span>
                        </a>
                        <a href="{{ route('painel.verse-explainer') }}" class="flex flex-col items-center justify-center gap-2 p-4 rounded-2xl bg-amber-50 dark:bg-amber-900/10 hover:bg-amber-100 dark:hover:bg-amber-900/20 border border-amber-100 dark:border-amber-900/20 transition-all text-center">
                            <div class="w-10 h-10 rounded-full bg-white dark:bg-amber-900/30 flex items-center justify-center shadow-sm"><x-icon name="brain-circuit" class="w-5 h-5 text-amber-600 dark:text-amber-400" /></div>
                            <span class="text-xs font-bold text-gray-900 dark:text-white">Verse Explainer</span>
                        </a>
                        <a href="{{ route('painel.sermons.index') }}" class="flex flex-col items-center justify-center gap-2 p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-900/10 hover:bg-emerald-100 dark:hover:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-900/20 transition-all text-center col-span-2">
                            <div class="w-10 h-10 rounded-full bg-white dark:bg-emerald-900/30 flex items-center justify-center shadow-sm"><x-icon name="podium" class="w-5 h-5 text-emerald-600 dark:text-emerald-400" /></div>
                            <span class="text-xs font-bold text-gray-900 dark:text-white">Sermões</span>
                        </a>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-3xl shadow-xl shadow-indigo-500/20 p-6 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm"><x-icon name="lightbulb" class="w-5 h-5 text-white" /></div>
                            <h3 class="font-black text-lg tracking-tight">Dica do dia</h3>
                        </div>
                        <p class="text-indigo-100 text-sm font-medium leading-relaxed opacity-90 mb-4">
                            Mantenha seu perfil atualizado e aproveite os planos de leitura e o Verse Explainer para aprofundar seus estudos.
                        </p>
                        <a href="{{ route('painel.profile.edit') }}" class="inline-flex items-center justify-center w-full py-3 bg-white text-indigo-600 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-indigo-50 transition-colors shadow-lg active:scale-95">
                            Editar perfil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

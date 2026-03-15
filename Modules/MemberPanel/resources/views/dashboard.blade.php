<x-memberpanel::layouts.master title="Dashboard">
    <div class="p-6 space-y-6">
        {{-- Header (mesmo padrão da página Ministérios) --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="space-y-1">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight flex items-center gap-2">
                    <x-icon name="grid-2" style="duotone" class="w-9 h-9 text-indigo-500" />
                    Dashboard
                </h1>
                <p class="text-gray-600 dark:text-gray-400">Visão geral do seu estudo, escalas e rede de sermões.</p>
            </div>
        </div>

        {{-- Grid: Progresso | Próxima escala | Feed (cards no estilo Ministérios) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {{-- Widget: Progresso de estudo --}}
            <div class="backdrop-blur-xl bg-white/70 dark:bg-white/10 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg transition-all duration-300">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-start gap-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl flex items-center justify-center bg-indigo-500/20">
                        <x-icon name="book-sparkles" style="duotone" class="w-6 h-6 text-indigo-600 dark:text-indigo-400" />
                    </div>
                    <div class="min-w-0 flex-1">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white truncate">Progresso de estudo</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Planos de leitura</p>
                    </div>
                </div>
                @if ($subscriptions->isNotEmpty())
                    @php $first = $subscriptions->first(); @endphp
                    <div class="px-6 py-4">
                        <div class="flex items-baseline justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">{{ $first->plan->title ?? 'Plano ativo' }}</span>
                            <span class="font-medium text-indigo-600 dark:text-indigo-400">{{ $first->percent ?? 0 }}%</span>
                        </div>
                        <div class="mt-2 h-2 overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
                            <div class="h-full rounded-full bg-indigo-500 transition-all"
                                 style="width: {{ min($first->percent ?? 0, 100) }}%"></div>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50/50 dark:bg-gray-800/30 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('painel.bible.reader', ['subscriptionId' => $first->id, 'day' => $first->current_day_number ?? 1]) }}"
                           class="inline-flex items-center gap-1.5 text-sm font-medium text-indigo-700 bg-indigo-100 rounded-lg hover:bg-indigo-200 dark:bg-indigo-900/30 dark:text-indigo-300 px-3 py-1.5">
                            Continuar leitura
                            <x-icon name="arrow-right" style="solid" class="w-4 h-4" />
                        </a>
                    </div>
                @else
                    <div class="px-6 py-4">
                        <p class="text-sm text-gray-600 dark:text-gray-400">Nenhum plano ativo.</p>
                    </div>
                    <div class="px-6 py-4 bg-gray-50/50 dark:bg-gray-800/30 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('painel.bible.plans.catalog') }}"
                           class="inline-flex items-center text-sm font-medium text-indigo-700 bg-indigo-100 rounded-lg hover:bg-indigo-200 dark:bg-indigo-900/30 dark:text-indigo-300 px-3 py-1.5">
                            <x-icon name="eye" style="duotone" class="w-4 h-4 mr-1.5" />
                            Ver catálogo
                        </a>
                    </div>
                @endif
            </div>

            {{-- Widget: Minha próxima escala --}}
            <div class="backdrop-blur-xl bg-white/70 dark:bg-white/10 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg transition-all duration-300">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-start gap-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl flex items-center justify-center bg-amber-500/20">
                        <x-icon name="calendar-days" style="duotone" class="w-6 h-6 text-amber-600 dark:text-amber-400" />
                    </div>
                    <div class="min-w-0 flex-1">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white truncate">Minha próxima escala</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Ministério</p>
                    </div>
                </div>
                @if ($nextSchedule && $nextSchedule->ministrySchedule)
                    @php $schedule = $nextSchedule->ministrySchedule; $ministry = $schedule->ministry; @endphp
                    <div class="px-6 py-4">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $schedule->activity_name }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $schedule->scheduled_at?->format('d/m/Y H:i') }}</p>
                        @if ($ministry)
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-500">{{ $ministry->name }}</p>
                        @endif
                    </div>
                    <div class="px-6 py-4 bg-gray-50/50 dark:bg-gray-800/30 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <a href="{{ $ministry ? route('painel.ministries.dashboard', $ministry) : route('ministry.index') }}"
                           class="inline-flex items-center gap-1.5 text-sm font-medium text-amber-700 bg-amber-100 rounded-lg hover:bg-amber-200 dark:bg-amber-900/30 dark:text-amber-300 px-3 py-1.5">
                            <x-icon name="eye" style="duotone" class="w-4 h-4" />
                            {{ $ministry?->name ?? 'Ver detalhes' }}
                        </a>
                    </div>
                @else
                    <div class="px-6 py-4">
                        <p class="text-sm text-gray-600 dark:text-gray-400">Nenhuma escala próxima.</p>
                    </div>
                    <div class="px-6 py-4 bg-gray-50/50 dark:bg-gray-800/30 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('ministry.index') }}"
                           class="inline-flex items-center text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 px-3 py-1.5">
                            <x-icon name="users-viewfinder" style="duotone" class="w-4 h-4 mr-1.5" />
                            Ver ministérios
                        </a>
                    </div>
                @endif
            </div>

            {{-- Widget: Feed rápido (últimos sermões) --}}
            <div class="backdrop-blur-xl bg-white/70 dark:bg-white/10 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg transition-all duration-300">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-start gap-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl flex items-center justify-center bg-emerald-500/20">
                        <x-icon name="podium" style="duotone" class="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
                    </div>
                    <div class="min-w-0 flex-1">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white truncate">Feed rápido</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Sermões recentes</p>
                    </div>
                </div>
                @if ($latestSermons->isNotEmpty())
                    <div class="px-6 py-3">
                        <ul class="space-y-2">
                            @foreach ($latestSermons as $sermon)
                                <li>
                                    <a href="{{ route('painel.sermons.show', $sermon) }}"
                                       class="block rounded-lg p-2 text-sm text-gray-700 dark:text-gray-300 transition-colors hover:bg-gray-100 dark:hover:bg-gray-800/60">
                                        <span class="font-medium line-clamp-1">{{ $sermon->title }}</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-500">{{ $sermon->published_at?->format('d/m') }} · {{ $sermon->user->name ?? '' }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="px-6 py-4 bg-gray-50/50 dark:bg-gray-800/30 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('painel.sermons.index') }}"
                           class="inline-flex items-center text-sm font-medium text-emerald-700 bg-emerald-100 rounded-lg hover:bg-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-300 px-3 py-1.5">
                            <x-icon name="eye" style="duotone" class="w-4 h-4 mr-1.5" />
                            Ver todos
                        </a>
                    </div>
                @else
                    <div class="px-6 py-4">
                        <p class="text-sm text-gray-600 dark:text-gray-400">Nenhum sermão recente.</p>
                    </div>
                    <div class="px-6 py-4 bg-gray-50/50 dark:bg-gray-800/30 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('painel.sermons.index') }}"
                           class="inline-flex items-center text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 px-3 py-1.5">
                            <x-icon name="podium" style="duotone" class="w-4 h-4 mr-1.5" />
                            Explorar sermões
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Ações rápidas (botões no estilo Ministérios: Ver / Editar) --}}
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('painel.bible.plans.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300">
                <x-icon name="book-sparkles" style="duotone" class="w-4 h-4" />
                Planos de leitura
            </a>
            <a href="{{ route('painel.verse-explainer') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300">
                <x-icon name="brain-circuit" style="duotone" class="w-4 h-4" />
                Verse Explainer
            </a>
            <a href="{{ route('painel.sermons.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-indigo-700 bg-indigo-100 rounded-lg hover:bg-indigo-200 dark:bg-indigo-900/30 dark:text-indigo-300">
                <x-icon name="podium" style="duotone" class="w-4 h-4" />
                Sermões
            </a>
        </div>
    </div>
</x-memberpanel::layouts.master>

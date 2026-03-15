<x-pastoralpanel::layouts.master title="Dashboard">
    <div class="space-y-8">
            <div>
                <h2 class="text-lg font-semibold text-slate-100">Visão geral</h2>
                <p class="mt-1 text-sm text-slate-400">Métricas e ações rápidas da área pastoral.</p>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <x-pastoralpanel::stats-card
                    title="Membros ativos"
                    :value="$activeMembers"
                    icon="users"
                />
                <x-pastoralpanel::stats-card
                    title="Sermões em rascunho"
                    :value="$draftSermons"
                    icon="book"
                    :href="Route::has('pastor.sermoes.sermons.index') ? route('pastor.sermoes.sermons.index', ['status' => 'draft']) : null"
                />
                <x-pastoralpanel::stats-card
                    title="Escalas desta semana"
                    :value="$schedulesCount"
                    icon="calendar-days"
                    :href="route('ministry.index')"
                />
                <x-pastoralpanel::stats-card
                    title="EAD – planos concluídos"
                    :value="$eadEngagement"
                    icon="graduation-cap"
                />
            </div>

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                {{-- Próximos cultos / escalas --}}
                <section class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/50 p-5 shadow-sm" aria-labelledby="schedules-heading">
                    <h3 id="schedules-heading" class="text-base font-semibold text-slate-900 dark:text-slate-100">Próximos cultos e escalas</h3>
                    @if ($upcomingSchedules->isEmpty())
                        <x-pastoralpanel::empty-state
                            message="Nenhuma escala nos próximos 7 dias."
                            :action-url="route('ministry.index')"
                            action-label="Ver ministérios"
                        />
                    @else
                        <ul class="mt-4 divide-y divide-slate-200 dark:divide-slate-600" role="list">
                            @foreach ($upcomingSchedules as $schedule)
                                <li class="flex items-center justify-between gap-4 py-3 first:pt-0 last:pb-0">
                                    <div class="min-w-0 flex-1">
                                        <p class="font-medium text-slate-900 dark:text-slate-100">{{ $schedule->activity_name }}</p>
                                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ $schedule->ministry->name ?? '' }} · {{ $schedule->scheduled_at?->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <a href="{{ route('ministry.schedules.show', [$schedule->ministry, $schedule]) }}" class="shrink-0 text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">Ver</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </section>

                {{-- Sermões recentes --}}
                <section class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/50 p-5 shadow-sm" aria-labelledby="sermons-heading">
                    <h3 id="sermons-heading" class="text-base font-semibold text-slate-900 dark:text-slate-100">Sermões recentes</h3>
                    @if ($recentSermons->isEmpty())
                        <x-pastoralpanel::empty-state
                            message="Nenhum sermão ainda."
                            :action-url="Route::has('pastor.sermoes.sermons.create') ? route('pastor.sermoes.sermons.create') : null"
                            action-label="Novo sermão"
                        />
                    @else
                        <ul class="mt-4 divide-y divide-slate-200 dark:divide-slate-600" role="list">
                            @foreach ($recentSermons as $sermon)
                                <li class="flex items-center justify-between gap-4 py-3 first:pt-0 last:pb-0">
                                    <div class="min-w-0 flex-1">
                                        <p class="font-medium text-slate-900 dark:text-slate-100">{{ $sermon->title }}</p>
                                        <p class="text-sm text-slate-500 dark:text-slate-400">
                                            {{ $sermon->updated_at?->format('d/m/Y') }}
                                            @if ($sermon->category)
                                                · {{ $sermon->category->name }}
                                            @endif
                                        </p>
                                    </div>
                                    @if (Route::has('pastor.sermoes.sermons.edit'))
                                        <a href="{{ route('pastor.sermoes.sermons.edit', $sermon) }}" class="shrink-0 text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">Editar</a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </section>
            </div>
        </div>
</x-pastoralpanel::layouts.master>

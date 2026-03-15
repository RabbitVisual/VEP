<x-pastoralpanel::layouts.master title="Perfil – {{ $member->name }}">
    <div class="space-y-8">
            @if (session('success'))
                <div class="rounded-lg bg-emerald-50 dark:bg-emerald-900/20 px-4 py-3 text-sm text-emerald-800 dark:text-emerald-200" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Header --}}
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-4">
                    <div class="flex size-16 shrink-0 items-center justify-center rounded-full bg-slate-700 text-slate-200 dark:bg-slate-600 dark:text-slate-300">
                        <x-icon name="user" style="duotone" class="size-8" />
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-slate-100">{{ $member->name }}</h2>
                        <p class="text-sm text-slate-400">{{ $member->email }}</p>
                        @if ($member->phone)
                            <p class="text-sm text-slate-400">{{ $member->phone }}</p>
                        @endif
                    </div>
                </div>
                <a href="{{ route('pastoral.members.index') }}" class="inline-flex items-center rounded-lg border border-slate-600 px-4 py-2 text-sm font-medium text-slate-300 hover:bg-slate-800">
                    Voltar à lista
                </a>
            </div>

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                {{-- Progresso no plano de leitura --}}
                <section class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/50 p-5 shadow-sm" aria-labelledby="reading-heading">
                    <h3 id="reading-heading" class="text-base font-semibold text-slate-900 dark:text-slate-100">Plano de leitura bíblica</h3>
                    @if (empty($readingProgress['subscriptions']) && ($readingProgress['completed_count'] ?? 0) === 0)
                        <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">Nenhum plano de leitura ativo ou concluído.</p>
                    @else
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $readingProgress['completed_count'] ?? 0 }} plano(s) concluído(s).</p>
                        <ul class="mt-4 space-y-3" role="list">
                            @foreach ($readingProgress['subscriptions'] ?? [] as $sub)
                                <li class="flex items-center justify-between gap-4 rounded-lg border border-slate-200 dark:border-slate-600 p-3">
                                    <div class="min-w-0 flex-1">
                                        <p class="font-medium text-slate-900 dark:text-slate-100">{{ $sub['plan']->title ?? 'Plano' }}</p>
                                        <div class="mt-1 h-2 w-full overflow-hidden rounded-full bg-slate-200 dark:bg-slate-600">
                                            <div class="h-full rounded-full bg-indigo-600 transition-all duration-300" style="width: {{ $sub['percent'] }}%"></div>
                                        </div>
                                        <p class="mt-1 text-xs text-slate-500">{{ $sub['completed'] }}/{{ $sub['total_days'] }} dias · {{ $sub['percent'] }}%</p>
                                    </div>
                                    @if ($sub['is_completed'] ?? false)
                                        <span class="shrink-0 rounded-full bg-emerald-100 dark:bg-emerald-900/40 px-2.5 py-0.5 text-xs font-medium text-emerald-800 dark:text-emerald-300">Concluído</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </section>

                {{-- Ministérios e escalas --}}
                <section class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/50 p-5 shadow-sm" aria-labelledby="ministries-heading">
                    <h3 id="ministries-heading" class="text-base font-semibold text-slate-900 dark:text-slate-100">Ministérios e escalas</h3>
                    @if ($member->ministryMemberships->isEmpty())
                        <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">Não participa de nenhum ministério.</p>
                    @else
                        <ul class="mt-4 space-y-2" role="list">
                            @foreach ($member->ministryMemberships as $mm)
                                <li class="text-sm text-slate-700 dark:text-slate-300">{{ $mm->ministry->name ?? '—' }} <span class="text-slate-500">({{ $mm->role }})</span></li>
                            @endforeach
                        </ul>
                        @if ($scheduleAssignments->isNotEmpty())
                            <h4 class="mt-4 text-sm font-medium text-slate-700 dark:text-slate-300">Histórico recente de escalas</h4>
                            <ul class="mt-2 space-y-1 text-sm text-slate-600 dark:text-slate-400" role="list">
                                @foreach ($scheduleAssignments as $a)
                                    <li>{{ $a->ministrySchedule->activity_name ?? '—' }} · {{ $a->ministrySchedule->scheduled_at?->format('d/m/Y') }} · {{ $a->ministrySchedule->ministry->name ?? '' }}</li>
                                @endforeach
                            </ul>
                        @endif
                    @endif
                </section>
            </div>

            {{-- Anotações pastorais --}}
            <section class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/50 p-5 shadow-sm" aria-labelledby="notes-heading">
                <h3 id="notes-heading" class="text-base font-semibold text-slate-900 dark:text-slate-100">Anotações pastorais</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Privadas e apenas para uso no aconselhamento.</p>

                <form action="{{ route('pastoral.members.notes.store', $member) }}" method="POST" class="mt-4">
                    @csrf
                    <label for="note-content" class="sr-only">Nova anotação</label>
                    <textarea id="note-content"
                              name="content"
                              rows="3"
                              required
                              maxlength="10000"
                              placeholder="Registre uma anotação sobre aconselhamento..."
                              class="block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                              aria-label="Conteúdo da anotação"></textarea>
                    @error('content')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    <button type="submit" class="mt-2 inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition-all duration-300 hover:bg-indigo-700">
                        Adicionar anotação
                    </button>
                </form>

                @if ($pastoralNotes->isEmpty())
                    <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">Nenhuma anotação ainda.</p>
                @else
                    <ul class="mt-4 divide-y divide-slate-200 dark:divide-slate-600" role="list">
                        @foreach ($pastoralNotes as $note)
                            <li class="py-4 first:pt-0">
                                <p class="text-sm text-slate-700 dark:text-slate-300 whitespace-pre-wrap">{{ $note->content }}</p>
                                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $note->created_at->format('d/m/Y H:i') }} · {{ $note->author->name ?? '—' }}</p>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>
        </div>
</x-pastoralpanel::layouts.master>

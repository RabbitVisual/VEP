<x-pastoralpanel::layouts.master title="Gestão do Rebanho">
    <div class="space-y-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-100">Gestão do Rebanho</h2>
                    <p class="mt-1 text-sm text-slate-400">Membros e ovelhas sob seu cuidado.</p>
                </div>
            </div>

            <form method="GET" action="{{ route('pastoral.members.index') }}" class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/50 p-4 shadow-sm" role="search" aria-label="Filtrar membros">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                    <div class="md:col-span-2">
                        <label for="search" class="sr-only">Buscar</label>
                        <input type="search"
                               id="search"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Nome ou e-mail..."
                               class="block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               aria-label="Buscar por nome ou e-mail">
                    </div>
                    <div>
                        <label for="ministry_id" class="sr-only">Ministério</label>
                        <select id="ministry_id"
                                name="ministry_id"
                                class="block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                aria-label="Filtrar por ministério">
                            <option value="">Todos os ministérios</option>
                            @foreach ($ministries as $m)
                                <option value="{{ $m->id }}" @selected(request('ministry_id') == $m->id)>{{ $m->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="status" class="sr-only">Status</label>
                        <select id="status"
                                name="status"
                                class="block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                aria-label="Filtrar por status">
                            <option value="">Todos</option>
                            <option value="active" @selected(request('status') === 'active')>Ativos</option>
                            <option value="inactive" @selected(request('status') === 'inactive')>Inativos</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4 flex flex-wrap gap-2">
                    <button type="submit" class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition-all duration-300 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-slate-900">
                        Filtrar
                    </button>
                    <a href="{{ route('pastoral.members.index') }}" class="inline-flex items-center rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 transition-colors hover:bg-slate-50 dark:hover:bg-slate-700">
                        Limpar
                    </a>
                </div>
            </form>

            <div class="overflow-hidden rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/50 shadow-sm">
                @if ($members->isEmpty())
                    <x-pastoralpanel::empty-state message="Nenhum membro encontrado." />
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-600" role="table" aria-label="Lista de membros">
                            <thead class="bg-slate-50 dark:bg-slate-800/80">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Nome</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">E-mail</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Ministérios</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Status</th>
                                    <th scope="col" class="relative px-4 py-3"><span class="sr-only">Ações</span></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-600 bg-white dark:bg-slate-800/30">
                                @foreach ($members as $member)
                                    <tr class="transition-colors hover:bg-slate-50 dark:hover:bg-slate-700/30">
                                        <td class="whitespace-nowrap px-4 py-3 text-sm font-medium text-slate-900 dark:text-slate-100">{{ $member->name }}</td>
                                        <td class="whitespace-nowrap px-4 py-3 text-sm text-slate-500 dark:text-slate-400">{{ $member->email }}</td>
                                        <td class="px-4 py-3 text-sm text-slate-500 dark:text-slate-400">{{ $member->ministry_memberships_count ?? 0 }}</td>
                                        <td class="whitespace-nowrap px-4 py-3">
                                            @if ($member->is_active)
                                                <span class="inline-flex rounded-full bg-emerald-100 dark:bg-emerald-900/40 px-2.5 py-0.5 text-xs font-medium text-emerald-800 dark:text-emerald-300">Ativo</span>
                                            @else
                                                <span class="inline-flex rounded-full bg-slate-100 dark:bg-slate-700 px-2.5 py-0.5 text-xs font-medium text-slate-600 dark:text-slate-400">Inativo</span>
                                            @endif
                                        </td>
                                        <td class="relative whitespace-nowrap px-4 py-3 text-right text-sm font-medium">
                                            <a href="{{ route('pastoral.members.show', $member) }}" class="text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">Ver perfil</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if ($members->hasPages())
                        <div class="border-t border-slate-200 dark:border-slate-600 px-4 py-3">
                            {{ $members->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
</x-pastoralpanel::layouts.master>

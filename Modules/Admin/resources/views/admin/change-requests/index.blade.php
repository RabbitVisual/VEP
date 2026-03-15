@extends('admin::components.layouts.master')

@section('title', 'Solicitações de alteração de dados sensíveis')

@section('content')
    <div class="p-6 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="space-y-1">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight flex items-center gap-2">
                    <x-icon name="clipboard-list" style="duotone" class="w-9 h-9 text-indigo-500" />
                    Gerenciador de alterações
                </h1>
                <p class="text-gray-600 dark:text-gray-400">Aprove ou recuse solicitações de membros/pastores para alterar CPF, e-mail ou telefone. Cada campo pode ser solicitado apenas uma vez por usuário.</p>
            </div>
            <a href="{{ route('admin.profile.show') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800">Meu perfil</a>
        </div>

        @if (session('success'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 dark:border-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-200" role="alert">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800 dark:border-red-800 dark:bg-red-900/20 dark:text-red-200" role="alert">{{ session('error') }}</div>
        @endif

        {{-- Pendentes --}}
        <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800/50 overflow-hidden">
            <div class="border-b border-gray-200 dark:border-gray-700 p-4">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Pendentes de aprovação</h2>
            </div>
            @if ($pending->isEmpty())
                <div class="p-8 text-center text-gray-500 dark:text-gray-400">Nenhuma solicitação pendente.</div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Solicitante</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Campo</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Valor atual</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Valor solicitado</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Data</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($pending as $req)
                                @php $current = $req->user->{$req->field_name} ?? '—'; @endphp
                                <tr class="bg-white dark:bg-gray-900/50">
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $req->user->name ?? $req->user->email }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ \App\Models\SensitiveFieldChangeRequest::getFieldLabel($req->field_name) }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $current ?: '—' }}</td>
                                    <td class="px-4 py-3 text-sm font-medium text-indigo-600 dark:text-indigo-400">{{ $req->requested_value }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $req->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <form action="{{ route('admin.change-requests.approve', $req) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="rounded-lg bg-emerald-600 px-2 py-1 text-xs font-medium text-white hover:bg-emerald-700">Aprovar</button>
                                        </form>
                                        <button type="button" onclick="document.getElementById('reject-{{ $req->id }}').showModal()" class="rounded-lg bg-red-600 px-2 py-1 text-xs font-medium text-white hover:bg-red-700 ml-1">Recusar</button>
                                    </td>
                                </tr>
                                <dialog id="reject-{{ $req->id }}" class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-6 shadow-xl backdrop:bg-black/50">
                                    <form action="{{ route('admin.change-requests.reject', $req) }}" method="POST">
                                        @csrf
                                        <p class="text-gray-900 dark:text-white mb-2">Recusar solicitação de {{ $req->user->name }} ({{ \App\Models\SensitiveFieldChangeRequest::getFieldLabel($req->field_name) }})?</p>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Motivo (opcional)</label>
                                        <textarea name="rejection_reason" rows="2" class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white mb-4"></textarea>
                                        <div class="flex justify-end gap-2">
                                            <button type="button" onclick="document.getElementById('reject-{{ $req->id }}').close()" class="rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300">Cancelar</button>
                                            <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">Recusar</button>
                                        </div>
                                    </form>
                                </dialog>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-2">{{ $pending->links() }}</div>
            @endif
        </div>

        {{-- Histórico (aprovados/recusados) --}}
        <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800/50 overflow-hidden">
            <div class="border-b border-gray-200 dark:border-gray-700 p-4">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Histórico (anterior → novo / recusado)</h2>
            </div>
            @if ($history->isEmpty())
                <div class="p-8 text-center text-gray-500 dark:text-gray-400">Nenhum registro ainda.</div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Usuário</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Campo</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Anterior</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Novo / Solicitado</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Data</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($history as $req)
                                <tr class="bg-white dark:bg-gray-900/50">
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $req->user->name ?? $req->user->email }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ \App\Models\SensitiveFieldChangeRequest::getFieldLabel($req->field_name) }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $req->previous_value ?: '—' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $req->requested_value }}</td>
                                    <td class="px-4 py-3">
                                        @if ($req->status === 'approved')
                                            <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200">Aprovado</span>
                                        @else
                                            <span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900/30 dark:text-red-200">Recusado</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $req->reviewed_at?->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection

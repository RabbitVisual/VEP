@extends('memberpanel::components.layouts.master')

@section('title', 'Meu Perfil')
@section('page-title', 'Meu Perfil')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-200 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 pt-4 sm:pt-6 space-y-6 sm:space-y-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs text-gray-500 dark:text-slate-400 mb-2" aria-label="Breadcrumb">
                    <a href="{{ route('painel.dashboard') }}" class="hover:text-purple-600 dark:hover:text-purple-400 transition-colors">Painel</a>
                    <x-icon name="chevron-right" class="w-3 h-3 shrink-0" />
                    <span class="text-gray-900 dark:text-white font-medium">Meu Perfil</span>
                </nav>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Meu Perfil</h1>
                <p class="text-gray-500 dark:text-slate-400 mt-1 text-sm max-w-md">Visualize e edite seus dados. Alterações em CPF, e-mail e telefone requerem aprovação do administrador.</p>
            </div>
            <a href="{{ route('painel.profile.edit') }}"
                class="inline-flex items-center gap-2 px-4 sm:px-5 py-2.5 bg-purple-600 dark:bg-purple-500 text-white rounded-xl font-bold hover:bg-purple-700 dark:hover:bg-purple-600 transition-all shadow-lg shadow-purple-500/20 active:scale-[0.98] shrink-0">
                <x-icon name="pen-to-square" class="w-5 h-5" />
                Editar Cadastro
            </a>
        </div>

        <div class="relative overflow-hidden bg-white dark:bg-slate-900 rounded-3xl shadow-xl dark:shadow-2xl border border-gray-100 dark:border-slate-800 transition-colors duration-200">
            <div class="absolute inset-0 opacity-20 dark:opacity-40 pointer-events-none">
                <div class="absolute -top-24 -left-20 w-96 h-96 bg-blue-400 dark:bg-blue-600 rounded-full blur-[100px]"></div>
                <div class="absolute top-1/2 -right-20 w-80 h-80 bg-purple-400 dark:bg-purple-600 rounded-full blur-[100px]"></div>
            </div>
            <div class="relative px-4 sm:px-6 md:px-8 py-8 flex flex-col lg:flex-row items-center gap-8 z-10">
                <div class="shrink-0">
                    <div class="w-28 h-28 sm:w-36 sm:h-36 rounded-full overflow-hidden border-4 border-white dark:border-slate-800 shadow-xl bg-gray-100 dark:bg-slate-800">
                        @if ($user->avatar_url ?? null)
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-4xl font-black text-gray-300 dark:text-slate-600 bg-gray-50 dark:bg-slate-900">
                                {{ strtoupper(substr($user->first_name ?? $user->name ?? 'U', 0, 1)) }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="flex-1 text-center lg:text-left space-y-2">
                    <h2 class="text-3xl font-black text-gray-900 dark:text-white leading-none">{{ $user->name }}</h2>
                    <p class="text-purple-600 dark:text-purple-400 font-medium">{{ $user->email }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="p-4 sm:p-6 border-b border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/50">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-xl">
                            <x-icon name="user" class="w-5 h-5" />
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-white">Dados pessoais</h3>
                            <p class="text-xs text-gray-500 dark:text-slate-400">Você pode alterar estes campos em &quot;Editar perfil&quot;.</p>
                        </div>
                    </div>
                </div>
                <dl class="p-6 space-y-4">
                    <div>
                        <dt class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-slate-500 mb-0.5">Nome</dt>
                        <dd class="text-gray-900 dark:text-white font-medium">{{ $user->first_name }} {{ $user->last_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-slate-500 mb-0.5">Data de nascimento</dt>
                        <dd class="text-gray-900 dark:text-white font-medium">{{ $user->birth_date?->format('d/m/Y') ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-slate-500 mb-0.5">Igreja / Ministério</dt>
                        <dd class="text-gray-900 dark:text-white font-medium">{{ $user->church ?? '—' }}{{ $user->ministry ? ' · ' . $user->ministry : '' }}</dd>
                    </div>
                </dl>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="p-4 sm:p-6 border-b border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/50">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-xl">
                            <x-icon name="lock" class="w-5 h-5" />
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-white">Dados sensíveis</h3>
                            <p class="text-xs text-gray-500 dark:text-slate-400">CPF, e-mail e telefone só podem ser alterados mediante solicitação aprovada (uma vez por campo).</p>
                        </div>
                    </div>
                </div>
                <dl class="p-6 space-y-4">
                    @foreach (App\Models\SensitiveFieldChangeRequest::SENSITIVE_FIELDS as $field)
                        @php
                            $label = App\Models\SensitiveFieldChangeRequest::getFieldLabel($field);
                            $value = $user->{$field} ?? '—';
                            $req = $sensitiveRequests->get($field);
                            $canRequest = $canRequestField($field);
                        @endphp
                        <div>
                            <dt class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-slate-500 mb-0.5">{{ $label }}</dt>
                            <dd class="mt-0.5 flex items-center justify-between gap-2 flex-wrap">
                                <span class="text-gray-900 dark:text-white font-medium">{{ $value ?: '—' }}</span>
                                @if ($req)
                                    @if ($req->status === 'pending')
                                        <span class="rounded-full bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-800 dark:bg-amber-900/30 dark:text-amber-200">Pendente</span>
                                    @elseif ($req->status === 'approved')
                                        <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200">Alterado</span>
                                    @else
                                        <span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900/30 dark:text-red-200">Recusado</span>
                                    @endif
                                @elseif ($canRequest)
                                    <button type="button" onclick="document.getElementById('modal-{{ $field }}').showModal()"
                                            class="rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-2 py-1 text-xs font-medium text-purple-600 dark:text-purple-400 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        Solicitar alteração
                                    </button>
                                @endif
                            </dd>
                        </div>
                    @endforeach
                </dl>
            </div>
        </div>

        @if ($sensitiveRequests->isNotEmpty())
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="p-4 sm:p-6 border-b border-gray-100 dark:border-slate-800">
                    <h3 class="font-bold text-gray-900 dark:text-white">Solicitações de alteração</h3>
                </div>
                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($sensitiveRequests as $req)
                        <li class="p-4 flex items-center justify-between gap-4">
                            <div>
                                <span class="font-medium text-gray-900 dark:text-white">{{ App\Models\SensitiveFieldChangeRequest::getFieldLabel($req->field_name) }}</span>
                                <span class="text-gray-500 dark:text-slate-400"> → {{ $req->requested_value }}</span>
                            </div>
                            <span @class([
                                'rounded-full px-2 py-0.5 text-xs font-medium',
                                'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-200' => $req->status === 'pending',
                                'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200' => $req->status === 'approved',
                                'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200' => $req->status === 'rejected',
                            ])>
                                {{ $req->status === 'pending' ? 'Pendente' : ($req->status === 'approved' ? 'Aprovado' : 'Recusado') }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>

@foreach (App\Models\SensitiveFieldChangeRequest::SENSITIVE_FIELDS as $field)
    @if ($canRequestField($field))
        <dialog id="modal-{{ $field }}" class="fixed left-1/2 top-1/2 z-[100] m-0 w-full max-w-md -translate-x-1/2 -translate-y-1/2 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-6 shadow-xl backdrop:bg-black/50 open:flex open:flex-col">
            <form method="post" action="{{ route('painel.profile.request-sensitive-change') }}">
                @csrf
                <input type="hidden" name="field_name" value="{{ $field }}">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Solicitar alteração de {{ App\Models\SensitiveFieldChangeRequest::getFieldLabel($field) }}</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Esta solicitação pode ser feita apenas uma vez. O administrador aprovará ou recusará.</p>
                <div class="mt-4">
                    <label for="requested_value_{{ $field }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Novo valor</label>
                    @if ($field === 'email')
                        <input type="email" name="requested_value" id="requested_value_{{ $field }}" required
                               class="mt-1 block w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5">
                    @else
                        <input type="text" name="requested_value" id="requested_value_{{ $field }}" required maxlength="255"
                               class="mt-1 block w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5"
                               placeholder="{{ $field === 'cpf' ? '000.000.000-00' : '(00) 00000-0000' }}">
                    @endif
                    @error('requested_value')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-6 flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('modal-{{ $field }}').close()"
                            class="rounded-xl border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800">Cancelar</button>
                    <button type="submit" class="rounded-xl bg-purple-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-purple-700">Enviar solicitação</button>
                </div>
            </form>
        </dialog>
    @endif
@endforeach
@endsection

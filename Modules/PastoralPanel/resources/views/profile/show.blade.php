<x-pastoralpanel::layouts.master title="Meu perfil">
    <div class="p-6 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="space-y-1">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight flex items-center gap-2">
                    <x-icon name="user" style="duotone" class="w-9 h-9 text-indigo-500" />
                    Meu perfil
                </h1>
                <p class="text-gray-600 dark:text-gray-400">Visualize e edite seus dados. Alterações em CPF, e-mail e telefone requerem aprovação do administrador (uma única solicitação por campo).</p>
            </div>
            <a href="{{ route('pastoral.profile.edit') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700">
                <x-icon name="pen-to-square" style="duotone" class="w-4 h-4" />
                Editar perfil
            </a>
        </div>

        @if (session('success'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 dark:border-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-200" role="alert">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800 dark:border-red-800 dark:bg-red-900/20 dark:text-red-200" role="alert">{{ session('error') }}</div>
        @endif

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div class="backdrop-blur-xl bg-white/70 dark:bg-white/10 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="border-b border-gray-200 dark:border-gray-700 p-4">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Dados pessoais</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Você pode alterar estes campos em &quot;Editar perfil&quot;.</p>
                </div>
                <dl class="p-4 space-y-3">
                    <div>
                        <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Nome</dt>
                        <dd class="mt-0.5 text-gray-900 dark:text-white">{{ $user->first_name }} {{ $user->last_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Data de nascimento</dt>
                        <dd class="mt-0.5 text-gray-900 dark:text-white">{{ $user->birth_date?->format('d/m/Y') ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Igreja / Ministério</dt>
                        <dd class="mt-0.5 text-gray-900 dark:text-white">{{ $user->church ?? '—' }} {{ $user->ministry ? ' · ' . $user->ministry : '' }}</dd>
                    </div>
                </dl>
            </div>

            <div class="backdrop-blur-xl bg-white/70 dark:bg-white/10 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="border-b border-gray-200 dark:border-gray-700 p-4">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Dados sensíveis</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">CPF, e-mail e telefone só podem ser alterados mediante solicitação aprovada pelo administrador (uma única vez por campo).</p>
                </div>
                <dl class="p-4 space-y-3">
                    @foreach (App\Models\SensitiveFieldChangeRequest::SENSITIVE_FIELDS as $field)
                        @php
                            $label = App\Models\SensitiveFieldChangeRequest::getFieldLabel($field);
                            $value = $user->{$field} ?? '—';
                            $req = $sensitiveRequests->get($field);
                            $canRequest = $canRequestField($field);
                        @endphp
                        <div>
                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ $label }}</dt>
                            <dd class="mt-0.5 flex items-center justify-between gap-2">
                                <span class="text-gray-900 dark:text-white">{{ $value ?: '—' }}</span>
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
                                            class="rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-2 py-1 text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:bg-gray-50 dark:hover:bg-gray-700">
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
            <div class="backdrop-blur-xl bg-white/70 dark:bg-white/10 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="border-b border-gray-200 dark:border-gray-700 p-4">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Solicitações de alteração</h2>
                </div>
                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($sensitiveRequests as $req)
                        <li class="p-4 flex items-center justify-between gap-4">
                            <div>
                                <span class="font-medium text-gray-900 dark:text-white">{{ App\Models\SensitiveFieldChangeRequest::getFieldLabel($req->field_name) }}</span>
                                <span class="text-gray-500 dark:text-gray-400"> → {{ $req->requested_value }}</span>
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

    @foreach (App\Models\SensitiveFieldChangeRequest::SENSITIVE_FIELDS as $field)
        @if ($canRequestField($field))
            <dialog id="modal-{{ $field }}" class="fixed left-1/2 top-1/2 z-[100] m-0 w-full max-w-md -translate-x-1/2 -translate-y-1/2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-6 shadow-xl backdrop:bg-black/50 open:flex open:flex-col">
                <form method="post" action="{{ route('pastoral.profile.request-sensitive-change') }}">
                    @csrf
                    <input type="hidden" name="field_name" value="{{ $field }}">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Solicitar alteração de {{ App\Models\SensitiveFieldChangeRequest::getFieldLabel($field) }}</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Esta solicitação pode ser feita apenas uma vez. O administrador aprovará ou recusará.</p>
                    <div class="mt-4">
                        <label for="requested_value_{{ $field }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Novo valor</label>
                        @if ($field === 'email')
                            <input type="email" name="requested_value" id="requested_value_{{ $field }}" required
                                   class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                        @else
                            <input type="text" name="requested_value" id="requested_value_{{ $field }}" required maxlength="255"
                                   class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                                   placeholder="{{ $field === 'cpf' ? '000.000.000-00' : '(00) 00000-0000' }}">
                        @endif
                        @error('requested_value')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mt-6 flex justify-end gap-2">
                        <button type="button" onclick="document.getElementById('modal-{{ $field }}').close()"
                                class="rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800">Cancelar</button>
                        <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Enviar solicitação</button>
                    </div>
                </form>
            </dialog>
        @endif
    @endforeach
</x-pastoralpanel::layouts.master>

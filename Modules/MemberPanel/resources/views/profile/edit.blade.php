<x-memberpanel::layouts.master title="Editar perfil">
    <div class="p-6 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="space-y-1">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight flex items-center gap-2">
                    <x-icon name="pen-to-square" style="duotone" class="w-9 h-9 text-indigo-500" />
                    Editar perfil
                </h1>
                <p class="text-gray-600 dark:text-gray-400">Altere apenas os dados permitidos. CPF, e-mail e telefone devem ser solicitados na página de perfil.</p>
            </div>
            <a href="{{ route('painel.profile.show') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800">Voltar</a>
        </div>

        @if ($errors->any())
            <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800 dark:border-red-800 dark:bg-red-900/20 dark:text-red-200" role="alert">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('painel.profile.update') }}" method="POST" enctype="multipart/form-data" class="max-w-2xl space-y-6">
            @csrf
            @method('PUT')
            <div class="backdrop-blur-xl bg-white/70 dark:bg-white/10 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 space-y-4">
                <div class="flex items-center gap-4">
                    @if ($user->avatar_url)
                        <img src="{{ $user->avatar_url }}" alt="" class="size-20 rounded-full object-cover ring-2 ring-gray-200 dark:ring-slate-700">
                    @else
                        <div class="flex size-20 shrink-0 items-center justify-center rounded-full bg-gray-200 text-gray-500 dark:bg-slate-700 dark:text-slate-400">
                            <x-icon name="user" style="duotone" class="size-10" />
                        </div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <label for="avatar" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Foto de perfil</label>
                        <input type="file" name="avatar" id="avatar" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                               class="mt-1 block w-full text-sm text-gray-600 dark:text-gray-400 file:mr-3 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-indigo-700 dark:file:bg-indigo-900/30 dark:file:text-indigo-300">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">JPEG, PNG, GIF ou WebP. Máx. 2 MB.</p>
                        @error('avatar')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome</label>
                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $user->first_name) }}" required
                               class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                    </div>
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sobrenome</label>
                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->last_name) }}" required
                               class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                    </div>
                </div>
                <div>
                    <label for="birth_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data de nascimento</label>
                    <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', $user->birth_date?->format('Y-m-d')) }}"
                           class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                </div>
                <div>
                    <label for="church" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Igreja</label>
                    <input type="text" name="church" id="church" value="{{ old('church', $user->church) }}"
                           class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                </div>
                <div>
                    <label for="ministry" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ministério</label>
                    <input type="text" name="ministry" id="ministry" value="{{ old('ministry', $user->ministry) }}"
                           class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700">Salvar</button>
                    <a href="{{ route('painel.profile.show') }}" class="rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
</x-memberpanel::layouts.master>

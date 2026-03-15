<x-admin::layouts.master>
    <div class="p-6 space-y-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Painel Admin</h1>
        <p class="text-gray-600 dark:text-gray-400">Module: {!! config('admin.name') !!}</p>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.profile.show') }}" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700">
                <x-icon name="user" style="duotone" class="w-4 h-4" />
                Meu perfil
            </a>
            <a href="{{ route('admin.change-requests.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <x-icon name="clipboard-list" style="duotone" class="w-4 h-4" />
                Solicitações de alteração
            </a>
        </div>
    </div>
</x-admin::layouts.master>

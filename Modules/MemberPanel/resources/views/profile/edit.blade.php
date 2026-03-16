@extends('memberpanel::components.layouts.master')

@section('title', 'Editar Perfil')
@section('page-title', 'Editar Perfil')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-200 pb-24 sm:pb-28">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 pt-4 sm:pt-6">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-6">
            <div class="min-w-0">
                <nav class="flex items-center gap-2 text-xs text-gray-500 dark:text-slate-400 mb-2" aria-label="Breadcrumb">
                    <a href="{{ route('painel.dashboard') }}" class="hover:text-purple-600 dark:hover:text-purple-400 transition-colors">Painel</a>
                    <x-icon name="chevron-right" class="w-3 h-3 shrink-0" />
                    <a href="{{ route('painel.profile.show') }}" class="hover:text-purple-600 dark:hover:text-purple-400 transition-colors">Meu Perfil</a>
                    <x-icon name="chevron-right" class="w-3 h-3 shrink-0" />
                    <span class="text-gray-900 dark:text-white font-medium truncate">Editar Cadastro</span>
                </nav>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Editar Perfil</h1>
                <p class="text-gray-500 dark:text-slate-400 mt-1 text-sm">Altere apenas os dados permitidos. CPF, e-mail e telefone devem ser solicitados na página de perfil.</p>
            </div>
            <a href="{{ route('painel.profile.show') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl shadow-sm font-bold text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800 transition-all shrink-0">
                <x-icon name="arrow-left" class="w-4 h-4" />
                Voltar ao Perfil
            </a>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-2xl border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/20 p-4 sm:p-5">
                <div class="flex items-start gap-3">
                    <x-icon name="circle-exclamation" class="w-5 h-5 text-red-500 dark:text-red-400 mt-0.5 shrink-0" />
                    <div>
                        <p class="text-xs font-black uppercase tracking-widest text-red-600 dark:text-red-300 mb-1">Atenção</p>
                        <p class="text-sm text-red-700 dark:text-red-200 mb-2">Alguns campos precisam ser corrigidos antes de salvar.</p>
                        <ul class="text-sm text-red-700 dark:text-red-200 space-y-0.5 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form id="profile-form" action="{{ route('painel.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <section class="bg-white dark:bg-slate-900 rounded-2xl sm:rounded-3xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="p-4 sm:p-6 border-b border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/50 flex items-center gap-3">
                    <div class="p-2 bg-purple-600 text-white rounded-xl shadow-lg shadow-purple-500/10">
                        <x-icon name="user" class="w-5 h-5" />
                    </div>
                    <h2 class="text-lg font-black text-gray-900 dark:text-white">Dados básicos</h2>
                </div>
                <div class="p-4 sm:p-6 md:p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <div class="space-y-1">
                            <label for="first_name" class="block text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-slate-500">Nome *</label>
                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $user->first_name) }}" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 dark:bg-slate-800 text-gray-900 dark:text-white font-medium focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                            @error('first_name') <p class="text-[10px] text-red-500 font-bold mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-1">
                            <label for="last_name" class="block text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-slate-500">Sobrenome *</label>
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->last_name) }}" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 dark:bg-slate-800 text-gray-900 dark:text-white font-medium focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                            @error('last_name') <p class="text-[10px] text-red-500 font-bold mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="md:col-span-2 space-y-1">
                            <label for="birth_date" class="block text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-slate-500">Data de nascimento</label>
                            <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', $user->birth_date?->format('Y-m-d')) }}"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 dark:bg-slate-800 text-gray-900 dark:text-white focus:ring-4 focus:ring-purple-500/20 transition-all">
                            @error('birth_date') <p class="text-[10px] text-red-500 font-bold mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </section>

            <section class="bg-white dark:bg-slate-900 rounded-2xl sm:rounded-3xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="p-4 sm:p-6 border-b border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/50 flex items-center gap-3">
                    <div class="p-2 bg-indigo-600 text-white rounded-xl shadow-lg shadow-indigo-500/10">
                        <x-icon name="church" class="w-5 h-5" />
                    </div>
                    <h2 class="text-lg font-black text-gray-900 dark:text-white">Igreja e ministério</h2>
                </div>
                <div class="p-4 sm:p-6 md:p-8 grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    <div class="space-y-1">
                        <label for="church" class="block text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-slate-500">Igreja</label>
                        <input type="text" name="church" id="church" value="{{ old('church', $user->church) }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 dark:bg-slate-800 text-gray-900 dark:text-white focus:ring-4 focus:ring-purple-500/20 transition-all">
                    </div>
                    <div class="space-y-1">
                        <label for="ministry" class="block text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-slate-500">Ministério</label>
                        <input type="text" name="ministry" id="ministry" value="{{ old('ministry', $user->ministry) }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 dark:bg-slate-800 text-gray-900 dark:text-white focus:ring-4 focus:ring-purple-500/20 transition-all">
                    </div>
                </div>
            </section>

            <section class="bg-white dark:bg-slate-900 rounded-2xl sm:rounded-3xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="p-4 sm:p-6 border-b border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/50 flex items-center gap-3">
                    <div class="p-2 bg-emerald-600 text-white rounded-xl shadow-lg shadow-emerald-500/10">
                        <x-icon name="image" class="w-5 h-5" />
                    </div>
                    <h2 class="text-lg font-black text-gray-900 dark:text-white">Foto de perfil</h2>
                </div>
                <div class="p-4 sm:p-6 md:p-8 flex flex-col sm:flex-row items-center gap-6">
                    @if ($user->avatar_url ?? null)
                        <img src="{{ $user->avatar_url }}" alt="" class="w-24 h-24 rounded-full object-cover ring-4 ring-white dark:ring-slate-800 shadow-xl">
                    @else
                        <div class="w-24 h-24 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center text-3xl font-black text-purple-600 dark:text-purple-400">
                            {{ strtoupper(substr($user->first_name ?? $user->name ?? 'U', 0, 1)) }}
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <label for="avatar" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Escolher imagem</label>
                        <input type="file" name="avatar" id="avatar" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                            class="mt-2 block w-full text-sm text-gray-600 dark:text-gray-400 file:mr-3 file:rounded-lg file:border-0 file:bg-purple-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-purple-700 dark:file:bg-purple-900/30 dark:file:text-purple-300">
                        <p class="mt-1 text-xs text-gray-500 dark:text-slate-400">JPEG, PNG, GIF ou WebP. Máx. 2 MB.</p>
                        @error('avatar') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>
        </form>
    </div>
</div>

<div class="fixed bottom-4 sm:bottom-8 left-0 right-0 z-50 px-4 pointer-events-none">
    <div class="max-w-4xl mx-auto flex justify-end pointer-events-auto">
        <div class="bg-white/90 dark:bg-slate-800/90 backdrop-blur-xl p-2 rounded-2xl border border-gray-200 dark:border-slate-700 shadow-2xl flex items-center gap-3 sm:gap-4">
            <button type="button" onclick="window.location='{{ route('painel.profile.show') }}'"
                class="px-4 sm:px-6 py-2.5 text-sm font-bold text-gray-500 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white uppercase tracking-widest transition-colors">
                Descartar
            </button>
            <button type="submit" form="profile-form"
                class="px-6 sm:px-8 py-2.5 bg-purple-600 text-white rounded-xl font-bold text-sm uppercase tracking-widest shadow-xl shadow-purple-500/20 hover:bg-purple-700 transition-all active:scale-[0.98]">
                Salvar Mudanças
            </button>
        </div>
    </div>
</div>
@endsection

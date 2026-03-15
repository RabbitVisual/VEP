@extends('memberpanel::components.layouts.master')

@section('title', 'Bíblia Digital')

@include('bible::memberpanel.bible.partials.bible-header-assets')
@section('content')
    <div class="min-h-screen bible-page-wrapper bg-gray-50 dark:bg-slate-950 transition-colors pb-12">

        <!-- Sticky Header: mesmo padrão da Leitura -->
        <div class="sticky top-0 z-30 bg-white/90 dark:bg-slate-950/90 backdrop-blur-xl border-b border-gray-200 dark:border-slate-800 transition-colors duration-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg">
                        <x-icon name="book-open" style="duotone" class="w-6 h-6 text-indigo-600 dark:text-indigo-400" />
                    </div>
                    <div>
                        <h1 class="text-xl font-black text-gray-900 dark:text-white tracking-tight leading-tight">Bíblia Sagrada</h1>
                        <p class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wide mt-1">Leitura & Estudo</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
            <div class="max-w-xl mx-auto">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 p-8 shadow-sm text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-amber-50 dark:bg-amber-900/30 rounded-2xl mb-6 text-amber-600 dark:text-amber-400">
                        <x-icon name="triangle-exclamation" class="w-8 h-8" />
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Nenhuma versão encontrada</h2>
                    <p class="text-sm text-gray-500 dark:text-slate-400 mb-8 leading-relaxed">
                        Nenhuma versão da Bíblia (NVI, ACF, etc.) foi importada para o sistema ainda. Entre em contato com a administração para habilitar a leitura.
                    </p>
                    <a href="{{ route('memberpanel.dashboard') }}"
                        class="inline-flex items-center justify-center px-6 py-3 text-sm font-bold text-gray-700 bg-gray-50 border border-gray-200 rounded-xl hover:bg-gray-100 dark:bg-slate-800 dark:text-white dark:border-slate-700 dark:hover:bg-slate-700 transition-all">
                        <x-icon name="house" class="w-4 h-4 mr-2" />
                        Voltar ao Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection


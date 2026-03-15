@props([
    'title' => 'Bíblia Sagrada',
    'titleHtml' => null,
    'subtitle' => 'Leitura & estudo · atalhos rápidos',
    'showBack' => false,
    'backUrl' => null,
    'showVersionSelector' => false,
    'version' => null,
    'versionChangeUrl' => null,
    'showSearch' => true,
    'showFavorites' => true,
    'showInterlinear' => true,
    'showLeitura' => false,
    'interlinearParams' => [],
    'dataTour' => null,
])

<div class="bible-reader-bar sticky top-4 bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 p-4 sm:p-5 shadow-sm"
     @if($dataTour) data-tour="{{ $dataTour }}" @endif
     @if($showVersionSelector && $versionChangeUrl) data-version-change-url="{{ $versionChangeUrl }}" @endif>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex-1 min-w-0 flex items-center gap-3 flex-wrap">
            @if($showBack && $backUrl)
                <a href="{{ $backUrl }}" class="shrink-0 flex items-center justify-center w-10 h-10 rounded-xl bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-600 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors" aria-label="Voltar">
                    <x-icon name="arrow-left" class="w-5 h-5" />
                </a>
            @endif
            <div class="min-w-0 flex items-center gap-2 sm:gap-3 flex-wrap">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-0.5 truncate">
                        @if($titleHtml){!! $titleHtml !!}@else{{ $title }}@endif
                    </h2>
                    @if($subtitle)
                        <p class="text-sm text-gray-500 dark:text-slate-400">{{ $subtitle }}</p>
                    @endif
                </div>
                @if($showVersionSelector && $version && $versionChangeUrl)
                    <div class="relative shrink-0 min-w-[5rem]">
                        <select onchange="var u=this.closest('.bible-reader-bar').getAttribute('data-version-change-url');if(u)location.href=u.replace(':version',this.value);" class="appearance-none w-full pl-3 pr-9 py-2 rounded-xl bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-sm font-bold text-gray-700 dark:text-slate-300 outline-none cursor-pointer hover:bg-gray-100 dark:hover:bg-slate-700 focus:border-indigo-500 dark:focus:border-indigo-500 transition-colors">
                            @foreach(\Modules\Bible\App\Models\BibleVersion::active()->get() as $v)
                                <option value="{{ $v->abbreviation }}" {{ $v->id === $version->id ? 'selected' : '' }} class="bg-white dark:bg-slate-900">{{ $v->abbreviation }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center justify-center pr-2.5 pointer-events-none w-9 text-gray-400">
                            <x-icon name="chevron-down" class="w-4 h-4" />
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="bible-reader-bar__actions flex items-center gap-2 flex-wrap">
            @if($showSearch)
                <a href="{{ route('painel.bible.search') }}" class="bible-reader-bar-btn inline-flex items-center justify-center px-3 sm:px-4 py-2 rounded-xl bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-sm font-medium text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                    <x-icon name="magnifying-glass" class="w-4 h-4 bible-reader-bar-btn__icon mr-2" />
                    <span class="bible-reader-bar-btn__label">Buscar</span>
                </a>
            @endif
            @if($showFavorites)
                <a href="{{ route('painel.bible.favorites') }}" class="bible-reader-bar-btn inline-flex items-center justify-center px-3 sm:px-4 py-2 rounded-xl bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-sm font-medium text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700 hover:text-red-500 dark:hover:text-red-400 transition-colors">
                    <x-icon name="heart" class="w-4 h-4 bible-reader-bar-btn__icon mr-2" />
                    <span class="bible-reader-bar-btn__label">Favoritos</span>
                </a>
            @endif
            @if($showLeitura)
                <a href="{{ route('painel.bible.read', $version ? $version->abbreviation : (\Modules\Bible\App\Models\BibleVersion::first()->abbreviation ?? 'NVI')) }}" class="bible-reader-bar-btn inline-flex items-center justify-center px-3 sm:px-4 py-2 rounded-xl bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-sm font-medium text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                    <x-icon name="book-open" class="w-4 h-4 bible-reader-bar-btn__icon mr-2" />
                    <span class="bible-reader-bar-btn__label">Leitura</span>
                </a>
            @endif
            @if($showInterlinear)
                <a href="{{ route('painel.bible.interlinear', $interlinearParams) }}" class="bible-reader-bar-btn inline-flex items-center justify-center px-3 sm:px-4 py-2 rounded-xl bg-indigo-600 border border-transparent text-sm font-medium text-white hover:bg-indigo-700 transition-colors shrink-0">
                    <x-icon name="language" class="w-4 h-4 bible-reader-bar-btn__icon mr-2" />
                    <span class="bible-reader-bar-btn__label">Interlinear</span>
                </a>
            @endif
            {!! $extraActions ?? '' !!}
        </div>
    </div>
</div>

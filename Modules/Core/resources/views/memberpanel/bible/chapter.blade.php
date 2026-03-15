@extends('memberpanel::components.layouts.master')

@section('title', $book->name . ' ' . $chapter->chapter_number . ' - ' . $version->name)

@include('bible::memberpanel.bible.partials.bible-header-assets')
@section('content')
    <div class="min-h-screen bible-page-wrapper bg-gray-50 dark:bg-slate-950 transition-colors duration-500 pb-20" id="bible-chapter"
         style="--bible-mobile-font-size: 1.25rem; --bible-line-height: 1.75;"
         data-theme="pergaminho">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 sm:pt-6">
            <div class="bible-reader-bar-wrapper mb-6" data-bible-hide-in-fullscreen>
            @include('bible::memberpanel.bible.partials.bible-reader-bar', [
                'titleHtml' => e($book->name) . ' <span class="text-indigo-600 dark:text-indigo-400">' . $chapter->chapter_number . '</span>',
                'subtitle' => '',
                'showBack' => true,
                'backUrl' => route('painel.bible.book', ['versionAbbr' => $version->abbreviation, 'bookNumber' => $book->book_number]),
                'showVersionSelector' => true,
                'version' => $version,
                'versionChangeUrl' => route('painel.bible.chapter', ['versionAbbr' => ':version', 'bookNumber' => $book->book_number, 'chapterNumber' => $chapter->chapter_number]),
                'showSearch' => true,
                'showFavorites' => true,
                'showInterlinear' => true,
                'interlinearParams' => ['book' => $book->name, 'chapter' => $chapter->chapter_number],
                'dataTour' => 'bible-chapter-nav',
                'extraActions' => view('bible::memberpanel.bible.partials.chapter-reader-extra-actions')->render(),
            ])
            </div>

        <!-- Reading Area (Kindle / pergaminho) -->
        <main class="bible-reading-area px-4 sm:px-6 lg:px-8 py-8 sm:py-10 transition-colors duration-300" style="background-color: var(--bible-bg); color: var(--bible-text);">
            <div class="max-w-3xl mx-auto px-4 sm:px-8 lg:px-12">
            @if (!empty($chapterAudioUrl))
                <div class="mb-8 p-4 rounded-xl border transition-colors duration-300" style="background: rgba(0,0,0,0.03); border-color: var(--bible-border);" aria-label="Ouvir capítulo em áudio">
                    <p class="text-sm font-bold mb-3 flex items-center gap-2" style="color: var(--bible-muted);">
                        <x-icon name="volume-high" class="w-4 h-4 text-indigo-600 dark:text-indigo-400" />
                        Áudio deste capítulo ({{ $version->name }})
                    </p>
                    <audio controls class="w-full max-w-md" preload="metadata" src="{{ $chapterAudioUrl }}">
                        Seu navegador não suporta o elemento de áudio.
                    </audio>
                </div>
            @endif
            @if ($verses->isEmpty())
                <div class="text-center py-20">
                    <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6" style="background: var(--bible-border);">
                        <x-icon name="triangle-exclamation" class="w-8 h-8 opacity-50" style="color: var(--bible-muted);" />
                    </div>
                    <h3 class="text-xl font-bold mb-2" style="color: var(--bible-text);">Capítulo Indisponível</h3>
                    <p style="color: var(--bible-muted);">Este capítulo ainda não foi carregado nesta versão.</p>
                </div>
            @else
                <!-- Chapter header (Kindle style) -->
                <header class="text-center mb-10 sm:mb-12">
                    <p class="text-xs font-black uppercase tracking-[0.3em] mb-2" style="color: var(--bible-muted);">Capítulo {{ $chapter->chapter_number }}</p>
                    <div class="flex items-center justify-center gap-2 mb-1">
                        <x-icon name="book-open" class="w-5 h-5" style="color: var(--bible-muted);" />
                    </div>
                    <h1 class="text-lg sm:text-xl font-serif font-semibold tracking-wide" style="color: var(--bible-text);">{{ $book->name }}</h1>
                </header>

                <div class="space-y-5 sm:space-y-6" data-tour="bible-verse">
                    @foreach ($verses as $verse)
                        <div class="flex items-start gap-3 sm:gap-5 group relative p-3 sm:p-4 rounded-xl transition-colors duration-200 border border-transparent hover:border-[var(--bible-border)]" id="verse-{{ $verse->verse_number }}" style="background: transparent;">

                            <span class="bible-verse-num shrink-0 w-8 h-8 flex items-center justify-center rounded-lg font-bold text-xs select-none transition-colors" style="background: var(--bible-border);">
                                {{ $verse->verse_number }}
                            </span>

                            <div class="flex-1 min-w-0">
                                <p class="bible-verse-text font-serif text-justify tracking-wide transition-colors" style="font-size: var(--bible-mobile-font-size); line-height: var(--bible-line-height);">
                                    {{ $verse->text }}
                                </p>
                            </div>

                            <div class="md:opacity-0 md:group-hover:opacity-100 flex flex-col gap-1 transition-opacity duration-200 absolute right-2 top-2 sm:static">
                                <button onclick="toggleFavorite({{ $verse->id }})"
                                    class="p-2 rounded-lg text-gray-300 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors favorite-btn"
                                    data-verse-id="{{ $verse->id }}"
                                    title="Favoritar Versículo">
                                    <x-icon name="heart" class="w-5 h-5 transition-transform active:scale-95" />
                                </button>
                                <button type="button" onclick="shareVerse(this.getAttribute('data-verse-text'), this.getAttribute('data-verse-ref'))"
                                    data-verse-text="{{ e($verse->text) }}"
                                    data-verse-ref="{{ $book->name }} {{ $chapter->chapter_number }}:{{ $verse->verse_number }} ({{ $version->abbreviation }})"
                                    class="p-2 rounded-lg text-gray-300 hover:text-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-colors hidden sm:block"
                                    title="Compartilhar no WhatsApp">
                                    <x-icon name="share-nodes" class="w-5 h-5" />
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            </div>
        </main>

        <!-- Bottom Navigation Controls -->
        <div class="bible-bottom-nav fixed bottom-0 left-0 right-0 bg-white/90 dark:bg-slate-950/90 backdrop-blur-xl border-t border-gray-200 dark:border-slate-800 py-4 px-4 sm:px-6 z-30" data-bible-hide-in-fullscreen>
             <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
                <!-- Previous -->
                <div class="flex-1">
                    @if ($previousChapter)
                        <a href="{{ route('painel.bible.chapter', ['versionAbbr' => $version->abbreviation, 'bookNumber' => $previousChapter->book->book_number, 'chapterNumber' => $previousChapter->chapter_number]) }}"
                            class="flex items-center gap-3 text-gray-500 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white transition-colors group">
                             <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-slate-800 group-hover:bg-indigo-50 dark:group-hover:bg-indigo-900/20 flex items-center justify-center transition-colors">
                                 <x-icon name="chevron-left" class="w-5 h-5 group-hover:text-indigo-600 dark:group-hover:text-indigo-400" />
                             </div>
                             <div class="hidden sm:block text-left">
                                 <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Anterior</p>
                                 <p class="text-sm font-bold">Cap. {{ $previousChapter->chapter_number }}</p>
                             </div>
                        </a>
                    @else
                        <span class="w-10 h-10 block"></span>
                    @endif
                </div>

                <!-- Current Indicator (Mobile) -->
                <div class="sm:hidden text-center">
                    <span class="text-xs font-black uppercase tracking-widest text-indigo-500">{{ $book->abbreviation }} {{ $chapter->chapter_number }}</span>
                </div>

                <!-- Next -->
                 <div class="flex-1 flex justify-end">
                    @if ($nextChapter)
                        <a href="{{ route('painel.bible.chapter', ['versionAbbr' => $version->abbreviation, 'bookNumber' => $nextChapter->book->book_number, 'chapterNumber' => $nextChapter->chapter_number]) }}"
                            class="flex items-center gap-3 text-gray-500 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white transition-colors group text-right">
                             <div class="hidden sm:block">
                                 <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Próximo</p>
                                 <p class="text-sm font-bold">Cap. {{ $nextChapter->chapter_number }}</p>
                             </div>
                             <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-slate-800 group-hover:bg-indigo-50 dark:group-hover:bg-indigo-900/20 flex items-center justify-center transition-colors">
                                 <x-icon name="chevron-right" class="w-5 h-5 group-hover:text-indigo-600 dark:group-hover:text-indigo-400" />
                             </div>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Botão Sair do modo foco (visível só no modo foco; usa inert quando oculto para acessibilidade) -->
        <div id="bible-fullscreen-exit" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[110] opacity-0 pointer-events-none transition-opacity duration-300 bible-fullscreen-exit-bar" hidden>
            <button type="button" id="bible-fullscreen-exit-btn" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/95 dark:bg-slate-900/95 border border-gray-200 dark:border-slate-700 text-sm font-bold text-gray-700 dark:text-slate-300 shadow-lg backdrop-blur-sm hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors">
                <x-icon name="compress" class="w-4 h-4" />
                Sair do modo foco
            </button>
        </div>
        </div>
    </div>

    <style>
        #bible-chapter .bible-verse-text {
            font-size: var(--bible-mobile-font-size, 1.25rem) !important;
        }
    </style>

    <script>
        (function() {
            var SIZES = ['1rem', '1.25rem', '1.5rem', '1.75rem', '2rem', '2.25rem', '2.5rem'];
            var FONT_LABELS = ['Muito pequeno', 'Pequeno', 'Médio', 'Grande', 'Muito grande', 'Extra', 'Máximo'];
            var FONT_STORAGE = 'bible_reader_font_size_index';
            var THEME_STORAGE = 'bible_reader_theme';
            var LINE_HEIGHT_STORAGE = 'bible_reader_line_height';
            var THEMES = ['pergaminho', 'paper', 'night'];
            var THEME_LABELS = { pergaminho: 'Pergaminho', paper: 'Papel', night: 'Noite' };
            var LINE_HEIGHTS = [1.5, 1.75, 2.1];
            var LINE_LABELS = ['Compacto', 'Normal', 'Amplo'];

            function applyFontSize(wrapper, index) {
                if (!wrapper) return;
                wrapper.style.setProperty('--bible-mobile-font-size', SIZES[index]);
                try { localStorage.setItem(FONT_STORAGE, String(index)); } catch (e) {}
                var label = document.querySelector('.bible-font-label');
                if (label) label.textContent = 'A';
            }

            var THEME_VARS = {
                pergaminho: { bg: '#f5f0e6', text: '#2d2a26', muted: '#6b6560', border: 'rgba(0,0,0,0.06)' },
                paper: { bg: '#ffffff', text: '#1a1a1a', muted: '#5a5a5a', border: 'rgba(0,0,0,0.08)' },
                night: { bg: '#1a1814', text: '#e8e4dc', muted: '#9a958c', border: 'rgba(255,255,255,0.08)' }
            };
            function applyTheme(wrapper, theme) {
                var area = wrapper && wrapper.querySelector('.bible-reading-area');
                if (!wrapper || !area) return;
                wrapper.setAttribute('data-theme', theme);
                var v = THEME_VARS[theme] || THEME_VARS.pergaminho;
                [wrapper, area].forEach(function(el) {
                    el.style.setProperty('--bible-bg', v.bg);
                    el.style.setProperty('--bible-text', v.text);
                    el.style.setProperty('--bible-muted', v.muted);
                    el.style.setProperty('--bible-border', v.border);
                });
                area.style.backgroundColor = v.bg;
                area.style.color = v.text;
                if (wrapper.classList.contains('bible-fullscreen')) wrapper.style.backgroundColor = v.bg;
                try { localStorage.setItem(THEME_STORAGE, theme); } catch (e) {}
            }

            function applyLineHeight(wrapper, index) {
                if (!wrapper) return;
                wrapper.style.setProperty('--bible-line-height', String(LINE_HEIGHTS[index]));
                try { localStorage.setItem(LINE_HEIGHT_STORAGE, String(index)); } catch (e) {}
            }

            /* Modo foco: mesmo padrão do interlinear – só toggle de classes, sem mover nó no DOM */
            function setFullscreenUI(wrapper, isFull) {
                if (!wrapper) return;
                var exitBar = document.getElementById('bible-fullscreen-exit');
                var exitBtn = exitBar && exitBar.querySelector('button');
                var fullscreenBtn = document.getElementById('bible-fullscreen-btn');

                if (isFull) {
                    document.body.classList.add('bible-focus-mode-active');
                    wrapper.classList.add('bible-fullscreen');
                    var area = wrapper.querySelector('.bible-reading-area');
                    var bg = area ? getComputedStyle(area).getPropertyValue('--bible-bg').trim() || '#f5f0e6' : '#f5f0e6';
                    wrapper.style.backgroundColor = bg;
                    if (exitBar) {
                        exitBar.removeAttribute('hidden');
                        exitBar.classList.remove('opacity-0', 'pointer-events-none');
                        exitBar.style.pointerEvents = 'auto';
                    }
                } else {
                    if (exitBtn && document.activeElement === exitBtn) {
                        exitBtn.blur();
                        if (fullscreenBtn) fullscreenBtn.focus({ preventScroll: true });
                    }
                    if (exitBar) {
                        exitBar.setAttribute('hidden', '');
                        exitBar.classList.add('opacity-0', 'pointer-events-none');
                        exitBar.style.pointerEvents = 'none';
                    }
                    document.body.classList.remove('bible-focus-mode-active');
                    wrapper.classList.remove('bible-fullscreen');
                    wrapper.style.backgroundColor = '';
                }
                if (fullscreenBtn) {
                    fullscreenBtn.setAttribute('title', isFull ? 'Sair do modo foco' : 'Modo foco');
                    fullscreenBtn.setAttribute('aria-label', isFull ? 'Sair do modo foco' : 'Modo foco');
                    var enter = fullscreenBtn.querySelector('.bible-fs-enter');
                    var exit = fullscreenBtn.querySelector('.bible-fs-exit');
                    if (enter) enter.classList.toggle('hidden', isFull);
                    if (exit) exit.classList.toggle('hidden', !isFull);
                }
            }

            function showBibleToast(msg) {
                if (typeof showToast === 'function') showToast(msg);
            }

            document.addEventListener('DOMContentLoaded', function() {
                var wrapper = document.getElementById('bible-chapter');
                if (!wrapper) return;

                var fontIndex = 1;
                try { fontIndex = Math.min(Math.max(0, parseInt(localStorage.getItem(FONT_STORAGE), 10) || 1), SIZES.length - 1); } catch (e) {}
                applyFontSize(wrapper, fontIndex);

                var themeIndex = 0;
                try {
                    var s = localStorage.getItem(THEME_STORAGE);
                    themeIndex = THEMES.indexOf(s);
                    if (themeIndex === -1) themeIndex = 0;
                } catch (e) {}
                applyTheme(wrapper, THEMES[themeIndex]);

                var lineIndex = 1;
                try { lineIndex = Math.min(Math.max(0, parseInt(localStorage.getItem(LINE_HEIGHT_STORAGE), 10) || 1), LINE_HEIGHTS.length - 1); } catch (e) {}
                applyLineHeight(wrapper, lineIndex);

                wrapper.addEventListener('click', function(e) {
                    var target = e.target.closest('#bible-font-minus-btn');
                    if (target) {
                        e.preventDefault();
                        e.stopPropagation();
                        if (fontIndex > 0) {
                            fontIndex--;
                            applyFontSize(wrapper, fontIndex);
                            showBibleToast('Fonte: ' + FONT_LABELS[fontIndex]);
                        }
                        return;
                    }
                    target = e.target.closest('#bible-font-plus-btn');
                    if (target) {
                        e.preventDefault();
                        e.stopPropagation();
                        if (fontIndex < SIZES.length - 1) {
                            fontIndex++;
                            applyFontSize(wrapper, fontIndex);
                            showBibleToast('Fonte: ' + FONT_LABELS[fontIndex]);
                        }
                        return;
                    }
                    target = e.target.closest('#bible-theme-btn');
                    if (target) {
                        e.preventDefault();
                        e.stopPropagation();
                        themeIndex = (themeIndex + 1) % THEMES.length;
                        applyTheme(wrapper, THEMES[themeIndex]);
                        showBibleToast('Tema: ' + (THEME_LABELS[THEMES[themeIndex]] || THEMES[themeIndex]));
                        return;
                    }
                    target = e.target.closest('#bible-line-height-btn');
                    if (target) {
                        e.preventDefault();
                        e.stopPropagation();
                        lineIndex = (lineIndex + 1) % LINE_HEIGHTS.length;
                        applyLineHeight(wrapper, lineIndex);
                        showBibleToast('Espaçamento: ' + LINE_LABELS[lineIndex]);
                        return;
                    }
                    target = e.target.closest('#bible-fullscreen-btn');
                    if (target) {
                        e.preventDefault();
                        e.stopPropagation();
                        var isFull = wrapper.classList.contains('bible-fullscreen');
                        setFullscreenUI(wrapper, !isFull);
                        showBibleToast(isFull ? 'Modo leitura' : 'Modo foco ativado');
                        return;
                    }
                });

                var exitFullscreenBtn = document.getElementById('bible-fullscreen-exit-btn');
                if (exitFullscreenBtn) {
                    exitFullscreenBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        var w = document.getElementById('bible-chapter');
                        if (w) setFullscreenUI(w, false);
                        showBibleToast('Modo foco desativado');
                    });
                }
            });
        })();

        // Initialize Toast
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `fixed bottom-20 left-1/2 -translate-x-1/2 px-6 py-3 rounded-xl shadow-2xl backdrop-blur-md text-sm font-bold transition-all duration-300 transform translate-y-10 opacity-0 z-50 flex items-center gap-3 ${type === 'success' ? 'bg-gray-900/90 text-white dark:bg-white/90 dark:text-gray-900' : 'bg-red-500/90 text-white'}`;

            // Icon
            const icon = type === 'success'
                ? '<svg class="w-5 h-5 text-green-400 dark:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>'
                : '<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';

            toast.innerHTML = `${icon}<span>${message}</span>`;
            document.body.appendChild(toast);

            // Animate In
            requestAnimationFrame(() => {
                toast.classList.remove('translate-y-10', 'opacity-0');
            });

            // Animate Out
            setTimeout(() => {
                toast.classList.add('translate-y-10', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        function toggleFavorite(verseId) {
            const btn = document.querySelector(`[data-verse-id="${verseId}"]`);
            const isFavorite = btn.classList.contains('text-red-500');

            fetch(`{{ url('/social/bible/favorites') }}/${verseId}`, {
                        method: isFavorite ? 'DELETE' : 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json',
                        },
                    })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (!isFavorite) {
                            btn.classList.remove('text-gray-300');
                            btn.classList.add('text-red-500');
                            showToast('Versículo favoritado!');
                        } else {
                            btn.classList.add('text-gray-300');
                            btn.classList.remove('text-red-500');
                             showToast('Removido dos favoritos.');
                        }
                    } else {
                         // Revert state if error
                         showToast('Erro ao atualizar favorito.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Failed to toggle favorite:', error);
                    showToast('Erro de conexão.', 'error');
                });
        }

        function shareVerse(text, reference) {
            const shareText = `"${(text || '').replace(/"/g, '')}" - ${reference}`;
            const url = window.location.href;
            const whatsappText = encodeURIComponent(shareText + '\n' + url);
            const whatsappUrl = 'https://wa.me/?text=' + whatsappText;
            window.open(whatsappUrl, '_blank', 'noopener,noreferrer');
        }

        // Load favorites on page load
        document.addEventListener('DOMContentLoaded', function() {
            @php
                $favoriteIds = Auth::user()->bibleFavorites()->pluck('verse_id')->toArray();
            @endphp
            const favorites = @json($favoriteIds);
            favorites.forEach(id => {
                const btn = document.querySelector(`[data-verse-id="${id}"]`);
                if (btn) {
                    btn.classList.remove('text-gray-300');
                    btn.classList.add('text-red-500');
                }
            });
        });
    </script>
@endsection


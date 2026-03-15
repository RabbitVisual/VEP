{{--
    Loading Overlay – Vertex Escola de Pastores (EAD)
    Overlay global de carregamento: feedback imediato, blur, mensagem contextual,
    tempo mínimo de exibição, detecção de espera longa, acessibilidade (WCAG 2.2).
    Uso: <x-loading-overlay /> ou <x-loading-overlay message="Salvando progresso..." />
    Eventos: loading-overlay:show / loading-overlay:hide / stop-loading
    Exemplo: window.dispatchEvent(new CustomEvent('loading-overlay:show', { detail: { message: 'Processando...' } }));
--}}
@props([
    'message' => null,
    'showBrand' => true,
])

@php
    $slotContent = trim((string) $slot);
    $defaultMessage = $message ?? ($slotContent !== '' ? $slotContent : __('Carregando...'));
@endphp

<div
    x-data="{
        loading: false,
        contextMessage: @js($defaultMessage),
        longWait: false,
        timeout: null,
        minDisplayTimeout: null,
        longWaitTimeout: null,
        shownAt: null,
        minDisplayMs: 1200,
        longWaitMs: 12000,
        maxDisplayMs: 15000,
        delayMs: 200,
        dismiss() {
            this.loading = false;
            this.longWait = false;
            this.shownAt = null;
            if (this.longWaitTimeout) clearTimeout(this.longWaitTimeout);
            this.longWaitTimeout = null;
        },
        stop() {
            const self = this;
            if (self.timeout) clearTimeout(self.timeout);
            self.timeout = null;
            if (self.minDisplayTimeout) clearTimeout(self.minDisplayTimeout);
            self.minDisplayTimeout = null;
            if (self.longWaitTimeout) clearTimeout(self.longWaitTimeout);
            self.longWaitTimeout = null;
            if (!self.loading) return;
            const elapsed = self.shownAt ? (Date.now() - self.shownAt) : self.minDisplayMs;
            const remain = Math.max(0, self.minDisplayMs - elapsed);
            if (remain <= 0) self.dismiss();
            else self.minDisplayTimeout = setTimeout(() => self.dismiss(), remain);
        },
        start(immediate = false, eventMessage = null) {
            const self = this;
            if (self.timeout) clearTimeout(self.timeout);
            if (self.minDisplayTimeout) clearTimeout(self.minDisplayTimeout);
            if (self.longWaitTimeout) clearTimeout(self.longWaitTimeout);
            self.minDisplayTimeout = null;
            self.longWaitTimeout = null;
            self.shownAt = null;
            self.longWait = false;
            if (eventMessage != null) self.contextMessage = eventMessage;
            const delay = immediate ? 0 : self.delayMs;
            self.timeout = setTimeout(() => {
                self.loading = true;
                self.shownAt = Date.now();
                self.longWaitTimeout = setTimeout(() => { if (self.loading) self.longWait = true; }, self.longWaitMs);
            }, delay);
        },
        init() {
            const self = this;
            window.addEventListener('beforeunload', () => self.start(false));
            window.addEventListener('submit', () => self.start(false));
            window.addEventListener('loading-overlay:show', (e) => {
                const msg = e.detail && e.detail.message != null ? e.detail.message : null;
                self.start(true, msg);
            });
            window.addEventListener('loading-overlay:hide', () => self.stop());
            window.addEventListener('stop-loading', () => self.stop());
            window.addEventListener('pageshow', () => self.stop());
            window.addEventListener('load', () => self.stop());
            window.addEventListener('DOMContentLoaded', () => self.stop());
            window.addEventListener('focus', () => self.stop());
            window.addEventListener('visibilitychange', () => { if (document.visibilityState === 'visible') self.stop(); });
            this.$watch('loading', (v) => { if (v) setTimeout(() => { if (self.loading) self.stop(); }, self.maxDisplayMs); });
            this.stop();
        }
    }"
    x-show="loading"
    x-cloak
    x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-250"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @keydown.escape.window="if (longWait) dismiss()"
    role="status"
    aria-live="polite"
    aria-modal="true"
    :aria-label="contextMessage"
    :aria-hidden="!loading"
    class="fixed inset-0 z-[9999] flex items-center justify-center overflow-hidden bg-black/25 dark:bg-black/50 backdrop-blur-md"
>
    {{-- Card central (glass, Vertex) – spinner + mensagem --}}
    <div
        class="vertex-loading-card flex flex-col items-center justify-center gap-6 rounded-2xl px-8 py-10 shadow-2xl border border-white/20 dark:border-white/10 bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl min-w-[220px] max-w-[90vw]"
        aria-hidden="true"
    >
        {{-- Spinner (anel contínuo, cor primary) --}}
        <div class="vertex-loading-spinner relative flex items-center justify-center text-primary" aria-hidden="true">
            <svg class="vertex-loading-ring" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle class="vertex-loading-track" cx="32" cy="32" r="28" stroke="currentColor" stroke-width="4" fill="none" opacity="0.2" />
                <circle class="vertex-loading-fill" cx="32" cy="32" r="28" stroke="currentColor" stroke-width="4" fill="none" stroke-linecap="round" stroke-dasharray="88 176" stroke-dashoffset="44" />
            </svg>
        </div>

        {{-- Mensagem contextual --}}
        <p class="text-center text-sm font-semibold text-gray-800 dark:text-slate-100 max-w-[280px]" x-text="contextMessage"></p>

        {{-- Opcional: marca Vertex EAD --}}
        @if ($showBrand)
            <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-slate-400 font-medium">
                <x-icon name="graduation-cap" style="duotone" class="w-4 h-4 text-primary" />
                <span>Vertex Escola de Pastores</span>
            </div>
        @endif

        {{-- Espera longa: mensagem + ação (WCAG, Escape para fechar) --}}
        <div
                x-show="longWait"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-150"
                class="w-full text-center space-y-3 pt-2 border-t border-gray-200/80 dark:border-slate-600/50"
        >
            <p class="text-xs text-amber-600 dark:text-amber-400 font-medium">Está demorando mais que o esperado.</p>
            <button
                type="button"
                @click="dismiss()"
                class="text-xs font-semibold text-primary hover:underline underline-offset-2 transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 rounded px-2 py-1"
            >
                Fechar e continuar na página
            </button>
        </div>
    </div>

    <span class="sr-only" x-text="contextMessage"></span>
</div>

<style>
    .vertex-loading-spinner .vertex-loading-ring {
        width: 56px;
        height: 56px;
    }
    .vertex-loading-fill {
        transform-origin: 50% 50%;
        animation: vertex-loading-spin 1.4s cubic-bezier(0.5, 0.2, 0.5, 0.8) infinite;
    }
    @keyframes vertex-loading-spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    @media (prefers-reduced-motion: reduce) {
        .vertex-loading-fill {
            animation-duration: 2.5s;
        }
    }
</style>

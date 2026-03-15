@once
@push('styles')
<style>
    /* Barra auxiliadora: mesmo padrão visual do card "Selecione uma Versão" */
    .bible-reader-bar {
        z-index: 20;
    }
    .bible-reader-bar-wrapper {
        margin-bottom: 1.5rem;
    }
    .bible-reader-bar__actions {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: flex-end;
        gap: 0.5rem;
    }
    .bible-reader-bar__actions .bible-reader-bar-btn {
        flex-shrink: 0;
    }
    @media (max-width: 480px) {
        .bible-reader-bar .bible-reader-bar-btn .bible-reader-bar-btn__label {
            display: none;
        }
        .bible-reader-bar .bible-reader-bar-btn {
            padding-left: 0.625rem;
            padding-right: 0.625rem;
        }
        .bible-reader-bar .bible-reader-bar-btn .bible-reader-bar-btn__icon {
            margin-right: 0;
        }
    }

    /* Temas de leitura (Kindle / pergaminho) – variáveis aplicadas também via JS */
    .bible-reading-area {
        --bible-bg: #f5f0e6;
        --bible-text: #2d2a26;
        --bible-muted: #6b6560;
        --bible-border: rgba(0,0,0,0.06);
        background-color: var(--bible-bg) !important;
        color: var(--bible-text);
    }
    .bible-reading-area .bible-verse-text {
        color: var(--bible-text) !important;
    }
    .bible-reading-area .bible-verse-num {
        color: var(--bible-muted) !important;
        background-color: var(--bible-border) !important;
    }

    /* Modo foco (igual interlinear): remover sidebar, overlay mobile e navbar do layout */
    body.bible-focus-mode-active .flex.min-h-screen > aside,
    body.bible-focus-mode-active .flex.min-h-screen > div.fixed.inset-0,
    body.bible-focus-mode-active .flex.min-h-screen .flex-1 > header {
        display: none !important;
    }

    /* Overlay modo foco: fixed inset-0 como interlinear, z-index acima do sidebar (z-50) */
    #bible-chapter.bible-fullscreen {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        z-index: 9999 !important;
        overflow-y: auto !important;
        overflow-x: hidden !important;
        padding: 2rem 1rem 5rem !important;
        background: var(--bible-bg, #f5f0e6) !important;
    }
    #bible-chapter.bible-fullscreen .bible-reader-bar,
    #bible-chapter.bible-fullscreen .bible-bottom-nav,
    #bible-chapter.bible-fullscreen [data-bible-hide-in-fullscreen] {
        display: none !important;
    }
    #bible-chapter.bible-fullscreen .bible-reading-area {
        max-width: 36rem;
        margin: 0 auto;
        padding: 2rem 0;
    }
    #bible-chapter.bible-fullscreen .bible-fullscreen-exit-bar:not([hidden]) {
        opacity: 1 !important;
        pointer-events: auto !important;
    }

    #bible-chapter .bible-reading-area {
        scroll-margin-top: 6rem;
    }
</style>
@endpush
@endonce

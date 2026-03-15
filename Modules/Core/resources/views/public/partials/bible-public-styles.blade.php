{{-- Design system: Bíblia pública – visual de livro sagrado, Font Awesome Duotone --}}
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=merriweather:400,700,900&display=swap" rel="stylesheet" />
<style>
    :root {
        --bible-serif: 'Merriweather', Georgia, 'Times New Roman', serif;
        --bible-bg: #f7f3eb;
        --bible-bg-end: #efe9df;
        --bible-card: rgba(255, 255, 255, 0.92);
        --bible-border: rgba(180, 160, 120, 0.35);
        --bible-border-strong: rgba(139, 119, 101, 0.5);
        --bible-accent: #8b6914;
        --bible-accent-soft: rgba(139, 105, 20, 0.12);
        --bible-verse-num-bg: linear-gradient(135deg, #8b6914 0%, #a67c1a 100%);
        --bible-verse-num-shadow: 0 2px 8px rgba(139, 105, 20, 0.35);
        --bible-at: #b45309;
        --bible-nt: #047857;
        --bible-text: #2c2825;
        --bible-muted: #6b5b4f;
        /* Modo comparação: versão atual (que você está lendo) vs versão comparada */
        --bible-compare-current: #1c1917;
        --bible-compare-current-bg: rgba(139, 105, 20, 0.08);
        --bible-compare-other: #0f766e;
        --bible-compare-other-bg: rgba(15, 118, 110, 0.08);
    }
    .dark {
        --bible-bg: #1a1814;
        --bible-bg-end: #141210;
        --bible-card: rgba(30, 27, 24, 0.95);
        --bible-border: rgba(120, 100, 80, 0.3);
        --bible-border-strong: rgba(160, 140, 110, 0.4);
        --bible-accent: #c9a227;
        --bible-accent-soft: rgba(201, 162, 39, 0.15);
        --bible-verse-num-bg: linear-gradient(135deg, #8b6914 0%, #c9a227 100%);
        --bible-verse-num-shadow: 0 2px 10px rgba(139, 105, 20, 0.4);
        --bible-at: #d97706;
        --bible-nt: #059669;
        --bible-text: #e8e4dc;
        --bible-muted: #9c8f7a;
        --bible-compare-current: #e8e4dc;
        --bible-compare-current-bg: rgba(201, 162, 39, 0.12);
        --bible-compare-other: #5eead4;
        --bible-compare-other-bg: rgba(94, 234, 212, 0.1);
    }

    .bible-public-container {
        font-family: var(--bible-serif);
        background-color: var(--bible-bg);
        background-image: linear-gradient(180deg, var(--bible-bg) 0%, var(--bible-bg-end) 100%);
        min-height: 100vh;
    }

    .bible-public-header {
        background: var(--bible-card);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-bottom: 1px solid var(--bible-border);
    }

    .bible-card {
        background: var(--bible-card);
        border: 1px solid var(--bible-border);
        border-radius: 1rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    }
    .dark .bible-card { box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2); }

    .bible-btn-back {
        color: var(--bible-muted);
        background: var(--bible-bg);
        border: 1px solid var(--bible-border);
    }
    .bible-btn-back:hover { color: var(--bible-text); border-color: var(--bible-border-strong); }

    /* Número de versículo: estilo clássico (escritura), discreto e profissional */
    .bible-verse-num {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 1.75rem;
        font-family: var(--bible-serif);
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--bible-muted);
        flex-shrink: 0;
    }

    .bible-chapter-num {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 2.25rem;
        font-family: system-ui, sans-serif;
        font-size: 0.875rem;
        font-weight: 700;
        color: #fff;
        background: var(--bible-verse-num-bg);
        border-radius: 0.25rem;
        box-shadow: var(--bible-verse-num-shadow);
    }

    .bible-reading-column {
        max-width: 42rem;
        line-height: 1.9;
        color: var(--bible-text);
        font-size: 1.0625rem;
    }

    /* Página de leitura: fundo de escritura, sóbrio e profissional */
    .bible-page {
        background: var(--bible-card);
        border: 1px solid var(--bible-border);
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        padding: 2.5rem 2rem;
    }
    .dark .bible-page {
        box-shadow: 0 1px 3px rgba(0,0,0,0.15);
    }

    /* Título do capítulo (centrado, estilo referência) */
    .bible-chapter-heading {
        font-family: var(--bible-serif);
        font-weight: 700;
        font-size: 1.25rem;
        letter-spacing: 0.02em;
        color: var(--bible-text);
        text-align: center;
        margin-bottom: 0.25rem;
    }
    .bible-chapter-theme {
        font-family: var(--bible-serif);
        font-size: 1rem;
        color: var(--bible-muted);
        text-align: center;
        margin-bottom: 2rem;
    }

    /* Linha de versículo: número à esquerda, texto alinhado (padrão Bíblia) */
    .bible-verse-line {
        display: flex;
        align-items: flex-start;
        gap: 0.625rem;
        padding: 0.375rem 0;
        margin-bottom: 0.125rem;
        scroll-margin-top: 8rem; /* ao abrir link da busca (#v10), o versículo fica visível abaixo do header fixo */
    }
    .bible-verse-line:hover {
        background: var(--bible-accent-soft);
    }
    /* Destaque do versículo quando veio da busca (clique no resultado): fixa a atenção no que o leitor procurou */
    .bible-verse-line:target {
        background: var(--bible-accent-soft);
        box-shadow: inset 3px 0 0 var(--bible-accent);
        padding-left: 0.5rem;
        margin-left: -0.5rem;
    }
    .bible-verse-line .verse-num {
        flex-shrink: 0;
        min-width: 1.75rem;
        font-family: var(--bible-serif);
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--bible-muted);
    }
    .bible-verse-line .verse-text {
        flex: 1;
        min-width: 0;
        font-family: var(--bible-serif);
        color: var(--bible-text);
        line-height: 1.9;
    }

    /* Barra de capítulos: limpa e organizada */
    .bible-chapter-pill {
        flex-shrink: 0;
        min-width: 2.25rem;
        height: 2.25rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.25rem;
        font-size: 0.8125rem;
        font-weight: 600;
        transition: all 0.15s;
    }
    .bible-chapter-pill.current {
        color: #fff;
        background: var(--bible-verse-num-bg);
    }
    .bible-chapter-pill:not(.current) {
        color: var(--bible-muted);
        border: 1px solid var(--bible-border);
        background: transparent;
    }
    .bible-chapter-pill:not(.current):hover {
        border-color: var(--bible-accent);
        color: var(--bible-accent);
    }

    /* Modo comparação: cores distintas por versão */
    .bible-compare-current { color: var(--bible-compare-current); }
    .bible-compare-current-bg { background: var(--bible-compare-current-bg); }
    .bible-compare-other { color: var(--bible-compare-other); }
    .bible-compare-other-bg { background: var(--bible-compare-other-bg); }

    .bible-accent { color: var(--bible-accent); }
    .bible-at-accent { color: var(--bible-at); }
    .bible-nt-accent { color: var(--bible-nt); }

    [x-cloak] { display: none !important; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    body.bible-reading-mode > nav,
    body.bible-reading-mode > footer { display: none !important; }
</style>

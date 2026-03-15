// Vertex Escola de Pastores – app entry (tudo local, sem CDN)
import '../css/app.css';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// Tema claro/escuro: um único listener, troca rápida sem travar
(function () {
  const THEME_KEY = 'theme';
  const ROOT = document.documentElement;

  function applyTheme(isDark) {
    ROOT.classList.toggle('dark', isDark);
    ROOT.setAttribute('data-theme', isDark ? 'dark' : 'light');
  }

  function getPreferredDark() {
    const stored = localStorage.getItem(THEME_KEY);
    if (stored === 'dark' || stored === 'light') return stored === 'dark';
    return window.matchMedia('(prefers-color-scheme: dark)').matches;
  }

  // Aplicar tema na carga (já feito no <head>; reforço para ícones)
  applyTheme(getPreferredDark());

  document.body.addEventListener('click', function (e) {
    const btn = e.target.closest('[data-theme-toggle]');
    if (!btn) return;
    e.preventDefault();
    const isDark = !ROOT.classList.contains('dark');
    applyTheme(isDark);
    localStorage.setItem(THEME_KEY, isDark ? 'dark' : 'light');
  });
})();

// Bible @mentions: init when an editor with data-mention-editor="true" is present (e.g. Ministry materials)
(function () {
  function init() {
    if (!document.querySelector('[data-mention-editor="true"]')) return;
    import('./components/mention-autocomplete.js').then((m) => {
      if (typeof m.initBibleMentionAutocomplete === 'function') m.initBibleMentionAutocomplete();
    }).catch(() => {});
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
  else init();
})();

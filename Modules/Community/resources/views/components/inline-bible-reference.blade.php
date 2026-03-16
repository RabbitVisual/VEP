@props(['ref'])
@php
  $refSafe = e($ref);
@endphp
<span
  x-data="{
    expanded: false,
    text: null,
    loading: false,
    error: false,
    async fetchVerse() {
      if (this.text !== null || this.loading) return;
      this.loading = true;
      this.error = false;
      try {
        const res = await fetch(`/api/v1/bible/find?ref=${encodeURIComponent('{{ $refSafe }}')}`, { headers: { 'Accept': 'application/json' } });
        const data = await res.json();
        if (res.ok && data.data && data.data.verses) {
          this.text = data.data.verses.map(v => v.text).join(' ');
        } else {
          this.error = true;
        }
      } catch (e) {
        this.error = true;
      } finally {
        this.loading = false;
        this.expanded = true;
      }
    },
    toggle() {
      if (!this.expanded && this.text === null && !this.loading) this.fetchVerse();
      else this.expanded = !this.expanded;
    }
  }"
  class="inline"
>
  <button
    type="button"
    @click="toggle()"
    class="inline-flex items-center gap-1 rounded px-1.5 py-0.5 text-sm font-medium text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/20 border border-amber-200/80 dark:border-amber-700/80 transition-colors"
    :disabled="typeof loading !== 'undefined' && loading"
  >
    <i class="fa-duotone fa-book-bible text-xs"></i>
    <span>{{ '@' . $refSafe }}</span>
  </button>
  <div
    x-show="typeof expanded !== 'undefined' && expanded"
    x-cloak
    x-transition
    class="mt-2 rounded-r-lg border-l-4 border-indigo-500 bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm shadow-sm p-3"
  >
    <template x-if="typeof loading !== 'undefined' && loading">
      <p class="text-slate-500 dark:text-slate-400 text-sm italic">Carregando...</p>
    </template>
    <template x-if="typeof error !== 'undefined' && error && !(typeof loading !== 'undefined' && loading)">
      <p class="text-slate-500 dark:text-slate-400 text-sm italic">Texto não encontrado.</p>
    </template>
    <template x-if="typeof text !== 'undefined' && text && !(typeof loading !== 'undefined' && loading)">
      <p class="font-serif text-slate-600 dark:text-slate-300 text-sm italic" x-text="text"></p>
    </template>
  </div>
</span>

<x-memberpanel::layouts.master title="Verse Explainer">
    <div class="p-6 space-y-6">
        {{-- Header (mesmo padrão do dashboard) --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="space-y-1">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight flex items-center gap-2">
                    <x-icon name="brain-circuit" style="duotone" class="w-9 h-9 text-indigo-500" />
                    Explicador de Versículos
                </h1>
                <p class="text-gray-600 dark:text-gray-400">Digite uma referência bíblica e receba uma explicação com base nos comentários. Use @mentions na resposta para navegar à Bíblia.</p>
            </div>
        </div>

        <div
            x-data="{
                reference: '',
                question: '',
                messages: [],
                loading: false,
                lastVerseText: null,
                lastVerseReference: null,
                send() {
                    const ref = (this.reference || '').trim();
                    if (!ref) return;
                    this.messages.push({ role: 'user', content: ref + (this.question ? '\n\nPergunta: ' + this.question : '') });
                    this.loading = true;
                    this.lastVerseText = null;
                    this.lastVerseReference = null;
                    const formData = new FormData();
                    formData.append('reference', ref);
                    formData.append('question', this.question);
                    formData.append('_token', document.querySelector('meta[name=csrf-token]').content);
                    this.reference = '';
                    this.question = '';
                    fetch('{{ route('painel.verse-explainer.explain') }}', { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                        .then(r => r.json())
                        .then(d => {
                            this.messages.push({ role: 'assistant', content: d.content || '', contentHtml: d.content_html || '' });
                            if (d.verse_text) this.lastVerseText = d.verse_text;
                            if (d.verse_reference) this.lastVerseReference = d.verse_reference;
                        })
                        .catch(() => this.messages.push({ role: 'assistant', content: 'Erro ao processar. Tente novamente.', contentHtml: '' }))
                        .finally(() => this.loading = false);
                }
            }"
        >
            {{-- Conexão exegética: texto bíblico da última referência (card no estilo dashboard) --}}
            <div x-show="lastVerseText && lastVerseReference"
                 x-cloak
                 class="rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/30 backdrop-blur-xl p-4 shadow-sm">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-icon name="book-bible" style="duotone" class="h-4 w-4 text-indigo-500 dark:text-indigo-400" />
                    <span x-text="lastVerseReference"></span>
                    <a :href="'{{ route('painel.bible.search') }}?ref=' + encodeURIComponent(lastVerseReference || '')"
                       class="ml-auto font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300">
                        Abrir na Bíblia
                    </a>
                </div>
                <p class="mt-2 text-gray-800 dark:text-gray-200" x-text="lastVerseText"></p>
            </div>

            {{-- Card principal (estilo dashboard: backdrop-blur, bordas e hover) --}}
            <div class="backdrop-blur-xl bg-white/70 dark:bg-white/10 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg transition-all duration-300">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-start gap-4">
                    <div class="shrink-0 w-12 h-12 rounded-xl flex items-center justify-center bg-indigo-500/20">
                        <x-icon name="microchip-ai" style="duotone" class="w-6 h-6 text-indigo-600 dark:text-indigo-400" />
                    </div>
                    <div class="min-w-0 flex-1">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white truncate">Explicador de Versículos</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Resposta com @mentions para navegar aos versículos</p>
                    </div>
                </div>
                <div class="flex flex-col min-h-[200px]">
                    <div class="flex-1 overflow-y-auto p-4 space-y-1">
                        <template x-for="(msg, i) in messages" :key="i">
                            <div :class="msg.role === 'user' ? 'flex gap-3 px-4 py-3 justify-end' : 'flex gap-3 px-4 py-3 justify-start'">
                                <template x-if="msg.role !== 'user'">
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-indigo-500/20 text-indigo-600 dark:text-indigo-400">
                                        <x-icon name="microchip-ai" style="duotone" class="text-sm" />
                                    </div>
                                </template>
                                <div :class="msg.role === 'user' ? 'max-w-[85%] rounded-xl px-4 py-3 text-sm bg-indigo-600 text-white' : 'max-w-[85%] rounded-xl px-4 py-3 text-sm bg-gray-100 dark:bg-gray-800/60 text-gray-800 dark:text-gray-200 border border-gray-200 dark:border-gray-700'">
                                    <template x-if="msg.role === 'user'">
                                        <p class="whitespace-pre-wrap" x-text="msg.content"></p>
                                    </template>
                                    <template x-if="msg.role === 'assistant'">
                                        <div class="prose prose-sm dark:prose-invert max-w-none prose-p:my-1.5 prose-ul:my-1.5 prose-li:my-0.5 prose-a:text-indigo-600 dark:prose-a:text-indigo-400 prose-a:no-underline hover:prose-a:underline" x-html="msg.contentHtml || msg.content"></div>
                                    </template>
                                </div>
                                <template x-if="msg.role === 'user'">
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400">
                                        <x-icon name="user" style="duotone" class="text-sm" />
                                    </div>
                                </template>
                            </div>
                        </template>
                        <div x-show="loading" x-cloak>
                            <x-ai.loading-state message="Explicando versículo..." />
                        </div>
                    </div>
                    <div class="space-y-2 border-t border-gray-200 dark:border-gray-700 p-4 bg-gray-50/50 dark:bg-gray-800/30">
                        <input
                            type="text"
                            x-model="reference"
                            placeholder="Ex: João 3:16 ou Salmos 23:1-3"
                            class="w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800/60 px-4 py-2.5 text-sm text-gray-900 dark:text-gray-100 placeholder-gray-500 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50"
                            :disabled="loading"
                            @keydown.enter.prevent="send()"
                        />
                        <input
                            type="text"
                            x-model="question"
                            placeholder="Pergunta opcional (ex: Qual o contexto histórico?)"
                            class="w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800/60 px-4 py-2.5 text-sm text-gray-900 dark:text-gray-100 placeholder-gray-500 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50"
                            :disabled="loading"
                        />
                        <button
                            type="button"
                            @click="send()"
                            class="w-full rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-indigo-500 disabled:opacity-50"
                            :disabled="!reference.trim() || loading"
                        >
                            Explicar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-memberpanel::layouts.master>

<x-pastoralpanel::layouts.master>
    <div class="min-h-screen bg-background py-6 px-4">
        <div class="mx-auto max-w-[1600px]">
            <header class="mb-4">
                <h1 class="text-2xl font-bold text-foreground">Assistente de Exegese</h1>
                <p class="mt-1 text-sm text-muted-foreground">Selecione um versículo, estude o texto original e converse com a IA.</p>
            </header>

            <div
                x-data="{
                    books: @js($books->map(fn ($b) => ['id' => $b->id, 'name' => $b->name, 'book_number' => $b->book_number])->values()),
                    chapters: [],
                    verses: [],
                    bookId: null,
                    chapterId: null,
                    chapterNumber: null,
                    bookNumber: null,
                    verseNumber: null,
                    interlinearVerseId: null,
                    referenceLabel: '',
                    segments: [],
                    segmentsLoading: false,
                    strongSlideOver: null,
                    messages: [],
                    input: '',
                    loading: false,
                    loadChapters() {
                        if (! this.bookId) return;
                        this.loading = true;
                        fetch(`/pastoral/exegesis-assistant/chapters?book_id=${this.bookId}`, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                            .then(r => r.json())
                            .then(d => { this.chapters = d.chapters || []; this.chapterNumber = null; this.verses = []; this.verseNumber = null; this.interlinearVerseId = null; this.referenceLabel = ''; this.segments = []; })
                            .finally(() => this.loading = false);
                    },
                    loadVerses() {
                        if (! this.bookId || this.chapterNumber == null) return;
                        this.loading = true;
                        fetch(`/pastoral/exegesis-assistant/verses?book_id=${this.bookId}&chapter_number=${this.chapterNumber}`, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                            .then(r => r.json())
                            .then(d => {
                                this.verses = d.verses || [];
                                this.bookNumber = d.book_number;
                                this.verseNumber = null;
                                this.interlinearVerseId = null;
                                this.referenceLabel = '';
                                this.segments = [];
                            })
                            .finally(() => this.loading = false);
                    },
                    onVerseSelect() {
                        if (this.verseNumber == null || this.bookNumber == null || this.chapterNumber == null) return;
                        const bookName = this.books.find(b => b.id == this.bookId)?.name || '';
                        this.referenceLabel = `${bookName} ${this.chapterNumber}:${this.verseNumber}`;
                        this.loading = true;
                        fetch(`/pastoral/exegesis-assistant/interlinear-verse?book_number=${this.bookNumber}&chapter_number=${this.chapterNumber}&verse_number=${this.verseNumber}`, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                            .then(r => r.json())
                            .then(d => {
                                this.interlinearVerseId = d.interlinear_verse_id;
                                if (d.interlinear_verse_id) this.loadInterlinearData(d.interlinear_verse_id);
                            })
                            .finally(() => this.loading = false);
                    },
                    loadInterlinearData(interlinearVerseId) {
                        this.segments = [];
                        this.segmentsLoading = true;
                        fetch(`/pastoral/exegesis-assistant/interlinear-data?interlinear_verse_id=${interlinearVerseId}`, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                            .then(r => r.json())
                            .then(d => { this.segments = d.segments || []; })
                            .finally(() => this.segmentsLoading = false);
                    },
                    openStrong(segment) {
                        this.strongSlideOver = segment;
                    },
                    closeStrong() {
                        this.strongSlideOver = null;
                    },
                    sendMessage() {
                        const msg = (this.input || '').trim();
                        if (! msg || this.interlinearVerseId == null) return;
                        this.messages.push({ role: 'user', content: msg });
                        this.input = '';
                        this.loading = true;
                        const formData = new FormData();
                        formData.append('interlinear_verse_id', this.interlinearVerseId);
                        formData.append('message', msg);
                        formData.append('_token', document.querySelector('meta[name=csrf-token]').content);
                        fetch('{{ route("pastoral.exegesis-assistant.chat") }}', { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                            .then(r => {
                                return r.json().then(d => ({ status: r.status, data: d }));
                            })
                            .then(({ status, data: d }) => {
                                if (status === 429) {
                                    this.messages.push({ role: 'assistant', content: d.message || 'Calma, pastor! Vamos estudar um versículo por vez? Aguarde um minuto.', contentHtml: '' });
                                    return;
                                }
                                if (status === 503 || d.ai_unavailable) {
                                    this.messages.push({ role: 'assistant', content: d.message || 'O assistente de IA está temporariamente indisponível. Você ainda pode estudar o texto original e Strong ao lado.', contentHtml: '' });
                                    return;
                                }
                                this.messages.push({ role: 'assistant', content: d.content || '', contentHtml: d.content_html || '' });
                            })
                            .catch(() => this.messages.push({ role: 'assistant', content: 'Erro ao processar. Tente novamente.', contentHtml: '' }))
                            .finally(() => this.loading = false);
                    }
                }"
                x-init="books = books || []"
            >
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-12">
                    {{-- Col 1: Seletor (3 cols) --}}
                    <aside class="lg:col-span-3 rounded-xl border border-border bg-card/80 p-4 relative">
                        <div class="flex items-center gap-2 text-card-foreground mb-3">
                            <x-icon name="book-bible" style="duotone" class="w-5 h-5 text-primary" />
                            <h2 class="text-sm font-semibold">Versículo</h2>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-medium text-muted-foreground mb-1">Livro</label>
                                <select x-model="bookId" @change="loadChapters()" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm">
                                    <option value="">Selecione</option>
                                    <template x-for="b in books" :key="b.id">
                                        <option :value="b.id" x-text="b.name"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-muted-foreground mb-1">Capítulo</label>
                                <select x-model="chapterNumber" @change="loadVerses()" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" :disabled="!chapters.length">
                                    <option value="">Selecione</option>
                                    <template x-for="c in chapters" :key="c.id">
                                        <option :value="c.chapter_number" x-text="c.chapter_number"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-muted-foreground mb-1">Versículo</label>
                                <select x-model="verseNumber" @change="onVerseSelect()" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm" :disabled="!verses.length">
                                    <option value="">Selecione</option>
                                    <template x-for="v in verses" :key="v.id">
                                        <option :value="v.verse_number" x-text="v.verse_number"></option>
                                    </template>
                                </select>
                            </div>
                            <p x-show="referenceLabel" class="text-xs text-primary font-medium" x-text="referenceLabel"></p>
                        </div>
                        <div x-show="loading" x-cloak class="absolute inset-0 rounded-xl bg-background/80 flex items-center justify-center">
                            <x-ai.loading-state message="Carregando..." />
                        </div>
                    </aside>

                    {{-- Col 2: Estudo Original (5 cols) --}}
                    <section class="lg:col-span-5 rounded-xl border border-border bg-card/80 p-4 min-h-[320px] flex flex-col">
                        <div class="flex items-center gap-2 text-card-foreground mb-3">
                            <x-icon name="book-bible" style="duotone" class="w-5 h-5 text-primary" />
                            <h2 class="text-sm font-semibold">Estudo original</h2>
                        </div>
                        <div class="flex-1">
                            <div x-show="segmentsLoading" x-cloak class="flex items-center justify-center py-12">
                                <x-ai.loading-state message="Carregando interlinear..." />
                            </div>
                            <div x-show="!segmentsLoading && interlinearVerseId != null && segments.length === 0" x-cloak class="py-8 text-center text-muted-foreground text-sm">
                                Este versículo ainda não possui análise interlinear no banco.
                            </div>
                            <div x-show="!segmentsLoading && segments.length > 0" x-cloak class="font-serif text-lg leading-relaxed">
                                <template x-for="(seg, idx) in segments" :key="idx">
                                    <span>
                                        <button
                                            type="button"
                                            @click="openStrong(seg)"
                                            class="inline cursor-pointer rounded px-0.5 hover:bg-muted/80 focus:outline-none focus:ring-2 focus:ring-ring"
                                            :class="seg.language === 'G' ? 'text-indigo-600 dark:text-indigo-400' : (seg.language === 'H' ? 'text-amber-600 dark:text-amber-400' : 'text-foreground')"
                                            x-text="seg.word_original"
                                        ></button><span x-show="idx < segments.length - 1" class="text-foreground"> </span>
                                    </span>
                                </template>
                            </div>
                            <div x-show="interlinearVerseId == null && !segmentsLoading" class="py-8 text-center text-muted-foreground text-sm">
                                Selecione um versículo no seletor ao lado.
                            </div>
                        </div>
                    </section>

                    {{-- Col 3: Chat (4 cols) --}}
                    <div class="lg:col-span-4 flex flex-col min-h-[400px]">
                        <x-ai.chat-card title="Assistente de Exegese" :subtitle="'Baseado no texto interlinear (Strong)'" class="flex-1 flex flex-col backdrop-blur-sm bg-card/80">
                            <div class="flex items-center gap-2 text-card-foreground mb-2 px-2">
                                <x-icon name="sparkles" style="duotone" class="w-4 h-4 text-primary" />
                            </div>
                            <div class="flex-1 overflow-y-auto p-2 space-y-1" style="min-height: 200px;">
                                <template x-for="(msg, i) in messages" :key="i">
                                    <div :class="msg.role === 'user' ? 'flex gap-3 px-4 py-3 justify-end' : 'flex gap-3 px-4 py-3 justify-start'">
                                        <template x-if="msg.role !== 'user'">
                                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary">
                                                <x-icon name="microchip-ai" style="duotone" class="text-sm" />
                                            </div>
                                        </template>
                                        <div :class="msg.role === 'user' ? 'max-w-[85%] rounded-xl px-4 py-3 text-sm bg-primary text-primary-foreground' : 'max-w-[85%] rounded-xl px-4 py-3 text-sm bg-muted/60 dark:bg-muted/40 text-foreground border border-border/60'">
                                            <template x-if="msg.role === 'user'">
                                                <p class="whitespace-pre-wrap" x-text="msg.content"></p>
                                            </template>
                                            <template x-if="msg.role === 'assistant'">
                                                <div class="prose prose-sm dark:prose-invert max-w-none prose-p:my-1.5 prose-ul:my-1.5 prose-li:my-0.5 prose-a:text-primary prose-a:no-underline hover:prose-a:underline" x-html="msg.contentHtml || msg.content"></div>
                                            </template>
                                        </div>
                                        <template x-if="msg.role === 'user'">
                                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-muted text-muted-foreground">
                                                <x-icon name="user" style="duotone" class="text-sm" />
                                            </div>
                                        </template>
                                    </div>
                                </template>
                                <div x-show="loading" x-cloak>
                                    <x-ai.loading-state message="Analisando exegese..." />
                                </div>
                            </div>
                            <div class="p-3 border-t border-border/80">
                                <form @submit.prevent="sendMessage()" class="flex gap-2">
                                    <input
                                        type="text"
                                        x-model="input"
                                        placeholder="Pergunte sobre o versículo..."
                                        class="flex-1 rounded-lg border border-input bg-background px-4 py-2 text-sm focus:ring-2 focus:ring-ring"
                                        :disabled="interlinearVerseId == null || loading"
                                    />
                                    <button
                                        type="submit"
                                        class="rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:opacity-90 disabled:opacity-50"
                                        :disabled="!input.trim() || interlinearVerseId == null || loading"
                                    >
                                        Enviar
                                    </button>
                                </form>
                                <p x-show="interlinearVerseId == null && referenceLabel === ''" class="mt-2 text-xs text-muted-foreground">Selecione um versículo para começar.</p>
                            </div>
                        </x-ai.chat-card>
                    </div>
                </div>

                {{-- Slide-over Strong --}}
                <div
                    x-show="strongSlideOver !== null"
                    x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-x-4"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    class="fixed inset-y-0 right-0 z-50 w-full max-w-md border-l border-border bg-card shadow-xl"
                    @click.self="closeStrong()"
                >
                    <template x-if="strongSlideOver">
                        <div class="flex flex-col h-full">
                            <div class="flex items-center justify-between p-4 border-b border-border">
                                <h3 class="font-semibold text-card-foreground" x-text="strongSlideOver.word_original"></h3>
                                <button type="button" @click="closeStrong()" class="rounded-lg p-2 hover:bg-muted text-muted-foreground">
                                    <x-icon name="times" style="duotone" class="w-5 h-5" />
                                </button>
                            </div>
                            <div class="flex-1 overflow-y-auto p-4 space-y-3 text-sm">
                                <div x-show="strongSlideOver.strong_number" class="flex gap-2 items-center">
                                    <span class="font-medium text-muted-foreground">Strong</span>
                                    <span class="rounded bg-muted px-2 py-0.5 font-mono" x-text="strongSlideOver.language === 'G' ? 'G' + strongSlideOver.strong_number : (strongSlideOver.language === 'H' ? 'H' + strongSlideOver.strong_number : strongSlideOver.strong_number)"></span>
                                    <span class="text-muted-foreground" x-text="strongSlideOver.language === 'G' ? 'Grego' : (strongSlideOver.language === 'H' ? 'Hebraico' : '')"></span>
                                </div>
                                <div x-show="strongSlideOver.lemma" class="font-sans">
                                    <span class="text-muted-foreground">Lemma:</span>
                                    <span class="ml-1" x-text="strongSlideOver.lemma"></span>
                                </div>
                                <div x-show="strongSlideOver.lemma_br" class="font-sans">
                                    <span class="text-muted-foreground">Lemma (BR):</span>
                                    <span class="ml-1" x-text="strongSlideOver.lemma_br"></span>
                                </div>
                                <div x-show="strongSlideOver.part_of_speech" class="font-sans">
                                    <span class="text-muted-foreground">Classe:</span>
                                    <span class="ml-1" x-text="strongSlideOver.part_of_speech"></span>
                                </div>
                                <div x-show="strongSlideOver.definitions && strongSlideOver.definitions.length" class="pt-2 border-t border-border">
                                    <h4 class="font-medium text-card-foreground mb-2">Definições</h4>
                                    <ul class="list-disc list-inside space-y-1 text-muted-foreground">
                                        <template x-for="(def, di) in strongSlideOver.definitions" :key="di">
                                            <li class="font-sans" x-text="def.definition_text"></li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
                <div x-show="strongSlideOver !== null" x-cloak x-transition class="fixed inset-0 z-40 bg-black/30" @click="closeStrong()" aria-hidden="true"></div>
            </div>
        </div>
    </div>
</x-pastoralpanel::layouts.master>

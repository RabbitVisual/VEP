<x-homepage::components.layouts.master>
    {{-- Hero --}}
    <section class="relative overflow-hidden border-b border-border/40 bg-gradient-to-b from-primary/5 via-background to-background dark:from-primary/10">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-16 lg:px-8 lg:py-24">
            <div class="text-center">
                <a href="{{ route('home') }}" class="inline-block mb-6 sm:mb-8">
                    <img src="{{ asset('storage/logo/vertex-escola-de-pastores.svg') }}" alt="VEP" class="mx-auto h-12 max-w-full sm:h-14 dark:hidden" width="280" height="63" />
                    <img src="{{ asset('storage/logo/vertex-escola-de-pastores-dark.svg') }}" alt="VEP" class="mx-auto hidden h-12 max-w-full sm:h-14 dark:block" width="280" height="63" />
                </a>
                <p class="text-sm font-semibold uppercase tracking-wider text-primary">Plataforma EAD</p>
                <h1 class="mt-3 text-3xl font-bold tracking-tight text-foreground sm:text-4xl lg:text-6xl">
                    Formação de <span class="text-primary">Pastores</span> e Líderes
                </h1>
                <p class="mx-auto mt-6 max-w-2xl text-lg text-muted-foreground sm:text-xl">
                    Bíblia em múltiplas versões, planos de leitura, sermões e estudos bíblicos. Tudo em um só lugar para sua igreja e ministério.
                </p>
                <div class="mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row">
                    @if(Route::has('bible.public.index'))
                        <a href="{{ route('bible.public.index') }}" class="inline-flex w-full min-h-[48px] items-center justify-center gap-2 rounded-xl border-2 border-primary bg-primary px-6 py-3.5 text-base font-semibold text-primary-foreground shadow-lg transition-all duration-300 hover:opacity-90 sm:w-auto focus:ring-2 focus:ring-primary focus:ring-offset-2">
                            <x-icon name="book-bible" class="h-5 w-5 shrink-0" />
                            Acessar Bíblia
                        </a>
                    @endif
                    <a href="{{ route('register') }}" class="inline-flex w-full min-h-[48px] items-center justify-center gap-2 rounded-xl border-2 border-primary bg-background px-6 py-3.5 text-base font-semibold text-primary transition-all duration-300 hover:bg-primary/10 sm:w-auto focus:ring-2 focus:ring-primary focus:ring-offset-2">
                        Começar agora
                    </a>
                    <a href="{{ route('login') }}" class="inline-flex w-full min-h-[48px] items-center justify-center gap-2 rounded-xl border border-border bg-muted/50 px-6 py-3.5 text-base font-semibold text-foreground transition-all duration-300 hover:bg-muted sm:w-auto focus:ring-2 focus:ring-primary focus:ring-offset-2">
                        Já tenho conta
                    </a>
                </div>
                <p class="mt-6 text-xs text-muted-foreground">Conforme LGPD · Dados protegidos</p>
            </div>
        </div>
    </section>

    {{-- O que é a plataforma --}}
    <section class="border-b border-border/40 py-12 sm:py-16 lg:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-3xl text-center">
                <h2 class="text-2xl font-bold text-foreground sm:text-3xl">O que é o VEP?</h2>
                <p class="mt-6 text-lg leading-relaxed text-muted-foreground">
                    O <strong class="text-foreground">Vertex Escola de Pastores (VEP)</strong> é uma plataforma de ensino a distância pensada para <strong class="text-foreground">pastores, líderes e alunos</strong>. Aqui você tem acesso à Bíblia em várias versões, cria e acompanha planos de leitura, organiza sermões e estudos, e usa ferramentas como concordância e anotações — tudo integrado e em conformidade com a <strong class="text-foreground">LGPD</strong>.
                </p>
                <p class="mt-4 text-muted-foreground">
                    Desenvolvida pela <strong class="text-foreground">Vertex Solutions LTDA</strong>, a plataforma une conteúdo bíblico de qualidade e tecnologia para apoiar igrejas e ministérios.
                </p>
            </div>
        </div>
    </section>

    {{-- Para quem é --}}
    <section class="border-b border-border/40 bg-muted/20 py-16 sm:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-center text-3xl font-bold text-foreground sm:text-4xl">Para quem é a plataforma</h2>
            <p class="mx-auto mt-4 max-w-2xl text-center text-muted-foreground">Pastores, colaboradores e alunos encontram ferramentas feitas para cada etapa da formação e do ministério.</p>
            <div class="mt-10 sm:mt-14 grid gap-6 sm:gap-8 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
                <div class="rounded-2xl border border-border/60 bg-card p-4 sm:p-6 shadow-sm transition-all duration-300 hover:shadow-md hover:border-primary/30">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-primary/15 text-primary">
                        <x-icon name="user-tie" class="h-7 w-7" />
                    </div>
                    <h3 class="mt-5 text-xl font-semibold text-foreground">Pastor</h3>
                    <p class="mt-2 text-muted-foreground">Organize sermões, comentários e estudos. Crie planos de leitura para a igreja. Use concordância Strong e Bíblia interlinear no preparo das mensagens.</p>
                    <ul class="mt-4 space-y-2 text-sm text-muted-foreground">
                        <li class="flex items-center gap-2"><x-icon name="circle-check" class="h-4 w-4 shrink-0 text-primary" /> Sermões e séries</li>
                        <li class="flex items-center gap-2"><x-icon name="circle-check" class="h-4 w-4 shrink-0 text-primary" /> Planos de leitura</li>
                        <li class="flex items-center gap-2"><x-icon name="circle-check" class="h-4 w-4 shrink-0 text-primary" /> Estudos bíblicos</li>
                    </ul>
                </div>
                <div class="rounded-2xl border border-border/60 bg-card p-4 sm:p-6 shadow-sm transition-all duration-300 hover:shadow-md hover:border-primary/30">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-primary/15 text-primary">
                        <x-icon name="users" class="h-7 w-7" />
                    </div>
                    <h3 class="mt-5 text-xl font-semibold text-foreground">Líder / Colaborador</h3>
                    <p class="mt-2 text-muted-foreground">Apoie a formação da igreja com acesso às mesmas ferramentas: Bíblia, planos de leitura e conteúdo de ensino para compartilhar com grupos e discipulado.</p>
                    <ul class="mt-4 space-y-2 text-sm text-muted-foreground">
                        <li class="flex items-center gap-2"><x-icon name="circle-check" class="h-4 w-4 shrink-0 text-primary" /> Conteúdo para grupos</li>
                        <li class="flex items-center gap-2"><x-icon name="circle-check" class="h-4 w-4 shrink-0 text-primary" /> Acompanhamento</li>
                        <li class="flex items-center gap-2"><x-icon name="circle-check" class="h-4 w-4 shrink-0 text-primary" /> Múltiplas versões</li>
                    </ul>
                </div>
                <div class="rounded-2xl border border-border/60 bg-card p-4 sm:p-6 shadow-sm transition-all duration-300 hover:shadow-md hover:border-primary/30 sm:col-span-2 lg:col-span-1">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-primary/15 text-primary">
                        <x-icon name="graduation-cap" class="h-7 w-7" />
                    </div>
                    <h3 class="mt-5 text-xl font-semibold text-foreground">Aluno</h3>
                    <p class="mt-2 text-muted-foreground">Acompanhe seus planos de leitura, salve versículos favoritos e anotações. Leia a Bíblia em várias versões e use a busca para aprofundar seus estudos.</p>
                    <ul class="mt-4 space-y-2 text-sm text-muted-foreground">
                        <li class="flex items-center gap-2"><x-icon name="circle-check" class="h-4 w-4 shrink-0 text-primary" /> Favoritos e anotações</li>
                        <li class="flex items-center gap-2"><x-icon name="circle-check" class="h-4 w-4 shrink-0 text-primary" /> Busca na Bíblia</li>
                        <li class="flex items-center gap-2"><x-icon name="circle-check" class="h-4 w-4 shrink-0 text-primary" /> Progresso de leitura</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- O que você tem acesso --}}
    <section class="py-16 sm:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-center text-3xl font-bold text-foreground sm:text-4xl">O que você tem acesso</h2>
            <p class="mx-auto mt-4 max-w-2xl text-center text-muted-foreground">Recursos pensados para formação bíblica e ministério.</p>
            <div class="mt-10 sm:mt-14 grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
                <div class="flex flex-col gap-4 rounded-2xl border border-border/60 bg-card/50 p-4 sm:p-6 transition-all duration-300 hover:border-primary/30 hover:shadow-md sm:flex-row">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                        <x-icon name="book-bible" class="h-6 w-6" />
                    </div>
                    <div>
                        <h3 class="font-semibold text-foreground">Bíblia em múltiplas versões</h3>
                        <p class="mt-1 text-sm text-muted-foreground">Leia e compare versões. Busca por palavra e navegação por livro e capítulo.</p>
                    </div>
                </div>
                <div class="flex flex-col gap-4 rounded-2xl border border-border/60 bg-card/50 p-4 sm:p-6 transition-all duration-300 hover:border-primary/30 hover:shadow-md sm:flex-row">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                        <x-icon name="book-open-reader" class="h-6 w-6" />
                    </div>
                    <div>
                        <h3 class="font-semibold text-foreground">Planos de leitura</h3>
                        <p class="mt-1 text-sm text-muted-foreground">Crie e siga planos de leitura. Acompanhe seu progresso.</p>
                    </div>
                </div>
                <div class="flex flex-col gap-4 rounded-2xl border border-border/60 bg-card/50 p-4 sm:p-6 transition-all duration-300 hover:border-primary/30 hover:shadow-md sm:flex-row">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                        <x-icon name="microphone" class="h-6 w-6" />
                    </div>
                    <div>
                        <h3 class="font-semibold text-foreground">Sermões e estudos</h3>
                        <p class="mt-1 text-sm text-muted-foreground">Organize sermões, comentários e estudos bíblicos em um só lugar.</p>
                    </div>
                </div>
                <div class="flex flex-col gap-4 rounded-2xl border border-border/60 bg-card/50 p-4 sm:p-6 transition-all duration-300 hover:border-primary/30 hover:shadow-md sm:flex-row">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                        <x-icon name="magnifying-glass" class="h-6 w-6" />
                    </div>
                    <div>
                        <h3 class="font-semibold text-foreground">Concordância e busca</h3>
                        <p class="mt-1 text-sm text-muted-foreground">Concordância Strong e busca avançada para estudo aprofundado.</p>
                    </div>
                </div>
                <div class="flex flex-col gap-4 rounded-2xl border border-border/60 bg-card/50 p-4 sm:p-6 transition-all duration-300 hover:border-primary/30 hover:shadow-md sm:flex-row">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                        <x-icon name="bookmark" class="h-6 w-6" />
                    </div>
                    <div>
                        <h3 class="font-semibold text-foreground">Favoritos e anotações</h3>
                        <p class="mt-1 text-sm text-muted-foreground">Salve versículos e faça anotações para consultar depois.</p>
                    </div>
                </div>
                <div class="flex flex-col gap-4 rounded-2xl border border-border/60 bg-card/50 p-4 sm:p-6 transition-all duration-300 hover:border-primary/30 hover:shadow-md sm:flex-row">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                        <x-icon name="shield-halved" class="h-6 w-6" />
                    </div>
                    <div>
                        <h3 class="font-semibold text-foreground">Privacidade e LGPD</h3>
                        <p class="mt-1 text-sm text-muted-foreground">Seus dados tratados com transparência e em conformidade com a lei.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Bíblia em destaque --}}
    @if(Route::has('bible.public.index'))
    <section class="border-y border-border/40 bg-primary/5 py-16 sm:py-20 dark:bg-primary/10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center gap-6 rounded-3xl border border-primary/20 bg-card/80 p-6 shadow-lg sm:flex-row sm:justify-between sm:p-8 lg:p-12">
                <div class="text-center sm:text-left">
                    <div class="inline-flex items-center justify-center rounded-xl bg-primary/15 p-3 text-primary">
                        <x-icon name="book-bible" class="h-10 w-10" />
                    </div>
                    <h2 class="mt-4 text-2xl font-bold text-foreground sm:text-3xl">Leia a Bíblia sem cadastro</h2>
                    <p class="mt-2 max-w-xl text-muted-foreground">Várias versões, busca por palavra e navegação por livro e capítulo. Gratuito e acessível a todos.</p>
                </div>
                <a href="{{ route('bible.public.index') }}" class="inline-flex w-full min-h-[48px] shrink-0 items-center justify-center gap-2 rounded-xl bg-primary px-6 py-3.5 font-semibold text-primary-foreground shadow-lg transition-all duration-300 hover:opacity-90 sm:w-auto focus:ring-2 focus:ring-primary focus:ring-offset-2">
                    Abrir Bíblia <x-icon name="arrow-right" class="h-5 w-5 shrink-0" />
                </a>
            </div>
        </div>
    </section>
    @endif

    {{-- Como funciona --}}
    <section class="py-16 sm:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-center text-3xl font-bold text-foreground sm:text-4xl">Como começar</h2>
            <p class="mx-auto mt-4 max-w-2xl text-center text-muted-foreground">Em poucos passos você já está usando a plataforma.</p>
            <div class="mt-10 sm:mt-14 grid gap-8 grid-cols-1 sm:grid-cols-3">
                <div class="text-center">
                    <div class="mx-auto flex h-14 w-14 sm:h-16 sm:w-16 items-center justify-center rounded-2xl bg-primary text-xl sm:text-2xl font-bold text-primary-foreground">1</div>
                    <h3 class="mt-4 text-lg font-semibold text-foreground">Cadastre-se</h3>
                    <p class="mt-2 text-sm text-muted-foreground">Crie sua conta gratuitamente com e-mail e alguns dados básicos.</p>
                </div>
                <div class="text-center">
                    <div class="mx-auto flex h-14 w-14 sm:h-16 sm:w-16 items-center justify-center rounded-2xl bg-primary text-xl sm:text-2xl font-bold text-primary-foreground">2</div>
                    <h3 class="mt-4 text-lg font-semibold text-foreground">Acesse a plataforma</h3>
                    <p class="mt-2 text-sm text-muted-foreground">Entre com sua conta e escolha por onde começar: Bíblia, planos ou sermões.</p>
                </div>
                <div class="text-center">
                    <div class="mx-auto flex h-14 w-14 sm:h-16 sm:w-16 items-center justify-center rounded-2xl bg-primary text-xl sm:text-2xl font-bold text-primary-foreground">3</div>
                    <h3 class="mt-4 text-lg font-semibold text-foreground">Use as ferramentas</h3>
                    <p class="mt-2 text-sm text-muted-foreground">Leia, anote, crie planos de leitura e organize seus estudos.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Prova social e confiança --}}
    <section class="border-t border-border/40 bg-muted/30 py-16 sm:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-center text-3xl font-bold text-foreground sm:text-4xl">Feito para igrejas e ministérios</h2>
            <p class="mx-auto mt-4 max-w-2xl text-center text-muted-foreground">Uma plataforma séria, com privacidade e conteúdo de qualidade.</p>
            <div class="mt-10 sm:mt-14 grid gap-6 sm:gap-8 grid-cols-2 lg:grid-cols-4">
                <div class="rounded-2xl border border-border/60 bg-card/50 p-4 sm:p-6 text-center">
                    <div class="text-3xl font-bold text-primary">EAD</div>
                    <p class="mt-1 text-sm text-muted-foreground">Ensino a distância completo</p>
                </div>
                <div class="rounded-2xl border border-border/60 bg-card/50 p-4 sm:p-6 text-center">
                    <div class="text-3xl font-bold text-primary">LGPD</div>
                    <p class="mt-1 text-sm text-muted-foreground">Privacidade e transparência</p>
                </div>
                <div class="rounded-2xl border border-border/60 bg-card/50 p-4 sm:p-6 text-center">
                    <div class="text-3xl font-bold text-primary">Bíblia</div>
                    <p class="mt-1 text-sm text-muted-foreground">Múltiplas versões e busca</p>
                </div>
                <div class="rounded-2xl border border-border/60 bg-card/50 p-4 sm:p-6 text-center">
                    <a href="{{ route('home') }}" class="inline-block transition-opacity hover:opacity-90 focus:ring-2 focus:ring-primary focus:ring-offset-2 rounded">
                        <img src="{{ asset('storage/business/vertex_solutions_logo.svg') }}" alt="Vertex Solutions LTDA" class="mx-auto h-7 max-w-full dark:invert dark:brightness-0 dark:opacity-90" width="130" height="35" loading="lazy" />
                    </a>
                    <p class="mt-2 text-sm text-muted-foreground">Uma marca Vertex Solutions LTDA</p>
                </div>
            </div>
        </div>
    </section>

    {{-- FAQ e contato --}}
    <section class="border-t border-border/40 py-16 sm:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center justify-between gap-8 sm:flex-row sm:flex-wrap">
                <div>
                    <h2 class="text-xl font-bold text-foreground">Dúvidas frequentes</h2>
                    <p class="mt-1 text-sm text-muted-foreground">Veja as respostas para as principais perguntas sobre a plataforma.</p>
                    <a href="{{ route('faq') }}" class="mt-3 inline-flex min-h-[44px] items-center gap-2 font-semibold text-primary hover:underline focus:ring-2 focus:ring-primary focus:ring-offset-2 rounded">
                        Ver FAQ <x-icon name="arrow-right" class="h-4 w-4 shrink-0" />
                    </a>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-foreground">Fale conosco</h2>
                    <p class="mt-1 text-sm text-muted-foreground">Precisa de ajuda ou quer saber mais? Entre em contato.</p>
                    <a href="{{ route('contact') }}" class="mt-3 inline-flex min-h-[44px] items-center gap-2 font-semibold text-primary hover:underline focus:ring-2 focus:ring-primary focus:ring-offset-2 rounded">
                        Página de contato <x-icon name="arrow-right" class="h-4 w-4 shrink-0" />
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA final --}}
    <section class="border-t border-border/40 py-16 sm:py-24">
        <div class="mx-auto max-w-3xl px-4 text-center sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-foreground sm:text-4xl">Pronto para começar?</h2>
            <p class="mt-4 text-lg text-muted-foreground">Crie sua conta e acesse a Bíblia, planos de leitura, sermões e muito mais.</p>
            <div class="mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row">
                <a href="{{ route('register') }}" class="inline-flex w-full min-h-[48px] items-center justify-center rounded-xl bg-primary px-6 py-3.5 text-base font-semibold text-primary-foreground shadow-lg transition-all duration-300 hover:opacity-90 sm:w-auto focus:ring-2 focus:ring-primary focus:ring-offset-2">
                    Começar agora
                </a>
                <a href="{{ route('login') }}" class="inline-flex w-full min-h-[48px] items-center justify-center rounded-xl border-2 border-border bg-background px-6 py-3.5 text-base font-semibold text-foreground transition-all duration-300 hover:bg-muted sm:w-auto focus:ring-2 focus:ring-primary focus:ring-offset-2">
                    Já tenho conta
                </a>
            </div>
        </div>
    </section>
</x-homepage::components.layouts.master>

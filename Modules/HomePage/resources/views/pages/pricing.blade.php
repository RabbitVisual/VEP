<x-homepage::components.layouts.master>
    <div class="mx-auto max-w-4xl px-4 py-12 sm:py-16 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-foreground text-center">Preços</h1>
        <p class="mt-2 text-center text-muted-foreground">Planos para pastores, igrejas e alunos.</p>

        <div class="mt-10 sm:mt-12 grid gap-6 sm:gap-8 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
            <div class="rounded-2xl border border-border bg-card/50 p-4 sm:p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-foreground">Bíblia pública</h2>
                <p class="mt-2 text-2xl font-bold text-primary">Grátis</p>
                <p class="mt-2 text-sm text-muted-foreground">Acesso à Bíblia sem cadastro. Várias versões e busca.</p>
                @if(Route::has('bible.public.index'))
                    <a href="{{ route('bible.public.index') }}" class="mt-4 inline-flex min-h-[48px] items-center justify-center rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-primary-foreground transition-all hover:opacity-90 focus:ring-2 focus:ring-primary focus:ring-offset-2">Acessar</a>
                @endif
            </div>
            <div class="rounded-2xl border border-primary/50 bg-card/50 p-4 sm:p-6 shadow-md ring-1 ring-primary/20">
                <span class="text-xs font-semibold uppercase text-primary">Recomendado</span>
                <h2 class="mt-2 text-lg font-semibold text-foreground">Membro / Aluno</h2>
                <p class="mt-2 text-2xl font-bold text-primary">Sob consulta</p>
                <p class="mt-2 text-sm text-muted-foreground">Planos de leitura, favoritos, anotações e área do aluno.</p>
                <a href="{{ route('contact') }}" class="mt-4 inline-flex min-h-[48px] items-center justify-center rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-primary-foreground transition-all hover:opacity-90 focus:ring-2 focus:ring-primary focus:ring-offset-2">Contato</a>
            </div>
            <div class="rounded-2xl border border-border bg-card/50 p-4 sm:p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-foreground">Pastor / Igreja</h2>
                <p class="mt-2 text-2xl font-bold text-primary">Sob consulta</p>
                <p class="mt-2 text-sm text-muted-foreground">Sermões, comentários, gestão de conteúdo e múltiplos usuários.</p>
                <a href="{{ route('contact') }}" class="mt-4 inline-flex min-h-[48px] items-center justify-center rounded-lg border border-border bg-background px-4 py-2 text-sm font-semibold text-foreground transition-all hover:bg-muted focus:ring-2 focus:ring-primary focus:ring-offset-2">Contato</a>
            </div>
        </div>
    </div>
</x-homepage::components.layouts.master>

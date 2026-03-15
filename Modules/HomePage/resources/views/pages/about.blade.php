<x-homepage::components.layouts.master>
    <div class="mx-auto max-w-4xl px-4 py-12 sm:py-16 sm:px-6 lg:px-8">
        <div class="flex flex-col items-center gap-8 md:flex-row md:items-start">
            <div class="flex-1 min-w-0">
                <h1 class="text-3xl font-bold text-foreground">Sobre</h1>
                <p class="mt-4 text-muted-foreground">
                    A <strong>Vertex Escola de Pastores</strong> é uma plataforma de ensino a distância (EAD) desenvolvida pela <strong>Vertex Solutions LTDA</strong>, voltada à formação de pastores, líderes e alunos. Oferecemos acesso à Bíblia em múltiplas versões, planos de leitura, ferramentas para elaboração de sermões e estudos, além de recursos que integram ministério e formação.
                </p>
                <p class="mt-4 text-muted-foreground">
                    Nossa missão é apoiar igrejas e ministérios com tecnologia que respeita a privacidade (LGPD) e a qualidade do conteúdo bíblico.
                </p>
                <div class="mt-8 flex flex-wrap items-center gap-6">
                    <a href="{{ route('home') }}" class="inline-block transition-opacity hover:opacity-90 focus:ring-2 focus:ring-primary focus:ring-offset-2 rounded">
                        <img src="{{ asset('storage/business/vertex_solutions_logo.svg') }}" alt="Vertex Solutions LTDA" class="h-7 max-w-full" width="140" height="37" loading="lazy" />
                    </a>
                </div>
            </div>
            {{-- Desenvolvedor – formato story (vertical) --}}
            <div class="flex shrink-0 flex-col items-center rounded-2xl border border-border/60 bg-card/50 p-4 shadow-sm">
                <div class="aspect-[9/16] w-full max-w-[144px] overflow-hidden rounded-xl bg-muted">
                    @if(Storage::disk('public')->exists('business/ReinanRodrigues.png'))
                        <img src="{{ asset('storage/business/ReinanRodrigues.png') }}" alt="Reinan Rodrigues" class="h-full w-full object-cover" width="144" height="256" loading="lazy" />
                    @else
                        <div class="flex h-full w-full items-center justify-center text-muted-foreground">
                            <x-icon name="user" class="h-12 w-12" />
                        </div>
                    @endif
                </div>
                <p class="mt-3 text-sm font-semibold text-foreground">Reinan Rodrigues</p>
                <p class="text-xs text-muted-foreground">Desenvolvimento</p>
                <p class="mt-1 text-xs text-muted-foreground">Vertex Solutions LTDA</p>
            </div>
        </div>
        <p class="mt-10 text-center text-sm text-muted-foreground">© {{ date('Y') }} Vertex Solutions LTDA. Todos os direitos reservados.</p>
    </div>
</x-homepage::components.layouts.master>

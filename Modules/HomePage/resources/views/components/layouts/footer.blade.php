<footer class="border-t border-border/40 bg-card/50 backdrop-blur-sm">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:py-12 sm:px-6 lg:px-8">
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
            <div>
                <a href="{{ route('home') }}" class="inline-block">
                    <img src="{{ asset('storage/logo/vertex-escola-de-pastores.svg') }}" alt="Vertex Escola de Pastores" class="h-9 max-w-full dark:hidden" width="180" height="40" loading="lazy" />
                    <img src="{{ asset('storage/logo/vertex-escola-de-pastores-dark.svg') }}" alt="Vertex Escola de Pastores" class="hidden h-9 max-w-full dark:block" width="180" height="40" loading="lazy" />
                </a>
                <p class="mt-2 text-sm text-muted-foreground">Formação de pastores e líderes. Plataforma EAD.</p>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-foreground">Institucional</h3>
                <ul class="mt-3 space-y-1">
                    <li><a href="{{ route('about') }}" class="inline-flex min-h-[44px] items-center py-2 text-sm text-muted-foreground transition-colors hover:text-foreground focus:ring-2 focus:ring-primary focus:ring-offset-2 rounded">Sobre</a></li>
                    <li><a href="{{ route('faq') }}" class="inline-flex min-h-[44px] items-center py-2 text-sm text-muted-foreground transition-colors hover:text-foreground focus:ring-2 focus:ring-primary focus:ring-offset-2 rounded">FAQ</a></li>
                    <li><a href="{{ route('pricing') }}" class="inline-flex min-h-[44px] items-center py-2 text-sm text-muted-foreground transition-colors hover:text-foreground focus:ring-2 focus:ring-primary focus:ring-offset-2 rounded">Preços</a></li>
                    <li><a href="{{ route('contact') }}" class="inline-flex min-h-[44px] items-center py-2 text-sm text-muted-foreground transition-colors hover:text-foreground focus:ring-2 focus:ring-primary focus:ring-offset-2 rounded">Contato</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-foreground">Legal</h3>
                <ul class="mt-3 space-y-1">
                    <li><a href="{{ route('legal.privacy') }}" class="inline-flex min-h-[44px] items-center py-2 text-sm text-muted-foreground transition-colors hover:text-foreground focus:ring-2 focus:ring-primary focus:ring-offset-2 rounded">Privacidade (LGPD)</a></li>
                    <li><a href="{{ route('legal.terms') }}" class="inline-flex min-h-[44px] items-center py-2 text-sm text-muted-foreground transition-colors hover:text-foreground focus:ring-2 focus:ring-primary focus:ring-offset-2 rounded">Termos de Uso</a></li>
                    <li><a href="{{ route('legal.cookies') }}" class="inline-flex min-h-[44px] items-center py-2 text-sm text-muted-foreground transition-colors hover:text-foreground focus:ring-2 focus:ring-primary focus:ring-offset-2 rounded">Cookies</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-foreground">Recursos</h3>
                <ul class="mt-3 space-y-1">
                    @if(Route::has('bible.public.index'))
                        <li><a href="{{ route('bible.public.index') }}" class="inline-flex min-h-[44px] items-center gap-1.5 py-2 text-sm text-muted-foreground transition-colors hover:text-foreground focus:ring-2 focus:ring-primary focus:ring-offset-2 rounded"><x-icon name="book-bible" class="h-4 w-4 shrink-0" /> Bíblia</a></li>
                    @endif
                </ul>
            </div>
        </div>
        <div class="mt-10 flex flex-col items-center gap-6 border-t border-border/40 pt-8 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-center text-xs text-muted-foreground sm:text-left">© {{ date('Y') }} Vertex Solutions LTDA. Todos os direitos reservados.</p>
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-muted-foreground transition-opacity hover:opacity-90 focus:ring-2 focus:ring-primary focus:ring-offset-2 rounded" aria-label="Uma marca Vertex Solutions LTDA">
                <img src="{{ asset('storage/business/vertex_solutions_logo.svg') }}" alt="Vertex Solutions LTDA" class="h-6 max-w-full" width="120" height="32" loading="lazy" />
            </a>
            <div class="flex items-center gap-3">
                <img src="{{ asset('storage/business/ReinanRodrigues.png') }}" alt="Reinan Rodrigues" class="h-12 w-12 shrink-0 rounded-full object-cover ring-2 ring-border" width="48" height="48" loading="lazy" />
                <div class="text-left">
                    <p class="text-xs font-medium text-foreground">Desenvolvido por Reinan Rodrigues</p>
                    <p class="text-xs text-muted-foreground">Vertex Solutions LTDA</p>
                </div>
            </div>
        </div>
    </div>
</footer>

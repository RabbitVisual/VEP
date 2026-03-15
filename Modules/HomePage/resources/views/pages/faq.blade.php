<x-homepage::components.layouts.master>
    <div class="mx-auto max-w-3xl px-4 py-12 sm:py-16 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-foreground">Perguntas frequentes</h1>
        <p class="mt-2 text-muted-foreground">Dúvidas comuns sobre a plataforma Vertex Escola de Pastores.</p>

        <div class="mt-10 space-y-2" x-data="{ open: null }">
            @foreach ([
                ['q' => 'O que é a Vertex Escola de Pastores?', 'a' => 'É uma plataforma EAD (ensino a distância) voltada à formação de pastores e líderes, com Bíblia em múltiplas versões, planos de leitura, sermões e ferramentas de estudo.'],
                ['q' => 'Preciso pagar para usar a Bíblia?', 'a' => 'Não. A Bíblia é de acesso público e gratuito. Basta acessar o menu Bíblia ou a página inicial.'],
                ['q' => 'Como faço para criar uma conta?', 'a' => 'Clique em "Começar agora" no menu ou na página inicial. Preencha nome, e-mail, CPF (opcional), celular e senha.'],
                ['q' => 'Posso recuperar minha senha com CPF?', 'a' => 'Sim. Na tela "Esqueci a senha" há uma aba "Por CPF + Data nasc." em que você informa CPF e data de nascimento para receber o link de recuperação no e-mail cadastrado.'],
                ['q' => 'Meus dados estão seguros?', 'a' => 'Sim. Tratamos seus dados em conformidade com a LGPD. Consulte a página de Privacidade e os Termos de Uso.'],
            ] as $i => $faq)
                <div class="rounded-xl border border-border/60 bg-card/50 overflow-hidden transition-all duration-300">
                    <button type="button"
                            @click="open = open === {{ $i }} ? null : {{ $i }}"
                            :aria-expanded="open === {{ $i }}"
                            :aria-controls="'faq-answer-{{ $i }}'"
                            id="faq-question-{{ $i }}"
                            class="flex min-h-[48px] w-full items-center justify-between px-4 py-4 text-left font-medium text-foreground hover:bg-muted/50 focus:ring-2 focus:ring-primary focus:ring-inset rounded-xl">
                        <span>{{ $faq['q'] }}</span>
                        <x-icon name="chevron-down" class="h-5 w-5 shrink-0 transition-transform duration-200" x-bind:class="open === {{ $i }} ? 'rotate-180' : ''" />
                    </button>
                    <div x-show="open === {{ $i }}" x-cloak id="faq-answer-{{ $i }}" role="region" aria-labelledby="faq-question-{{ $i }}" class="border-t border-border/40" style="display: none;">
                        <p class="px-4 py-4 text-sm text-muted-foreground">{{ $faq['a'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-homepage::components.layouts.master>

<x-homepage::components.layouts.master>
    <div class="mx-auto max-w-3xl px-4 py-12 sm:py-16 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-foreground">Termos de Uso</h1>
        <p class="mt-2 text-sm text-muted-foreground">Última atualização: {{ now()->format('d/m/Y') }}. Vertex Solutions LTDA.</p>

        <div class="prose prose-slate dark:prose-invert prose-sm sm:prose mt-8 max-w-none text-foreground">
            <h2 class="text-xl font-semibold text-foreground mt-8">1. Aceitação</h2>
            <p class="text-muted-foreground">Ao acessar ou usar a plataforma Vertex Escola de Pastores (“Plataforma”), você concorda com estes Termos de Uso. Se não concordar, não utilize o serviço.</p>

            <h2 class="text-xl font-semibold text-foreground mt-8">2. Descrição do serviço</h2>
            <p class="text-muted-foreground">A Plataforma oferece ambiente EAD com acesso à Bíblia em múltiplas versões, planos de leitura, ferramentas para sermões e estudos, e recursos para pastores e alunos. O uso pode ser público (Bíblia) ou mediante cadastro e, quando aplicável, adesão a planos pagos.</p>

            <h2 class="text-xl font-semibold text-foreground mt-8">3. Cadastro e conta</h2>
            <p class="text-muted-foreground">Ao se cadastrar, você declara que as informações são verdadeiras e se compromete a mantê-las atualizadas. Você é responsável pela confidencialidade da sua senha e por todas as atividades realizadas em sua conta.</p>

            <h2 class="text-xl font-semibold text-foreground mt-8">4. Uso aceitável</h2>
            <p class="text-muted-foreground">É proibido: usar a Plataforma para fins ilícitos, violar direitos de terceiros, tentar acessar áreas restritas sem autorização, distribuir malware ou praticar atos que prejudiquem a disponibilidade ou a segurança do serviço.</p>

            <h2 class="text-xl font-semibold text-foreground mt-8">5. Propriedade intelectual</h2>
            <p class="text-muted-foreground">O conteúdo da Plataforma (exceto textos bíblicos em domínio público ou licenciados) é de propriedade da Vertex Solutions LTDA ou de licenciadores. O usuário não adquire direitos sobre o software ou o design da Plataforma.</p>

            <h2 class="text-xl font-semibold text-foreground mt-8">6. Privacidade</h2>
            <p class="text-muted-foreground">O tratamento de dados pessoais rege-se pela nossa <a href="{{ route('legal.privacy') }}" class="text-primary hover:underline">Política de Privacidade</a> e pela LGPD.</p>

            <h2 class="text-xl font-semibold text-foreground mt-8">7. Alterações e rescisão</h2>
            <p class="text-muted-foreground">Podemos alterar estes Termos a qualquer momento, com divulgação na Plataforma. O uso continuado após as alterações constitui aceitação. Podemos suspender ou encerrar contas em caso de violação destes Termos.</p>

            <h2 class="text-xl font-semibold text-foreground mt-8">8. Contato</h2>
            <p class="text-muted-foreground">Dúvidas: <a href="{{ route('contact') }}" class="text-primary hover:underline">Contato</a>. Vertex Solutions LTDA.</p>
        </div>
    </div>
</x-homepage::components.layouts.master>

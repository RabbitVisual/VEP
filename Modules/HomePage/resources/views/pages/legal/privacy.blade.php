<x-homepage::components.layouts.master>
    <div class="mx-auto max-w-3xl px-4 py-12 sm:py-16 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-foreground">Política de Privacidade</h1>
        <p class="mt-2 text-sm text-muted-foreground">Última atualização: {{ now()->format('d/m/Y') }}. Vertex Solutions LTDA.</p>

        <div class="prose prose-slate dark:prose-invert prose-sm sm:prose mt-8 max-w-none text-foreground">
            <h2 class="text-xl font-semibold text-foreground mt-8">1. Introdução</h2>
            <p class="text-muted-foreground">Esta Política de Privacidade descreve como a <strong>Vertex Solutions LTDA</strong> (“Vertex”, “nós”) coleta, usa, armazena e protege os dados pessoais dos usuários da plataforma Vertex Escola de Pastores, em conformidade com a Lei Geral de Proteção de Dados (LGPD – Lei nº 13.709/2018).</p>

            <h2 class="text-xl font-semibold text-foreground mt-8">2. Dados que coletamos</h2>
            <p class="text-muted-foreground">Podemos coletar: nome, sobrenome, e-mail, CPF, data de nascimento, telefone, dados de acesso (endereço IP, data/hora de login), preferências de uso da plataforma e conteúdos que você criar (anotações, favoritos). O CPF e a data de nascimento são utilizados para identificação e recuperação de conta, quando aplicável.</p>

            <h2 class="text-xl font-semibold text-foreground mt-8">3. Finalidade do tratamento</h2>
            <p class="text-muted-foreground">Os dados são utilizados para: prestação do serviço EAD, autenticação e recuperação de senha, cumprimento de obrigações legais, melhoria da plataforma e comunicação com o usuário (quando autorizado).</p>

            <h2 class="text-xl font-semibold text-foreground mt-8">4. Base legal (LGPD)</h2>
            <p class="text-muted-foreground">O tratamento baseia-se em: execução de contrato ou procedimentos preliminares, cumprimento de obrigação legal, legítimo interesse e, quando aplicável, consentimento do titular.</p>

            <h2 class="text-xl font-semibold text-foreground mt-8">5. Retenção de dados</h2>
            <p class="text-muted-foreground">Mantemos os dados pelo tempo necessário para as finalidades descritas e para cumprimento de obrigações legais e regulatórias. Após o encerramento da relação, os dados podem ser anonimizados ou eliminados, salvo retenção legal.</p>

            <h2 class="text-xl font-semibold text-foreground mt-8">6. Direitos do titular (LGPD)</h2>
            <p class="text-muted-foreground">Você tem direito a: confirmação da existência de tratamento, acesso aos dados, correção de dados incompletos ou desatualizados, anonimização, portabilidade, eliminação (ressalvados os casos de retenção legal), revogação do consentimento e informações sobre com quem compartilhamos os dados. Para exercer esses direitos, entre em contato conosco pela página de Contato.</p>

            <h2 class="text-xl font-semibold text-foreground mt-8">7. Compartilhamento</h2>
            <p class="text-muted-foreground">Não vendemos dados pessoais. Podemos compartilhar dados com prestadores de serviço que atuam em nosso nome (hospedagem, e-mail), sempre com obrigação de confidencialidade e em conformidade com a LGPD.</p>

            <h2 class="text-xl font-semibold text-foreground mt-8">8. Contato</h2>
            <p class="text-muted-foreground">Dúvidas sobre esta política: utilize a página <a href="{{ route('contact') }}" class="text-primary hover:underline">Contato</a>. Vertex Solutions LTDA.</p>
        </div>
    </div>
</x-homepage::components.layouts.master>

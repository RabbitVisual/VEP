<x-homepage::components.layouts.master>
    <div class="mx-auto max-w-3xl px-4 py-12 sm:py-16 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-foreground">Política de Cookies</h1>
        <p class="mt-2 text-sm text-muted-foreground">Última atualização: {{ now()->format('d/m/Y') }}. Vertex Solutions LTDA.</p>

        <div class="prose prose-slate dark:prose-invert prose-sm sm:prose mt-8 max-w-none text-foreground">
            <h2 class="text-xl font-semibold text-foreground mt-8">1. O que são cookies</h2>
            <p class="text-muted-foreground">Cookies são pequenos arquivos de texto armazenados no seu dispositivo quando você visita um site. Eles permitem que a plataforma reconheça seu dispositivo e armazene preferências ou informações de sessão.</p>

            <h2 class="text-xl font-semibold text-foreground mt-8">2. Cookies que utilizamos</h2>
            <p class="text-muted-foreground">Utilizamos cookies estritamente necessários para: manter sua sessão de login (autenticação), lembrar preferências como tema (claro/escuro), e garantir a segurança (proteção CSRF). Não utilizamos cookies de publicidade ou de rastreamento de terceiros para fins de marketing sem seu consentimento.</p>

            <h2 class="text-xl font-semibold text-foreground mt-8">3. Base legal e finalidade</h2>
            <p class="text-muted-foreground">O uso de cookies essenciais baseia-se no legítimo interesse e na execução do contrato de uso da plataforma. Cookies opcionais (quando aplicáveis) dependerão de seu consentimento, em conformidade com a LGPD.</p>

            <h2 class="text-xl font-semibold text-foreground mt-8">4. Retenção</h2>
            <p class="text-muted-foreground">Cookies de sessão são removidos ao encerrar o navegador. Cookies persistentes (ex.: preferência de tema) podem permanecer pelo tempo definido no navegador ou até você excluí-los.</p>

            <h2 class="text-xl font-semibold text-foreground mt-8">5. Gerenciamento</h2>
            <p class="text-muted-foreground">Você pode configurar seu navegador para recusar ou apagar cookies. A recusa de cookies essenciais pode afetar o funcionamento da plataforma (por exemplo, manter você logado).</p>

            <h2 class="text-xl font-semibold text-foreground mt-8">6. Contato</h2>
            <p class="text-muted-foreground">Dúvidas: <a href="{{ route('contact') }}" class="text-primary hover:underline">Contato</a>. Para mais informações sobre dados pessoais, consulte nossa <a href="{{ route('legal.privacy') }}" class="text-primary hover:underline">Política de Privacidade</a>. Vertex Solutions LTDA.</p>
        </div>
    </div>
</x-homepage::components.layouts.master>

<x-homepage::components.layouts.master>
    <div class="mx-auto max-w-2xl px-4 py-12 sm:py-16 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-foreground">Contato</h1>
        <p class="mt-2 text-muted-foreground">Envie sua mensagem ou solicitação para a Vertex Solutions LTDA.</p>

        <form action="#" method="POST" class="mt-8 space-y-4">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-foreground">Nome</label>
                <input id="name" type="text" name="name" required class="mt-1 block w-full rounded-lg border border-input bg-background px-3 py-3 text-foreground shadow-sm focus:border-primary focus:ring-2 focus:ring-primary">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-foreground">E-mail</label>
                <input id="email" type="email" name="email" required class="mt-1 block w-full rounded-lg border border-input bg-background px-3 py-3 text-foreground shadow-sm focus:border-primary focus:ring-2 focus:ring-primary">
            </div>
            <div>
                <label for="message" class="block text-sm font-medium text-foreground">Mensagem</label>
                <textarea id="message" name="message" rows="5" required class="mt-1 block w-full rounded-lg border border-input bg-background px-3 py-3 text-foreground shadow-sm focus:border-primary focus:ring-2 focus:ring-primary"></textarea>
            </div>
            <button type="submit" class="w-full min-h-[48px] rounded-lg bg-primary px-4 py-3 font-semibold text-primary-foreground shadow-sm transition-all duration-300 hover:opacity-90 sm:w-auto sm:px-6 focus:ring-2 focus:ring-primary focus:ring-offset-2">Enviar</button>
        </form>

        <p class="mt-8 text-sm text-muted-foreground">Para dúvidas sobre privacidade e termos, consulte as páginas <a href="{{ route('legal.privacy') }}" class="text-primary hover:underline py-1 focus:ring-2 focus:ring-primary focus:ring-offset-2 rounded">Privacidade</a> e <a href="{{ route('legal.terms') }}" class="text-primary hover:underline py-1 focus:ring-2 focus:ring-primary focus:ring-offset-2 rounded">Termos de Uso</a>.</p>
    </div>
</x-homepage::components.layouts.master>

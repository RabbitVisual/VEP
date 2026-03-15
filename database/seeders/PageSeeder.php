<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use VertexSolutions\Storefront\Models\Page;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        Page::create([
            'title' => 'Termos de Uso',
            'slug' => 'termos-de-uso',
            'content' => '<h1>Termos de Uso</h1><p>Conteúdo padrão para termos de uso do VertexShop...</p>',
            'is_active' => true,
        ]);

        Page::create([
            'title' => 'Política de Privacidade',
            'slug' => 'politica-de-privacidade',
            'content' => '<h1>Política de Privacidade</h1><p>Sua privacidade é importante para nós...</p>',
            'is_active' => true,
        ]);

        Page::create([
            'title' => 'Política de Entrega',
            'slug' => 'politica-de-entrega',
            'content' => '<h1>Política de Entrega</h1><p>Detalhes sobre nossos prazos e logística global...</p>',
            'is_active' => true,
        ]);

        Page::create([
            'title' => 'Devolução e Reembolso',
            'slug' => 'devolucao-e-reembolso',
            'content' => '<h1>Devolução e Reembolso</h1><p>Regras para trocas e devoluções...</p>',
            'is_active' => true,
        ]);
    }
}

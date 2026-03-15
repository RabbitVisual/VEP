<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use VertexSolutions\Storefront\Models\StorefrontSetting;

class StorefrontSettingsSeeder extends Seeder
{
    public function run(): void
    {
        StorefrontSetting::set('store_name', 'VertexShop Global');
        StorefrontSetting::set('store_logo', null);
        StorefrontSetting::set('contact_email', 'contato@vertexshop.com');

        StorefrontSetting::set('navbar_links', [
            ['label' => 'Coleções', 'url' => '/shop'],
            ['label' => 'Novidades', 'url' => '/new'],
            ['label' => 'Dropshipping', 'url' => '/dropshipping'],
            ['label' => 'Suporte', 'url' => '/support'],
        ]);

        StorefrontSetting::set('footer_links', [
            'institucional' => [
                ['label' => 'Quem Somos', 'url' => '/p/quem-somos'],
                ['label' => 'Termos de Uso', 'url' => '/p/termos-de-uso'],
                ['label' => 'Privacidade', 'url' => '/p/politica-de-privacidade'],
            ],
            'ajuda' => [
                ['label' => 'Entregas', 'url' => '/p/politica-de-entrega'],
                ['label' => 'Trocas', 'url' => '/p/devolucao-e-reembolso'],
                ['label' => 'Rastreio', 'url' => '/customer/tracking'],
            ],
        ]);

        StorefrontSetting::set('social_links', [
            ['platform' => 'instagram', 'url' => 'https://instagram.com/vertexshop'],
            ['platform' => 'facebook', 'url' => 'https://facebook.com/vertexshop'],
        ]);
    }
}

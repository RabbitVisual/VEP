<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use VertexSolutions\Storefront\Models\StorefrontSection;
use VertexSolutions\Dropshipping\Models\DropshippingProduct;

class StorefrontSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Hero Section
        StorefrontSection::create([
            'type' => 'hero',
            'title' => 'Nexus Explorer',
            'order' => 1,
            'is_active' => true,
            'settings' => [
                'badge' => 'Exploração Elite 2026',
                'title' => 'O Arsenal de Estilo',
                'subtitle' => 'Performance Nexus',
                'description' => 'A curadoria mais agressiva do mercado global. Tecnologia e design em um só lugar.',
                'background_image' => 'https://picsum.photos/1920/1080?random=15'
            ]
        ]);

        // 2. Featured Products Section
        StorefrontSection::create([
            'type' => 'featured_products',
            'title' => 'Dropshipping Selection',
            'order' => 2,
            'is_active' => true,
            'settings' => [
                'limit' => 4,
                'view_mode' => 'grid'
            ]
        ]);
    }
}

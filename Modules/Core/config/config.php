<?php

/**
 * Configuração do módulo Core (Bíblia, Strong, planos de leitura).
 * Laravel Modules: config carregado como config('core.*').
 *
 * @author Reinan Rodrigues
 * @company © 2026 Vertex Solution LTDA
 */

return [
    'name' => 'Core',
    'description' => 'Sistema central: Bíblia, concordância Strong, planos de leitura e favoritos.',
    'icon' => 'book-bible',
    'icon_style' => 'duotone',
    'route_prefix' => null,
    'nav_label' => 'Bíblia',
    'nav_order' => 10,
    'enabled' => true,

    'bible' => [
        'name' => 'Bible',
        'default_version' => env('BIBLE_DEFAULT_VERSION', 'ARA'),
    ],
];

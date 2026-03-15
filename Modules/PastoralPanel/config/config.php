<?php

/**
 * Configuração do módulo PastoralPanel.
 * Laravel Modules: config carregado como config('pastoralpanel.*').
 *
 * @author Reinan Rodrigues
 * @company © 2026 Vertex Solution LTDA
 */

return [
    'name' => 'PastoralPanel',
    'description' => 'Área pastoral: sermões, comentários e gestão de conteúdo.',
    'icon' => 'user-tie',
    'icon_style' => 'duotone',
    'route_prefix' => 'pastoral',
    'nav_label' => 'Área Pastoral',
    'nav_order' => 20,
    'enabled' => true,
];

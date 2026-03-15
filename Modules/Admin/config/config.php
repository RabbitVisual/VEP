<?php

/**
 * Configuração do módulo Admin.
 * Laravel Modules: config carregado como config('admin.*').
 *
 * @author Reinan Rodrigues
 * @company © 2026 Vertex Solution LTDA
 */

return [
    'name' => 'Admin',
    'description' => 'Painel administrativo: Bíblia, Strong, sermões, usuários e configurações.',
    'icon' => 'gears',
    'icon_style' => 'duotone',
    'route_prefix' => 'admin',
    'nav_label' => 'Administração',
    'nav_order' => 100,
    'enabled' => true,
];

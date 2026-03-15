<?php

/**
 * Configuração do módulo MemberPanel.
 * Laravel Modules: config carregado como config('memberpanel.*').
 *
 * @author Reinan Rodrigues
 * @company © 2026 Vertex Solution LTDA
 */

return [
    'name' => 'MemberPanel',
    'description' => 'Área do membro: perfil, sermões, estudos e interação.',
    'icon' => 'user',
    'icon_style' => 'duotone',
    'route_prefix' => 'social',
    'nav_label' => 'Área do Membro',
    'nav_order' => 30,
    'enabled' => true,
];

<?php

/**
 * Helpers globais do Vertex Escola de Pastores.
 * Ícones e configuração de módulos (Laravel Modules).
 *
 * @author Reinan Rodrigues
 * @company © 2026 Vertex Solution LTDA
 */

if (! function_exists('module_icon')) {
    /**
     * Retorna o nome do ícone Font Awesome (duotone) do módulo para uso em <x-icon>.
     *
     * @param  string  $alias  Alias do módulo (core, admin, academy, homepage, memberpanel, ministry, pastoralpanel, sermons)
     * @return string Nome do ícone (ex: book-bible, gears)
     */
    function module_icon(string $alias): string
    {
        return (string) config($alias.'.icon', 'circle');
    }
}

if (! function_exists('module_config')) {
    /**
     * Retorna valor da configuração do módulo.
     *
     * @param  string  $alias  Alias do módulo
     * @param  string|array|null  $key  Chave (ex: 'name', 'nav_label') ou null para array completo
     * @param  mixed  $default  Valor padrão
     * @return mixed
     */
    function module_config(string $alias, string|array|null $key = null, mixed $default = null): mixed
    {
        $config = config($alias, []);

        if ($key === null) {
            return $config;
        }

        return data_get($config, $key, $default);
    }
}

if (! function_exists('module_nav_label')) {
    /**
     * Retorna o label de navegação do módulo.
     */
    function module_nav_label(string $alias): string
    {
        return (string) module_config($alias, 'nav_label', ucfirst($alias));
    }
}

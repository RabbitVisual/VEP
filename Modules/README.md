# Módulos – Vertex Escola de Pastores

Configuração e ícones globais seguindo [Laravel Modules](https://nwidart.com/laravel-modules) (nwidart/laravel-modules).

## Padrão Vertex Solutions LTDA (VertexSLTDA)

Todos os módulos (existentes e novos) seguem o padrão:

- **Autor:** Reinan Rodrigues  
- **Empresa:** Vertex Solutions LTDA.  
- **Versão:** vs.1.0.0  
- **Copyright:** © 2026 Vertex Solutions LTDA  

O namespace dos módulos é **VertexSolutions**; o vendor Composer é **vertexsolutions**.  
Stubs em `resources/stubs/modules/` e configuração em `config/modules.php` já estão setados para esse padrão. Ao criar um novo módulo com `php artisan module:make NomeModulo`, o `module.json` e o `composer.json` saem com autor, versão e empresa Vertex.

Variáveis de ambiente opcionais: `MODULE_VENDOR`, `MODULE_AUTHOR_NAME`, `MODULE_AUTHOR_EMAIL`, `MODULE_COMPANY`, `MODULE_COPYRIGHT`, `MODULE_VERSION`.

## Estrutura de configuração por módulo

Cada módulo possui em `Modules/<Nome>/config/config.php` uma configuração carregada pelo ServiceProvider sob a chave do **alias** (ex.: `config('core.*')`, `config('admin.*')`).

### Campos padrão

| Chave           | Uso |
|-----------------|-----|
| `name`          | Nome do módulo |
| `description`   | Descrição curta |
| `icon`          | Nome do ícone Font Awesome (duotone) para `<x-icon>` / `<x-module-icon>` |
| `icon_style`    | Estilo do ícone: `duotone`, `solid`, `regular`, etc. (default: `duotone`) |
| `route_prefix`  | Prefixo de rotas (ex.: `admin`, `social`, `pastoral`) ou `null` |
| `nav_label`     | Texto para menus e navegação |
| `nav_order`     | Ordem em listagens (0 = primeiro) |
| `enabled`       | Módulo ativo ou não |

### Ícones por módulo (duotone)

| Alias          | Ícone               | Uso |
|----------------|---------------------|-----|
| `core`         | `book-bible`        | Bíblia, Strong, planos de leitura |
| `admin`        | `gears`             | Administração |
| `academy`      | `graduation-cap`    | Academia / formação |
| `homepage`     | `house`             | Página inicial |
| `memberpanel`  | `user`              | Área do membro |
| `ministry`     | `hands-praying`     | Ministérios |
| `pastoralpanel`| `user-tie`          | Área pastoral |
| `sermons`      | `book-open-reader`  | Sermões e estudos |

## Uso em views

### Ícone do módulo (global)

```blade
{{-- Ícone do módulo (usa config do módulo) --}}
<x-module-icon alias="core" class="w-6 h-6" />

{{-- Ícone + label --}}
<x-module-icon alias="admin" class="w-5 h-5 mr-2" />
<span>{{ module_nav_label('admin') }}</span>
```

### Helpers globais

- **`module_icon(string $alias): string`** – Nome do ícone do módulo (ex.: `book-bible`).
- **`module_config(string $alias, ?string $key = null, mixed $default = null): mixed`** – Config do módulo; sem `$key` retorna o array completo.
- **`module_nav_label(string $alias): string`** – Label de navegação do módulo.

Exemplos:

```blade
<x-icon :name="module_icon('sermons')" />
{{ module_nav_label('core') }}
@if (module_config('admin', 'enabled'))
    ...
@endif
```

## module.json

Cada módulo deve ter `module.json` com: `name`, `alias`, `description`, `keywords`, `providers`, `version`, `author` (com `name`, `email`). O `alias` é o usado em `config('alias')` e nos helpers acima.

## Comandos Artisan (nwidart/laravel-modules)

Principais comandos (todos configurados para Vertex Solutions LTDA):

| Comando | Uso |
|--------|-----|
| `php artisan module:make Nome` | Cria novo módulo (module.json e composer já com autor/versão Vertex) |
| `php artisan module:list` | Lista módulos |
| `php artisan module:enable/disable Nome` | Ativa/desativa módulo |
| `php artisan module:use Nome` | Define módulo “em uso” para comandos make-* |
| `php artisan module:make-controller NomeController` | Cria controller no módulo em uso |
| `php artisan module:make-model Nome` | Cria model no módulo em uso |
| `php artisan module:make-migration create_tabela` | Cria migration no módulo em uso |
| `php artisan module:make-command NomeCommand` | Cria comando Artisan no módulo em uso |
| `php artisan module:migrate` | Roda migrations dos módulos |
| `php artisan module:seed` | Roda seeders dos módulos |
| `php artisan module:publish-config Nome` | Publica config do módulo em `config/` |
| `php artisan module:dump` | Regenera autoload dos módulos (útil após composer merge) |

Documentação completa: [nwidart.com/laravel-modules](https://nwidart.com/laravel-modules).

## Font Awesome (duotone)

Os layouts já carregam `@vite(['resources/css/app.css', 'resources/js/app.js'])`, e `app.css` importa os estilos do Font Awesome Pro em `resources/fw-pro/css/` (duotone, solid, regular, etc.). Assim, `<x-icon>` e `<x-module-icon>` funcionam em todas as views que usam esses layouts.

# Módulo Core – Vertex Escola de Pastores

**Descrição:** Sistema central de funcionalidades: Bíblia (pública e área do membro), concordância Strong, interlinear, planos de leitura, favoritos, APIs e motor de IA para exegese/sermões.

**Autor:** Reinan Rodrigues  
**Empresa:** Vertex Solutions LTDA  
**Versão:** vs.1.0.0  
**Alias:** `core` / `bible` (views)

---

## Índice

1. [Visão geral](#visão-geral)
2. [Rotas e controllers](#rotas-e-controllers)
3. [Bíblia pública](#bíblia-pública)
4. [Bíblia (área autenticada)](#bíblia-área-autenticada)
5. [Planos de leitura (member panel)](#planos-de-leitura-member-panel)
6. [Favoritos e destaques](#favoritos-e-destaques)
7. [Admin – Concordância Strong](#admin--concordância-strong)
8. [APIs](#apis)
9. [Serviços](#serviços)
10. [Modelos (Eloquent)](#modelos-eloquent)
11. [Comandos Artisan](#comandos-artisan)
12. [Configuração](#configuração)
13. [Migrações](#migrações)
14. [Views e namespaces](#views-e-namespaces)

---

## Visão geral

O Core concentra:

- **Bíblia pública** – Leitura e busca sem login (`/bible`, `/biblia-online`).
- **Bíblia para membros** – Comparar versões, favoritos, busca integrada a planos.
- **Planos de leitura** – Catálogo, inscrição, leitor diário, conclusão de dias, notas, PDF, recálculo de atraso.
- **Concordância Strong** – Dados (Hebraico/Grego) e editor admin para refino (lemma_br, descrição, etc.).
- **Interlinear** – Segmentos por versículo para uso em exegese e painéis.
- **APIs** – Autocomplete, segmentos interlinear, comparação e busca de versículos (v1).
- **IA** – Contexto exegético (Strong, comentários, interlinear), esboço de sermão, cache e log de uso.

As rotas públicas da Bíblia são registradas no `routes/web.php` da aplicação; as rotas autenticadas e de API vêm do próprio módulo.

---

## Rotas e controllers

### Rotas públicas (registradas em `routes/web.php` da raiz)

| Método | URI | Nome | Controller |
|--------|-----|------|------------|
| GET | `/bible` | `bible.public.index` | PublicBibleController@index |
| GET | `/bible/search` | `bible.public.search` | PublicBibleController@search |
| GET | `/bible/{versionAbbr}` | `bible.public.read` | PublicBibleController@read |
| GET | `/bible/{versionAbbr}/{bookNumber}` | `bible.public.book` | PublicBibleController@book |
| GET | `/bible/{versionAbbr}/{bookNumber}/{chapterNumber}` | `bible.public.chapter` | PublicBibleController@chapter |
| GET | `/biblia-online` | `bible.public.index.biblia` | PublicBibleController@index |
| GET | `/biblia-online/buscar`, `/biblia-online/search` | — | PublicBibleController@search |
| GET | `/biblia-online/versao/{versionAbbr}` | — | PublicBibleController@read |
| GET | `/biblia-online/versao/.../livro/{bookNumber}` | — | PublicBibleController@book |
| GET | `/biblia-online/versao/.../livro/.../capitulo/{chapterNumber}` | — | PublicBibleController@chapter |

### Rotas web (auth + verified) – `Modules/Core/routes/web.php`

- **Bíblia (área autenticada)**  
  - `GET bibles/compare` → `bible.compare` (BibleController)  
  - Resource `bibles` → `bible.web.*` (BibleController)

- **Admin – Concordância Strong**  
  - `GET admin/bible/strong` → `admin.bible.strong.index`  
  - `GET admin/bible/strong/{strong}/edit` → `admin.bible.strong.edit`  
  - `PUT admin/bible/strong/{strong}` → `admin.bible.strong.update`

- **Member – Planos de leitura** (`social/bible/plans`)  
  - Índice, catálogo, preview, inscrever, retomar, recálculo, download PDF.  
  - Leitor: ler dia, marcar completo/desfazer, congratulações, nota.  
  - Busca e API de busca.

- **Member – Favoritos** (`social/bible/favorites`)  
  - POST batch, POST toggle, DELETE destroy.

---

## Bíblia pública

- **Controller:** `VertexSolutions\Core\Http\Controllers\PublicBibleController`
- **Views:** `bible::public.*` (fisicamente em `Modules/Core/resources/views/public/`)

Funcionalidades:

- **index** – Seletor de versão ou redirecionamento para primeira versão ativa; sem versão → `no-version`.
- **search** – Página de busca com campo e resultados via API (Alpine).
- **read** – Lista de livros (AT/NT) por versão.
- **book** – Capítulos de um livro.
- **chapter** – Versículos do capítulo (com suporte a Strong/interlinear conforme views).

Dados compartilhados: em modo manutenção (`LARAVEL_MAINTENANCE_SECRET`) pode passar `hideNavFooter` para esconder navbar/footer.

Arquivos de view: `index.blade.php`, `search.blade.php`, `read.blade.php`, `book.blade.php`, `chapter.blade.php`, `no-version.blade.php`, `partials/bible-public-styles.blade.php`.

---

## Bíblia (área autenticada)

- **Controller:** `VertexSolutions\Core\Http\Controllers\BibleController`
- **Rotas:** `bible.compare`, `bible.web.*` (resource bibles)

Funcionalidades:

- Listagem de versões ativas, seleção de versão/livro/capítulo (incluindo padrão global e default).
- Comparação de versões (`compare`).
- Uso de modelos: `BibleVersion`, `BibleBook`, `BibleChapter`, `BibleVerse` (namespace do Core; o controller pode referir `Book`, `Chapter`, `Verse` como aliases em parte do código).

---

## Planos de leitura (member panel)

Controllers: `ReadingPlanController`, `PlanReaderController` (namespace `VertexSolutions\Core\Http\Controllers\MemberPanel`).  
Dependem de modelos de planos (ex.: `BiblePlan`, `BiblePlanSubscription`) que podem estar no módulo Bible; o Core fornece a lógica de apresentação, leitor e serviços.

### ReadingPlanController

- **index** – Dashboard: planos inscritos, totais, percentual, oferta de recálculo, oração por atraso.
- **catalog** – Catálogo de planos (destaques + listagem paginada, filtro por busca).
- **preview** – Prévia do plano antes de inscrever.
- **subscribe** – Inscrever no plano.
- **show** (resume/{id}) – Retomar plano.
- **recalculate** – Recalcular atraso (subscriptionId).
- **downloadPdf** – Download do plano em PDF.

### PlanReaderController

- **read** – Tela de leitura do dia (subscriptionId + day).
- **complete** – Marcar dia como concluído.
- **uncomplete** – Desmarcar conclusão.
- **congratulations** – Tela de parabéns ao concluir dia.
- **storeNote** – Salvar nota do dia.

### MemberPanel BibleController (planos)

- **search** – Página de busca no contexto de planos.
- **performSearch** – API `api/find` para busca.

Serviços usados: `PdfService` (app), `ReadingCatchUpService` (Core). BadgeService avalia conquistas (ex.: Bereano, Fiel ao Pacto, Leitor do Corpo) após conclusão de dias.

---

## Favoritos e destaques

- **Controller:** `VertexSolutions\Core\Http\Controllers\MemberPanel\FavoriteController`
- **Modelo:** `BibleFavorite` (user_id, verse_id, color, note)

Funcionalidades:

- **toggle** – Criar/atualizar favorito ou destaque (cor, nota) por versículo.
- **destroy** – Remover favorito/destaque.
- **batchUpdate** – Atualização em lote de vários versículos.

Rotas: `member.bible.favorites.toggle`, `member.bible.favorites.destroy`, `member.bible.favorites.batch`.

---

## Admin – Concordância Strong

- **Controller:** `VertexSolutions\Core\Http\Controllers\Admin\BibleStrongController`
- **Rotas:** `admin.bible.strong.index`, `admin.bible.strong.edit`, `admin.bible.strong.update`

Funcionalidades:

- Listagem paginada com filtro por termo (number, lemma, lemma_br, transliteration) e por idioma (H/G).
- Edição de entrada Strong: refino de lemma_br, description, part_of_speech, referências, Gematria (estilo NEPE).
- Uso de `BibleStrong` e `BibleInterlinearLexiconMetadata`.

Views: `bible::admin.bible.strong-index`, `bible::admin.bible.strong-edit`.

---

## APIs

### API geral (`routes/api.php` – prefixo `api/bible`)

- **GET api/bible/autocomplete** – Autocomplete de livros; com `book_id`, autocomplete de capítulos/versículos. Controller: `BibleDataController`.
- **GET api/bible/interlinear-segments** – Segmentos interlineares (para editor @mentions, painel de exegese). Controller: `BibleDataController`.

### API v1 (`api/v1/bible`)

- **GET api/v1/bible/compare** – Comparar versículos entre duas versões (v1, v2, book_number, chapter, opcional verse). Controller: `Api\V1\BibleCompareController`.
- **GET api/v1/bible/search** – Busca. Controller: `Api\V1\BibleCompareController`.

Serviço central de dados: `BibleApiService` (comparação, busca, etc.).

---

## Serviços

| Serviço | Descrição |
|--------|-----------|
| **AIService** | Motor de IA: contexto exegético (Strong, interlinear, comentários), prompt por orientação (academic/pastoral/devotional), geração de esboço de sermão, cache e integração com OpenAI. |
| **ExegesisContextResolver** | Monta contexto de exegese (versículo, segmentos, Strong, comentários) para a IA. |
| **BibleApiService** | Comparação de versões, busca, dados para APIs. |
| **BibleVerseResolver** | Resolução de referências (livro, capítulo, versículo). |
| **ReadingPlanGeneratorService** | Geração de planos de leitura. |
| **PlanGeneratorEngine** | Motor de geração de planos. |
| **ReadingCatchUpService** | Lógica de “recuperar atraso” e oferta de recálculo; oração por atraso. |
| **BadgeService** | Conquistas pós-conclusão de dia (ex.: Bereano da Semana, Fiel ao Pacto, Leitor do Corpo). Depende de modelos de plano/inscrição/log. |
| **BiblePlanTemplates** | Templates de planos. |

---

## Modelos (Eloquent)

- **BibleVersion** – Versões da Bíblia (ativa, padrão, nome, abreviatura).
- **BibleBook** – Livros (versão, book_number, nome, abreviatura, testamento).
- **BibleChapter** – Capítulos.
- **BibleVerse** – Versículos (texto, número).
- **BibleFavorite** – Favoritos/destaques do usuário (verse_id, color, note).
- **BibleMetadata** – Metadados por versão/livro.
- **BibleChapterAudio** – Áudio por capítulo.
- **BibleBookPanorama** – Panoramas do livro (seeder próprio).
- **BibleStrong** – Concordância Strong (number, language H/G, lemma, lemma_br, transliteration, etc.).
- **BibleStrongDefinition** – Definições Strong.
- **BibleInterlinearSegment** – Segmentos interlineares (palavra/morfologia).
- **BibleInterlinearVerse** – Versículo interlinear.
- **BibleInterlinearSource** – Fonte do léxico interlinear.
- **BibleInterlinearLexiconMetadata** – Metadados do léxico (slug, nome, etc.).

(Planos de leitura e badges podem usar modelos em outro módulo, ex.: BiblePlan, BiblePlanSubscription, BiblePlanDay, UserReadingLog, BibleUserBadge.)

---

## Comandos Artisan

Registrados em `CoreServiceProvider`:

- **ImportBibleCommand** – `bible:import` – Importa versão a partir de CSV (nome, abreviatura, default).
- **ImportBibleJsonCommand** – Importação a partir de JSON.
- **ImportAllBiblesCommand** – Importação em lote de versões.
- **ImportInterlinearCommand** – Importação de dados interlineares.

---

## Configuração

- **config/config.php** – Nome, descrição, ícone, prefixo, ordem no nav, `bible.default_version` (env `BIBLE_DEFAULT_VERSION`).
- **config/ai.php** – Motor de IA:
  - OpenAI: model, timeout.
  - Cache: exegesis TTL, prefix, lexicon.
  - Context: max commentaries, max comment length.
  - theological_orientation (academic | pastoral | devotional), system_prompt_suffix.
  - usage_log, rate_limit, cost_per_1k_tokens.

---

## Migrações

- `0001_01_01_000001_create_bible_versions_table`
- `0001_01_01_000002_create_bible_books_table`
- `0001_01_01_000003_create_bible_chapters_table`
- `0001_01_01_000004_create_bible_verses_table`
- `0001_01_01_000005_create_bible_favorites_table`
- `0001_01_01_000006_create_bible_chapter_audio_table`
- `0001_01_01_000007_create_bible_metadata_table`
- `0001_01_01_000008_create_bible_book_panoramas_table`
- `0001_01_01_000009_create_bible_strongs_table`
- `0001_01_01_000010_create_bible_interlinear_tables`
- `0001_01_01_000011_alter_bible_strongs_lemma_br_to_text`
- `0001_01_01_000012_create_ai_usage_logs_table`
- `0001_01_01_000013_add_cost_estimate_to_ai_usage_logs`
- `0001_01_01_000014_add_book_normalized_to_bible_commentaries`

---

## Views e namespaces

- Views carregadas com os nomes `core` e `bible` (CoreServiceProvider).
- Caminho base: `Modules/Core/resources/views/`.
- **Public:** `public/index`, `public/search`, `public/read`, `public/book`, `public/chapter`, `public/no-version`, `public/partials/bible-public-styles`.
- **Admin:** `admin/bible/index`, `admin/bible/strong-index`, `admin/bible/strong-edit`.
- **Member panel (planos):** views em `memberpanel/plans/` (dashboard, catalog, preview, read, etc.).
- **Layout:** `components/layouts/master` (Core).

---

## Jobs e eventos

- **GenerateSermonOutlineJob** – Gera esboço de sermão via AIService (tema + referências), coloca em cache e pode notificar usuário. Fila configurável (`queue.queues.ai`).

EventServiceProvider: sem listeners fixos; discovery de eventos habilitado.

---

## Resumo das funcionalidades

| Área | Funcionalidade |
|------|----------------|
| **Público** | Bíblia online: escolher versão, listar livros/capítulos, ler capítulo, buscar. URLs `/bible` e `/biblia-online`. |
| **Membro** | Comparar versões; favoritos/destaques por versículo; planos de leitura: catálogo, inscrever, leitor diário, concluir dia, notas, PDF, recálculo de atraso; badges (Bereano, Fiel ao Pacto, etc.). |
| **Admin** | Editor da concordância Strong (listar, editar lemma_br, descrição, Gematria, etc.). |
| **API** | Autocomplete (livros/capítulos/versículos), segmentos interlinear, comparação e busca v1. |
| **IA** | Contexto exegético por versículo; esboço de sermão; cache e log de uso; orientação pastoral/acadêmica/devocional. |
| **Dados** | Versões, livros, capítulos, versículos, Strong, interlinear, favoritos, metadados, áudio, panoramas, comentários (quando existir tabela). |

Este documento descreve o estado do módulo Core e todas as funcionalidades identificadas no código.

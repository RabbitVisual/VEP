# Módulo Core – Vertex Escola de Pastores

**Autor:** Reinan Rodrigues  
**Empresa:** Vertex Solutions LTDA.  
**Versão:** vs.1.0.0  

## Bíblia Pública

A Bíblia de acesso público (sem autenticação) é exposta pelo controller **`PublicBibleController`** e pelas views em **`resources/views/public/`**.

### Controller

- **Namespace:** `Modules\Bible\App\Http\Controllers\PublicBibleController` (arquivo em `Modules/Core/app/Http/Controllers/PublicBibleController.php`)
- **Métodos:** `index`, `search`, `read`, `book`, `chapter`

### Views

- **Namespace:** As views são carregadas com o alias **`bible`** (registrado no CoreServiceProvider além de `core`) para compatibilidade.
- **Caminho físico:** `Modules/Core/resources/views/public/`
  - `index.blade.php` – Seletor de versão
  - `search.blade.php` – Busca
  - `read.blade.php` – Lista de livros
  - `book.blade.php` – Capítulos do livro
  - `chapter.blade.php` – Versículos do capítulo
  - `no-version.blade.php` – Nenhuma versão disponível

### Rotas Públicas

**As rotas da Bíblia pública devem ser registradas em `routes/web.php` (raiz da aplicação):**

- `GET /bible` → `bible.public.index`
- `GET /bible/search` → `bible.public.search`
- `GET /bible/{versionAbbr}` → `bible.public.read`
- `GET /bible/{versionAbbr}/{bookNumber}` → `bible.public.book`
- `GET /bible/{versionAbbr}/{bookNumber}/{chapterNumber}` → `bible.public.chapter`

O módulo Core registra apenas rotas que exigem `auth`/`verified` no seu próprio `routes/web.php`. As rotas públicas ficam centralizadas no `routes/web.php` principal.

## Outros Recursos do Core

- Planos de leitura, favoritos, Strong (concordância), interlinear, admin da Bíblia – todos documentados nas respectivas áreas do sistema.

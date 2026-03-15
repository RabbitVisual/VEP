# Módulo HomePage – Vertex Escola de Pastores

**Autor:** Reinan Rodrigues  
**Empresa:** Vertex Solutions LTDA.  
**Versão:** vs.1.0.0  

## Objetivo

Landing e vitrine pública do sistema, focada em conversão de **Pastores**, **Colaboradores** e **Alunos**. Interface de nível SaaS Enterprise, com suporte a dark/light mode e autenticação profissional (LGPD).

## Estrutura de Views

- **homepage.blade.php** – Página inicial (Hero, recursos, prova social, CTAs)
- **auth/** – Login (split-screen, E-mail/CPF), registro, esqueci senha (E-mail e CPF + data nascimento), reset, verificação e-mail, 2FA
- **pages/** – FAQ, Sobre, Preços, Contato
- **pages/legal/** – Privacidade (LGPD), Termos de Uso, Cookies
- **components/layouts/master.blade.php** – Layout global (navbar sticky, footer, loading overlay)

## Dependências

- **Core:** Bíblia pública (rotas em `routes/web.php`: `/bible`, `/bible/search`, etc.)
- **Laravel Fortify:** Autenticação (views customizadas neste módulo)
- **Tailwind 4.1** + **Font Awesome Pro** (resources/fw-pro)

## Rotas Públicas

As rotas públicas (home, FAQ, sobre, preços, contato, legal, Bíblia) são registradas no arquivo principal da aplicação: **`routes/web.php`** (raiz do projeto). O controller usado é `VertexSolutions\HomePage\Http\Controllers\HomePageController`.

## Padrão Vertex Solutions LTDA

Este módulo segue o padrão Vertex (vs.1.0.0, autor e empresa nos cabeçalhos dos arquivos).

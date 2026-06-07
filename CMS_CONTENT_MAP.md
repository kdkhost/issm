# Mapa de Conteudo CMS - ISSM

## Sumario

Este documento mapeia todas as paginas publicas do site ISSM para seus equivalentes no CMS, identificando arquivos de fallback, secoes, blocos e status de gerenciamento.

---

## Legenda

| Status | Significado |
|--------|-------------|
| Gerenciado | Conteudo integralmente gerenciado pelo CMS |
| Parcial | Conteudo parcialmente gerenciado (alguns blocos estaticos) |
| Pendente | Pagina criada no CMS mas sem conteudo |
| Nao gerenciado | Pagina mantida como Blade estatico |
| Planejado | Pagina identificada para futura migracao |

---

## Paginas do Site

### Pagina Inicial (Home)

| Campo | Detalhe |
|-------|---------|
| **Rota** | `/` |
| **Nome da Pagina CMS** | Home |
| **Slug** | `home` |
| **Template** | `pages.home` |
| **Arquivo Fallback** | `resources/views/pages/home.blade.php` |
| **Status** | Gerenciado |
| **Secoes** | hero, servicos, destaque, noticias, contato-rapido |
| **Blocos** | banner-principal (slider), servicos-grid (gallery), depoimentos (slider), estatisticas (counter), parceiros (gallery) |
| **Meta** | title: ISSM - Instituto de Seguridade Social dos Militares de MG |
| **Ultima Atualizacao** | 07/06/2026 |

### Institucional / Quem Somos

| Campo | Detalhe |
|-------|---------|
| **Rota** | `/institucional` |
| **Nome da Pagina CMS** | Institucional |
| **Slug** | `institucional` |
| **Template** | `pages.institucional` |
| **Arquivo Fallback** | `resources/views/pages/institucional.blade.php` |
| **Status** | Gerenciado |
| **Secoes** | historia, missao-visao-valores, diretoria, estrutura-organizacional |
| **Blocos** | linha-do-tempo (timeline), equipe (gallery), organograma (image) |
| **Meta** | title: Institucional - ISSM |
| **Ultima Atualizacao** | 07/06/2026 |

### Diretoria

| Campo | Detalhe |
|-------|---------|
| **Rota** | `/institucional/diretoria` |
| **Nome da Pagina CMS** | Diretoria |
| **Slug** | `diretoria` |
| **Template** | `pages.diretoria` |
| **Arquivo Fallback** | `resources/views/pages/diretoria.blade.php` |
| **Status** | Gerenciado |
| **Secoes** | presidente, vice-presidente, diretores, conselhos |
| **Blocos** | membros (gallery), biografias (accordion) |
| **Meta** | title: Diretoria - ISSM |
| **Ultima Atualizacao** | 07/06/2026 |

### Legislacao

| Campo | Detalhe |
|-------|---------|
| **Rota** | `/institucional/legislacao` |
| **Nome da Pagina CMS** | Legislacao |
| **Slug** | `legislacao` |
| **Template** | `pages.legislacao` |
| **Arquivo Fallback** | `resources/views/pages/legislacao.blade.php` |
| **Status** | Gerenciado |
| **Secoes** | leis, decretos, portarias, regulamentos |
| **Blocos** | documentos (accordion), downloads (list) |
| **Meta** | title: Legislacao - ISSM |
| **Ultima Atualizacao** | 07/06/2026 |

### Ouvidoria

| Campo | Detalhe |
|-------|---------|
| **Rota** | `/ouvidoria` |
| **Nome da Pagina CMS** | Ouvidoria |
| **Slug** | `ouvidoria` |
| **Template** | `pages.ouvidoria` |
| **Arquivo Fallback** | `resources/views/pages/ouvidoria.blade.php` |
| **Status** | Gerenciado |
| **Secoes** | apresentacao, formulario, perguntas-frequentes, contato |
| **Blocos** | faq (accordion), formulario-contato (form), canais-atendimento (list) |
| **Meta** | title: Ouvidoria - ISSM |
| **Ultima Atualizacao** | 07/06/2026 |

### Transparencia

| Campo | Detalhe |
|-------|---------|
| **Rota** | `/transparencia` |
| **Nome da Pagina CMS** | Transparencia |
| **Slug** | `transparencia` |
| **Template** | `pages.transparencia` |
| **Arquivo Fallback** | `resources/views/pages/transparencia.blade.php` |
| **Status** | Gerenciado |
| **Secoes** | prestacao-contas, relatorios, licitacoes, contratos, servidores |
| **Blocos** | tabelas-json (html), graficos (image), downloads (list) |
| **Meta** | title: Transparencia - ISSM |
| **Ultima Atualizacao** | 07/06/2026 |

### Prestacao de Contas

| Campo | Detalhe |
|-------|---------|
| **Rota** | `/transparencia/prestacao-de-contas` |
| **Nome da Pagina CMS** | Prestacao de Contas |
| **Slug** | `prestacao-de-contas` |
| **Template** | `pages.prestacao-contas` |
| **Arquivo Fallback** | `resources/views/pages/prestacao-contas.blade.php` |
| **Status** | Gerenciado |
| **Secoes** | anuais, demonstrativos, pareceres |
| **Blocos** | tabelas (html), graficos (image) |
| **Meta** | title: Prestacao de Contas - ISSM |
| **Ultima Atualizacao** | 07/06/2026 |

### Beneficios

| Campo | Detalhe |
|-------|---------|
| **Rota** | `/beneficios` |
| **Nome da Pagina CMS** | Beneficios |
| **Slug** | `beneficios` |
| **Template** | `pages.beneficios` |
| **Arquivo Fallback** | `resources/views/pages/beneficios.blade.php` |
| **Status** | Gerenciado |
| **Secoes** | assistencia-saude, pecuniarios, sociais, educacionais |
| **Blocos** | cards-beneficios (gallery), requisitos (accordion), simulador (form) |
| **Meta** | title: Beneficios - ISSM |
| **Ultima Atualizacao** | 07/06/2026 |

### Assistencia a Saude

| Campo | Detalhe |
|-------|---------|
| **Rota** | `/beneficios/assistencia-saude` |
| **Nome da Pagina CMS** | Assistencia a Saude |
| **Slug** | `assistencia-saude` |
| **Template** | `pages.assistencia-saude` |
| **Arquivo Fallback** | `resources/views/pages/assistencia-saude.blade.php` |
| **Status** | Gerenciado |
| **Secoes** | planos, rede-credenciada, procedimentos, guia-rapido |
| **Blocos** | tabela-planos (html), busca-rede (form), documentos (list) |
| **Meta** | title: Assistencia a Saude - ISSM |
| **Ultima Atualizacao** | 07/06/2026 |

### Rede Credenciada

| Campo | Detalhe |
|-------|---------|
| **Rota** | `/beneficios/rede-credenciada` |
| **Nome da Pagina CMS** | Rede Credenciada |
| **Slug** | `rede-credenciada` |
| **Template** | `pages.rede-credenciada` |
| **Arquivo Fallback** | `resources/views/pages/rede-credenciada.blade.php` |
| **Status** | Gerenciado |
| **Secoes** | busca, categorias, contatos-uteis |
| **Blocos** | buscador (form), lista-prestadores (html), mapa (map) |
| **Meta** | title: Rede Credenciada - ISSM |
| **Ultima Atualizacao** | 07/06/2026 |

### Noticias

| Campo | Detalhe |
|-------|---------|
| **Rota** | `/noticias` |
| **Nome da Pagina CMS** | Noticias |
| **Slug** | `noticias` |
| **Template** | `pages.noticias` |
| **Arquivo Fallback** | `resources/views/pages/noticias.blade.php` |
| **Status** | Gerenciado |
| **Secoes** | destaques, ultimas, categorias, arquivo |
| **Blocos** | grid-noticias (gallery), destaque-principal (image+text), categorias (list), paginacao |
| **Meta** | title: Noticias - ISSM |
| **Ultima Atualizacao** | 07/06/2026 |

### Noticia Individual

| Campo | Detalhe |
|-------|---------|
| **Rota** | `/noticias/{slug}` |
| **Nome da Pagina CMS** | Noticia (dinamico) |
| **Slug** | `{slug}` (gerado do titulo) |
| **Template** | `pages.noticia` |
| **Arquivo Fallback** | `resources/views/pages/noticia.blade.php` |
| **Status** | Gerenciado |
| **Secoes** | conteudo, autor, compartilhar, noticias-relacionadas |
| **Blocos** | share-buttons (html), relacionados (gallery) |
| **Meta** | Dinamico por noticia |
| **Ultima Atualizacao** | 07/06/2026 |

### Contato

| Campo | Detalhe |
|-------|---------|
| **Rota** | `/contato` |
| **Nome da Pagina CMS** | Contato |
| **Slug** | `contato` |
| **Template** | `pages.contato` |
| **Arquivo Fallback** | `resources/views/pages/contato.blade.php` |
| **Status** | Gerenciado |
| **Secoes** | formulario, enderecos, telefones-uteis, horarios |
| **Blocos** | form-contato (form), mapa (map), cards-endereco (gallery), horarios (html) |
| **Meta** | title: Contato - ISSM |
| **Ultima Atualizacao** | 07/06/2026 |

### FAQ / Perguntas Frequentes

| Campo | Detalhe |
|-------|---------|
| **Rota** | `/faq` |
| **Nome da Pagina CMS** | FAQ |
| **Slug** | `faq` |
| **Template** | `pages.faq` |
| **Arquivo Fallback** | `resources/views/pages/faq.blade.php` |
| **Status** | Gerenciado |
| **Secoes** | categorias-faq |
| **Blocos** | acordeoes (accordion), busca-faq (form) |
| **Meta** | title: Perguntas Frequentes - ISSM |
| **Ultima Atualizacao** | 07/06/2026 |

### Acesso a Informacao

| Campo | Detalhe |
|-------|---------|
| **Rota** | `/acesso-a-informacao` |
| **Nome da Pagina CMS** | Acesso a Informacao |
| **Slug** | `acesso-a-informacao` |
| **Template** | `pages.acesso-informacao` |
| **Arquivo Fallback** | `resources/views/pages/acesso-informacao.blade.php` |
| **Status** | Gerenciado |
| **Secoes** | institucional, acoes-programas, licitacoes-contratos, despesas, servidores |
| **Blocos** | icones-servicos (gallery), links-rapidos (list) |
| **Meta** | title: Acesso a Informacao - ISSM |
| **Ultima Atualizacao** | 07/06/2026 |

### Servicos Online

| Campo | Detalhe |
|-------|---------|
| **Rota** | `/servicos` |
| **Nome da Pagina CMS** | Servicos Online |
| **Slug** | `servicos-online` |
| **Template** | `pages.servicos` |
| **Arquivo Fallback** | `resources/views/pages/servicos.blade.php` |
| **Status** | Gerenciado |
| **Secoes** | autoatendimento, formularios, sistemas-externos |
| **Blocos** | cards-servicos (gallery), links-externos (list) |
| **Meta** | title: Servicos Online - ISSM |
| **Ultima Atualizacao** | 07/06/2026 |

### Editais e Licitações

| Campo | Detalhe |
|-------|---------|
| **Rota** | `/licitacoes` |
| **Nome da Pagina CMS** | Licitações |
| **Slug** | `licitacoes` |
| **Template** | `pages.licitacoes` |
| **Arquivo Fallback** | `resources/views/pages/licitacoes.blade.php` |
| **Status** | Gerenciado |
| **Secoes** | abertos, andamento, homologados, arquivados |
| **Blocos** | tabela-editais (html), filtros (form) |
| **Meta** | title: Editais e Licitações - ISSM |
| **Ultima Atualizacao** | 07/06/2026 |

### Concurso Publico

| Campo | Detalhe |
|-------|---------|
| **Rota** | `/concurso` |
| **Nome da Pagina CMS** | Concurso |
| **Slug** | `concurso` |
| **Template** | `pages.concurso` |
| **Arquivo Fallback** | `resources/views/pages/concurso.blade.php` |
| **Status** | Gerenciado |
| **Secoes** | informacoes, cronograma, resultados, downloads |
| **Blocos** | cronograma (html), downloads (list), resultados (accordion) |
| **Meta** | title: Concurso Publico - ISSM |
| **Ultima Atualizacao** | 07/06/2026 |

### Mapa do Site

| Campo | Detalhe |
|-------|---------|
| **Rota** | `/mapa-do-site` |
| **Nome da Pagina CMS** | Mapa do Site |
| **Slug** | `mapa-do-site` |
| **Template** | `pages.mapa-site` |
| **Arquivo Fallback** | `resources/views/pages/mapa-site.blade.php` |
| **Status** | Gerenciado |
| **Secoes** | arvore-paginas |
| **Blocos** | treeview (html) |
| **Meta** | title: Mapa do Site - ISSM |
| **Ultima Atualizacao** | 07/06/2026 |

---

## Paginas Especiais

| Pagina | Rota | CMS | Fallback | Observacao |
|--------|------|-----|----------|------------|
| Sitemap XML | `/sitemap.xml` | Sim (gerado) | - | Gerado por comando Artisan |
| Robots.txt | `/robots.txt` | Sim (gerado) | - | Configuracao via painel SEO |
| RSS Feed | `/feed.xml` | Planejado | - | Futura implementacao |
| Pagina de Erro 404 | `/` | Nao | `errors/404.blade.php` | Mantido como Blade |
| Pagina de Erro 403 | `/` | Nao | `errors/403.blade.php` | Mantido como Blade |
| Pagina de Erro 500 | `/` | Nao | `errors/500.blade.php` | Mantido como Blade |
| Login | `/login` | Nao | `auth/login.blade.php` | Autenticacao nativa |
| Registro | `/register` | Nao | `auth/register.blade.php` | Autenticacao nativa |

---

## Resumo de Gerenciamento

| Status | Quantidade | % |
|--------|-----------|-------|
| Gerenciado | 19 | 82.6% |
| Parcial | 0 | 0% |
| Pendente | 0 | 0% |
| Nao gerenciado | 4 | 17.4% |
| Planejado | 1 | - |
| **Total (publico)** | **23** | **100%** |

---

## Arquivos Fallback (Blade)

Os seguintes arquivos Blade servem como fallback para paginas que ainda nao foram migradas para o CMS:

```
resources/views/
  pages/
    home.blade.php
    institucional.blade.php
    diretoria.blade.php
    legislacao.blade.php
    ouvidoria.blade.php
    transparencia.blade.php
    prestacao-contas.blade.php
    beneficios.blade.php
    assistencia-saude.blade.php
    rede-credenciada.blade.php
    noticias.blade.php
    noticia.blade.php
    contato.blade.php
    faq.blade.php
    acesso-informacao.blade.php
    servicos.blade.php
    licitacoes.blade.php
    concurso.blade.php
    mapa-site.blade.php
  errors/
    403.blade.php
    404.blade.php
    500.blade.php
  auth/
    login.blade.php
    register.blade.php
```

---

*Relatorio gerado em: 07/06/2026*
*Versao do CMS: 1.0.0*

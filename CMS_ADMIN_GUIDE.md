# Guia do Administrador - CMS ISSM

## Sumario

Este guia explica como utilizar o painel administrativo do CMS Institucional do ISSM. Aborda todas as funcionalidades disponiveis para gerenciamento de conteudo, midia, menus, SEO, auditoria e cache.

---

## Acesso ao Painel

### URL de Acesso

```
https://www.issm.mg.gov.br/admin
```

### Login

1. Acesse a URL acima
2. Insira seu **email** e **senha** fornecidos pela equipe de TI
3. Clique em **Entrar**
4. Se habilitado, insira o codigo de **autenticacao de dois fatores**

### Primeiro Acesso

- Sua senha inicial sera fornecida pela TI
- O sistema solicitara alteracao de senha no primeiro acesso
- A senha deve ter no minimo 8 caracteres, com letras maiusculas, minusculas, numeros e caracteres especiais

---

## Dashboard

### Visao Geral

O dashboard exibe:

- **Total de paginas** publicadas, rascunhos e arquivadas
- **Total de midias** na biblioteca
- **Menus ativos**
- **Ultimas atividades** (auditoria)
- **Cache status** e atalhos para limpeza
- **Links rapidos** para acoes comuns

### Widgets

Cada widget pode ser clicado para navegar diretamente para a area correspondente.

---

## Paginas

### Listagem de Paginas

1. Navegue ate **Conteudo > Paginas** no menu lateral
2. A listagem exibe: titulo, slug, status, autor, data de atualizacao, acoes
3. Use o campo de **busca** para filtrar por titulo ou conteudo
4. Use os **filtros de status** (Publicado, Rascunho, Arquivado)
5. Clique nos cabecalhos para **ordenar** a lista

### Criar Pagina

1. Clique em **Nova Pagina**
2. Preencha os campos:

| Campo | Descricao | Obrigatorio |
|-------|-----------|-------------|
| Titulo | Titulo da pagina | Sim |
| Slug | URL amigavel (gerado automaticamente) | Sim |
| Conteudo | Conteudo principal (Summernote) | Nao |
| Meta Description | Resumo para SEO (max 160 caracteres) | Nao |
| Palavras-chave | Tags separadas por virgula | Nao |
| Template | Layout da pagina | Sim |
| Pagina Pai | Hierarquia (ex: Institucional > Diretoria) | Nao |
| Status | Publicado, Rascunho, Arquivado | Sim |

3. Clique em **Salvar**

### Editar Pagina

1. Clique no icone de **editar** (lapis) na pagina desejada
2. Alterar os campos necessarios
3. Clique em **Atualizar**

### Excluir Pagina

1. Clique no icone de **excluir** (lixeira)
2. Confirme a exclusao no dialogo
3. A pagina sera **marcada como excluida** (soft delete), podendo ser restaurada

### Publicar/Arquivar

1. Use o **toggle de status** para alternar entre Publicado e Rascunho
2. Use o menu de acoes para **Arquivar** (ocultar do site mas manter no admin)

### Duplicar Pagina

1. Clique em **Duplicar** no menu de acoes
2. Uma copia da pagina sera criada com sufixo "- copia"
3. Edite a copia conforme necessario

---

## Secoes

Secoes organizam o conteudo dentro de uma pagina. Cada pagina pode ter multiplas secoes.

### Gerenciar Secoes

1. Dentro da pagina, va ate a aba **Secoes**
2. As secoes aparecem em ordem, que pode ser alterada por **drag & drop**
3. Clique em **Nova Secao** para adicionar

### Campos da Secao

| Campo | Descricao |
|-------|-----------|
| Titulo | Titulo da secao |
| Slug | URL amigavel (ancora) |
| Subtitulo | Texto complementar |
| Conteudo | Conteudo HTML (Summernote) |
| Tipo | content, banner, gallery, destaque |
| CSS Class | Classe CSS extra para personalizacao |
| Ativo | Alternar visibilidade |

---

## Blocos

Blocos sao unidades modulares de conteudo que podem ser reutilizadas em diferentes secoes e paginas.

### Tipos de Bloco

| Tipo | Descricao | Icone |
|------|-----------|-------|
| Text | Texto formatado com Summernote | T |
| Image | Imagem unica com legenda | IMG |
| Video | Video incorporado (YouTube, Vimeo) | VID |
| Gallery | Galeria de imagens | GAL |
| Slider | Slideshow de imagens/texto | SLD |
| Accordion | Itens expansiveis (FAQ) | ACC |
| Tabs | Conteudo em abas | TAB |
| CTA | Call-to-action com botao | CTA |
| HTML | HTML personalizado (uso avancado) | HTML |
| Form | Formulario embutido | FRM |
| Map | Mapa interativo | MAP |
| Separator | Linha divisoria | SEP |
| Quote | Citacao destacada | QOT |
| Code | Bloco de codigo | COD |
| Template | Template pre-definido | TPL |

### Criar Bloco

1. Navegue ate **Conteudo > Blocos**
2. Clique em **Novo Bloco**
3. Selecione o **tipo** de bloco
4. Preencha os campos especificos do tipo
5. Configure as **configuracoes avancadas** (se aplicavel)
6. Clique em **Salvar**

### Vincular Bloco

Para adicionar um bloco a uma secao ou pagina:

1. Edite a secao ou pagina
2. Na area de **Blocos Vinculados**, clique em **Adicionar Bloco**
3. Selecione o bloco existente ou crie um novo
4. Ajuste a **ordem** por drag & drop

---

## Midia

### Biblioteca de Midia

A biblioteca centraliza todos os arquivos de midia do site.

1. Navegue ate **Midia > Biblioteca**
2. Visualizacao em **grade** com miniaturas
3. Use o campo de **busca** para filtrar por nome
4. Filtre por **tipo** (Imagens, Documentos, Videos, etc.)
5. Clique em uma midia para ver **detalhes**

### Upload de Midia

1. Clique em **Fazer Upload**
2. Arraste arquivos para a area pontilhada ou clique para selecionar
3. Formatos aceitos:
   - **Imagens**: JPG, PNG, GIF, SVG, WebP (ate 50MB)
   - **Documentos**: PDF, DOC, DOCX, XLS, XLSX (ate 50MB)
   - **Videos**: MP4, WebM, OGG (ate 50MB)
   - **Outros**: ZIP (ate 50MB)
4. As imagens sao **redimensionadas e otimizadas** automaticamente
5. Preencha: titulo, texto alternativo (alt), legenda

### Editar Midia

Clique na midia e edite:
- Titulo e descricao
- Texto alternativo (importante para acessibilidade)
- Legenda
- Pasta de organizacao

### Excluir Midia

1. Selecione a midia ou midias
2. Clique em **Excluir**
3. Confirme a exclusao
4. O arquivo fisico e removido, mas o registro permanece (soft delete)

### Seletor de Midia

Ao editar conteudo, voce pode abrir o seletor de midia:

1. Clique em **Selecionar Midia** ao lado de campos de imagem
2. Navegue pela biblioteca
3. Clique na imagem desejada
4. A URL e inserida automaticamente no campo

---

## Menus

### Gerenciar Menus

1. Navegue ate **Aparencia > Menus**
2. Lista de menus cadastrados (Header, Footer, etc.)

### Criar Menu

1. Clique em **Novo Menu**
2. Defina:
   - **Nome**: Identificacao interna
   - **Slug**: Identificador unico
   - **Descricao**: Opcional
   - **Localizacao**: header, footer, sidebar
3. Clique em **Salvar**

### Gerenciar Itens do Menu

1. Clique em **Gerenciar Itens** no menu desejado
2. Os itens aparecem em **arvore hierarquica**
3. Arraste para **reordenar** ou mudar o nivel (arrastando para direita como subitem)
4. Clique em **Novo Item**

### Criar Item de Menu

| Campo | Descricao |
|-------|-----------|
| Titulo | Texto exibido no menu |
| Tipo | Pagina interna, URL personalizada, Rota |
| Pagina (se interno) | Selecione a pagina do CMS |
| URL (se personalizada) | URL completa |
| Abrir em | Mesma janela (\_self) ou nova (\_blank) |
| Icone | Classe CSS do icon (opcional) |
| Ativo | Visivel ou oculto |

---

## SEO

### Configuracoes Globais de SEO

1. Navegue ate **SEO > Configuracoes**
2. Defina padroes globais:

| Campo | Descricao |
|-------|-----------|
| Meta Title Padrao | Titulo padrao para paginas sem SEO especifico |
| Meta Description Padrao | Descricao padrao |
| Open Graph Default | Imagem padrao para compartilhamento |
| Twitter Card | Card type (summary, summary_large_image) |
| JSON-LD | Dados estruturados (Schema.org) |
| Google Analytics | Codigo de tracking |
| Google Tag Manager | Container ID |
| Robots.txt | Conteudo personalizado |

### SEO por Pagina

Cada pagina pode ter configuracao SEO individual:

1. Edite a pagina
2. Va ate a aba **SEO**
3. Preencha os campos (se vazio, usa o padrao global):

| Campo | Limite | Descricao |
|-------|--------|-----------|
| Meta Title | 60 caracteres | Titulo da pagina nos resultados de busca |
| Meta Description | 160 caracteres | Descricao nos resultados |
| Palavras-chave | - | Termos relevantes separados por virgula |
| OG Title | 60 caracteres | Titulo para compartilhamento social |
| OG Description | 200 caracteres | Descricao para compartilhamento |
| OG Image | - | Imagem para compartilhamento (1200x630px) |
| Canonical URL | - | URL canonica |
| Robots | - | index,follow / noindex,nofollow |

### Preview de SEO

Ao editar SEO, um **preview em tempo real** mostra como a pagina aparecera nos resultados do Google e ao compartilhar no Facebook/Twitter.

### Sitemap

O sitemap.xml e gerado automaticamente e inclui todas as paginas publicadas. Para regenerar:

```bash
php artisan cms:generate-sitemap
```

---

## Auditoria

### Logs de Auditoria

1. Navegue ate **Sistema > Auditoria**
2. Visualize em formato de **tabela** ou **linha do tempo**
3. Cada entrada mostra:

| Coluna | Descricao |
|--------|-----------|
| Usuario | Quem realizou a acao |
| Acao | create, update, delete, restore, login |
| Entidade | Tipo e ID do registro |
| Descricao | Resumo da alteracao |
| IP | Endereco IP |
| Data | Quando ocorreu |

### Filtros

- Por **periodo** (data inicial e final)
- Por **usuario**
- Por **tipo de acao**
- Por **entidade**

### Diferenca (Diff)

Ao clicar em uma atualizacao, o sistema mostra um **diff visual** com:
- Linhas em **verde**: conteudo adicionado
- Linhas em **vermelho**: conteudo removido
- Destaque em **ambos**: conteudo alterado

### Exportacao

Os logs podem ser exportados em:
- **CSV** para analise em planilhas
- **PDF** para registro/auditoria

---

## Cache

### Gerenciamento de Cache

1. Navegue ate **Sistema > Cache**
2. O painel mostra o **status** de cada tipo de cache:

| Cache | Descricao | Tamanho Estimado |
|-------|-----------|------------------|
| Paginas | Conteudo das paginas publicas | 50-200KB por pagina |
| Configuracao | Configuracoes do Laravel | ~50KB |
| Rotas | Rotas registradas | ~100KB |
| Views | Templates compilados | ~2-5MB |
| Eventos | Listeners registrados | ~10KB |

### Acoes Disponiveis

| Botao | Descricao | Quando usar |
|-------|-----------|-------------|
| Limpar Paginas | Remove cache de paginas | Apos alterar conteudo |
| Limpar Config | Remove config cache | Apos alterar .env ou config/ |
| Limpar Rotas | Remove route cache | Apos adicionar/modificar rotas |
| Limpar Views | Recompila templates | Apos alterar views Blade |
| Limpar Tudo | Executa todos os clears | Apos deploy, problemas de cache |
| Aquecer Cache | Pre-carrega paginas ativas | Apos deploy, horario de baixo trafego |

### Quando Limpar o Cache

- **Sempre** apos publicar/alterar conteudo
- **Sempre** apos alterar configuracoes do sistema
- **Apos deploy** para garantir que tudo esteja atualizado
- **Ao notar** informacoes desatualizadas no site

---

## Versoes

### Controle de Versoes

Todas as alteracoes em paginas, secoes e blocos geram automaticamente uma **nova versao**.

1. Navegue ate **Conteudo > Versoes**
2. Visualize o historico de versoes de todas as entidades

### Comparar Versoes

1. Selecione duas versoes
2. Clique em **Comparar**
3. O diff visual mostra as alteracoes lado a lado

### Restaurar Versao

1. Encontre a versao desejada
2. Clique em **Restaurar**
3. Confirme a restauracao
4. Uma nova versao sera criada com o conteudo restaurado

### Politica de Retencao

- As **5 versoes mais recentes** de cada entidade sao mantidas
- Versoes com mais de **30 dias** sao removidas automaticamente
- Versoes marcadas como **importantes** nao sao removidas

---

## Usuarios e Permissoes

### Gerenciar Usuarios

1. Navegue ate **Sistema > Usuarios**
2. Lista de usuarios administrativos
3. Cada usuario tem: nome, email, papel, data de criacao, status

### Criar Usuario

1. Clique em **Novo Usuario**
2. Preencha: nome, email, senha, papel
3. O usuario recebera um email de boas-vindas com instrucoes

### Papeis e Permissoes

| Papel | Descricao |
|-------|-----------|
| Super Admin | Acesso total e irrestrito |
| Admin | Gerenciamento completo do CMS |
| Editor | Criar/editar conteudo, midia, menus, SEO |
| Author | Criar e editar conteudo proprio |

### Alterar Permissoes

Permissoes finas podem ser ajustadas em **Sistema > Permissoes** (apenas Super Admin).

---

## Backup

### Gerenciar Backups

1. Navegue ate **Sistema > Backup**
2. Visualize backups disponiveis com data e tamanho

### Criar Backup

1. Clique em **Criar Backup**
2. O backup inclui: banco de dados + arquivos de midia
3. Backups sao processados em **fila** (pode levar alguns minutos)

### Download

Clique no icone de download ao lado do backup desejado.

### Politica de Retencao

- Backups com mais de **30 dias** sao removidos automaticamente
- Recomenda-se download e armazenamento externo periodico

---

## Dicas e Boas Praticas

### Conteudo

1. **Sempre** preencha a meta description (aparece nos resultados de busca)
2. Use **imagens otimizadas** (comprima antes de enviar)
3. **Texto alternativo** em todas as imagens (acessibilidade)
4. Revise o **preview SEO** antes de publicar
5. **Nunca** cole conteudo diretamente do Word (use "Colar como texto")

### Organizacao

6. Crie uma **estrutura de pastas** na midia (ex: `institucional/`, `noticias/`)
7. Use **nomes descritivos** para blocos e secoes
8. Mantenha **menus organizados** com no maximo 3 niveis
9. **Arquive** paginas antigas em vez de excluir

### Seguranca

10. **Nao compartilhe** sua senha
11. **Sempre** faca logout ao finalizar
12. Verifique **logs de auditoria** periodicamente
13. Comunique a TI qualquer atividade suspeita

### Performance

14. Prefira **blocos reutilizaveis** em vez de criar conteudo duplicado
15. Limpe o **cache** apos publicar alteracoes importantes
16. Use **imagens no tamanho correto** (nao redimensione via HTML)
17. **Exporte backups** periodicamente

---

## Atalhos de Teclado

| Atalho | Acao |
|--------|------|
| `Ctrl + S` | Salvar pagina/bloco |
| `Ctrl + Shift + P` | Publicar pagina |
| `Ctrl + Z` | Desfazer (no editor) |
| `Ctrl + Shift + Z` | Refazer (no editor) |
| `Ctrl + B` | Negrito (Summernote) |
| `Ctrl + I` | Italico (Summernote) |
| `Ctrl + U` | Sublinhado (Summernote) |
| `Esc` | Fechar modal |

---

## Solucao de Problemas

| Problema | Solucao |
|----------|---------|
| Pagina nao aparece no site | Verificar status (Publicado?), limpar cache |
| Upload falha | Verificar tamanho/tipo do arquivo |
| Alteracao nao salva | Verificar conexao, tentar novamente |
| Erro 403 (Acesso negado) | Solicitar permissao ao admin |
| Erro 500 | Comunicar a TI (incluir horario e acao) |
| Cache nao limpa | Usar "Limpar Tudo" no painel de cache |
| Preview SEO diferente do esperado | Limpar cache e recarregar |
| Bloco nao aparece na pagina | Verificar se esta ativo e vinculado a secao correta |

---

## Suporte

### Canais de Suporte

- **Email**: suporte-cms@issm.mg.gov.br
- **Telefone**: (31) XXXX-XXXX (ramal TI)
- **Sistema de Tickets**: [helpdesk.issm.mg.gov.br](https://helpdesk.issm.mg.gov.br)

### Horario de Atendimento

- Segunda a Sexta: 08:00 as 18:00
- Emergencias: contato via telefone 24h (apenas para problemas criticos)

---

*Guia gerado em: 07/06/2026*
*Versao do CMS: 1.0.0*
*Framework: Laravel 10.x*

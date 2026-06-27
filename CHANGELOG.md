# CHANGELOG

## [1.3.0] - 2026-06-27

### Adicionado - Galeria por albuns e eventos

- Nova estrutura de galeria com albuns/eventos, fotos vinculadas e migracao dos registros antigos da tabela `gallery`
- Vinculo de um ou mais projetos por album, exibidos junto ao evento na galeria publica
- Upload multiplo simultaneo com arrasta e solta, progresso por arquivo, estimativa de tempo restante e alerta de dimensao ideal
- Controle de ativar/desativar album e foto individualmente
- Galeria publica ajustada para exibir somente fotos ativas dentro de albuns ativos, com filtros por evento
- Telas administrativas da galeria padronizadas com o layout e os campos dos demais CRUDs
- Corrigido hover e alinhamento dos checkboxes de projetos vinculados no tema dark
- Dropzone de fotos iniciais do album agora permite selecionar ou arrastar multiplas imagens de uma so vez
- Listagem administrativa da galeria remodelada com tabela, resumo, detalhes expansíveis e ações padronizadas
- Fotos do album no admin agora usam cards menores e paginacao para reduzir carregamento
- Galeria publica reorganizada por album com previa limitada, album completo paginado e imagens com lazy loading
- Edicao do album no admin reorganizada em secoes de dados, upload, resumo e fotos compactas
- Galeria publica passa a exibir pastas de albuns na raiz, carregando fotos somente ao abrir um album
- Capa configurada no album pelo administrativo passa a ser priorizada nas pastas da galeria publica
- Listagem administrativa da galeria passa a usar o mesmo modelo visual de pastas por album
- Fotos do album na galeria publica passam a carregar e aparecer gradualmente por rolagem com lazy loading via IntersectionObserver
- Paginacao visual da galeria publica removida e substituida por carregamento automatico ao rolar
- Secao de fotos do album no admin reformulada em cards visuais com miniatura, metadados, status e acoes agrupadas

## [1.2.0] - 2026-06-09

### Adicionado - Cloudflare Turnstile + Google reCAPTCHA no formulário de contato

- Suporte a **Cloudflare Turnstile** (recomendado, widget não-interativo) e **Google reCAPTCHA v3** (fallback automático)
- Dois novos campos de configuração: `turnstile_site_key` e `turnstile_secret_key` (grupo Segurança)
- Card de instruções passo-a-passo no admin (`/admin/configuracoes` > Segurança) explicando como obter chaves de ambos os provedores
- Prioridade: Turnstile se configurado, senão reCAPTCHA v3

### Adicionado - Preview de Compartilhamento em tempo real nas telas de SEO

- Card "Preview de Compartilhamento" na segunda coluna dos formulários de criar/editar notícias e projetos
- Simulações visuais de **Google (SERP)**, **Facebook/Meta** e **WhatsApp** atualizadas em tempo real enquanto o usuário digita os campos de SEO
- Pré-visualização de imagem via FileReader ao selecionar arquivo

### Alterado - Layout dos formulários admin de notícias e projetos

- Card **SEO** movido da coluna lateral para a coluna principal, abaixo do card de conteúdo
- Coluna lateral agora exibe card de "Preview de Compartilhamento" com simuladores de Google, Facebook e WhatsApp

### Corrigido - Acentuação em todo o sistema

- Corrigidos erros de acentuação em controllers, seeders, migrations e views administrativas
- Arquivos afetados: controllers admin, `SettingsSeeder`, `OdsSeeder`, `AdminMenuSeeder`, migrations de menu e configurações, views admin de notícias e projetos

## [1.1.2] - 2026-06-08

### Adicionado - Integracao Google Drive para Portal da Transparencia

- Sincronizacao automatica de documentos do Google Drive com o Portal da Transparencia
- Service `GoogleDriveService` para leitura da API do Google Drive via Service Account
- Command `php artisan transparency:sync-drive` para sincronizacao manual ou agendada
- Command suporta modo simulacao (`--dry-run`) para testar sem alterar o banco
- Novos campos na tabela `transparency_documents`: `google_drive_file_id`, `google_drive_url`, `source`
- View publica ajustada para exibir documentos do Drive em somente leitura/download
- Documentos removidos do Drive sao automaticamente desativados no portal
- Documentos manuais do admin (`source = local`) continuam independentes da sincronizacao

### Painel Administrativo

- Configuracao do Google Drive movida para o painel admin (banco de dados, nao mais `.env`)
- Nova aba **Google Drive** em **Configuracoes** com:
  - Toggle para ativar/desativar a integracao
  - Campo para ID da pasta raiz do Drive
  - Upload do arquivo JSON de credenciais da Service Account
- Novo link fixo **Configuracoes** na sidebar do admin (secao Sistema)
- Seeder `AdminMenuSeeder` atualizado: "Configuracoes" agora e item top-level no menu

### Documentacao

- `GOOGLE_DRIVE_SETUP.md` - Guia completo de configuracao da integracao

### Melhorias UX

- Adicionado card de instrucoes passo a passo diretamente na aba **Google Drive** do painel de configuracoes, facilitando a descoberta do processo de setup

## [1.1.2] - 2026-06-08 (correcao)

### Corrigido

- Corrigido card de instrucoes do Google Drive para respeitar o tema dark/light do sistema. Removidos estilos inline hardcoded e adicionadas regras CSS `[data-theme="dark"]` consistentes com o restante do painel administrativo

## [1.1.2] - 2026-06-08 (correcao dependencia)

### Corrigido

- Adicionada dependencia `google/apiclient` ao `composer.json` que estava faltando. Sem ela, a integracao com Google Drive falhava com erro `Class "Google\Client" not found` no servidor

## [1.1.2] - 2026-06-08 (teste OK)

### Testado

- Sincronizacao executada com sucesso no servidor de producao. Resultado: 2 pastas (`Atas 2026`, `Estatuto 2025`) e 2 documentos criados automaticamente no Portal da Transparencia. Integracao Google Drive operacional

## [1.1.2] - 2026-06-08 (correcao sync-drive)

### Corrigido

- `SyncTransparencyFromDrive` agora detecta alteracao de **categoria** (nome da pasta no Drive) e **ano** ao sincronizar. Antes, so atualizava se o nome do arquivo ou o link mudassem, ignorando renomeacao de pastas

## [1.1.2] - 2026-06-08 (SEO Preview + Pontuacao)

### Adicionado

- **Painel SEO interativo** para paginas CMS com:
  - **Preview em tempo real** do Google SERP, Facebook/LinkedIn e WhatsApp/Telegram
  - **Pontuacao SEO** (0-100) com anel de progresso colorido e dicas automaticas
  - **Contadores de caracteres** para Meta Title (50-60 ideal) e Meta Description (120-160 ideal)
  - **Select de Robots Meta** (index/follow, noindex, etc.)
  - Tema dark compativel em todos os elementos
- Campo `seo_score` na tabela `cms_public_page_seo` via migration
- Calculo automatico de pontuacao no `CmsContentService` ao salvar SEO
- **Paginas Dinamicas** agora tambem tem SEO completo:
  - Campos `og_title`, `og_description`, `og_image`, `canonical_url`, `robots_meta`, `seo_score` na tabela `pages`
  - Preview em tempo real e pontuacao nas telas de criar e editar pagina
  - Calculo de `seo_score` automatico no `PageController`
- **SEO Tags Persistentes (Hashtags)**
  - Campo `seo_tags` nas tabelas `cms_public_page_seo` e `pages`
  - Tags separadas por virgula com contador visual em tempo real
  - 5 pontos extras na pontuacao SEO quando tags estao presentes
  - Combina automaticamente com meta keywords para melhor indexacao no Google

## [1.1.2] - 2026-06-08 (correcao menu admin v2)

### Corrigido

- Consolidado menu **Sistema** em um unico dropdown dinamico do banco:
  - Itens adicionados: Configuracoes, Central de Cron, Pastas do Drive, Categorias de Transparencia
  - Removida secao fixa "SISTEMA" hardcoded da view `menu.blade.php`
  - Migration atualiza o children do item "Sistema" no banco e remove qualquer item "Configuracoes" standalone
  - AdminMenuSeeder atualizado com todos os itens no dropdown Sistema

## [1.1.2] - 2026-06-08 (Categorias de Transparencia v2)

### Atualizado

- **Drag-and-drop** para reordenar categorias via SortableJS — arraste os cards para a posicao desejada e clique "Salvar Ordem"
- **Endpoint** `POST /admin/transparencia-categorias/ordenar` atualiza o `sort_order` de todas as categorias
- Categorias exibidas como cards arrastaveis com icone de grip e animacao suave

### Adicionado

- **Migracao automatica** de categorias existentes — a migration `migrate_existing_categories` importa todas as categorias unicas do campo `category` de `transparency_documents` para a nova tabela `transparency_categories`, vinculando os documentos automaticamente
- Botao "Salvar Ordem" aparece apenas apos alguma mudanca de posicao

## [1.1.2] - 2026-06-08 (Categorias de Transparencia)

### Adicionado

- **Gerenciamento de Categorias** do Portal da Transparencia via `/admin/transparencia-categorias`
- Tabela `transparency_categories` com nome, `google_drive_folder_id`, ordem e status
- Model `TransparencyCategory` com relacionamento para documentos
- Controller `Admin\TransparencyCategoryController` com CRUD completo
- Ao criar uma categoria no sistema, a pasta correspondente e criada automaticamente no Google Drive (se configurado)
- Ao renomear uma categoria, a pasta no Drive tambem e renomeada
- Formularios de documentos (create/edit) agora usam select dinamico de categorias do banco (`category_id`)
- Link **Categorias de Transparencia** na sidebar do admin (secao Sistema)
- Campo `category_id` nullable em `transparency_documents` para migracao gradual

## [1.1.2] - 2026-06-08 (Pastas do Drive)

### Adicionado

- **Gerenciamento de Pastas do Google Drive** diretamente pelo painel administrativo em `/admin/drive-pastas`
- `GoogleDriveService` com metodos de escrita: `createFolder()`, `renameFolder()`, `deleteFolder()`
- Scope da API alterado de `DRIVE_READONLY` para `DRIVE` para permitir operacoes de escrita
- Controller `Admin\GoogleDriveFolderController` com listagem, criacao, renomeacao e exclusao de pastas
- View `admin/drive-folders/index.blade.php` com aviso sobre permissao de Editor necessaria
- Link **Pastas do Drive** na sidebar do admin (secao Sistema)

> Aviso: a Service Account precisa ter permissao de **Editor** (nao apenas Leitor) na pasta raiz do Google Drive para operacoes de escrita funcionarem.

## [1.1.2] - 2026-06-08 (Central de Cron v2)

### Atualizado

- **Central de Cron** aprimorada com interface tipo cPanel:
  - Presets de frequencia: a cada minuto, 5 min, 15 min, 30 min, hora, dia, semana, mes
  - Modo **Personalizado** com campos individuais: Minuto, Hora, Dia, Mes, Dia da Semana
  - Expressao cron calculada e exibida em tempo real (ex: `*/5 * * * *`)
  - **Ultima execucao** e **proxima execucao** calculadas e exibidas para cada tarefa
  - Suporte a expressoes cron complexas via biblioteca `dragonmantank/cron-expression`
- Migration `add_cron_fields_to_scheduled_tasks` adiciona campos: `minute`, `hour`, `day_of_month`, `month`, `day_of_week`, `expression`
- Model `ScheduledTask` com metodos `buildExpression()` e `nextRunAt()`
- `Kernel.php` usa `$schedule->command()->cron($expression)` para agendamento preciso

## [1.1.2] - 2026-06-08 (Central de Cron)

### Adicionado

- **Central de Cron** — painel administrativo para gerenciamento de tarefas agendadas
- Tabela `scheduled_tasks` armazena comando, descricao, frequencia e status (ativo/inativo)
- `app/Console/Kernel.php` le tasks ativas do banco e agenda automaticamente no Laravel Scheduler
- Controller `Admin\CronController` com listagem, edicao de frequencia, ativar/desativar e executar manualmente
- View `admin/cron/index.blade.php` com interface para controle total das tarefas
- Link **Central de Cron** na sidebar do admin (secao Sistema)
- Frequencias suportadas: a cada minuto, a cada hora, diario, semanal, mensal
- Sem necessidade de trafego HTTP — o servidor executa `php artisan schedule:run` a cada minuto e o sistema decide internamente quais comandos rodar
- Cron principal registrado no servidor de producao: `* * * * * cd /home/issmorg/public_html && php artisan schedule:run`

## [1.1.2] - 2026-06-08 (melhoria docs)

### Atualizado

- Instrucoes de criacao da Conta de Servico e geracao da chave JSON no `GOOGLE_DRIVE_SETUP.md` e no painel admin detalhadas passo a passo: botao **+ CRIAR CREDENCIAIS** (topo direito), escolher **Conta de servico**, depois clicar no nome da conta, aba **CHAVES**, **ADICIONAR CHAVE > Criar nova chave**, formato **JSON**, **CRIAR**

## [1.1.1] - 2026-06-07

### Atualizado - CMS de Páginas Públicas Reais

- Implementado mapeamento de páginas públicas reais com Graphifyy para preservar URLs, rotas, views e layout existentes
- Integração administrativa de páginas públicas existentes sem criar páginas aleatórias
- Painel CMS atualizado para editar páginas públicas reais com campos e seções baseados nas views atuais
- Confirmações de ação migradas para SweetAlert2 e notificações unificadas com Toastify/Toastr
- Editor de rich text padronizado com Summernote nas telas de conteúdo CMS
- Manutenção do fallback original: conteúdo público exibe valores atuais quando o CMS não estiver preenchido
- Atualização de validação e segurança do painel sem alterar visual público
- Adicionadas migrações de Spatie Permissions para criar as tabelas de roles e permissions no deploy

## [1.1.0] - 2026-06-07

### Adicionado - Super CMS Institucional

- Sistema completo de CMS com gerenciamento de páginas, seções, blocos, campos e mídia
- 11 novas tabelas CMS: cms_pages, cms_sections, cms_blocks, cms_fields, cms_media, cms_versions, cms_audit_logs, cms_page_seo, cms_redirects, cms_menus, cms_menu_items
- 11 Models Eloquent completos com relacionamentos, scopes e SoftDeletes
- Painel administrativo completo para CMS com 47 novas rotas
- Page Builder controlado com 20 tipos de blocos pré-definidos
- Sistema de versionamento de conteúdo
- Auditoria administrativa completa com logs de todas as ações
- SEO completo por página (meta tags, Open Graph, Twitter Cards, Schema JSON-LD)
- Sitemap XML dinâmico
- Robots.txt configurável
- Biblioteca de mídia com upload seguro
- Gerenciamento de menus com arrastar e soltar
- Redirecionamentos 301 administrativos

### Segurança

- Middleware de Security Headers (CSP, X-Frame, HSTS, etc.)
- Middleware de bloqueio de requisições suspeitas (SQLi, XSS, Path Traversal)
- Middleware de auditoria administrativa
- Sanitização de HTML com whitelist de tags seguras
- Upload seguro com validação de MIME real e bloqueio de executáveis
- Rate limiting para login, contato e upload
- Honeypot em formulários públicos
- Proteção contra mass assignment
- CSRF em todos os formulários

### Performance

- Cache inteligente por página, seção, bloco, menu e SEO
- Eager loading em todas as consultas
- Paginação em todas as listagens
- Índices otimizados no banco de dados
- Comandos Artisan para gerenciamento de cache

### Comandos Artisan

- `php artisan cms:clear-cache` - Limpa cache do CMS
- `php artisan cms:audit-hardcoded-content` - Auditoria de conteúdo fixo em views
- `php artisan cms:health-check` - Verificação de saúde do sistema

### Documentação

- CMS_IMPLEMENTATION_REPORT.md
- SECURITY_HARDENING_REPORT.md
- PERFORMANCE_OPTIMIZATION_REPORT.md
- CMS_CONTENT_MAP.md
- CMS_ADMIN_GUIDE.md

### Dependências

- Spatie Laravel Permission já incluso
- SweetAlert2 para confirmações
- Toastify para notificações
- Summernote para edição rica

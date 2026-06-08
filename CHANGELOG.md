# CHANGELOG

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

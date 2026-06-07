# CHANGELOG

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

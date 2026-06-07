<?php

namespace App\Services\Cms;

use Illuminate\Support\Facades\File;

class CmsReportGenerator
{
    public function generatePerformanceReport(): string
    {
        $lines = ["# Relatório de Performance CMS — ISSM\n"];
        $lines[] = 'Gerado em: ' . now()->format('d/m/Y H:i:s') . "\n";

        $lines[] = '## Otimizações Implementadas';
        $lines[] = '- Cache por campo CMS (600s) via `CmsContentService::get()`';
        $lines[] = '- Cache por página (`cms_original_page_fields_{page_key}`)';
        $lines[] = '- Cache de settings existente (300s) preservado';
        $lines[] = '- Limpeza de cache por página no painel admin';
        $lines[] = '- Consultas CMS só executadas quando tabelas existem (Schema::hasTable)';

        $lines[] = "\n## Gargalos Identificados (Graphifyy)";
        $lines[] = '- `HomeController` faz 10+ queries separadas sem eager loading';
        $lines[] = '- `layouts/app.blade.php` consulta `Setting::get()` múltiplas vezes no head';
        $lines[] = '- Seções home (ODS, equipe, galeria) passadas pelo controller mas não renderizadas';

        $lines[] = "\n## Recomendações Aplicáveis";
        $lines[] = '- Consolidar queries do HomeController em Service com cache';
        $lines[] = '- Mover SEO do layout para View Composer com cache';
        $lines[] = '- Paginação já ativa em news/projects (9 por página)';
        $lines[] = '- Índices criados: `page_key`, `route_name`, `section_key` compostos';

        $lines[] = "\n## Cache por Página";
        foreach (CmsPageDefinitions::pages() as $page) {
            if ($page['is_editable'] ?? true) {
                $lines[] = "- `{$page['page_key']}`: cache ativo, TTL 600s";
            }
        }

        return implode("\n", $lines);
    }

    public function generateSecurityReport(): string
    {
        $lines = ["# Relatório de Segurança CMS — ISSM\n"];
        $lines[] = 'Gerado em: ' . now()->format('d/m/Y H:i:s') . "\n";

        $lines[] = '## Medidas Implementadas';
        $lines[] = '- FormRequest com validação (`UpdateCmsPublicPageRequest`, `UpdateCmsPublicPageSeoRequest`)';
        $lines[] = '- Autorização: apenas `is_admin` pode editar';
        $lines[] = '- CSRF em todos os formulários admin';
        $lines[] = '- Sanitização Summernote via `CmsSanitizer` (strip_tags + remoção on*)';
        $lines[] = '- Proteção XSS: `cms()` escapa, `cms_html()` sanitiza HTML';
        $lines[] = '- Auditoria administrativa (`cms_original_page_audit_logs`)';
        $lines[] = '- Versionamento de alterações (`cms_original_page_versions`)';
        $lines[] = '- Rate limit existente em `RouteServiceProvider` preservado';
        $lines[] = '- Middleware `auth` + `admin` nas rotas CMS';
        $lines[] = '- Soft delete em páginas CMS (nunca apaga rotas reais)';

        $lines[] = "\n## Padrões do Painel";
        $lines[] = '- Confirmações: SweetAlert2 (intercepta `onsubmit confirm`)';
        $lines[] = '- Notificações: Toastify-js';
        $lines[] = '- Editor: Summernote com lang pt-BR';

        $lines[] = "\n## Pontos de Atenção";
        $lines[] = '- Upload de mídia CMS: usar limites `Setting::uploadLimitKb()` quando implementado';
        $lines[] = '- `pages/show.blade.php`: conteúdo WYSIWYG ainda escapado com e() — corrigir separadamente';
        $lines[] = '- Permissões granulares: considerar spatie/laravel-permission por módulo CMS';

        return implode("\n", $lines);
    }

    public function saveAll(): void
    {
        $dir = storage_path('app/reports');
        if (!File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        File::put($dir . '/cms-performance-report.md', $this->generatePerformanceReport());
        File::put($dir . '/cms-security-report.md', $this->generateSecurityReport());
    }
}

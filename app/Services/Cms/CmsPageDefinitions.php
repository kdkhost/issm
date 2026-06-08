<?php

namespace App\Services\Cms;

class CmsPageDefinitions
{
    /**
     * Páginas públicas reais detectadas no sistema ISSM.
     * Não inclui páginas inventadas — apenas rotas existentes em routes/web.php.
     */
    public static function pages(): array
    {
        return [
            [
                'page_key' => 'home',
                'route_name' => 'home',
                'route_uri' => '/',
                'controller' => 'App\\Http\\Controllers\\HomeController',
                'method' => 'index',
                'view_path' => 'home.blade.php',
                'title' => 'Página Inicial',
                'admin_label' => 'Home',
                'sort_order' => 1,
                'sections' => self::homeSections(),
            ],
            [
                'page_key' => 'about',
                'route_name' => 'about.index',
                'route_uri' => '/sobre',
                'controller' => 'App\\Http\\Controllers\\PublicAboutController',
                'method' => 'index',
                'view_path' => 'about/index.blade.php',
                'title' => 'Sobre o ISSM',
                'admin_label' => 'Sobre',
                'sort_order' => 2,
                'sections' => self::aboutSections(),
            ],
            [
                'page_key' => 'ods',
                'route_name' => 'ods.index',
                'route_uri' => '/ods',
                'controller' => 'App\\Http\\Controllers\\PublicOdsController',
                'method' => 'index',
                'view_path' => 'ods/index.blade.php',
                'title' => 'ODS 2030',
                'admin_label' => 'ODS',
                'sort_order' => 3,
                'sections' => self::odsSections(),
            ],
            [
                'page_key' => 'gallery',
                'route_name' => 'gallery.index',
                'route_uri' => '/galeria',
                'controller' => 'App\\Http\\Controllers\\PublicGalleryController',
                'method' => 'index',
                'view_path' => 'gallery/index.blade.php',
                'title' => 'Galeria',
                'admin_label' => 'Galeria',
                'sort_order' => 4,
                'sections' => self::gallerySections(),
            ],
            [
                'page_key' => 'news',
                'route_name' => 'news.index',
                'route_uri' => '/noticias',
                'controller' => 'App\\Http\\Controllers\\PublicNewsController',
                'method' => 'index',
                'view_path' => 'news/index.blade.php',
                'title' => 'Notícias',
                'admin_label' => 'Notícias',
                'sort_order' => 5,
                'sections' => self::newsSections(),
            ],
            [
                'page_key' => 'projects',
                'route_name' => 'projects.index',
                'route_uri' => '/projetos',
                'controller' => 'App\\Http\\Controllers\\PublicProjectController',
                'method' => 'index',
                'view_path' => 'projects/index.blade.php',
                'title' => 'Projetos',
                'admin_label' => 'Projetos',
                'sort_order' => 6,
                'sections' => self::projectsSections(),
            ],
            [
                'page_key' => 'contact',
                'route_name' => 'contact.index',
                'route_uri' => '/contato',
                'controller' => 'App\\Http\\Controllers\\PublicContactController',
                'method' => 'index',
                'view_path' => 'contact/index.blade.php',
                'title' => 'Contato',
                'admin_label' => 'Contato',
                'sort_order' => 7,
                'sections' => self::contactSections(),
            ],
            [
                'page_key' => 'transparency',
                'route_name' => 'transparency.index',
                'route_uri' => '/transparencia',
                'controller' => 'App\\Http\\Controllers\\PublicTransparencyController',
                'method' => 'index',
                'view_path' => 'transparency/index.blade.php',
                'title' => 'Transparência',
                'admin_label' => 'Transparência',
                'sort_order' => 8,
                'sections' => self::transparencySections(),
            ],
            [
                'page_key' => 'pages_dynamic',
                'route_name' => 'pages.show',
                'route_uri' => '/pagina/{slug}',
                'controller' => 'App\\Http\\Controllers\\PublicPageController',
                'method' => 'show',
                'view_path' => 'pages/show.blade.php',
                'title' => 'Páginas Dinâmicas',
                'admin_label' => 'Páginas Dinâmicas (existente)',
                'sort_order' => 9,
                'is_editable' => false,
                'sections' => [],
            ],
            [
                'page_key' => 'news_detail',
                'route_name' => 'news.show',
                'route_uri' => '/noticias/{slug}',
                'controller' => 'App\\Http\\Controllers\\PublicNewsController',
                'method' => 'show',
                'view_path' => 'news/show.blade.php',
                'title' => 'Detalhe de Notícia',
                'admin_label' => 'Detalhe Notícia (modelo)',
                'sort_order' => 10,
                'is_editable' => false,
                'sections' => [],
            ],
            [
                'page_key' => 'projects_detail',
                'route_name' => 'projects.show',
                'route_uri' => '/projetos/{slug}',
                'controller' => 'App\\Http\\Controllers\\PublicProjectController',
                'method' => 'show',
                'view_path' => 'projects/show.blade.php',
                'title' => 'Detalhe de Projeto',
                'admin_label' => 'Detalhe Projeto (modelo)',
                'sort_order' => 11,
                'is_editable' => false,
                'sections' => [],
            ],
        ];
    }

    private static function homeSections(): array
    {
        return [
            [
                'section_key' => 'hero',
                'section_label' => 'Hero / Banner',
                'sort_order' => 1,
                'fields' => [
                    ['field_key' => 'title_line1', 'field_label' => 'Título linha 1', 'field_type' => 'text', 'default_value' => 'Instituto Socioambiental', 'sort_order' => 1],
                    ['field_key' => 'title_line2', 'field_label' => 'Título linha 2', 'field_type' => 'text', 'default_value' => 'Serra do Mendanha', 'sort_order' => 2],
                    ['field_key' => 'subtitle', 'field_label' => 'Subtítulo', 'field_type' => 'textarea', 'default_value' => 'Comprometidos com a preservacao ambiental e o desenvolvimento sustentavel alinhado com os ODS 2030.', 'sort_order' => 3],
                    ['field_key' => 'cta_text', 'field_label' => 'Texto do botão', 'field_type' => 'text', 'default_value' => 'Conheca o ISSM', 'sort_order' => 4],
                    ['field_key' => 'cta_url', 'field_label' => 'URL do botão', 'field_type' => 'url', 'default_value' => '/sobre', 'sort_order' => 5],
                ],
            ],
            [
                'section_key' => 'projects',
                'section_label' => 'Projetos em Destaque',
                'sort_order' => 2,
                'fields' => [
                    ['field_key' => 'eyebrow', 'field_label' => 'Rótulo superior', 'field_type' => 'text', 'default_value' => 'O que fazemos', 'sort_order' => 1],
                    ['field_key' => 'title', 'field_label' => 'Título', 'field_type' => 'text', 'default_value' => 'Nossos Projetos', 'sort_order' => 2],
                    ['field_key' => 'link_text', 'field_label' => 'Texto do link', 'field_type' => 'text', 'default_value' => 'Ver todos', 'sort_order' => 3],
                ],
            ],
            [
                'section_key' => 'testimonials',
                'section_label' => 'Depoimentos',
                'sort_order' => 3,
                'fields' => [
                    ['field_key' => 'eyebrow', 'field_label' => 'Rótulo superior', 'field_type' => 'text', 'default_value' => 'Impacto Real', 'sort_order' => 1],
                    ['field_key' => 'title', 'field_label' => 'Título', 'field_type' => 'text', 'default_value' => 'O que dizem Sobre Nós', 'sort_order' => 2],
                ],
            ],
            [
                'section_key' => 'news',
                'section_label' => 'Notícias Recentes',
                'sort_order' => 4,
                'fields' => [
                    ['field_key' => 'eyebrow', 'field_label' => 'Rótulo superior', 'field_type' => 'text', 'default_value' => 'Fique por dentro', 'sort_order' => 1],
                    ['field_key' => 'title', 'field_label' => 'Título', 'field_type' => 'text', 'default_value' => 'Últimas Notícias', 'sort_order' => 2],
                    ['field_key' => 'link_text', 'field_label' => 'Texto do link', 'field_type' => 'text', 'default_value' => 'Ver todas', 'sort_order' => 3],
                ],
            ],
            [
                'section_key' => 'faq',
                'section_label' => 'FAQ',
                'sort_order' => 5,
                'fields' => [
                    ['field_key' => 'eyebrow', 'field_label' => 'Rótulo superior', 'field_type' => 'text', 'default_value' => 'Dúvidas Comuns', 'sort_order' => 1],
                    ['field_key' => 'title', 'field_label' => 'Título', 'field_type' => 'text', 'default_value' => 'Perguntas Frequentes', 'sort_order' => 2],
                ],
            ],
            [
                'section_key' => 'partners',
                'section_label' => 'Parceiros',
                'sort_order' => 6,
                'fields' => [
                    ['field_key' => 'title', 'field_label' => 'Título', 'field_type' => 'text', 'default_value' => 'Nossos Parceiros', 'sort_order' => 1],
                ],
            ],
        ];
    }

    private static function aboutSections(): array
    {
        return [
            [
                'section_key' => 'hero',
                'section_label' => 'Hero',
                'sort_order' => 1,
                'fields' => [
                    ['field_key' => 'title', 'field_label' => 'Título', 'field_type' => 'text', 'default_value' => 'Instituto Socioambiental', 'sort_order' => 1],
                    ['field_key' => 'subtitle', 'field_label' => 'Subtítulo', 'field_type' => 'textarea', 'default_value' => 'Conheça nossa história, missão e o compromisso com a preservação da Serra do Mendanha.', 'sort_order' => 2],
                    ['field_key' => 'stat_label', 'field_label' => 'Rótulo do stat', 'field_type' => 'text', 'default_value' => 'Colaboradores ativos', 'sort_order' => 3],
                ],
            ],
            [
                'section_key' => 'identity',
                'section_label' => 'Identidade',
                'sort_order' => 2,
                'fields' => [
                    ['field_key' => 'eyebrow', 'field_label' => 'Rótulo superior', 'field_type' => 'text', 'default_value' => 'Nossa Identidade', 'sort_order' => 1],
                    ['field_key' => 'title', 'field_label' => 'Título', 'field_type' => 'text', 'default_value' => 'Preservação e Sustentabilidade', 'sort_order' => 2],
                    ['field_key' => 'mission_label', 'field_label' => 'Rótulo Missão', 'field_type' => 'text', 'default_value' => 'Missão', 'sort_order' => 3],
                    ['field_key' => 'vision_label', 'field_label' => 'Rótulo Visão', 'field_type' => 'text', 'default_value' => 'Visão', 'sort_order' => 4],
                    ['field_key' => 'values_label', 'field_label' => 'Rótulo Valores', 'field_type' => 'text', 'default_value' => 'Valores', 'sort_order' => 5],
                ],
            ],
            [
                'section_key' => 'team',
                'section_label' => 'Equipe',
                'sort_order' => 3,
                'fields' => [
                    ['field_key' => 'eyebrow', 'field_label' => 'Rótulo superior', 'field_type' => 'text', 'default_value' => 'Capital Humano', 'sort_order' => 1],
                    ['field_key' => 'title', 'field_label' => 'Título', 'field_type' => 'text', 'default_value' => 'Nossa Equipe', 'sort_order' => 2],
                    ['field_key' => 'subtitle', 'field_label' => 'Subtítulo', 'field_type' => 'textarea', 'default_value' => 'Conheça as pessoas dedicadas à preservação ambiental e ao desenvolvimento sustentável.', 'sort_order' => 3],
                ],
            ],
        ];
    }

    private static function odsSections(): array
    {
        return [
            [
                'section_key' => 'hero',
                'section_label' => 'Hero',
                'sort_order' => 1,
                'fields' => [
                    ['field_key' => 'title', 'field_label' => 'Título', 'field_type' => 'text', 'default_value' => 'Objetivos de Desenvolvimento', 'sort_order' => 1],
                    ['field_key' => 'subtitle', 'field_label' => 'Subtítulo', 'field_type' => 'textarea', 'default_value' => 'Nossas ações estão alinhadas à Agenda 2030 da ONU para um futuro mais sustentável.', 'sort_order' => 2],
                    ['field_key' => 'stat_label', 'field_label' => 'Rótulo do stat', 'field_type' => 'text', 'default_value' => 'Objetivos integrados', 'sort_order' => 3],
                ],
            ],
            [
                'section_key' => 'intro',
                'section_label' => 'Introdução',
                'sort_order' => 2,
                'fields' => [
                    ['field_key' => 'title', 'field_label' => 'Título', 'field_type' => 'text', 'default_value' => 'A Agenda 2030', 'sort_order' => 1],
                    ['field_key' => 'text', 'field_label' => 'Texto', 'field_type' => 'html', 'default_value' => 'A Agenda 2030 para o Desenvolvimento Sustentável é um plano de ação global adotado por todos os membros das Nações Unidas em 2015.', 'sort_order' => 2],
                ],
            ],
        ];
    }

    private static function gallerySections(): array
    {
        return [
            [
                'section_key' => 'hero',
                'section_label' => 'Hero',
                'sort_order' => 1,
                'fields' => [
                    ['field_key' => 'title', 'field_label' => 'Título', 'field_type' => 'text', 'default_value' => 'Galeria Completa', 'sort_order' => 1],
                    ['field_key' => 'subtitle', 'field_label' => 'Subtítulo', 'field_type' => 'textarea', 'default_value' => 'Registros fotográficos dos nossos projetos e ações na Serra do Mendanha.', 'sort_order' => 2],
                ],
            ],
            [
                'section_key' => 'empty',
                'section_label' => 'Estado Vazio',
                'sort_order' => 2,
                'fields' => [
                    ['field_key' => 'title', 'field_label' => 'Título', 'field_type' => 'text', 'default_value' => 'Nenhuma foto encontrada', 'sort_order' => 1],
                    ['field_key' => 'message', 'field_label' => 'Mensagem', 'field_type' => 'textarea', 'default_value' => 'A galeria está vazia no momento. Volte em breve!', 'sort_order' => 2],
                ],
            ],
        ];
    }

    private static function newsSections(): array
    {
        return [
            [
                'section_key' => 'hero',
                'section_label' => 'Hero',
                'sort_order' => 1,
                'fields' => [
                    ['field_key' => 'title', 'field_label' => 'Título', 'field_type' => 'text', 'default_value' => 'Blog & Notícias', 'sort_order' => 1],
                    ['field_key' => 'subtitle', 'field_label' => 'Subtítulo', 'field_type' => 'textarea', 'default_value' => 'Fique por dentro das novidades, eventos e ações do ISSM.', 'sort_order' => 2],
                    ['field_key' => 'stat_label', 'field_label' => 'Rótulo do stat', 'field_type' => 'text', 'default_value' => 'Artigos publicados', 'sort_order' => 3],
                ],
            ],
            [
                'section_key' => 'list',
                'section_label' => 'Listagem',
                'sort_order' => 2,
                'fields' => [
                    ['field_key' => 'empty_message', 'field_label' => 'Mensagem vazia', 'field_type' => 'text', 'default_value' => 'Nenhuma noticia publicada ainda.', 'sort_order' => 1],
                    ['field_key' => 'card_cta', 'field_label' => 'Texto do botão', 'field_type' => 'text', 'default_value' => 'Ler mais', 'sort_order' => 2],
                ],
            ],
        ];
    }

    private static function projectsSections(): array
    {
        return [
            [
                'section_key' => 'hero',
                'section_label' => 'Hero',
                'sort_order' => 1,
                'fields' => [
                    ['field_key' => 'title', 'field_label' => 'Título', 'field_type' => 'text', 'default_value' => 'Nossos Projetos', 'sort_order' => 1],
                    ['field_key' => 'subtitle', 'field_label' => 'Subtítulo', 'field_type' => 'textarea', 'default_value' => 'Iniciativas dedicadas à preservação ambiental e ao desenvolvimento sustentável.', 'sort_order' => 2],
                    ['field_key' => 'stat_label', 'field_label' => 'Rótulo do stat', 'field_type' => 'text', 'default_value' => 'Iniciativas ativas', 'sort_order' => 3],
                ],
            ],
            [
                'section_key' => 'list',
                'section_label' => 'Listagem',
                'sort_order' => 2,
                'fields' => [
                    ['field_key' => 'empty_message', 'field_label' => 'Mensagem vazia', 'field_type' => 'text', 'default_value' => 'Nenhum projeto publicado ainda.', 'sort_order' => 1],
                    ['field_key' => 'card_cta', 'field_label' => 'Texto do botão', 'field_type' => 'text', 'default_value' => 'Saiba mais', 'sort_order' => 2],
                ],
            ],
        ];
    }

    private static function contactSections(): array
    {
        return [
            [
                'section_key' => 'hero',
                'section_label' => 'Hero',
                'sort_order' => 1,
                'fields' => [
                    ['field_key' => 'title', 'field_label' => 'Título', 'field_type' => 'text', 'default_value' => 'Fale Conosco', 'sort_order' => 1],
                    ['field_key' => 'subtitle', 'field_label' => 'Subtítulo', 'field_type' => 'textarea', 'default_value' => 'Estamos prontos para ouvir você. Entre em contato conosco.', 'sort_order' => 2],
                ],
            ],
            [
                'section_key' => 'info',
                'section_label' => 'Informações',
                'sort_order' => 2,
                'fields' => [
                    ['field_key' => 'visit_title', 'field_label' => 'Título Visite-nos', 'field_type' => 'text', 'default_value' => 'Visite-nos', 'sort_order' => 1],
                    ['field_key' => 'email_title', 'field_label' => 'Título E-mail', 'field_type' => 'text', 'default_value' => 'E-mail', 'sort_order' => 2],
                    ['field_key' => 'phone_title', 'field_label' => 'Título Telefone', 'field_type' => 'text', 'default_value' => 'Telefone', 'sort_order' => 3],
                    ['field_key' => 'hours', 'field_label' => 'Horário de atendimento', 'field_type' => 'text', 'default_value' => 'Segunda a Sexta: 08:00 às 17:00 • Sábados: 08:00 às 12:00', 'sort_order' => 4],
                ],
            ],
            [
                'section_key' => 'form',
                'section_label' => 'Formulário',
                'sort_order' => 3,
                'fields' => [
                    ['field_key' => 'title', 'field_label' => 'Título do formulário', 'field_type' => 'text', 'default_value' => 'Envie uma Mensagem', 'sort_order' => 1],
                    ['field_key' => 'submit_text', 'field_label' => 'Texto do botão', 'field_type' => 'text', 'default_value' => 'Enviar agora', 'sort_order' => 2],
                    ['field_key' => 'map_title', 'field_label' => 'Título do mapa', 'field_type' => 'text', 'default_value' => 'Nossa Localização', 'sort_order' => 3],
                ],
            ],
        ];
    }

    private static function transparencySections(): array
    {
        return [
            [
                'section_key' => 'hero',
                'section_label' => 'Hero',
                'sort_order' => 1,
                'fields' => [
                    ['field_key' => 'title', 'field_label' => 'Título', 'field_type' => 'text', 'default_value' => 'Portal da Transparência', 'sort_order' => 1],
                    ['field_key' => 'subtitle', 'field_label' => 'Subtítulo', 'field_type' => 'textarea', 'default_value' => 'Compromisso com a integridade e a prestação de contas à sociedade.', 'sort_order' => 2],
                    ['field_key' => 'stat_text', 'field_label' => 'Texto do stat', 'field_type' => 'text', 'default_value' => 'Documentação Oficial', 'sort_order' => 3],
                ],
            ],
            [
                'section_key' => 'empty',
                'section_label' => 'Estado Vazio',
                'sort_order' => 2,
                'fields' => [
                    ['field_key' => 'title', 'field_label' => 'Título', 'field_type' => 'text', 'default_value' => 'Nenhum documento encontrado', 'sort_order' => 1],
                    ['field_key' => 'message', 'field_label' => 'Mensagem', 'field_type' => 'textarea', 'default_value' => 'Os documentos de transparência serão publicados em breve.', 'sort_order' => 2],
                ],
            ],
            [
                'section_key' => 'cta',
                'section_label' => 'CTA',
                'sort_order' => 3,
                'fields' => [
                    ['field_key' => 'eyebrow', 'field_label' => 'Rótulo superior', 'field_type' => 'text', 'default_value' => 'Dúvidas ou Informações?', 'sort_order' => 1],
                    ['field_key' => 'title', 'field_label' => 'Título', 'field_type' => 'text', 'default_value' => 'Entre em contato com nossa equipe', 'sort_order' => 2],
                    ['field_key' => 'text', 'field_label' => 'Texto', 'field_type' => 'textarea', 'default_value' => 'Estamos à disposição para esclarecer dúvidas sobre nossos documentos e prestação de contas.', 'sort_order' => 3],
                    ['field_key' => 'button_text', 'field_label' => 'Texto do botão', 'field_type' => 'text', 'default_value' => 'Fale com nossa equipe', 'sort_order' => 4],
                ],
            ],
        ];
    }
}

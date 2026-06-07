<?php

namespace Database\Seeders;

/**
 * @autor marcelo-brad rj
 * @contato Tel: 21 981325441
 * Email: contato@kdkhost.com.br
 * Telegram: @MARCELO_BRAD
 * Instagram: @marcelobradrj
 * WhatsApp: 21981325441
 */

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CmsDefaultSeeder extends Seeder
{
    public function run(): void
    {
        try {
            // Create default CMS settings
            $settings = [
                'cms_version' => '1.0.0',
                'cms_cache_enabled' => '1',
                'cms_audit_enabled' => '1',
            ];

            foreach ($settings as $key => $value) {
                DB::table('settings')->updateOrInsert(
                    ['key' => $key],
                    ['value' => $value, 'updated_at' => now(), 'created_at' => now()]
                );
            }

            $this->command->info('Default CMS settings created.');

            // Create default CMS menus (4 locations)
            $menuDefinitions = [
                'header' => [
                    'name' => 'Cabeçalho',
                    'slug' => 'header',
                    'description' => 'Menu principal do cabeçalho (desktop)',
                    'items' => [
                        ['title' => 'Início', 'url' => '/', 'sort_order' => 0],
                        ['title' => 'Sobre', 'url' => '/sobre', 'sort_order' => 1],
                        ['title' => 'Projetos', 'url' => '/projetos', 'sort_order' => 2],
                        ['title' => 'ODS 2030', 'url' => '/ods', 'sort_order' => 3],
                        ['title' => 'Notícias', 'url' => '/noticias', 'sort_order' => 4],
                        ['title' => 'Galeria', 'url' => '/galeria', 'sort_order' => 5],
                        ['title' => 'Transparência', 'url' => '/transparencia', 'sort_order' => 6],
                        ['title' => 'Contato', 'url' => '/contato', 'sort_order' => 7, 'css_class' => 'btn-primary'],
                    ],
                ],
                'sidebar' => [
                    'name' => 'Sidebar Mobile',
                    'slug' => 'sidebar',
                    'description' => 'Menu lateral mobile (drawer)',
                    'items' => [
                        ['title' => 'Início', 'url' => '/', 'sort_order' => 0],
                        ['title' => 'Sobre o ISSM', 'url' => '/sobre', 'sort_order' => 1],
                        ['title' => 'Projetos', 'url' => '/projetos', 'sort_order' => 2],
                        ['title' => 'ODS 2030', 'url' => '/ods', 'sort_order' => 3],
                        ['title' => 'Notícias', 'url' => '/noticias', 'sort_order' => 4],
                        ['title' => 'Galeria', 'url' => '/galeria', 'sort_order' => 5],
                        ['title' => 'Transparência', 'url' => '/transparencia', 'sort_order' => 6],
                        ['title' => 'Fale Conosco', 'url' => '/contato', 'sort_order' => 7, 'css_class' => 'btn-cta'],
                    ],
                ],
                'bottom' => [
                    'name' => 'Barra Inferior',
                    'slug' => 'bottom',
                    'description' => 'Barra de navegação inferior (mobile)',
                    'items' => [
                        ['title' => 'Início', 'url' => '/', 'sort_order' => 0],
                        ['title' => 'Projetos', 'url' => '/projetos', 'sort_order' => 1],
                        ['title' => 'Notícias', 'url' => '/noticias', 'sort_order' => 3],
                    ],
                ],
                'footer' => [
                    'name' => 'Rodapé',
                    'slug' => 'footer',
                    'description' => 'Links rápidos do rodapé',
                    'items' => [
                        ['title' => 'Sobre o ISSM', 'url' => '/sobre', 'sort_order' => 0],
                        ['title' => 'Nossos Projetos', 'url' => '/projetos', 'sort_order' => 1],
                        ['title' => 'ODS 2030', 'url' => '/ods', 'sort_order' => 2],
                        ['title' => 'Notícias', 'url' => '/noticias', 'sort_order' => 3],
                        ['title' => 'Galeria', 'url' => '/galeria', 'sort_order' => 4],
                        ['title' => 'Transparência', 'url' => '/transparencia', 'sort_order' => 5],
                        ['title' => 'Nossa Equipe', 'url' => '/sobre#equipe', 'sort_order' => 6],
                        ['title' => 'Contato', 'url' => '/contato', 'sort_order' => 7],
                    ],
                ],
            ];

            foreach ($menuDefinitions as $location => $def) {
                $existing = DB::table('cms_menus')->where('slug', $def['slug'])->first();
                if (!$existing) {
                    $menuId = DB::table('cms_menus')->insertGetId([
                        'name' => $def['name'],
                        'slug' => $def['slug'],
                        'location' => $location,
                        'description' => $def['description'],
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    foreach ($def['items'] as $item) {
                        $insertData = [
                            'cms_menu_id' => $menuId,
                            'title' => $item['title'],
                            'url' => $item['url'],
                            'sort_order' => $item['sort_order'],
                            'is_active' => true,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                        if (isset($item['css_class'])) {
                            $insertData['css_class'] = $item['css_class'];
                        }
                        DB::table('cms_menu_items')->insert($insertData);
                    }

                    $this->command->info("CMS menu '{$def['name']}' ({$location}) created with " . count($def['items']) . ' items.');
                } else {
                    $this->command->info("CMS menu '{$def['name']}' ({$location}) already exists, skipping.");
                }
            }

            // Create default CMS pages for each public route with real content
            $pages = [
                [
                    'title' => 'Início',
                    'slug' => 'inicio',
                    'content' => '<h2>Bem-vindo ao ISSM</h2><p>Instituto Socioambiental Serra do Mendanha — preservação ambiental e desenvolvimento sustentável alinhado com os 17 ODS da ONU para 2030.</p>',
                    'sections' => [
                        ['title' => 'Hero Principal', 'description' => 'Banner principal com chamada para ação', 'sort_order' => 0, 'blocks' => [
                            ['type' => 'hero', 'title' => 'Instituto Socioambiental<br>Serra do Mendanha', 'subtitle' => 'Preservação ambiental e desenvolvimento sustentável para um futuro melhor', 'content' => 'Trabalhamos incansavelmente para proteger a Serra do Mendanha e promover o desenvolvimento sustentável na região.', 'link_url' => '/sobre', 'link_text' => 'Conheça o ISSM', 'sort_order' => 0],
                        ]],
                        ['title' => 'Sobre o Instituto', 'description' => 'Resumo sobre o ISSM', 'sort_order' => 1, 'blocks' => [
                            ['type' => 'text', 'title' => 'Quem Somos', 'content' => '<p>O Instituto Socioambiental Serra do Mendanha (ISSM) é uma organização dedicada à preservação ambiental e ao desenvolvimento sustentável. Atuamos na região da Serra do Mendanha, no Rio de Janeiro, promovendo ações que integram conservação ambiental, educação e cidadania.</p><p>Nossos projetos abrangem desde a recuperação de áreas degradadas até programas de educação ambiental nas comunidades locais.</p>', 'sort_order' => 0],
                        ]],
                        ['title' => 'ODS em Destaque', 'description' => 'Cards com os principais ODS', 'sort_order' => 2, 'blocks' => [
                            ['type' => 'cards', 'title' => 'Objetivos de Desenvolvimento Sustentável', 'content' => '<p>Conheça os 17 ODS da ONU que guiam nossas ações e projetos.</p>', 'sort_order' => 0],
                        ]],
                        ['title' => 'CTA Engajamento', 'description' => 'Chamada para ação', 'sort_order' => 3, 'blocks' => [
                            ['type' => 'cta', 'title' => 'Faça Parte Desta Mudança', 'subtitle' => 'Junte-se a nós na preservação da Serra do Mendanha', 'content' => 'Seja voluntário, denuncie crimes ambientais ou contribua com nossos projetos.', 'link_url' => '/contato', 'link_text' => 'Fale Conosco', 'sort_order' => 0],
                        ]],
                    ],
                ],
                [
                    'title' => 'Sobre',
                    'slug' => 'sobre',
                    'content' => '<h2>Sobre o ISSM</h2><p>Conheça nossa história, missão e valores.</p>',
                    'sections' => [
                        ['title' => 'Hero Sobre', 'description' => 'Cabeçalho da página sobre', 'sort_order' => 0, 'blocks' => [
                            ['type' => 'hero', 'title' => 'Sobre o ISSM', 'subtitle' => 'Conheça nossa história e propósito', 'content' => 'Há anos dedicados à preservação ambiental e ao desenvolvimento sustentável na Serra do Mendanha.', 'sort_order' => 0],
                        ]],
                        ['title' => 'Nossa História', 'description' => 'História do instituto', 'sort_order' => 1, 'blocks' => [
                            ['type' => 'text', 'title' => 'Nossa Trajetória', 'content' => '<p>O Instituto Socioambiental Serra do Mendanha (ISSM) nasceu da necessidade de proteger um dos mais importantes remanescentes de Mata Atlântica da região metropolitana do Rio de Janeiro.</p><p>Desde nossa fundação, atuamos em diversas frentes: recuperação de nascentes, reflorestamento, educação ambiental, ecoturismo e desenvolvimento comunitário sustentável.</p>', 'sort_order' => 0],
                        ]],
                        ['title' => 'Missão e Valores', 'description' => 'Missão, visão e valores', 'sort_order' => 2, 'blocks' => [
                            ['type' => 'cards', 'title' => 'Missão, Visão e Valores', 'content' => '<p>Nossos pilares fundamentais guiam cada ação do instituto.</p>', 'sort_order' => 0],
                        ]],
                        ['title' => 'CTA Equipe', 'description' => 'Chamada para conhecer equipe', 'sort_order' => 3, 'blocks' => [
                            ['type' => 'cta', 'title' => 'Conheça Nossa Equipe', 'subtitle' => 'Pessoas dedicadas à causa ambiental', 'content' => 'Saiba mais sobre os profissionais que fazem o ISSM acontecer.', 'link_url' => '/sobre#equipe', 'link_text' => 'Nossa Equipe', 'sort_order' => 0],
                        ]],
                    ],
                ],
                [
                    'title' => 'ODS 2030',
                    'slug' => 'ods',
                    'content' => '<h2>ODS 2030</h2><p>Objetivos de Desenvolvimento Sustentável da ONU.</p>',
                    'sections' => [
                        ['title' => 'Hero ODS', 'description' => 'Cabeçalho ODS', 'sort_order' => 0, 'blocks' => [
                            ['type' => 'hero', 'title' => 'ODS 2030', 'subtitle' => '17 Objetivos para Transformar o Mundo', 'content' => 'Os Objetivos de Desenvolvimento Sustentável são um apelo global à ação para acabar com a pobreza, proteger o meio ambiente e garantir paz e prosperidade.', 'sort_order' => 0],
                        ]],
                        ['title' => 'O que são os ODS', 'description' => 'Explicação sobre ODS', 'sort_order' => 1, 'blocks' => [
                            ['type' => 'text', 'title' => 'Entenda os ODS', 'content' => '<p>Os Objetivos de Desenvolvimento Sustentável (ODS) são uma agenda mundial adotada pela ONU em 2015 com 17 objetivos e 169 metas a serem atingidos até 2030.</p><p>No ISSM, alinhamos todos os nossos projetos e ações com esses objetivos, contribuindo ativamente para um futuro mais sustentável.</p>', 'sort_order' => 0],
                        ]],
                        ['title' => '17 Objetivos', 'description' => 'Cards com os 17 ODS', 'sort_order' => 2, 'blocks' => [
                            ['type' => 'cards', 'title' => 'Conheça os 17 ODS', 'content' => '<p>Clique em cada objetivo para saber mais.</p>', 'sort_order' => 0],
                        ]],
                    ],
                ],
                [
                    'title' => 'Galeria',
                    'slug' => 'galeria',
                    'content' => '<h2>Galeria de Fotos</h2><p>Registros das atividades e projetos do ISSM.</p>',
                    'sections' => [
                        ['title' => 'Hero Galeria', 'description' => 'Cabeçalho galeria', 'sort_order' => 0, 'blocks' => [
                            ['type' => 'hero', 'title' => 'Galeria', 'subtitle' => 'Registros das nossas atividades', 'content' => 'Veja imagens dos nossos projetos, eventos e ações de preservação ambiental.', 'sort_order' => 0],
                        ]],
                        ['title' => 'Galeria de Imagens', 'description' => 'Grid de imagens', 'sort_order' => 1, 'blocks' => [
                            ['type' => 'gallery', 'title' => 'Nossas Fotos', 'content' => '<p>Registros que contam nossa história.</p>', 'sort_order' => 0],
                        ]],
                    ],
                ],
                [
                    'title' => 'Notícias',
                    'slug' => 'noticias',
                    'content' => '<h2>Notícias</h2><p>Fique por dentro das últimas notícias do ISSM.</p>',
                    'sections' => [
                        ['title' => 'Hero Notícias', 'description' => 'Cabeçalho notícias', 'sort_order' => 0, 'blocks' => [
                            ['type' => 'hero', 'title' => 'Notícias', 'subtitle' => 'Fique por dentro do que acontece no ISSM', 'content' => 'Acompanhe as últimas novidades sobre nossos projetos, eventos e ações ambientais.', 'sort_order' => 0],
                        ]],
                        ['title' => 'Lista de Notícias', 'description' => 'Notícias dinâmicas do banco de dados', 'sort_order' => 1, 'blocks' => [
                            ['type' => 'text', 'title' => 'Últimas Notícias', 'content' => '<p>As notícias abaixo são carregadas automaticamente do nosso banco de dados. Utilize o menu <strong>Admin > Notícias</strong> para gerenciar.</p>', 'sort_order' => 0],
                        ]],
                    ],
                ],
                [
                    'title' => 'Projetos',
                    'slug' => 'projetos',
                    'content' => '<h2>Projetos</h2><p>Conheça nossos projetos socioambientais.</p>',
                    'sections' => [
                        ['title' => 'Hero Projetos', 'description' => 'Cabeçalho projetos', 'sort_order' => 0, 'blocks' => [
                            ['type' => 'hero', 'title' => 'Projetos', 'subtitle' => 'Ações que transformam realidades', 'content' => 'Conheça os projetos que desenvolvemos para preservar a Serra do Mendanha e promover o desenvolvimento sustentável.', 'sort_order' => 0],
                        ]],
                        ['title' => 'Lista de Projetos', 'description' => 'Projetos dinâmicos do banco de dados', 'sort_order' => 1, 'blocks' => [
                            ['type' => 'text', 'title' => 'Nossos Projetos', 'content' => '<p>Os projetos abaixo são carregados automaticamente do nosso banco de dados. Utilize o menu <strong>Admin > Projetos</strong> para gerenciar.</p>', 'sort_order' => 0],
                        ]],
                    ],
                ],
                [
                    'title' => 'Contato',
                    'slug' => 'contato',
                    'content' => '<h2>Contato</h2><p>Entre em contato conosco.</p>',
                    'sections' => [
                        ['title' => 'Hero Contato', 'description' => 'Cabeçalho contato', 'sort_order' => 0, 'blocks' => [
                            ['type' => 'hero', 'title' => 'Contato', 'subtitle' => 'Estamos prontos para ouvir você', 'content' => 'Tem dúvidas, sugestões ou quer colaborar? Entre em contato conosco.', 'sort_order' => 0],
                        ]],
                        ['title' => 'Formulário de Contato', 'description' => 'Formulário de contato', 'sort_order' => 1, 'blocks' => [
                            ['type' => 'contact', 'title' => 'Envie sua Mensagem', 'content' => '<p>Preencha o formulário abaixo e entraremos em contato em breve.</p>', 'sort_order' => 0],
                        ]],
                        ['title' => 'FAQ', 'description' => 'Perguntas frequentes', 'sort_order' => 2, 'blocks' => [
                            ['type' => 'faq', 'title' => 'Perguntas Frequentes', 'content' => '<p>Tire suas dúvidas sobre o ISSM.</p>', 'sort_order' => 0],
                        ]],
                    ],
                ],
                [
                    'title' => 'Transparência',
                    'slug' => 'transparencia',
                    'content' => '<h2>Portal da Transparência</h2><p>Prestação de contas e documentos oficiais do ISSM.</p>',
                    'sections' => [
                        ['title' => 'Hero Transparência', 'description' => 'Cabeçalho transparência', 'sort_order' => 0, 'blocks' => [
                            ['type' => 'hero', 'title' => 'Transparência', 'subtitle' => 'Compromisso com a clareza e responsabilidade', 'content' => 'Acreditamos que a transparência é fundamental para construir confiança com nossos parceiros e a comunidade.', 'sort_order' => 0],
                        ]],
                        ['title' => 'Documentos', 'description' => 'Documentos oficiais', 'sort_order' => 1, 'blocks' => [
                            ['type' => 'text', 'title' => 'Documentos e Prestação de Contas', 'content' => '<p>Disponibilizamos aqui nossos relatórios, demonstrações financeiras e outros documentos oficiais para consulta pública, reforçando nosso compromisso com a transparência e a responsabilidade fiscal.</p>', 'sort_order' => 0],
                        ]],
                    ],
                ],
            ];

            foreach ($pages as $pageDef) {
                $existing = DB::table('cms_pages')->where('slug', $pageDef['slug'])->first();
                if (!$existing) {
                    $pageId = DB::table('cms_pages')->insertGetId([
                        'title' => $pageDef['title'],
                        'slug' => $pageDef['slug'],
                        'content' => $pageDef['content'],
                        'status' => 'published',
                        'is_active' => true,
                        'template' => 'default',
                        'layout' => 'default',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $this->command->info("CMS page '{$pageDef['title']}' created.");
                } else {
                    DB::table('cms_pages')->where('slug', $pageDef['slug'])->update([
                        'content' => $pageDef['content'],
                        'updated_at' => now(),
                    ]);
                    $pageId = $existing->id;
                    $this->command->info("CMS page '{$pageDef['title']}' updated with real content.");
                }

                // Create sections and blocks for this page
                foreach ($pageDef['sections'] as $secDef) {
                    $existingSection = DB::table('cms_sections')
                        ->where('cms_page_id', $pageId)
                        ->where('title', $secDef['title'])
                        ->first();

                    if (!$existingSection) {
                        $sectionId = DB::table('cms_sections')->insertGetId([
                            'cms_page_id' => $pageId,
                            'title' => $secDef['title'],
                            'description' => $secDef['description'],
                            'is_active' => true,
                            'sort_order' => $secDef['sort_order'],
                            'status' => 'published',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        foreach ($secDef['blocks'] as $blockDef) {
                            $existingBlock = DB::table('cms_blocks')
                                ->where('cms_section_id', $sectionId)
                                ->where('type', $blockDef['type'])
                                ->where('title', $blockDef['title'])
                                ->first();

                            if (!$existingBlock) {
                                DB::table('cms_blocks')->insert([
                                    'cms_section_id' => $sectionId,
                                    'cms_page_id' => $pageId,
                                    'type' => $blockDef['type'],
                                    'title' => $blockDef['title'],
                                    'subtitle' => $blockDef['subtitle'] ?? null,
                                    'content' => $blockDef['content'] ?? null,
                                    'link_url' => $blockDef['link_url'] ?? null,
                                    'link_text' => $blockDef['link_text'] ?? null,
                                    'is_active' => true,
                                    'sort_order' => $blockDef['sort_order'],
                                    'status' => 'published',
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ]);
                            }
                        }

                        $this->command->info("  Section '{$secDef['title']}' created with " . count($secDef['blocks']) . ' blocks.');
                    } else {
                        $this->command->info("  Section '{$secDef['title']}' already exists, skipping.");
                    }
                }
            }

            // Create default blocks config
            $blocksConfig = [
                'cms_blocks_enabled' => '1',
                'cms_blocks_per_page' => '20',
                'cms_editor' => 'tinymce',
            ];

            foreach ($blocksConfig as $key => $value) {
                DB::table('settings')->updateOrInsert(
                    ['key' => $key],
                    ['value' => $value, 'updated_at' => now(), 'created_at' => now()]
                );
            }

            $this->command->info('Default blocks configuration created.');
        } catch (\Exception $e) {
            $this->command->error('Error creating CMS defaults: ' . $e->getMessage());
        }
    }
}

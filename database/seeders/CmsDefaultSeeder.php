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

            // Create/update CMS pages with content matching the frontend views
            $pages = [
                [
                    'title' => 'Início',
                    'slug' => 'inicio',
                    'content' => '<h2>Instituto Socioambiental Serra do Mendanha</h2><p>Comprometidos com a preservação ambiental e o desenvolvimento sustentável alinhado com os 17 ODS da ONU para 2030.</p>',
                    'sections' => [
                        ['title' => 'Hero Principal', 'description' => 'Banner principal com chamada para ação', 'sort_order' => 0, 'blocks' => [
                            ['type' => 'hero', 'title' => 'Instituto Socioambiental<br>Serra do Mendanha', 'subtitle' => 'Comprometidos com a preservação ambiental e o desenvolvimento sustentável', 'content' => 'Trabalhamos para proteger a Serra do Mendanha e promover o desenvolvimento sustentável na região metropolitana do Rio de Janeiro.', 'link_url' => '/sobre', 'link_text' => 'Conheça o ISSM', 'sort_order' => 0],
                        ]],
                        ['title' => 'Sobre o Instituto', 'description' => 'Quem somos e o que fazemos', 'sort_order' => 1, 'blocks' => [
                            ['type' => 'text', 'title' => 'Quem Somos', 'content' => '<p>O Instituto Socioambiental Serra do Mendanha (ISSM) é uma organização dedicada à preservação ambiental e ao desenvolvimento sustentável. Atuamos na região da Serra do Mendanha, no Rio de Janeiro, promovendo ações que integram conservação ambiental, educação e cidadania.</p><p>Nossos projetos abrangem desde a recuperação de áreas degradadas até programas de educação ambiental nas comunidades locais, sempre alinhados aos 17 ODS da ONU.</p>', 'sort_order' => 0],
                        ]],
                        ['title' => 'ODS em Destaque', 'description' => 'Objetivos de Desenvolvimento Sustentável', 'sort_order' => 2, 'blocks' => [
                            ['type' => 'cards', 'title' => 'Objetivos de Desenvolvimento Sustentável', 'content' => '<p>Conheça os 17 ODS da ONU que guiam nossas ações e projetos. Cada iniciativa do ISSM é pensada para contribuir com um ou mais objetivos da Agenda 2030.</p>', 'sort_order' => 0],
                        ]],
                        ['title' => 'CTA Engajamento', 'description' => 'Chamada para se engajar', 'sort_order' => 3, 'blocks' => [
                            ['type' => 'cta', 'title' => 'Faça Parte Desta Mudança', 'subtitle' => 'Junte-se a nós na preservação da Serra do Mendanha', 'content' => 'Seja voluntário, denuncie crimes ambientais ou contribua com nossos projetos. Cada ação faz a diferença.', 'link_url' => '/contato', 'link_text' => 'Fale Conosco', 'sort_order' => 0],
                        ]],
                    ],
                ],
                [
                    'title' => 'Sobre',
                    'slug' => 'sobre',
                    'content' => '<h2>Instituto Socioambiental Serra do Mendanha</h2><p>Conheça nossa história, missão e o compromisso com a preservação da Serra do Mendanha.</p>',
                    'sections' => [
                        ['title' => 'Hero Sobre', 'description' => 'Cabeçalho da página sobre', 'sort_order' => 0, 'blocks' => [
                            ['type' => 'hero', 'title' => 'Instituto Socioambiental<br>Serra do Mendanha', 'subtitle' => 'Conheça nossa história, missão e o compromisso com a preservação da Serra do Mendanha', 'content' => 'Há anos dedicados à preservação ambiental e ao desenvolvimento sustentável na região.', 'sort_order' => 0],
                        ]],
                        ['title' => 'Nossa Identidade', 'description' => 'Nossa Identidade - Preservação e Sustentabilidade', 'sort_order' => 1, 'blocks' => [
                            ['type' => 'text', 'title' => 'Nossa Identidade', 'content' => '<p>O Instituto Socioambiental Serra do Mendanha (ISSM) nasceu da necessidade de proteger um dos mais importantes remanescentes de Mata Atlântica da região metropolitana do Rio de Janeiro.</p><p>Desde nossa fundação, atuamos em diversas frentes: recuperação de nascentes, reflorestamento, educação ambiental, ecoturismo e desenvolvimento comunitário sustentável. Nossa equipe é formada por profissionais dedicados à causa ambiental.</p>', 'sort_order' => 0],
                        ]],
                        ['title' => 'Missão Visão Valores', 'description' => 'Missão, Visão e Valores do instituto', 'sort_order' => 2, 'blocks' => [
                            ['type' => 'cards', 'title' => 'Preservação e Sustentabilidade', 'content' => '<p><strong>Missão:</strong> Preservar a Serra do Mendanha e promover o desenvolvimento sustentável através de ações integradas de conservação ambiental, educação e cidadania.</p><p><strong>Visão:</strong> Ser referência em gestão ambiental e desenvolvimento comunitário sustentável na região metropolitana do Rio de Janeiro.</p><p><strong>Valores:</strong> Responsabilidade ambiental, transparência, colaboração, inovação e respeito às comunidades locais.</p>', 'sort_order' => 0],
                        ]],
                        ['title' => 'CTA Equipe', 'description' => 'Chamada para conhecer equipe', 'sort_order' => 3, 'blocks' => [
                            ['type' => 'cta', 'title' => 'Capital Humano', 'subtitle' => 'Pessoas dedicadas que fazem o ISSM acontecer', 'content' => 'Conheça os profissionais comprometidos com a preservação ambiental e o desenvolvimento sustentável.', 'link_url' => '/sobre#equipe', 'link_text' => 'Nossa Equipe', 'sort_order' => 0],
                        ]],
                    ],
                ],
                [
                    'title' => 'ODS 2030',
                    'slug' => 'ods',
                    'content' => '<h2>ODS 2030 - Compromisso Sustentável</h2><p>Nossas ações estão alinhadas à Agenda 2030 da ONU para construir um futuro mais justo e sustentável.</p>',
                    'sections' => [
                        ['title' => 'Hero ODS', 'description' => 'Cabeçalho ODS', 'sort_order' => 0, 'blocks' => [
                            ['type' => 'hero', 'title' => 'Objetivos de<br>Desenvolvimento Sustentável', 'subtitle' => '17 Objetivos para Transformar o Mundo', 'content' => 'Nossas ações estão alinhadas à Agenda 2030 da ONU para construir um futuro mais justo e sustentável.', 'sort_order' => 0],
                        ]],
                        ['title' => 'O que são os ODS', 'description' => 'Entenda os ODS', 'sort_order' => 1, 'blocks' => [
                            ['type' => 'text', 'title' => 'A Agenda 2030', 'content' => '<p>Adotada por todos os Estados-Membros das Nações Unidas em 2015, a Agenda 2030 fornece um plano compartilhado para a paz e a prosperidade das pessoas e do planeta, agora e no futuro.</p><p>No centro da Agenda 2030 estão os 17 Objetivos de Desenvolvimento Sustentável (ODS), que são um apelo global à ação para acabar com a pobreza, proteger o meio ambiente e garantir que todas as pessoas desfrutem de paz e prosperidade.</p><p>No ISSM, cada projeto é pensado para impactar positivamente um ou mais destes objetivos.</p>', 'sort_order' => 0],
                        ]],
                    ],
                ],
                [
                    'title' => 'Galeria',
                    'slug' => 'galeria',
                    'content' => '<h2>Galeria</h2><p>Registros das atividades e projetos do ISSM.</p>',
                    'sections' => [
                        ['title' => 'Hero Galeria', 'description' => 'Cabeçalho galeria', 'sort_order' => 0, 'blocks' => [
                            ['type' => 'hero', 'title' => 'Galeria', 'subtitle' => 'Registros das nossas atividades', 'content' => 'Veja imagens dos nossos projetos, eventos e ações de preservação ambiental na Serra do Mendanha.', 'sort_order' => 0],
                        ]],
                    ],
                ],
                [
                    'title' => 'Notícias',
                    'slug' => 'noticias',
                    'content' => '<h2>Notícias</h2><p>Fique por dentro das últimas notícias do ISSM.</p>',
                    'sections' => [
                        ['title' => 'Hero Notícias', 'description' => 'Cabeçalho notícias', 'sort_order' => 0, 'blocks' => [
                            ['type' => 'hero', 'title' => 'Notícias', 'subtitle' => 'Fique por dentro do que acontece no ISSM', 'content' => 'Acompanhe as últimas novidades sobre nossos projetos, eventos e ações ambientais na Serra do Mendanha.', 'sort_order' => 0],
                        ]],
                        ['title' => 'Lista de Notícias', 'description' => 'Notícias dinâmicas do banco de dados', 'sort_order' => 1, 'blocks' => [
                            ['type' => 'text', 'title' => 'Últimas Notícias', 'content' => '<p>As notícias listadas abaixo são gerenciadas através do menu <strong>Admin > Notícias</strong>.</p>', 'sort_order' => 0],
                        ]],
                    ],
                ],
                [
                    'title' => 'Projetos',
                    'slug' => 'projetos',
                    'content' => '<h2>Projetos</h2><p>Conheça nossos projetos socioambientais.</p>',
                    'sections' => [
                        ['title' => 'Hero Projetos', 'description' => 'Cabeçalho projetos', 'sort_order' => 0, 'blocks' => [
                            ['type' => 'hero', 'title' => 'Projetos', 'subtitle' => 'Ações que transformam realidades', 'content' => 'Conheça os projetos que desenvolvemos para preservar a Serra do Mendanha e promover o desenvolvimento sustentável na região.', 'sort_order' => 0],
                        ]],
                        ['title' => 'Lista de Projetos', 'description' => 'Projetos dinâmicos do banco de dados', 'sort_order' => 1, 'blocks' => [
                            ['type' => 'text', 'title' => 'Nossos Projetos', 'content' => '<p>Os projetos listados abaixo são gerenciados através do menu <strong>Admin > Projetos</strong>.</p>', 'sort_order' => 0],
                        ]],
                    ],
                ],
                [
                    'title' => 'Contato',
                    'slug' => 'contato',
                    'content' => '<h2>Fale Conosco</h2><p>Estamos prontos para ouvir você. Entre em contato para parcerias, dúvidas ou informações sobre o ISSM.</p>',
                    'sections' => [
                        ['title' => 'Hero Contato', 'description' => 'Cabeçalho contato', 'sort_order' => 0, 'blocks' => [
                            ['type' => 'hero', 'title' => 'Fale Conosco', 'subtitle' => 'Estamos prontos para ouvir você', 'content' => 'Entre em contato para parcerias, dúvidas ou informações sobre o ISSM.', 'sort_order' => 0],
                        ]],
                        ['title' => 'Informações de Contato', 'description' => 'Endereço, e-mail e telefone', 'sort_order' => 1, 'blocks' => [
                            ['type' => 'contact', 'title' => 'Informações de Contato', 'content' => '<p><strong>Visite-nos:</strong> Serra do Mendanha, Rio de Janeiro - RJ</p><p><strong>E-mail:</strong> contato@issm.org.br</p><p><strong>Telefone:</strong> (21) 99999-9999</p><p><strong>Horário:</strong> Seg a Sex: 08:00 às 17:00 | Sáb: 08:00 às 12:00</p>', 'sort_order' => 0],
                        ]],
                        ['title' => 'FAQ', 'description' => 'Perguntas frequentes', 'sort_order' => 2, 'blocks' => [
                            ['type' => 'faq', 'title' => 'Perguntas Frequentes', 'content' => '<p>Tire suas dúvidas sobre o ISSM, nossos projetos e como participar.</p>', 'sort_order' => 0],
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
                        ['title' => 'Documentos', 'description' => 'Documentos oficiais e prestação de contas', 'sort_order' => 1, 'blocks' => [
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
                        'title' => $pageDef['title'],
                        'updated_at' => now(),
                    ]);
                    $pageId = $existing->id;
                    $this->command->info("CMS page '{$pageDef['title']}' updated.");
                }

                // Create/update sections and blocks for this page
                foreach ($pageDef['sections'] as $secDef) {
                    $existingSection = DB::table('cms_sections')
                        ->where('cms_page_id', $pageId)
                        ->where('title', $secDef['title'])
                        ->first();

                    if ($existingSection) {
                        DB::table('cms_sections')->where('id', $existingSection->id)->update([
                            'description' => $secDef['description'],
                            'sort_order' => $secDef['sort_order'],
                            'updated_at' => now(),
                        ]);
                        $sectionId = $existingSection->id;
                    } else {
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
                    }

                    foreach ($secDef['blocks'] as $blockDef) {
                        $existingBlock = DB::table('cms_blocks')
                            ->where('cms_section_id', $sectionId)
                            ->where('type', $blockDef['type'])
                            ->where('title', $blockDef['title'])
                            ->first();

                        if ($existingBlock) {
                            DB::table('cms_blocks')->where('id', $existingBlock->id)->update([
                                'subtitle' => $blockDef['subtitle'] ?? null,
                                'content' => $blockDef['content'] ?? null,
                                'link_url' => $blockDef['link_url'] ?? null,
                                'link_text' => $blockDef['link_text'] ?? null,
                                'sort_order' => $blockDef['sort_order'],
                                'updated_at' => now(),
                                'is_active' => true,
                                'status' => 'published',
                            ]);
                        } else {
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

                    $this->command->info("  Section '{$secDef['title']}' synced with " . count($secDef['blocks']) . ' blocks.');
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

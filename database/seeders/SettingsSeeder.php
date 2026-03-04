<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'site_name', 'value' => 'ISSM - Instituto Socioambiental Serra do Mendanha', 'type' => 'text', 'group' => 'general', 'label' => 'Nome do Site'],
            ['key' => 'site_description', 'value' => 'Instituto dedicado a preservacao ambiental e desenvolvimento sustentavel na regiao da Serra do Mendanha.', 'type' => 'textarea', 'group' => 'general', 'label' => 'Descricao do Site'],
            ['key' => 'site_logo', 'value' => '', 'type' => 'image', 'group' => 'general', 'label' => 'Logo do Site'],
            ['key' => 'site_favicon', 'value' => '', 'type' => 'image', 'group' => 'general', 'label' => 'Favicon'],
            ['key' => 'about_text', 'value' => 'O Instituto Socioambiental Serra do Mendanha (ISSM) e uma organizacao sem fins lucrativos dedicada a preservacao ambiental, educacao ecologica e desenvolvimento sustentavel.', 'type' => 'textarea', 'group' => 'general', 'label' => 'Texto Sobre Nos'],
            ['key' => 'mission', 'value' => 'Promover a conservacao do meio ambiente e o desenvolvimento sustentavel da Serra do Mendanha.', 'type' => 'textarea', 'group' => 'general', 'label' => 'Missao'],
            ['key' => 'vision', 'value' => 'Ser referencia nacional em gestao socioambiental, contribuindo para um mundo mais sustentavel e equitativo alinhado com os ODS 2030.', 'type' => 'textarea', 'group' => 'general', 'label' => 'Visao'],
            ['key' => 'values', 'value' => 'Sustentabilidade, Transparencia, Inclusao Social, Inovacao, Parceria e Responsabilidade Ambiental.', 'type' => 'textarea', 'group' => 'general', 'label' => 'Valores'],
            ['key' => 'contact_email', 'value' => 'contato@issm.org.br', 'type' => 'text', 'group' => 'contact', 'label' => 'E-mail de Contato'],
            ['key' => 'contact_phone', 'value' => '(21) 9999-9999', 'type' => 'text', 'group' => 'contact', 'label' => 'Telefone'],
            ['key' => 'contact_address', 'value' => 'Serra do Mendanha, Rio de Janeiro - RJ', 'type' => 'text', 'group' => 'contact', 'label' => 'Endereco'],
            ['key' => 'contact_cep', 'value' => '', 'type' => 'text', 'group' => 'contact', 'label' => 'CEP'],
            ['key' => 'contact_map_embed', 'value' => '', 'type' => 'textarea', 'group' => 'contact', 'label' => 'Embed do Mapa'],
            ['key' => 'social_facebook', 'value' => 'https://facebook.com/issm', 'type' => 'text', 'group' => 'social', 'label' => 'Facebook'],
            ['key' => 'social_instagram', 'value' => 'https://instagram.com/issm', 'type' => 'text', 'group' => 'social', 'label' => 'Instagram'],
            ['key' => 'social_twitter', 'value' => '', 'type' => 'text', 'group' => 'social', 'label' => 'Twitter/X'],
            ['key' => 'social_youtube', 'value' => '', 'type' => 'text', 'group' => 'social', 'label' => 'YouTube'],
            ['key' => 'social_linkedin', 'value' => '', 'type' => 'text', 'group' => 'social', 'label' => 'LinkedIn'],
            ['key' => 'social_whatsapp', 'value' => '', 'type' => 'text', 'group' => 'social', 'label' => 'WhatsApp'],
            // ── SEO Básico ──
            ['key' => 'meta_title', 'value' => 'ISSM - Instituto Socioambiental Serra do Mendanha', 'type' => 'text', 'group' => 'seo', 'label' => 'Meta Title'],
            ['key' => 'meta_description', 'value' => 'O Instituto Socioambiental Serra do Mendanha (ISSM) atua na preservacao ambiental, educacao ecologica e desenvolvimento sustentavel alinhado com os ODS 2030, na regiao da Serra do Mendanha, Rio de Janeiro.', 'type' => 'textarea', 'group' => 'seo', 'label' => 'Meta Description'],
            ['key' => 'meta_keywords', 'value' => 'ISSM, Instituto Socioambiental, Serra do Mendanha, meio ambiente, sustentabilidade, ODS 2030, preservacao ambiental, educacao ecologica, Rio de Janeiro, desenvolvimento sustentavel, conservacao, natureza', 'type' => 'text', 'group' => 'seo', 'label' => 'Meta Keywords'],
            ['key' => 'meta_author', 'value' => 'ISSM - Instituto Socioambiental Serra do Mendanha', 'type' => 'text', 'group' => 'seo', 'label' => 'Meta Author'],
            ['key' => 'canonical_url', 'value' => 'https://issm.org.br', 'type' => 'text', 'group' => 'seo', 'label' => 'URL Canônica'],
            ['key' => 'robots_meta', 'value' => 'index, follow', 'type' => 'text', 'group' => 'seo', 'label' => 'Robots (Meta Tag)'],

            // ── Open Graph (Facebook / WhatsApp / LinkedIn) ──
            ['key' => 'og_title', 'value' => 'ISSM - Instituto Socioambiental Serra do Mendanha', 'type' => 'text', 'group' => 'seo', 'label' => 'OG Title (Redes Sociais)'],
            ['key' => 'og_description', 'value' => 'Preservacao ambiental e desenvolvimento sustentavel alinhado com os ODS 2030 na Serra do Mendanha, Rio de Janeiro.', 'type' => 'textarea', 'group' => 'seo', 'label' => 'OG Description'],
            ['key' => 'og_image', 'value' => '', 'type' => 'image', 'group' => 'seo', 'label' => 'OG Image (1200x630px)'],
            ['key' => 'og_type', 'value' => 'website', 'type' => 'text', 'group' => 'seo', 'label' => 'OG Type'],
            ['key' => 'og_locale', 'value' => 'pt_BR', 'type' => 'text', 'group' => 'seo', 'label' => 'OG Locale'],
            ['key' => 'og_site_name', 'value' => 'ISSM - Instituto Socioambiental Serra do Mendanha', 'type' => 'text', 'group' => 'seo', 'label' => 'OG Site Name'],

            // ── Twitter Card ──
            ['key' => 'twitter_card', 'value' => 'summary_large_image', 'type' => 'text', 'group' => 'seo', 'label' => 'Twitter Card Type'],
            ['key' => 'twitter_title', 'value' => 'ISSM - Instituto Socioambiental Serra do Mendanha', 'type' => 'text', 'group' => 'seo', 'label' => 'Twitter Title'],
            ['key' => 'twitter_description', 'value' => 'Preservacao ambiental e desenvolvimento sustentavel alinhado com os ODS 2030 na Serra do Mendanha, RJ.', 'type' => 'textarea', 'group' => 'seo', 'label' => 'Twitter Description'],
            ['key' => 'twitter_image', 'value' => '', 'type' => 'image', 'group' => 'seo', 'label' => 'Twitter Image (1200x600px)'],
            ['key' => 'twitter_handle', 'value' => '', 'type' => 'text', 'group' => 'seo', 'label' => 'Twitter @handle'],

            // ── Verificação e Analytics ──
            ['key' => 'google_analytics', 'value' => '', 'type' => 'text', 'group' => 'seo', 'label' => 'Google Analytics ID'],
            ['key' => 'google_tag_manager', 'value' => '', 'type' => 'text', 'group' => 'seo', 'label' => 'Google Tag Manager ID'],
            ['key' => 'google_site_verification', 'value' => '', 'type' => 'text', 'group' => 'seo', 'label' => 'Google Search Console (Verificação)'],
            ['key' => 'bing_site_verification', 'value' => '', 'type' => 'text', 'group' => 'seo', 'label' => 'Bing Webmaster (Verificação)'],
            ['key' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean', 'group' => 'maintenance', 'label' => 'Modo Manutencao'],
            ['key' => 'maintenance_title', 'value' => 'Site em Manutencao', 'type' => 'text', 'group' => 'maintenance', 'label' => 'Titulo da Pagina de Manutencao'],
            ['key' => 'maintenance_message', 'value' => 'Estamos realizando melhorias no nosso site. Em breve estaremos de volta!', 'type' => 'textarea', 'group' => 'maintenance', 'label' => 'Mensagem de Manutencao'],
            ['key' => 'maintenance_email', 'value' => 'contato@issm.org.br', 'type' => 'text', 'group' => 'maintenance', 'label' => 'E-mail (Manutencao)'],
            ['key' => 'maintenance_logo', 'value' => '', 'type' => 'image', 'group' => 'maintenance', 'label' => 'Logo da Manutencao'],
            ['key' => 'maintenance_bg_image', 'value' => '', 'type' => 'image', 'group' => 'maintenance', 'label' => 'Imagem de Fundo'],
            ['key' => 'maintenance_bg_color', 'value' => '#14532d', 'type' => 'text', 'group' => 'maintenance', 'label' => 'Cor de Fundo (hex)'],
            ['key' => 'maintenance_animation', 'value' => 'gear', 'type' => 'text', 'group' => 'maintenance', 'label' => 'Animacao (gear/pulse/wave/none)'],
            ['key' => 'maintenance_show_countdown', 'value' => '0', 'type' => 'boolean', 'group' => 'maintenance', 'label' => 'Exibir Contagem Regressiva'],
            ['key' => 'maintenance_return_date', 'value' => '', 'type' => 'text', 'group' => 'maintenance', 'label' => 'Data/Hora de Retorno (YYYY-MM-DDTHH:MM)'],
            ['key' => 'maintenance_show_social', 'value' => '1', 'type' => 'boolean', 'group' => 'maintenance', 'label' => 'Exibir Redes Sociais'],
            ['key' => 'maintenance_progress', 'value' => '65', 'type' => 'text', 'group' => 'maintenance', 'label' => 'Progresso (0-100%)'],
            ['key' => 'mail_host', 'value' => '', 'type' => 'text', 'group' => 'email', 'label' => 'Servidor SMTP (Host)'],
            ['key' => 'mail_port', 'value' => '465', 'type' => 'text', 'group' => 'email', 'label' => 'Porta SMTP'],
            ['key' => 'mail_username', 'value' => '', 'type' => 'text', 'group' => 'email', 'label' => 'Usuário SMTP (E-mail)'],
            ['key' => 'mail_password', 'value' => '', 'type' => 'password', 'group' => 'email', 'label' => 'Senha SMTP'],
            ['key' => 'mail_encryption', 'value' => 'ssl', 'type' => 'text', 'group' => 'email', 'label' => 'Criptografia (ssl/tls/null)'],
            ['key' => 'mail_from_address', 'value' => '', 'type' => 'text', 'group' => 'email', 'label' => 'E-mail Remetente (From)'],
            ['key' => 'mail_from_name', 'value' => 'ISSM - Instituto Socioambiental Serra do Mendanha', 'type' => 'text', 'group' => 'email', 'label' => 'Nome Remetente'],
            ['key' => 'hero_bg_image', 'value' => '', 'type' => 'image', 'group' => 'home', 'label' => 'Imagem de Fundo do Hero'],
            ['key' => 'hero_overlay_opacity', 'value' => '70', 'type' => 'text', 'group' => 'home', 'label' => 'Opacidade do Degrade do Hero (0-100)'],
            ['key' => 'show_banners', 'value' => '1', 'type' => 'boolean', 'group' => 'home', 'label' => 'Exibir Banners'],
            ['key' => 'show_about', 'value' => '1', 'type' => 'boolean', 'group' => 'home', 'label' => 'Exibir Secao Sobre'],
            ['key' => 'show_ods', 'value' => '1', 'type' => 'boolean', 'group' => 'home', 'label' => 'Exibir ODS 2030'],
            ['key' => 'show_projects', 'value' => '1', 'type' => 'boolean', 'group' => 'home', 'label' => 'Exibir Projetos'],
            ['key' => 'show_news', 'value' => '1', 'type' => 'boolean', 'group' => 'home', 'label' => 'Exibir Noticias'],
            ['key' => 'show_team', 'value' => '1', 'type' => 'boolean', 'group' => 'home', 'label' => 'Exibir Equipe'],
            ['key' => 'show_partners', 'value' => '1', 'type' => 'boolean', 'group' => 'home', 'label' => 'Exibir Parceiros'],
            ['key' => 'show_gallery', 'value' => '1', 'type' => 'boolean', 'group' => 'home', 'label' => 'Exibir Galeria'],
            ['key' => 'show_contact', 'value' => '1', 'type' => 'boolean', 'group' => 'home', 'label' => 'Exibir Formulario de Contato'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}

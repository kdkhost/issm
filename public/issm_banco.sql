-- ============================================================
-- ISSM - Instituto Socioambiental Serra do Mendanha
-- Script SQL completo (equivalente a `php artisan migrate --seed`)
-- Gerado em: 2026-03-02
-- Banco: issmorg_renata
-- ============================================================
-- INSTRUCOES: Importe este arquivo no phpMyAdmin
--   1. Acesse o phpMyAdmin e selecione o banco `issmorg_renata`
--   2. Clique na aba "Importar"
--   3. Selecione este arquivo e clique em "Executar"
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- ============================================================
-- Tabela: migrations (controle do Laravel)
-- ============================================================
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Tabela: users
-- ============================================================
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Tabela: password_reset_tokens
-- ============================================================
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Tabela: failed_jobs
-- ============================================================
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Tabela: personal_access_tokens
-- ============================================================
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Tabela: banners
-- ============================================================
CREATE TABLE IF NOT EXISTS `banners` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `subtitle` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `button_text` varchar(255) DEFAULT NULL,
  `button_url` varchar(255) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Tabela: maintenance_ips
-- ============================================================
CREATE TABLE IF NOT EXISTS `maintenance_ips` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Tabela: settings
-- ============================================================
CREATE TABLE IF NOT EXISTS `settings` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'text',
  `group` varchar(255) NOT NULL DEFAULT 'general',
  `label` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Tabela: gallery
-- ============================================================
CREATE TABLE IF NOT EXISTS `gallery` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `album` varchar(255) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Tabela: news
-- ============================================================
CREATE TABLE IF NOT EXISTS `news` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` longtext NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `featured` tinyint(1) NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `published_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `news_slug_unique` (`slug`),
  KEY `news_user_id_foreign` (`user_id`),
  CONSTRAINT `news_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Tabela: partners
-- ============================================================
CREATE TABLE IF NOT EXISTS `partners` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'partner',
  `order` int(11) NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Tabela: projects
-- ============================================================
CREATE TABLE IF NOT EXISTS `projects` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` longtext NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `ods_goals` json DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `featured` tinyint(1) NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `projects_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Tabela: team_members
-- ============================================================
CREATE TABLE IF NOT EXISTS `team_members` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `bio` text DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Tabela: contacts
-- ============================================================
CREATE TABLE IF NOT EXISTS `contacts` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'new',
  `reply` text DEFAULT NULL,
  `replied_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Tabela: ods
-- ============================================================
CREATE TABLE IF NOT EXISTS `ods` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `number` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `color` varchar(20) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ods_number_unique` (`number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Tabela: pages
-- ============================================================
CREATE TABLE IF NOT EXISTS `pages` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `show_in_menu` tinyint(1) NOT NULL DEFAULT 0,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pages_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- DADOS INICIAIS: Registro de migrations executadas
-- ============================================================
INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2014_10_12_000000_create_users_table', 1),
('2014_10_12_100000_create_password_reset_tokens_table', 1),
('2019_08_19_000000_create_failed_jobs_table', 1),
('2019_12_14_000001_create_personal_access_tokens_table', 1),
('2026_03_02_130847_create_banners_table', 1),
('2026_03_02_130847_create_maintenance_ips_table', 1),
('2026_03_02_130847_create_settings_table', 1),
('2026_03_02_130848_create_gallery_table', 1),
('2026_03_02_130848_create_news_table', 1),
('2026_03_02_130848_create_partners_table', 1),
('2026_03_02_130848_create_projects_table', 1),
('2026_03_02_130848_create_team_members_table', 1),
('2026_03_02_130849_create_contacts_table', 1),
('2026_03_02_130849_create_ods_table', 1),
('2026_03_02_130849_create_pages_table', 1),
('2026_03_02_131602_add_is_admin_to_users_table', 1);

-- ============================================================
-- DADOS INICIAIS: Usuário Administrador
-- Senha: Admin@ISSM2024!
-- ============================================================
INSERT INTO `users` (`name`, `email`, `email_verified_at`, `password`, `remember_token`, `is_admin`, `created_at`, `updated_at`) VALUES
('Administrador ISSM', 'admin@issm.org.br', NOW(), '$2y$12$aaQf6K5BwgGyThjarn7cLeJD.tFIoqSVG5QZy5AwKEazqkm01bV2K', NULL, 1, NOW(), NOW());

-- ============================================================
-- DADOS INICIAIS: Configurações do Site
-- ============================================================
INSERT INTO `settings` (`key`, `value`, `type`, `group`, `label`, `created_at`, `updated_at`) VALUES
('site_name', 'ISSM - Instituto Socioambiental Serra do Mendanha', 'text', 'general', 'Nome do Site', NOW(), NOW()),
('site_description', 'Instituto dedicado a preservacao ambiental e desenvolvimento sustentavel na regiao da Serra do Mendanha.', 'textarea', 'general', 'Descricao do Site', NOW(), NOW()),
('site_logo', '', 'image', 'general', 'Logo do Site', NOW(), NOW()),
('site_favicon', '', 'image', 'general', 'Favicon', NOW(), NOW()),
('about_text', 'O Instituto Socioambiental Serra do Mendanha (ISSM) e uma organizacao sem fins lucrativos dedicada a preservacao ambiental, educacao ecologica e desenvolvimento sustentavel.', 'textarea', 'general', 'Texto Sobre Nos', NOW(), NOW()),
('mission', 'Promover a conservacao do meio ambiente e o desenvolvimento sustentavel da Serra do Mendanha.', 'textarea', 'general', 'Missao', NOW(), NOW()),
('vision', 'Ser referencia nacional em gestao socioambiental, contribuindo para um mundo mais sustentavel e equitativo alinhado com os ODS 2030.', 'textarea', 'general', 'Visao', NOW(), NOW()),
('values', 'Sustentabilidade, Transparencia, Inclusao Social, Inovacao, Parceria e Responsabilidade Ambiental.', 'textarea', 'general', 'Valores', NOW(), NOW()),
('contact_email', 'contato@issm.org.br', 'text', 'contact', 'E-mail de Contato', NOW(), NOW()),
('contact_phone', '(21) 9999-9999', 'text', 'contact', 'Telefone', NOW(), NOW()),
('contact_address', 'Serra do Mendanha, Rio de Janeiro - RJ', 'text', 'contact', 'Endereco', NOW(), NOW()),
('contact_cep', '', 'text', 'contact', 'CEP', NOW(), NOW()),
('contact_map_embed', '', 'textarea', 'contact', 'Embed do Mapa', NOW(), NOW()),
('social_facebook', 'https://facebook.com/issm', 'text', 'social', 'Facebook', NOW(), NOW()),
('social_instagram', 'https://instagram.com/issm', 'text', 'social', 'Instagram', NOW(), NOW()),
('social_twitter', '', 'text', 'social', 'Twitter/X', NOW(), NOW()),
('social_youtube', '', 'text', 'social', 'YouTube', NOW(), NOW()),
('social_linkedin', '', 'text', 'social', 'LinkedIn', NOW(), NOW()),
('social_whatsapp', '', 'text', 'social', 'WhatsApp', NOW(), NOW()),
('meta_title', 'ISSM - Instituto Socioambiental Serra do Mendanha', 'text', 'seo', 'Meta Title', NOW(), NOW()),
('meta_description', 'Instituto dedicado a preservacao ambiental alinhado com os ODS 2030.', 'textarea', 'seo', 'Meta Description', NOW(), NOW()),
('meta_keywords', 'ISSM, meio ambiente, sustentabilidade, ODS, Serra do Mendanha', 'text', 'seo', 'Meta Keywords', NOW(), NOW()),
('google_analytics', '', 'text', 'seo', 'Google Analytics ID', NOW(), NOW()),
('maintenance_mode', '0', 'boolean', 'maintenance', 'Modo Manutencao', NOW(), NOW()),
('maintenance_title', 'Site em Manutencao', 'text', 'maintenance', 'Titulo da Pagina de Manutencao', NOW(), NOW()),
('maintenance_message', 'Estamos realizando melhorias no nosso site. Em breve estaremos de volta!', 'textarea', 'maintenance', 'Mensagem de Manutencao', NOW(), NOW()),
('maintenance_email', 'contato@issm.org.br', 'text', 'maintenance', 'E-mail (Manutencao)', NOW(), NOW()),
('show_banners', '1', 'boolean', 'home', 'Exibir Banners', NOW(), NOW()),
('show_about', '1', 'boolean', 'home', 'Exibir Secao Sobre', NOW(), NOW()),
('show_ods', '1', 'boolean', 'home', 'Exibir ODS 2030', NOW(), NOW()),
('show_projects', '1', 'boolean', 'home', 'Exibir Projetos', NOW(), NOW()),
('show_news', '1', 'boolean', 'home', 'Exibir Noticias', NOW(), NOW()),
('show_team', '1', 'boolean', 'home', 'Exibir Equipe', NOW(), NOW()),
('show_partners', '1', 'boolean', 'home', 'Exibir Parceiros', NOW(), NOW()),
('show_gallery', '1', 'boolean', 'home', 'Exibir Galeria', NOW(), NOW()),
('show_contact', '1', 'boolean', 'home', 'Exibir Formulario de Contato', NOW(), NOW());

-- ============================================================
-- DADOS INICIAIS: ODS 2030 (17 Objetivos)
-- ============================================================
INSERT INTO `ods` (`number`, `title`, `description`, `color`, `icon`, `active`, `created_at`, `updated_at`) VALUES
(1, 'Erradicar a Pobreza', 'Acabar com a pobreza em todas as suas formas.', '#e5243b', NULL, 1, NOW(), NOW()),
(2, 'Erradicar a Fome', 'Acabar com a fome e alcancar a seguranca alimentar.', '#dda63a', NULL, 1, NOW(), NOW()),
(3, 'Saude de Qualidade', 'Assegurar uma vida saudavel e promover o bem-estar para todos.', '#4c9f38', NULL, 1, NOW(), NOW()),
(4, 'Educacao de Qualidade', 'Assegurar a educacao inclusiva e equitativa e de qualidade.', '#c5192d', NULL, 1, NOW(), NOW()),
(5, 'Igualdade de Genero', 'Alcancar a igualdade de genero e empoderar todas as mulheres.', '#ff3a21', NULL, 1, NOW(), NOW()),
(6, 'Agua Potavel e Saneamento', 'Assegurar a disponibilidade e gestao sustentavel da agua.', '#26bde2', NULL, 1, NOW(), NOW()),
(7, 'Energias Renovaveis e Acessiveis', 'Assegurar o acesso a energia sustentavel e acessivel.', '#fcc30b', NULL, 1, NOW(), NOW()),
(8, 'Trabalho Digno e Crescimento Economico', 'Promover o crescimento economico sustentado e inclusivo.', '#a21942', NULL, 1, NOW(), NOW()),
(9, 'Industria, Inovacao e Infraestruturas', 'Construir infraestruturas resilientes e promover a industrializacao.', '#fd6925', NULL, 1, NOW(), NOW()),
(10, 'Reduzir as Desigualdades', 'Reduzir a desigualdade dentro dos paises e entre eles.', '#dd1367', NULL, 1, NOW(), NOW()),
(11, 'Cidades e Comunidades Sustentaveis', 'Tornar as cidades inclusivas, seguras, resilientes e sustentaveis.', '#fd9d24', NULL, 1, NOW(), NOW()),
(12, 'Producao e Consumo Responsaveis', 'Assegurar padroes de producao e de consumo sustentaveis.', '#bf8b2e', NULL, 1, NOW(), NOW()),
(13, 'Acao Contra a Mudanca Global do Clima', 'Tomar medidas urgentes para combater a mudanca climatica.', '#3f7e44', NULL, 1, NOW(), NOW()),
(14, 'Vida na Agua', 'Conservacao e uso sustentavel dos oceanos e recursos marinhos.', '#0a97d9', NULL, 1, NOW(), NOW()),
(15, 'Vida Terrestre', 'Proteger e promover o uso sustentavel dos ecossistemas terrestres.', '#56c02b', NULL, 1, NOW(), NOW()),
(16, 'Paz, Justica e Instituicoes Eficazes', 'Promover sociedades pacificas e inclusivas para o desenvolvimento.', '#00689d', NULL, 1, NOW(), NOW()),
(17, 'Parcerias e Meios de Implementacao', 'Fortalecer os meios de implementacao e revitalizar a parceria global.', '#19486a', NULL, 1, NOW(), NOW());

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- FIM DO SCRIPT
-- 16 tabelas criadas + dados iniciais inseridos
-- Apos importar, acesse: https://issm.org.br/login
-- Email: admin@issm.org.br  |  Senha: Admin@ISSM2024!
-- ============================================================

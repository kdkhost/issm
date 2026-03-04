# 🌿 ISSM - Instituto Socioambiental Serra do Mendanha

> Site institucional e painel administrativo do Instituto Socioambiental Serra do Mendanha (ISSM), dedicado à preservação ambiental, educação ecológica e desenvolvimento sustentável alinhado com os ODS 2030.

🔗 **Produção:** [https://issm.org.br](https://issm.org.br)

---

## 📋 Sobre o Projeto

O ISSM é uma plataforma web completa composta por um **site público** voltado para divulgação institucional e um **painel administrativo** para gestão de todo o conteúdo. O sistema permite gerenciar notícias, projetos, equipe, parceiros, galeria de fotos, banners, páginas estáticas, ODS 2030, mensagens de contato e diversas configurações do site — tudo de forma intuitiva.

## ✨ Funcionalidades

### Site Público
- **Home** com hero, banners rotativos, seções sobre, ODS, projetos, notícias, equipe, parceiros, galeria e contato
- **Notícias** com listagem e página de detalhe
- **Projetos** com listagem e página de detalhe
- **Galeria** de fotos com visualização em lightbox
- **Formulário de contato** com reCAPTCHA
- **ODS 2030** — exibição dos objetivos de desenvolvimento sustentável
- **Páginas institucionais** dinâmicas
- **SEO completo** — Open Graph, Twitter Cards, JSON-LD, meta tags, sitemap
- **Preloader** de transição entre páginas
- **Botão voltar ao topo** e **widget de suporte**
- **Modo manutenção** com página personalizada
- **Design responsivo** (mobile-first)
- **Páginas de erro** personalizadas (401, 403, 404, 405, 408, 419, 429, 500, 503)

### Painel Administrativo (`/admin`)
- **Dashboard** com estatísticas e atalhos rápidos
- **CRUD completo** para: Banners, Notícias, Projetos, Equipe, Parceiros, Galeria, Páginas, ODS 2030
- **Mensagens de contato** com status (nova, lida, respondida)
- **Configurações gerais** — nome, logo, favicon, missão, visão, valores
- **Configurações de SEO** — meta tags, Open Graph, Twitter Cards, Analytics, Search Console
- **Configurações de e-mail SMTP** — host, porta, criptografia direto pelo painel
- **Configurações da página inicial** — seções visíveis, hero background, overlay
- **Configurações de manutenção** — ativar/desativar, mensagem, countdown, progresso
- **Configurações de parceiros** — carrossel (velocidade, efeito, logos por tela)
- **Configurações de segurança** — reCAPTCHA
- **Gerenciamento de IPs** para bypass de manutenção
- **Analytics** — visualizações de páginas, visitantes, dispositivos
- **Tema dark/light** com persistência
- **Upload drag & drop** com preview de imagens
- **Editor WYSIWYG** (Summernote) para conteúdo rico
- **Tooltips**, **toasts** e **confirmações SweetAlert2**
- **Perfil do usuário** com alteração de senha

## 🛠️ Stack Tecnológica

| Camada | Tecnologia |
|--------|-----------|
| **Backend** | PHP 8.4, Laravel 10 |
| **Frontend** | Blade, Tailwind CSS v4, Vite |
| **Banco de Dados** | MySQL |
| **Editor** | Summernote 0.9 |
| **UI Components** | SweetAlert2, Toastify, Swiper 11 |
| **Fontes** | Google Fonts (Inter) |
| **Servidor** | cPanel, EasyApache (ea-php84) |

## 📁 Estrutura do Projeto

```
app/
├── Http/Controllers/       # Controllers (Admin + Público)
├── Models/                 # Eloquent Models
├── Providers/              # Service Providers
resources/
├── views/
│   ├── layouts/            # Layouts (app.blade.php, admin.blade.php)
│   ├── admin/              # Views do painel administrativo
│   ├── errors/             # Páginas de erro personalizadas
│   ├── home.blade.php      # Página inicial
│   └── ...                 # Views públicas (news, projects, etc.)
routes/
├── web.php                 # Rotas web (públicas + admin)
├── api.php                 # Rotas API
database/
├── migrations/             # Migrações do banco
├── seeders/                # Seeders (AdminUser, Settings)
public/
├── media/                  # Uploads de mídia
├── build/                  # Assets compilados (Vite)
```

## ⚙️ Configuração

### Requisitos
- PHP >= 8.1
- Composer
- Node.js >= 18
- MySQL 5.7+

### Instalação

```bash
# Clonar repositório
git clone <repo-url> issm
cd issm

# Instalar dependências
composer install
npm install

# Configurar ambiente
cp .env.example .env
php artisan key:generate

# Configurar banco de dados no .env
# DB_DATABASE, DB_USERNAME, DB_PASSWORD

# Executar migrações e seeders
php artisan migrate --seed

# Compilar assets
npm run build

# Iniciar servidor de desenvolvimento
php artisan serve
```

### Variáveis de Ambiente Principais

```env
APP_NAME=ISSM
APP_URL=https://issm.org.br
APP_TIMEZONE=America/Sao_Paulo
APP_LOCALE=pt_BR

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=issm
```

> As configurações de e-mail SMTP, SEO, redes sociais e manutenção são gerenciadas pelo painel administrativo (tabela `settings`).

## 🌐 Rotas Principais

| Rota | Descrição |
|------|-----------|
| `/` | Página inicial |
| `/noticias` | Listagem de notícias |
| `/projetos` | Listagem de projetos |
| `/galeria` | Galeria de fotos |
| `/equipe` | Equipe |
| `/paginas/{slug}` | Páginas dinâmicas |
| `/admin` | Painel administrativo |
| `/admin/configuracoes` | Configurações do site |

## 📄 Licença

Projeto privado — ISSM © 2026. Todos os direitos reservados.

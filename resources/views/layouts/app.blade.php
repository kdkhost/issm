<!DOCTYPE html>
<html lang="pt-BR" style="scroll-behavior: smooth;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#15803d">

    {{-- ═══ SEO Básico ═══ --}}
    @php
        $seoTitle       = \App\Models\Setting::get('meta_title', 'ISSM - Instituto Socioambiental Serra do Mendanha');
        $seoDesc        = \App\Models\Setting::get('meta_description', 'Instituto Socioambiental Serra do Mendanha - Preservação ambiental e desenvolvimento sustentável alinhado com os ODS 2030');
        $seoKeywords    = \App\Models\Setting::get('meta_keywords', '');
        $seoAuthor      = \App\Models\Setting::get('meta_author', '');
        $seoRobots      = \App\Models\Setting::get('robots_meta', 'index, follow');
        $seoCanonical   = \App\Models\Setting::get('canonical_url', '');

        $ogTitle        = \App\Models\Setting::get('og_title', $seoTitle);
        $ogDesc         = \App\Models\Setting::get('og_description', $seoDesc);
        $ogImage        = \App\Models\Setting::get('og_image', '');
        $ogType         = \App\Models\Setting::get('og_type', 'website');
        $ogLocale       = \App\Models\Setting::get('og_locale', 'pt_BR');
        $ogSiteName     = \App\Models\Setting::get('og_site_name', $seoTitle);

        $twCard         = \App\Models\Setting::get('twitter_card', 'summary_large_image');
        $twTitle        = \App\Models\Setting::get('twitter_title', $ogTitle);
        $twDesc         = \App\Models\Setting::get('twitter_description', $ogDesc);
        $twImage        = \App\Models\Setting::get('twitter_image', $ogImage);
        $twHandle       = \App\Models\Setting::get('twitter_handle', '');

        $gaId           = \App\Models\Setting::get('google_analytics', '');
        $gtmId          = \App\Models\Setting::get('google_tag_manager', '');
        $googleVerify   = \App\Models\Setting::get('google_site_verification', '');
        $bingVerify     = \App\Models\Setting::get('bing_site_verification', '');

        $siteFavicon    = \App\Models\Setting::get('site_favicon');
    @endphp

    <title>@yield('title', $seoTitle)</title>
    <meta name="description" content="@yield('meta_description', $seoDesc)">
    @if($seoKeywords)<meta name="keywords" content="{{ $seoKeywords }}">@endif
    @if($seoAuthor)<meta name="author" content="{{ $seoAuthor }}">@endif
    @if($seoRobots)<meta name="robots" content="{{ $seoRobots }}">@endif
    @if($seoCanonical)<link rel="canonical" href="{{ $seoCanonical }}">@endif

    {{-- ═══ Open Graph (Facebook / WhatsApp / LinkedIn) ═══ --}}
    <meta property="og:type" content="{{ $ogType }}">
    <meta property="og:locale" content="{{ $ogLocale }}">
    <meta property="og:site_name" content="{{ $ogSiteName }}">
    <meta property="og:title" content="@yield('og_title', $ogTitle)">
    <meta property="og:description" content="@yield('og_description', $ogDesc)">
    <meta property="og:url" content="{{ $seoCanonical ?: url()->current() }}">
    @if($ogImage)
    <meta property="og:image" content="{{ asset('media/' . $ogImage) }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:type" content="image/{{ pathinfo($ogImage, PATHINFO_EXTENSION) === 'png' ? 'png' : 'jpeg' }}">
    @endif

    {{-- ═══ Twitter Card ═══ --}}
    <meta name="twitter:card" content="{{ $twCard }}">
    <meta name="twitter:title" content="@yield('twitter_title', $twTitle)">
    <meta name="twitter:description" content="@yield('twitter_description', $twDesc)">
    @if($twHandle)<meta name="twitter:site" content="{{ str_starts_with($twHandle, '@') ? $twHandle : '@'.$twHandle }}">@endif
    @if($twImage)
    <meta name="twitter:image" content="{{ asset('media/' . $twImage) }}">
    @elseif($ogImage)
    <meta name="twitter:image" content="{{ asset('media/' . $ogImage) }}">
    @endif

    {{-- ═══ Verificação de Mecanismos de Busca ═══ --}}
    @if($googleVerify)<meta name="google-site-verification" content="{{ $googleVerify }}">@endif
    @if($bingVerify)<meta name="msvalidate.01" content="{{ $bingVerify }}">@endif

    {{-- ═══ Favicon ═══ --}}
    @if($siteFavicon)
    <link rel="icon" type="image/x-icon" href="{{ asset('media/' . $siteFavicon) }}">
    <link rel="apple-touch-icon" href="{{ asset('media/' . $siteFavicon) }}">
    @endif

    {{-- ═══ JSON-LD Structured Data ═══ --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "{{ $ogSiteName }}",
        "url": "{{ $seoCanonical ?: url('/') }}",
        "description": "{{ $seoDesc }}",
        @if($ogImage)"logo": "{{ asset('media/' . $ogImage) }}",@endif
        @if($siteFavicon)"image": "{{ asset('media/' . $siteFavicon) }}",@endif
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "Rio de Janeiro",
            "addressRegion": "RJ",
            "addressCountry": "BR"
        },
        "sameAs": [
            @php
                $socialLinks = array_filter([
                    \App\Models\Setting::get('social_facebook'),
                    \App\Models\Setting::get('social_instagram'),
                    \App\Models\Setting::get('social_youtube'),
                    \App\Models\Setting::get('social_linkedin'),
                    \App\Models\Setting::get('social_twitter'),
                ]);
            @endphp
            {!! collect($socialLinks)->map(fn($l) => '"'.e($l).'"')->implode(",\n            ") !!}
        ]
    }
    </script>

    {{-- ═══ Google Tag Manager ═══ --}}
    @if($gtmId)
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','{{ $gtmId }}');</script>
    @endif

    {{-- ═══ Google Analytics ═══ --}}
    @if($gaId)
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
    <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','{{ $gaId }}');</script>
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @php $recaptchaSiteKey = \App\Models\Setting::get('recaptcha_site_key'); @endphp
    @if($recaptchaSiteKey)
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Sem padding-bottom no body — a bottom nav é fixed e não empurra conteúdo */
        @media (max-width: 1023px) {
            body { padding-bottom: 0; }
        }
        /* Previne scroll horizontal global em qualquer resolução */
        html, body { overflow-x: hidden; max-width: 100%; margin:0; padding:0; }
        /* Flex layout para empurrar footer ao fundo da viewport */
        html { min-height:100vh; background-color:#14532d; }
        body { display:flex; flex-direction:column; min-height:100vh; }
        main { flex:1 0 auto; }
        #main-footer { flex-shrink:0; }
        /* Garante que footer cole no fundo */
        #main-footer { margin-bottom:0 !important; padding-bottom:0 !important; }
        #main-footer + *, #main-footer ~ * { margin:0; padding:0; }
        /* Espaço para o header fixo ao usar âncoras */
        html { scroll-padding-top: 56px; }
        @media (min-width: 640px)  { html { scroll-padding-top: 72px; } }
        @media (min-width: 1024px) { html { scroll-padding-top: 100px; } }
        /* Header height – independente do build Tailwind */
        .main-hdr { height: 56px; overflow: hidden; }
        @media (min-width: 640px)  { .main-hdr { height: 72px; } }
        @media (min-width: 1024px) { .main-hdr { height: 100px; } }
        @media (min-width: 1024px) { .main-hdr-logo { max-height: 72px !important; } }
        /* ── Bottom Nav (Material 3) ── */
        #bottom-nav { display:none !important; }
        @media (max-width: 1023px) { #bottom-nav { display:block !important; } }
        #bottom-nav .nav-item { position:relative; overflow:hidden; -webkit-tap-highlight-color:transparent; }
        #bottom-nav .nav-item .nav-pill {
            position:absolute; inset:0 4px;
            border-radius: 16px;
            background: transparent;
            transition: background .2s ease;
        }
        #bottom-nav .nav-item.active-nav .nav-pill { background: #dcfce7; }
        #bottom-nav .nav-item.active-nav svg { color: #15803d !important; }
        #bottom-nav .nav-item.active-nav span { color: #15803d !important; font-weight: 700; }
        /* ripple no bottom nav */
        #bottom-nav .nav-item::after {
            content:''; position:absolute; border-radius:50%;
            background: rgba(21,128,61,.15);
            width:0; height:0; top:50%; left:50%; transform:translate(-50%,-50%);
            transition: width .4s ease, height .4s ease, opacity .4s ease;
            opacity:0; pointer-events:none;
        }
        #bottom-nav .nav-item:active::after { width:120px; height:120px; opacity:1; }
        /* ── Sidebar (Material 3 Nav Drawer) ── */
        #mobile-sidebar { will-change: transform; }
        .md-nav-item {
            position: relative; overflow: hidden;
            border-radius: 28px;
            -webkit-tap-highlight-color: transparent;
            transition: background .18s ease;
        }
        .md-nav-item .md-active-bg {
            position: absolute; inset: 0; border-radius: 28px;
            background: transparent;
            transition: background .18s ease;
            pointer-events: none;
        }
        .md-nav-item.is-active .md-active-bg { background: #dcfce7; }
        .md-nav-item.is-active span { color: #14532d; font-weight: 700; }
        .md-nav-item.is-active svg { color: #15803d; }
        /* ripple no sidebar */
        .md-nav-item::after {
            content:''; position:absolute; border-radius:50%;
            background: rgba(21,128,61,.12);
            width:0; height:0; top:50%; left:50%; transform:translate(-50%,-50%);
            transition: width .45s ease, height .45s ease, opacity .5s ease;
            opacity:0; pointer-events:none;
        }
        .md-nav-item:active::after { width:300px; height:300px; opacity:1; }
    </style>
    @stack('styles')
</head>
<body class="bg-white text-gray-800">
    {{-- GTM noscript fallback --}}
    @if($gtmId)
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $gtmId }}" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    @endif

    <!-- Preloader de transição de página -->
    <div id="page-preloader" style="position:fixed;inset:0;background:#166534;z-index:9999;display:flex;flex-direction:column;align-items:center;justify-content:center;transition:opacity 0.35s ease;opacity:0;pointer-events:none;" aria-hidden="true">
        @php $siteLogo = \App\Models\Setting::get('site_logo'); @endphp
        @if($siteLogo)
        <img src="{{ asset('media/' . $siteLogo) }}" alt="ISSM" style="height:70px;width:auto;object-fit:contain;animation:pulse 1.2s ease-in-out infinite alternate;">
        @else
        <div style="width:70px;height:70px;border-radius:50%;background:#16a34a;display:flex;align-items:center;justify-content:center;animation:pulse 1.2s ease-in-out infinite alternate;">
            <span style="color:white;font-weight:900;font-size:18px;">ISSM</span>
        </div>
        @endif
        <div style="margin-top:20px;display:flex;gap:8px;">
            <div style="width:8px;height:8px;border-radius:50%;background:rgba(255,255,255,0.7);animation:bounce 1.2s ease-in-out infinite;"></div>
            <div style="width:8px;height:8px;border-radius:50%;background:rgba(255,255,255,0.7);animation:bounce 1.2s ease-in-out 0.2s infinite;"></div>
            <div style="width:8px;height:8px;border-radius:50%;background:rgba(255,255,255,0.7);animation:bounce 1.2s ease-in-out 0.4s infinite;"></div>
        </div>
    </div>
    <style>
        @@keyframes pulse { from { opacity:0.6; transform:scale(0.97); } to { opacity:1; transform:scale(1.03); } }
        @@keyframes bounce { 0%,100% { transform:translateY(0); } 50% { transform:translateY(-8px); } }
        #back-to-top { display:none; }
        #back-to-top.show { display:flex; }
    </style>

    <!-- Header -->
    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="main-hdr flex justify-between items-center">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-2 sm:gap-3 h-full py-2 sm:py-2.5 lg:py-4">
                    @php $siteLogo = \App\Models\Setting::get('site_logo'); @endphp
                    @if($siteLogo)
                    <img src="{{ asset('media/' . $siteLogo) }}" alt="{{ config('app.name') }}" class="main-hdr-logo h-full w-auto max-h-10 object-contain flex-shrink-0">
                    @else
                    <div class="w-11 h-11 sm:w-14 sm:h-14 lg:w-24 lg:h-24 rounded-full bg-gradient-to-br from-green-700 to-green-500 flex items-center justify-center shadow-lg flex-shrink-0">
                        <span class="text-white font-black text-sm sm:text-lg lg:text-2xl">ISSM</span>
                    </div>
                    @endif
                    <div class="hidden sm:block">
                        <p class="font-bold text-green-800 text-sm lg:text-xl leading-tight">Instituto Socioambiental</p>
                        <p class="text-green-600 text-xs lg:text-sm">Serra do Mendanha</p>
                    </div>
                </a>

                <!-- Desktop Nav -->
                <nav class="hidden lg:flex items-center gap-8">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-green-700 font-medium transition-colors">Início</a>
                    <a href="{{ route('home') }}#sobre" class="text-gray-700 hover:text-green-700 font-medium transition-colors">Sobre</a>
                    <a href="{{ route('projects.index') }}" class="text-gray-700 hover:text-green-700 font-medium transition-colors">Projetos</a>
                    <a href="{{ route('home') }}#ods" class="text-gray-700 hover:text-green-700 font-medium transition-colors">ODS 2030</a>
                    <a href="{{ route('news.index') }}" class="text-gray-700 hover:text-green-700 font-medium transition-colors">Notícias</a>
                    <a href="{{ route('gallery.index') }}" class="text-gray-700 hover:text-green-700 font-medium transition-colors">Galeria</a>
                    <a href="{{ route('home') }}#contato" class="bg-green-700 text-white px-5 py-2 rounded-full hover:bg-green-800 font-medium transition-colors">Contato</a>
                </nav>

                <!-- Hamburger (mobile + tablet, oculto no desktop) -->
                <button id="mobile-menu-btn" class="flex lg:hidden p-2 text-gray-700 hover:text-green-700 transition-colors" aria-label="Abrir menu">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <!-- Material 3 Navigation Drawer -->
    <div id="mobile-overlay" style="display:none;position:fixed;inset:0;background:#000;z-index:40;transition:opacity 0.3s;opacity:0;cursor:pointer;pointer-events:none;"></div>
    <div id="mobile-sidebar" class="fixed top-0 left-0 h-full w-[85vw] max-w-[360px] z-50 flex flex-col"
         style="background:#f6faf6;transform:translateX(-100%);transition:transform 0.32s cubic-bezier(0.4,0,0.2,1);box-shadow:2px 0 24px rgba(0,0,0,.18);">

        {{-- ── Banner do drawer ── --}}
        <div class="relative overflow-hidden flex-shrink-0" style="background:linear-gradient(135deg,#14532d,#15803d);padding:28px 20px 20px;">
            {{-- Detalhe de fundo --}}
            <div style="position:absolute;right:-30px;top:-30px;width:120px;height:120px;border-radius:50%;background:rgba(255,255,255,.06);"></div>
            <div style="position:absolute;right:20px;bottom:-40px;width:80px;height:80px;border-radius:50%;background:rgba(255,255,255,.04);"></div>
            {{-- Botão fechar (← seta Android) --}}
            <button id="mobile-menu-close" type="button"
                    style="position:absolute;top:14px;right:14px;width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,.12);display:flex;align-items:center;justify-content:center;border:none;cursor:pointer;transition:background .2s;-webkit-tap-highlight-color:transparent;"
                    aria-label="Fechar menu">
                <svg style="width:18px;height:18px;color:#fff;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            {{-- Logo + nome --}}
            @php $siteLogo = \App\Models\Setting::get('site_logo'); $siteName = \App\Models\Setting::get('site_name','ISSM'); @endphp
            <div class="flex items-center gap-3 relative">
                @if($siteLogo)
                <div style="width:52px;height:52px;border-radius:14px;overflow:hidden;background:#fff;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 2px 8px rgba(0,0,0,.2);">
                    <img src="{{ asset('media/' . $siteLogo) }}" alt="ISSM" style="width:100%;height:100%;object-fit:contain;">
                </div>
                @else
                <div style="width:52px;height:52px;border-radius:14px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <span style="color:#fff;font-weight:900;font-size:15px;">ISSM</span>
                </div>
                @endif
                <div>
                    <p style="font-weight:800;color:#fff;font-size:15px;line-height:1.2;">Instituto Socioambiental</p>
                    <p style="color:#86efac;font-size:12px;margin-top:2px;">Serra do Mendanha</p>
                </div>
            </div>
        </div>

        {{-- ── Itens de navegação ── --}}
        <nav class="flex-1 overflow-y-auto" style="padding:12px 12px 8px;">

            {{-- Label de grupo --}}
            <p style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.08em;padding:4px 16px 8px;">Navegação</p>

            <a href="{{ route('home') }}" data-nav-path="/" class="md-nav-item flex items-center gap-4 px-4 py-0" style="height:56px;text-decoration:none;">
                <div class="md-active-bg"></div>
                <div style="width:40px;height:40px;border-radius:12px;background:#dcfce7;display:flex;align-items:center;justify-content:center;flex-shrink:0;position:relative;z-index:1;">
                    <svg style="width:20px;height:20px;color:#15803d;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </div>
                <span style="font-size:15px;font-weight:500;color:#1f2937;position:relative;z-index:1;">Início</span>
            </a>

            <a href="{{ route('home') }}#sobre" class="md-nav-item flex items-center gap-4 px-4 py-0" style="height:56px;text-decoration:none;">
                <div class="md-active-bg"></div>
                <div style="width:40px;height:40px;border-radius:12px;background:#dbeafe;display:flex;align-items:center;justify-content:center;flex-shrink:0;position:relative;z-index:1;">
                    <svg style="width:20px;height:20px;color:#1d4ed8;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span style="font-size:15px;font-weight:500;color:#1f2937;position:relative;z-index:1;">Sobre o ISSM</span>
            </a>

            <a href="{{ route('projects.index') }}" data-nav-path="/projetos" class="md-nav-item flex items-center gap-4 px-4 py-0" style="height:56px;text-decoration:none;">
                <div class="md-active-bg"></div>
                <div style="width:40px;height:40px;border-radius:12px;background:#ffedd5;display:flex;align-items:center;justify-content:center;flex-shrink:0;position:relative;z-index:1;">
                    <svg style="width:20px;height:20px;color:#c2410c;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <span style="font-size:15px;font-weight:500;color:#1f2937;position:relative;z-index:1;">Projetos</span>
            </a>

            <a href="{{ route('home') }}#ods" class="md-nav-item flex items-center gap-4 px-4 py-0" style="height:56px;text-decoration:none;">
                <div class="md-active-bg"></div>
                <div style="width:40px;height:40px;border-radius:12px;background:#d1fae5;display:flex;align-items:center;justify-content:center;flex-shrink:0;position:relative;z-index:1;">
                    <svg style="width:20px;height:20px;color:#065f46;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span style="font-size:15px;font-weight:500;color:#1f2937;position:relative;z-index:1;">ODS 2030</span>
            </a>

            <a href="{{ route('news.index') }}" data-nav-path="/noticias" class="md-nav-item flex items-center gap-4 px-4 py-0" style="height:56px;text-decoration:none;">
                <div class="md-active-bg"></div>
                <div style="width:40px;height:40px;border-radius:12px;background:#ede9fe;display:flex;align-items:center;justify-content:center;flex-shrink:0;position:relative;z-index:1;">
                    <svg style="width:20px;height:20px;color:#6d28d9;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                </div>
                <span style="font-size:15px;font-weight:500;color:#1f2937;position:relative;z-index:1;">Notícias</span>
            </a>

            <a href="{{ route('gallery.index') }}" data-nav-path="/galeria" class="md-nav-item flex items-center gap-4 px-4 py-0" style="height:56px;text-decoration:none;">
                <div class="md-active-bg"></div>
                <div style="width:40px;height:40px;border-radius:12px;background:#fce7f3;display:flex;align-items:center;justify-content:center;flex-shrink:0;position:relative;z-index:1;">
                    <svg style="width:20px;height:20px;color:#be185d;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <span style="font-size:15px;font-weight:500;color:#1f2937;position:relative;z-index:1;">Galeria</span>
            </a>

            {{-- Divisor --}}
            <div style="height:1px;background:#e5e7eb;margin:8px 16px 12px;"></div>

            {{-- CTA contato --}}
            <a href="{{ route('home') }}#contato" class="md-nav-item flex items-center gap-4 px-4 py-0" style="height:56px;text-decoration:none;">
                <div class="md-active-bg"></div>
                <div style="width:40px;height:40px;border-radius:12px;background:#15803d;display:flex;align-items:center;justify-content:center;flex-shrink:0;position:relative;z-index:1;">
                    <svg style="width:20px;height:20px;color:#fff;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <span style="font-size:15px;font-weight:600;color:#15803d;position:relative;z-index:1;">Fale Conosco</span>
                <svg style="width:16px;height:16px;color:#15803d;margin-left:auto;position:relative;z-index:1;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </nav>

        {{-- ── Rodapé do drawer ── --}}
        <div style="padding:12px 20px calc(12px + env(safe-area-inset-bottom,0px));border-top:1px solid #e5e7eb;display:flex;align-items:center;justify-content:space-between;">
            <p style="font-size:11px;color:#9ca3af;">© {{ date('Y') }} ISSM</p>
            @auth
            <a href="{{ route('admin.dashboard') }}" style="font-size:11px;color:#15803d;font-weight:600;text-decoration:none;">Painel Admin</a>
            @endauth
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 mx-4 mt-4 rounded">
        <p class="text-green-700">{{ session('success') }}</p>
    </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-green-900 text-white" id="main-footer" style="border:none;outline:none;margin:0;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        @if($siteLogo ?? \App\Models\Setting::get('site_logo'))
                        <img src="{{ asset('media/' . ($siteLogo ?? \App\Models\Setting::get('site_logo'))) }}" alt="ISSM" class="h-12 w-auto object-contain">
                        @else
                        <div class="w-12 h-12 rounded-full bg-green-600 flex items-center justify-center">
                            <span class="text-white font-black">ISSM</span>
                        </div>
                        @endif
                        <div>
                            <p class="font-bold text-white">Instituto Socioambiental</p>
                            <p class="text-green-300 text-sm">Serra do Mendanha</p>
                        </div>
                    </div>
                    <p class="text-green-200 text-sm leading-relaxed mb-4">
                        Comprometidos com a preservação ambiental e o desenvolvimento sustentável, alinhados com os 17 Objetivos de Desenvolvimento Sustentável da ONU para 2030.
                    </p>
                    <div class="flex gap-3">
                        @php $facebook = \App\Models\Setting::get('social_facebook'); $instagram = \App\Models\Setting::get('social_instagram'); $youtube = \App\Models\Setting::get('social_youtube'); @endphp
                        @if($facebook)<a href="{{ $facebook }}" target="_blank" class="w-9 h-9 bg-green-700 rounded-full flex items-center justify-center hover:bg-green-600 transition-colors" title="Facebook"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>@endif
                        @if($instagram)<a href="{{ $instagram }}" target="_blank" class="w-9 h-9 bg-green-700 rounded-full flex items-center justify-center hover:bg-green-600 transition-colors" title="Instagram"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a>@endif
                        @if($youtube)<a href="{{ $youtube }}" target="_blank" class="w-9 h-9 bg-green-700 rounded-full flex items-center justify-center hover:bg-green-600 transition-colors" title="YouTube"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg></a>@endif
                    </div>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-4">Links Rápidos</h4>
                    <ul class="space-y-2 text-green-200 text-sm">
                        <li><a href="{{ route('home') }}#sobre" class="hover:text-white transition-colors">Sobre o ISSM</a></li>
                        <li><a href="{{ route('projects.index') }}" class="hover:text-white transition-colors">Nossos Projetos</a></li>
                        <li><a href="{{ route('home') }}#ods" class="hover:text-white transition-colors">ODS 2030</a></li>
                        <li><a href="{{ route('news.index') }}" class="hover:text-white transition-colors">Notícias</a></li>
                        <li><a href="{{ route('gallery.index') }}" class="hover:text-white transition-colors">Galeria</a></li>
                        <li><a href="{{ route('home') }}#equipe" class="hover:text-white transition-colors">Nossa Equipe</a></li>
                        <li><a href="{{ route('home') }}#contato" class="hover:text-white transition-colors">Contato</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-4">Contato</h4>
                    <ul class="space-y-2 text-green-200 text-sm">
                        @php $email = \App\Models\Setting::get('contact_email'); $phone = \App\Models\Setting::get('contact_phone'); $address = \App\Models\Setting::get('contact_address'); @endphp
                        @if($email)<li class="flex items-center gap-2"><svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>{{ $email }}</li>@endif
                        @if($phone)<li class="flex items-center gap-2"><svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>{{ $phone }}</li>@endif
                        @if($address)<li class="flex items-start gap-2"><svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>{{ $address }}</li>@endif
                    </ul>
                </div>
            </div>
        </div>
        <div class="border-t border-green-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col sm:flex-row justify-between items-center gap-2">
                <p class="text-green-300 text-sm">&copy; {{ date('Y') }} ISSM - Instituto Socioambiental Serra do Mendanha. Todos os direitos reservados.</p>
                @auth
                <a href="{{ route('admin.dashboard') }}" class="text-green-400 hover:text-white text-sm transition-colors">Painel Admin</a>
                @endauth
            </div>
        </div>
    </footer>

    <!-- Smooth anchor scroll: evita o "piscar" ao clicar em links de seção -->
    <script>
    (function() {
        function smoothScrollToHash(hash) {
            var target = document.querySelector(hash);
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                history.replaceState(null, '', hash);
                return true;
            }
            return false;
        }

        // Ao carregar com hash na URL, rolar suavemente
        if (window.location.hash) {
            setTimeout(function() {
                smoothScrollToHash(window.location.hash);
            }, 80);
        }

        // Interceptar todos os cliques em links âncora da mesma página
        document.addEventListener('click', function(e) {
            var a = e.target.closest('a[href]');
            if (!a) return;
            var href = a.getAttribute('href');
            if (!href || !href.includes('#')) return;

            var parts = href.split('#');
            var pagePart = parts[0];
            var hash = '#' + parts[1];

            // Verifica se aponta para a mesma página (ou só o hash)
            var currentPath = window.location.pathname;
            var isSamePage = pagePart === '' || pagePart === currentPath ||
                             pagePart === window.location.origin + currentPath;

            // Extrai apenas o pathname se for URL absoluta
            try {
                if (pagePart.startsWith('http')) {
                    var url = new URL(pagePart);
                    isSamePage = url.pathname === currentPath;
                }
            } catch(ex) {}

            if (isSamePage) {
                var target = document.querySelector(hash);
                if (target) {
                    e.preventDefault();
                    // Fecha sidebar mobile se estiver aberta
                    var sidebar = document.getElementById('mobile-sidebar');
                    if (sidebar && sidebar.style.transform !== 'translateX(-100%)') {
                        sidebar.style.transform = 'translateX(-100%)';
                        var overlay = document.getElementById('mobile-overlay');
                        if (overlay) { overlay.style.opacity = '0'; setTimeout(function(){ overlay.style.display = 'none'; overlay.style.pointerEvents = 'none'; }, 350); }
                        document.body.style.overflow = '';
                    }
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    history.pushState(null, '', hash);
                }
            }
        });
    })();
    </script>
    <!-- Botão Voltar ao Topo (site) -->
    <button id="back-to-top" title="Voltar ao topo" aria-label="Voltar ao topo"
        style="display:none;position:fixed;bottom:96px;right:16px;z-index:40;width:44px;height:44px;border-radius:50%;background:#15803d;color:#fff;border:none;cursor:pointer;align-items:center;justify-content:center;box-shadow:0 4px 14px rgba(0,0,0,.25);transition:all .3s ease;">
        <svg style="width:20px;height:20px;margin:auto;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/></svg>
    </button>
    <style>
        #back-to-top { display:none !important; }
        #back-to-top.show { display:flex !important; }
        #back-to-top:hover { background:#166534; transform:scale(1.1); }
        @media(min-width:1024px) { #back-to-top { bottom:32px !important; right:24px !important; } }
    </style>

    <!-- Widget de Suporte Flutuante -->
    @php $supportMembers = \App\Models\TeamMember::where('support_active', true)->where('active', true)->orderBy('order')->get(); @endphp
    @if($supportMembers->count() > 0)
    <style>
        #sup-widget{position:fixed;bottom:96px;left:16px;z-index:40}
        @media(min-width:1024px){#sup-widget{bottom:32px;left:24px}}
        #sup-box{display:none;transform-origin:bottom left;position:absolute;bottom:68px;left:0;width:18rem;background:#fff;border-radius:1rem;box-shadow:0 25px 50px -12px rgba(0,0,0,.25);border:1px solid #f3f4f6;overflow:hidden}
        @media(min-width:640px){#sup-box{width:20rem}}
        #sup-box-header{background:linear-gradient(to right,#15803d,#16a34a);padding:.75rem 1rem;display:flex;align-items:center;justify-content:space-between}
        #sup-box-header p.sup-title{font-weight:700;color:#fff;font-size:.875rem;margin:0}
        #sup-box-header p.sup-sub{color:#bbf7d0;font-size:.75rem;margin:0}
        .sup-close-btn{color:#bbf7d0;padding:4px;border-radius:50%;border:none;background:transparent;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background .15s,color .15s}
        .sup-close-btn:hover{background:rgba(255,255,255,.15);color:#fff}
        .sup-members{max-height:18rem;overflow-y:auto}
        .sup-member{display:flex;align-items:center;gap:.75rem;padding:.75rem 1rem;transition:background .15s;border-bottom:1px solid #f9fafb}
        .sup-member:last-child{border-bottom:none}
        .sup-member:hover{background:#f9fafb}
        .sup-avatar{width:40px;height:40px;border-radius:50%;object-fit:cover;flex-shrink:0}
        .sup-avatar-placeholder{width:40px;height:40px;border-radius:50%;background:#dcfce7;display:flex;align-items:center;justify-content:center;flex-shrink:0;color:#15803d;font-weight:700;font-size:.875rem}
        .sup-info{flex:1;min-width:0}
        .sup-name{font-weight:600;color:#1f2937;font-size:.875rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;margin:0}
        .sup-role{color:#6b7280;font-size:.75rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;margin:0}
        .sup-actions{display:flex;align-items:center;gap:6px;flex-shrink:0}
        .sup-action-btn{width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;border:none;cursor:pointer;text-decoration:none;transition:background .15s;box-shadow:0 1px 3px rgba(0,0,0,.1)}
        .sup-wa{background:#22c55e}.sup-wa:hover{background:#16a34a}
        .sup-tel{background:#3b82f6}.sup-tel:hover{background:#2563eb}
        .sup-action-btn svg{width:16px;height:16px}
        .sup-footer{padding:.625rem 1rem;background:#f9fafb;border-top:1px solid #f3f4f6;text-align:center}
        .sup-footer p{font-size:.75rem;color:#9ca3af;margin:0}
        #sup-fab{position:relative;width:56px;height:56px;border-radius:50%;background:#16a34a;color:#fff;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;box-shadow:0 8px 24px rgba(22,163,74,.4);transition:background .2s,transform .2s}
        #sup-fab:hover{background:#15803d;transform:scale(1.1)}
        #sup-fab svg{width:24px;height:24px}
        .sup-badge{position:absolute;top:-4px;right:-4px;width:18px;height:18px;background:#ef4444;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:10px;font-weight:700;animation:supPulse 2s ease-in-out infinite}
        @@keyframes supPulse{0%,100%{opacity:1}50%{opacity:.6}}
    </style>
    <div id="sup-widget">
        <!-- Caixa de membros (abre/fecha) -->
        <div id="sup-box">
            <div id="sup-box-header">
                <div>
                    <p class="sup-title">Suporte ISSM</p>
                    <p class="sup-sub">Selecione com quem deseja falar</p>
                </div>
                <button id="sup-close" class="sup-close-btn" aria-label="Fechar">
                    <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="sup-members">
                @foreach($supportMembers as $sm)
                <div class="sup-member">
                    @if($sm->photo)
                    <img src="{{ asset('media/'.$sm->photo) }}" alt="{{ $sm->name }}" class="sup-avatar">
                    @else
                    <div class="sup-avatar-placeholder">{{ strtoupper(mb_substr($sm->name,0,1)) }}</div>
                    @endif
                    <div class="sup-info">
                        <p class="sup-name">{{ $sm->name }}</p>
                        <p class="sup-role">{{ $sm->role }}</p>
                    </div>
                    <div class="sup-actions">
                        @if($sm->whatsapp)
                        @php $wa = preg_replace('/\D/', '', $sm->whatsapp); @endphp
                        <a href="https://wa.me/55{{ $wa }}" target="_blank" rel="noopener" title="WhatsApp de {{ $sm->name }}" class="sup-action-btn sup-wa">
                            <svg fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </a>
                        @endif
                        @if($sm->phone_support)
                        <a href="tel:{{ preg_replace('/\D/', '', $sm->phone_support) }}" title="Ligar para {{ $sm->name }}" class="sup-action-btn sup-tel">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            <div class="sup-footer">
                <p>Horário comercial: seg–sex, 8h–18h</p>
            </div>
        </div>
        <!-- Botão principal -->
        <button id="sup-fab" title="Suporte ISSM" aria-label="Abrir suporte">
            <svg id="sup-icon-open" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            <svg id="sup-icon-close" style="display:none;width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            <span class="sup-badge">{{ $supportMembers->count() }}</span>
        </button>
    </div>
    @endif

    {{-- ── Material 3 Bottom Navigation Bar ── --}}
    <nav id="bottom-nav"
         style="display:none;position:fixed;bottom:0;left:0;right:0;z-index:30;background:#fff;border-top:1px solid #e5e7eb;box-shadow:0 -2px 12px rgba(0,0,0,.07);padding-bottom:env(safe-area-inset-bottom,0px);">
        <div style="display:grid;grid-template-columns:repeat(5,1fr);height:64px;">

            <a href="{{ route('home') }}" data-nav-path="/"
               class="nav-item flex flex-col items-center justify-center gap-0.5 text-gray-500" style="text-decoration:none;">
                <div class="nav-pill"></div>
                <svg class="w-5 h-5 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span class="text-xs font-medium relative z-10">Início</span>
            </a>

            <a href="{{ route('projects.index') }}" data-nav-path="/projetos"
               class="nav-item flex flex-col items-center justify-center gap-0.5 text-gray-500" style="text-decoration:none;">
                <div class="nav-pill"></div>
                <svg class="w-5 h-5 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <span class="text-xs font-medium relative z-10">Projetos</span>
            </a>

            {{-- FAB central – Contato --}}
            <a href="{{ route('home') }}#contato"
               class="nav-item flex flex-col items-center justify-center" style="text-decoration:none;position:relative;">
                <div style="width:52px;height:52px;border-radius:16px;background:#15803d;box-shadow:0 3px 12px rgba(21,128,61,.45);display:flex;align-items:center;justify-content:center;margin-top:-14px;transition:transform .15s ease,box-shadow .15s ease;" id="fab-contact">
                    <svg style="width:22px;height:22px;color:#fff;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <span class="text-xs font-semibold relative z-10" style="color:#15803d;margin-top:2px;">Contato</span>
            </a>

            <a href="{{ route('news.index') }}" data-nav-path="/noticias"
               class="nav-item flex flex-col items-center justify-center gap-0.5 text-gray-500" style="text-decoration:none;">
                <div class="nav-pill"></div>
                <svg class="w-5 h-5 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                <span class="text-xs font-medium relative z-10">Notícias</span>
            </a>

            <button id="mobile-menu-btn-bottom"
                    class="nav-item flex flex-col items-center justify-center gap-0.5 text-gray-500 w-full" style="background:none;border:none;cursor:pointer;" aria-label="Mais opções">
                <div class="nav-pill"></div>
                <svg class="w-5 h-5 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <span class="text-xs font-medium relative z-10">Menu</span>
            </button>

        </div>
    </nav>

    <script>
        (function() {
            var btnTop    = document.getElementById('mobile-menu-btn');
            var btnBottom = document.getElementById('mobile-menu-btn-bottom');
            var closeBtn  = document.getElementById('mobile-menu-close');
            var overlay   = document.getElementById('mobile-overlay');
            var sidebar   = document.getElementById('mobile-sidebar');
            var isOpen    = false;

            function openSidebar() {
                if (isOpen) return;
                isOpen = true;
                overlay.style.display = 'block';
                overlay.style.pointerEvents = 'auto';
                requestAnimationFrame(function() {
                    requestAnimationFrame(function() {
                        overlay.style.opacity   = '0.5';
                        sidebar.style.transform = 'translateX(0)';
                    });
                });
                document.body.style.overflow = 'hidden';
            }
            function closeSidebar() {
                if (!isOpen) return;
                isOpen = false;
                sidebar.style.transform = 'translateX(-100%)';
                overlay.style.opacity   = '0';
                document.body.style.overflow = '';
                setTimeout(function() {
                    overlay.style.display = 'none';
                    overlay.style.pointerEvents = 'none';
                }, 350);
            }

            /* Tap handler com debounce para evitar duplo disparo touch+click */
            function onTap(el, fn) {
                if (!el) return;
                var busy = false;
                function run(e) {
                    if (busy) return;
                    busy = true;
                    if (e.cancelable) e.preventDefault();
                    e.stopPropagation();
                    fn();
                    setTimeout(function() { busy = false; }, 400);
                }
                el.addEventListener('touchend', run, { passive: false });
                el.addEventListener('click', run);
            }

            onTap(btnTop, openSidebar);
            onTap(btnBottom, openSidebar);
            onTap(closeBtn, closeSidebar);
            onTap(overlay, closeSidebar);

            // Marcar item ativo na bottom nav e no sidebar
            var path = window.location.pathname;
            // Bottom nav
            document.querySelectorAll('#bottom-nav .nav-item[data-nav-path]').forEach(function(el) {
                var p = el.getAttribute('data-nav-path');
                if (path === p || (p !== '/' && path.startsWith(p))) {
                    el.classList.add('active-nav');
                }
            });
            // Sidebar
            document.querySelectorAll('#mobile-sidebar .md-nav-item[data-nav-path]').forEach(function(el) {
                var p = el.getAttribute('data-nav-path');
                if (path === p || (p !== '/' && path.startsWith(p))) {
                    el.classList.add('is-active');
                }
            });
        })();
    </script>
    <script>
    // ═══════════════════════════════════════════
    // PRELOADER de transição de página
    // ═══════════════════════════════════════════
    (function() {
        var preloader = document.getElementById('page-preloader');
        if (!preloader) return;
        var safetyTimer = null;

        function hidePreloader() {
            if (safetyTimer) { clearTimeout(safetyTimer); safetyTimer = null; }
            preloader.style.opacity = '0';
            preloader.style.pointerEvents = 'none';
        }

        // Ocultar ao carregar a página
        if (document.readyState === 'complete') {
            hidePreloader();
        } else {
            window.addEventListener('load', hidePreloader);
            setTimeout(hidePreloader, 3000);
        }

        // Ocultar também ao restaurar página do bfcache (back-forward cache)
        window.addEventListener('pageshow', function(e) {
            if (e.persisted) hidePreloader();
        });

        // Mostrar ao navegar para nova página (apenas links normais, não âncoras)
        document.addEventListener('click', function(e) {
            var a = e.target.closest('a[href]');
            if (!a || e.ctrlKey || e.metaKey || e.shiftKey || a.target === '_blank') return;
            var href = a.getAttribute('href');
            if (!href || href.startsWith('#') || href.startsWith('mailto:') || href.startsWith('tel:') || href.startsWith('javascript:')) return;

            // Verifica se é link de âncora para mesma página
            var parts = href.split('#');
            var pagePart = parts[0];
            // Normaliza: remove trailing slash e compara
            var currentPath = window.location.pathname.replace(/\/$/, '') || '/';
            var targetPath = pagePart.replace(/\/$/, '') || '/';
            // Extrai pathname de URLs absolutas
            if (targetPath.startsWith('http')) {
                try { targetPath = new URL(targetPath).pathname.replace(/\/$/, '') || '/'; } catch(ex) {}
            }
            var isAnchorOnly = pagePart === '' || targetPath === currentPath;
            var isExternal = href.startsWith('http') && !href.includes(window.location.hostname);
            if (isAnchorOnly || isExternal) return;

            // Link para nova página interna → mostrar preloader com timeout de segurança
            preloader.style.pointerEvents = 'all';
            preloader.style.opacity = '1';
            if (safetyTimer) clearTimeout(safetyTimer);
            safetyTimer = setTimeout(hidePreloader, 5000);
        });
    })();

    // ═══════════════════════════════════════════
    // BACK TO TOP (site)
    // ═══════════════════════════════════════════
    (function() {
        var btn = document.getElementById('back-to-top');
        if (!btn) return;
        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                btn.classList.add('show');
            } else {
                btn.classList.remove('show');
            }
        }, { passive: true });
        btn.addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    })();

    // ═══════════════════════════════════════════
    // WIDGET DE SUPORTE FLUTUANTE
    // ═══════════════════════════════════════════
    (function() {
        var btn = document.getElementById('sup-fab');
        var box = document.getElementById('sup-box');
        var closeBtn = document.getElementById('sup-close');
        var iconOpen = document.getElementById('sup-icon-open');
        var iconClose = document.getElementById('sup-icon-close');
        if (!btn || !box) return;

        var isOpen = false;

        function openBox() {
            box.style.display = 'block';
            box.style.animation = 'supIn 0.25s ease forwards';
            if (iconOpen) iconOpen.style.display = 'none';
            if (iconClose) iconClose.style.display = 'block';
            isOpen = true;
        }
        function closeBox() {
            box.style.display = 'none';
            if (iconOpen) iconOpen.style.display = 'block';
            if (iconClose) iconClose.style.display = 'none';
            isOpen = false;
        }

        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            isOpen ? closeBox() : openBox();
        });
        closeBtn && closeBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            closeBox();
        });
        document.addEventListener('click', function(e) {
            var widget = document.getElementById('sup-widget');
            if (isOpen && widget && !widget.contains(e.target)) closeBox();
        });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && isOpen) closeBox();
        });
    })();
    </script>
    <style>
        @@keyframes supIn {
            from { opacity:0; transform:scale(0.9) translateY(8px); }
            to { opacity:1; transform:scale(1) translateY(0); }
        }
    </style>
    @stack('scripts')
</body>
</html>

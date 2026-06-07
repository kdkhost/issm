<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $siteLogo    = \App\Models\Setting::get('site_logo');
        $siteFavicon = \App\Models\Setting::get('site_favicon');
        $siteName    = \App\Models\Setting::get('site_name', 'ISSM');
        $siteDesc    = \App\Models\Setting::get('site_description', 'Instituto Socioambiental Serra do Mendanha');
        $facebook    = \App\Models\Setting::get('social_facebook');
        $instagram   = \App\Models\Setting::get('social_instagram');
        $youtube     = \App\Models\Setting::get('social_youtube');
        $whatsapp    = \App\Models\Setting::get('social_whatsapp');
        $contactEmail= \App\Models\Setting::get('contact_email');
    @endphp
    <title>Acesso Administrativo — {{ $siteName }}</title>
    @if($siteFavicon)
    <link rel="icon" type="image/x-icon" href="{{ asset('media/' . $siteFavicon) }}">
    @endif
    @vite(["resources/css/app.css", "resources/js/app.js"])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; margin: 0; }

        /* ── Layout ── */
        .login-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* ── Painel esquerdo ── */
        .login-left {
            display: none;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 45%;
            flex-shrink: 0;
            background: linear-gradient(160deg, #064e3b 0%, #065f46 40%, #047857 75%, #059669 100%);
            padding: 3rem 3rem 5rem;
            position: relative;
            overflow: hidden;
        }
        @media (min-width: 900px) { .login-left { display: flex; } }

        /* blobs animados */
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(72px);
            opacity: .18;
            animation: blobFloat 14s ease-in-out infinite;
        }
        .blob-1 { width: 420px; height: 420px; background: #34d399; top: -160px; left: -120px; animation-delay: 0s; }
        .blob-2 { width: 320px; height: 320px; background: #6ee7b7; bottom: -100px; right: -80px; animation-delay: -5s; }
        .blob-3 { width: 200px; height: 200px; background: #a7f3d0; top: 40%; left: 50%; animation-delay: -9s; }
        @keyframes blobFloat {
            0%,100% { transform: translateY(0) scale(1); }
            50%      { transform: translateY(-30px) scale(1.07); }
        }

        /* grade de pontos de fundo */
        .login-left::before {
            content: '';
            position: absolute; inset: 0;
            background-image: radial-gradient(rgba(255,255,255,.07) 1px, transparent 1px);
            background-size: 28px 28px;
            pointer-events: none;
        }

        .left-content { position: relative; z-index: 1; display: flex; flex-direction: column; align-items: center; text-align: center; }

        .brand-logo-wrap {
            width: 100px; height: 100px;
            background: rgba(255,255,255,.15);
            backdrop-filter: blur(8px);
            border: 2px solid rgba(255,255,255,.25);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            overflow: hidden;
            margin-bottom: 1.5rem;
            transition: transform .3s;
        }
        .brand-logo-wrap:hover { transform: scale(1.05); }
        .brand-logo-wrap img { width: 100%; height: 100%; object-fit: cover; padding: 0; }
        .brand-logo-wrap .fallback { color: #fff; font-size: 1.4rem; font-weight: 900; }

        .brand-name {
            color: #fff;
            font-size: 1.7rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: .5rem;
        }
        .brand-desc {
            color: rgba(255,255,255,.7);
            font-size: .875rem;
            line-height: 1.6;
            max-width: 280px;
        }

        .left-badge {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(255,255,255,.1);
            border: 1px solid rgba(255,255,255,.15);
            border-radius: 999px;
            padding: 6px 14px;
            color: rgba(255,255,255,.85);
            font-size: .75rem;
            font-weight: 500;
            margin-top: 2rem;
            backdrop-filter: blur(6px);
        }
        .left-badge span.dot { width: 6px; height: 6px; border-radius: 50%; background: #4ade80; box-shadow: 0 0 6px #4ade80; }

        .left-footer { position: absolute; bottom: 2rem; left: 0; right: 0; z-index: 1; display: flex; flex-direction: column; align-items: center; text-align: center; }
        .social-links { display: flex; gap: 10px; margin-bottom: 1.2rem; flex-wrap: wrap; justify-content: center; }
        .social-link {
            width: 38px; height: 38px;
            border-radius: 50%;
            background: rgba(255,255,255,.1);
            border: 1px solid rgba(255,255,255,.15);
            display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,.8);
            transition: background .2s, transform .2s;
            text-decoration: none;
        }
        .social-link:hover { background: rgba(255,255,255,.25); transform: translateY(-2px); }
        .social-link svg { width: 16px; height: 16px; }
        .left-copy { color: rgba(255,255,255,.4); font-size: .72rem; }

        /* ── Painel direito ── */
        .login-right {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #f8fafc;
            padding: 2.5rem 1.5rem;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
        }

        /* topo mobile */
        .mobile-brand {
            display: flex; flex-direction: column; align-items: center;
            margin-bottom: 2rem;
            text-align: center;
        }
        @media (min-width: 900px) { .mobile-brand { display: none; } }
        .mobile-logo-wrap {
            width: 72px; height: 72px;
            background: #fff;
            border-radius: 50%;
            border: 2px solid #d1fae5;
            box-shadow: 0 4px 20px rgba(5,150,105,.15);
            display: flex; align-items: center; justify-content: center;
            overflow: hidden; margin-bottom: .75rem;
        }
        .mobile-logo-wrap img { width: 100%; height: 100%; object-fit: contain; padding: 6px; }
        .mobile-brand h1 { font-size: 1.25rem; font-weight: 800; color: #064e3b; margin: 0 0 .2rem; }
        .mobile-brand p  { font-size: .8rem; color: #6b7280; margin: 0; }

        /* card */
        .form-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,.05), 0 20px 50px -10px rgba(0,0,0,.1);
            padding: 2.25rem 2rem;
            border: 1px solid #e5e7eb;
        }
        .form-card h2 {
            font-size: 1.35rem; font-weight: 700;
            color: #111827; margin: 0 0 .35rem;
        }
        .form-card .subtitle {
            font-size: .82rem; color: #9ca3af; margin: 0 0 1.75rem;
        }

        /* floating label input */
        .field {
            position: relative;
            margin-bottom: 1.1rem;
        }
        .field input {
            width: 100%; border: 1.5px solid #e5e7eb;
            border-radius: 12px;
            padding: 1.2rem 1rem .4rem;
            font-size: .9rem; font-family: 'Inter', sans-serif;
            background: #f9fafb;
            color: #111827;
            outline: none;
            transition: border-color .2s, box-shadow .2s, background .2s;
            appearance: none;
        }
        .field input:focus {
            border-color: #059669;
            box-shadow: 0 0 0 3px rgba(5,150,105,.1);
            background: #fff;
        }
        .field input.has-error { border-color: #ef4444; }
        .field input.has-error:focus { box-shadow: 0 0 0 3px rgba(239,68,68,.1); }
        .field label {
            position: absolute;
            left: 1rem; top: 50%; transform: translateY(-50%);
            font-size: .9rem; color: #9ca3af; font-weight: 500;
            pointer-events: none;
            transition: top .18s, font-size .18s, color .18s, transform .18s;
            background: transparent;
        }
        .field input:focus ~ label,
        .field input:not(:placeholder-shown) ~ label {
            top: .55rem; transform: none;
            font-size: .7rem; color: #059669;
        }
        .field-error { font-size: .72rem; color: #ef4444; margin-top: .3rem; padding-left: .25rem; }

        /* password wrapper */
        .pwd-wrap { position: relative; }
        .pwd-wrap input { padding-right: 3rem; }
        .pwd-toggle {
            position: absolute; right: .75rem; top: 50%; transform: translateY(-50%);
            background: none; border: none; padding: 4px; cursor: pointer;
            color: #9ca3af; display: flex; align-items: center; justify-content: center;
            border-radius: 6px; transition: color .2s, background .2s;
            line-height: 0;
        }
        .pwd-toggle:hover { color: #059669; background: #f0fdf4; }
        .pwd-toggle svg { width: 18px; height: 18px; }
        /* tooltip */
        .pwd-toggle .tip {
            position: absolute; bottom: calc(100% + 10px); left: 50%; transform: translateX(-50%);
            background: #1f2937; color: #f9fafb;
            font-size: .72rem; font-weight: 500; white-space: nowrap;
            padding: 6px 12px; border-radius: 8px;
            pointer-events: none; opacity: 0;
            transition: opacity .18s, transform .18s;
            box-shadow: 0 8px 24px rgba(0,0,0,.25);
            letter-spacing: .01em;
            line-height: 1.4;
        }
        .pwd-toggle:hover .tip { opacity: 1; transform: translateX(-50%) translateY(-2px); }
        .pwd-toggle .tip::after {
            content: '';
            position: absolute; top: 100%; left: 50%; transform: translateX(-50%);
            border: 5px solid transparent; border-top-color: #1f2937;
        }

        /* remember */
        .remember-row {
            display: flex; align-items: center; gap: 8px;
            margin-bottom: 1.4rem;
        }
        .remember-row input[type="checkbox"] {
            width: 16px; height: 16px; accent-color: #059669;
            border-radius: 4px; cursor: pointer;
        }
        .remember-row label { font-size: .83rem; color: #6b7280; cursor: pointer; user-select: none; }

        /* botão entrar */
        .btn-login {
            width: 100%; padding: .85rem;
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: #fff; font-size: .95rem; font-weight: 700;
            border: none; border-radius: 12px; cursor: pointer;
            position: relative; overflow: hidden;
            transition: transform .15s, box-shadow .2s;
            box-shadow: 0 4px 14px rgba(5,150,105,.35);
            display: flex; align-items: center; justify-content: center; gap: 8px;
            font-family: 'Inter', sans-serif;
        }
        .btn-login:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(5,150,105,.4); }
        .btn-login:active { transform: translateY(0); }
        .btn-login::after {
            content: '';
            position: absolute; inset: 0;
            background: rgba(255,255,255,0);
            transition: background .2s;
        }
        .btn-login:hover::after { background: rgba(255,255,255,.06); }
        /* ripple */
        .btn-login .ripple-el {
            position: absolute; border-radius: 50%;
            background: rgba(255,255,255,.3);
            transform: scale(0); animation: ripple .5s linear;
            pointer-events: none;
        }
        @keyframes ripple { to { transform: scale(4); opacity: 0; } }
        /* spinner */
        .btn-login .spinner { display: none; width: 18px; height: 18px; border: 2px solid rgba(255,255,255,.4); border-top-color: #fff; border-radius: 50%; animation: spin .7s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* divider */
        .divider { height: 1px; background: #f3f4f6; margin: 1.25rem 0; }

        /* voltar ao site */
        .btn-back {
            width: 100%; padding: .8rem;
            background: transparent; border: 1.5px solid #e5e7eb;
            border-radius: 12px; color: #374151;
            font-size: .875rem; font-weight: 600;
            cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;
            transition: border-color .2s, background .2s, color .2s;
            text-decoration: none; font-family: 'Inter', sans-serif;
        }
        .btn-back:hover { border-color: #059669; background: #f0fdf4; color: #047857; }
        .btn-back svg { width: 15px; height: 15px; }

        /* alertas */
        .alert {
            border-radius: 10px; padding: .75rem 1rem;
            font-size: .82rem; display: flex; align-items: flex-start; gap: .5rem;
            margin-bottom: 1.25rem;
        }
        .alert-error   { background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; }
        .alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
        .alert svg { width: 16px; height: 16px; flex-shrink: 0; margin-top: 1px; }

        /* footer do card */
        .card-footer { text-align: center; margin-top: 1.5rem; }
        .card-footer p { font-size: .75rem; color: #9ca3af; margin: 0; }

        /* animação de entrada */
        .form-card { animation: slideUp .4s cubic-bezier(.16,1,.3,1) both; }
        @keyframes slideUp { from { opacity:0; transform: translateY(20px); } to { opacity:1; transform: none; } }
    </style>
</head>
<body>
<div class="login-wrapper">

    {{-- ══════════════════════════════════════════
         PAINEL ESQUERDO — branding
    ══════════════════════════════════════════ --}}
    <aside class="login-left">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>

        <div class="left-content">
            <div class="brand-logo-wrap">
                @if($siteLogo)
                    <img src="{{ asset('media/' . $siteLogo) }}" alt="{{ $siteName }}">
                @else
                    <span class="fallback">ISSM</span>
                @endif
            </div>
            <div class="brand-name">{{ $siteName }}</div>
            <div class="brand-desc">{{ $siteDesc }}</div>
            <div class="left-badge">
                <span class="dot"></span>
                Painel Administrativo
            </div>
        </div>

        <div class="left-footer">
            @php $hasSocial = $facebook || $instagram || $youtube || $whatsapp; @endphp
            @if($hasSocial)
            <div class="social-links">
                @if($facebook)
                <a href="{{ $facebook }}" target="_blank" rel="noopener" class="social-link" title="Facebook">
                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M22 12A10 10 0 1 0 12 22v-7H9v-3h3V9.5C12 7 13.5 5.5 16 5.5c.8 0 1.7.1 2.5.2V8h-1.4c-1.4 0-1.6.7-1.6 1.6V12h3l-.5 3H15v7a10 10 0 0 0 7-10Z"/></svg>
                </a>
                @endif
                @if($instagram)
                <a href="{{ $instagram }}" target="_blank" rel="noopener" class="social-link" title="Instagram">
                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.2c3.2 0 3.6 0 4.8.1 3.3.1 4.8 1.7 4.9 4.9.1 1.3.1 1.6.1 4.8s0 3.6-.1 4.8c-.1 3.2-1.7 4.8-4.9 4.9-1.2.1-1.6.1-4.8.1s-3.6 0-4.8-.1c-3.2-.1-4.8-1.7-4.9-4.9C2.2 15.6 2.2 15.2 2.2 12s0-3.6.1-4.8C2.4 3.9 4 2.3 7.2 2.2c1.2-.1 1.6-.1 4.8-.1ZM12 4a8 8 0 1 0 0 16A8 8 0 0 0 12 4Zm0 13.2a5.2 5.2 0 1 1 0-10.4 5.2 5.2 0 0 1 0 10.4ZM18.4 5.6a1.2 1.2 0 1 0 0-2.4 1.2 1.2 0 0 0 0 2.4Z"/></svg>
                </a>
                @endif
                @if($youtube)
                <a href="{{ $youtube }}" target="_blank" rel="noopener" class="social-link" title="YouTube">
                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M21.8 8s-.2-1.4-.8-2c-.8-.8-1.7-.8-2.1-.9C16.2 5 12 5 12 5s-4.2 0-6.9.1c-.4 0-1.3.1-2.1.9-.6.6-.8 2-.8 2S2 9.6 2 11.2v1.5c0 1.6.2 3.2.2 3.2s.2 1.4.8 2c.8.8 1.8.8 2.3.9C6.8 19 12 19 12 19s4.2 0 6.9-.2c.4 0 1.3-.1 2.1-.9.6-.6.8-2 .8-2s.2-1.6.2-3.2v-1.5C22 9.6 21.8 8 21.8 8ZM10 14.5v-5l5.5 2.5-5.5 2.5Z"/></svg>
                </a>
                @endif
                @if($whatsapp)
                <a href="https://wa.me/{{ preg_replace('/\D/','',$whatsapp) }}" target="_blank" rel="noopener" class="social-link" title="WhatsApp">
                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M17.5 14.4c-.3-.1-1.7-.8-2-1-.3-.1-.5-.1-.7.1-.2.3-.8 1-.9 1.2-.2.2-.3.2-.6.1-.3-.2-1.3-.5-2.4-1.5-.9-.8-1.5-1.8-1.6-2.1-.2-.3 0-.5.1-.6l.5-.5c.1-.2.2-.3.3-.5.1-.2 0-.4-.1-.5-.1-.1-.7-1.6-.9-2.2-.3-.6-.6-.5-.8-.5H7.8c-.2 0-.5.1-.8.4C6.7 7.4 6 8.1 6 9.5c0 1.3 1 2.6 1.1 2.8.1.2 1.9 3 4.7 4.2.7.3 1.2.4 1.6.5.7.2 1.4.2 1.9.1.6-.1 1.7-.7 2-1.4.2-.6.2-1.2.1-1.3Z"/><path d="M12.1 2C6.5 2 2 6.5 2 12.1c0 1.9.5 3.7 1.5 5.3L2 22l4.8-1.5c1.5.8 3.2 1.3 5 1.3C17.5 21.8 22 17.3 22 11.7 22 6.2 17.6 2 12.1 2Zm0 18.1c-1.7 0-3.3-.5-4.6-1.3l-.3-.2-3.1 1 1-3-.2-.3a8.3 8.3 0 0 1-1.3-4.5c0-4.6 3.7-8.3 8.3-8.3 4.6 0 8.3 3.7 8.3 8.3C20.4 16.4 16.7 20.1 12.1 20.1Z"/></svg>
                </a>
                @endif
            </div>
            @endif
            <p class="left-copy">&copy; {{ date('Y') }} {{ $siteName }}. Todos os direitos reservados.</p>
        </div>
    </aside>

    {{-- ══════════════════════════════════════════
         PAINEL DIREITO — formulário
    ══════════════════════════════════════════ --}}
    <main class="login-right">
        <div class="login-card">

            {{-- Logo mobile --}}
            <div class="mobile-brand">
                <div class="mobile-logo-wrap">
                    @if($siteLogo)
                        <img src="{{ asset('media/' . $siteLogo) }}" alt="{{ $siteName }}">
                    @else
                        <span style="font-weight:900;color:#064e3b;font-size:1rem;">ISSM</span>
                    @endif
                </div>
                <h1>{{ $siteName }}</h1>
                <p>Painel Administrativo</p>
            </div>

            <div class="form-card">
                <h2>Bem-vindo de volta</h2>
                <p class="subtitle">Insira suas credenciais para acessar o painel</p>

                @if(session('error'))
                <div class="alert alert-error">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
                @endif
                @if(session('success'))
                <div class="alert alert-success">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
                @endif

                <form method="POST" action="{{ route('login') }}" id="login-form" novalidate>
                    @csrf

                    {{-- E-mail --}}
                    <div class="field">
                        <input type="email" name="email" id="email"
                               value="{{ old('email') }}"
                               placeholder=" " required autofocus
                               class="{{ $errors->has('email') ? 'has-error' : '' }}">
                        <label for="email">E-mail</label>
                        @error('email')
                        <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Senha --}}
                    <div class="field">
                        <div class="pwd-wrap">
                            <input type="password" name="password" id="password"
                                   placeholder=" " required
                                   class="{{ $errors->has('password') ? 'has-error' : '' }}">
                            <label for="password">Senha</label>
                            <button type="button" class="pwd-toggle" id="pwd-toggle" tabindex="-1">
                                <span class="tip" id="pwd-tip">Mostrar senha</span>
                                <svg id="eye-show" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg id="eye-hide" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display:none">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.97 9.97 0 012.12-3.368M6.53 6.533A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.966 9.966 0 01-4.073 5.192M3 3l18 18"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                        <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Lembrar --}}
                    <div class="remember-row">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember">Lembrar de mim</label>
                    </div>

                    {{-- Entrar --}}
                    <button type="submit" class="btn-login" id="btn-login">
                        <span class="spinner" id="spinner"></span>
                        <span id="btn-text">Entrar</span>
                        <svg id="btn-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </button>

                    <div class="divider"></div>

                    {{-- Voltar ao site --}}
                    <a href="{{ url('/') }}" class="btn-back">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Voltar ao site
                    </a>
                </form>
            </div>

            {{-- Rodapé --}}
            <div class="card-footer">
                <p>&copy; {{ date('Y') }} {{ $siteName }}. Todos os direitos reservados.</p>
            </div>
        </div>
    </main>
</div>

<script>
    // ── Toggle senha ──
    const pwdInput  = document.getElementById('password');
    const pwdToggle = document.getElementById('pwd-toggle');
    const eyeShow   = document.getElementById('eye-show');
    const eyeHide   = document.getElementById('eye-hide');
    const pwdTip    = document.getElementById('pwd-tip');

    pwdToggle.addEventListener('click', () => {
        const visible = pwdInput.type === 'text';
        pwdInput.type = visible ? 'password' : 'text';
        eyeShow.style.display = visible ? '' : 'none';
        eyeHide.style.display = visible ? 'none' : '';
        pwdTip.textContent    = visible ? 'Mostrar senha' : 'Ocultar senha';
        pwdInput.focus();
    });

    // ── Spinner no submit ──
    const form    = document.getElementById('login-form');
    const spinner = document.getElementById('spinner');
    const btnText = document.getElementById('btn-text');
    const btnIcon = document.getElementById('btn-icon');
    const btnLogin= document.getElementById('btn-login');

    form.addEventListener('submit', () => {
        spinner.style.display = 'block';
        btnText.textContent   = 'Entrando…';
        btnIcon.style.display = 'none';
        btnLogin.disabled     = true;
    });

    // ── Ripple no botão ──
    btnLogin.addEventListener('click', function(e) {
        const r  = document.createElement('span');
        const d  = Math.max(this.clientWidth, this.clientHeight);
        const rect = this.getBoundingClientRect();
        r.className = 'ripple-el';
        r.style.cssText = `width:${d}px;height:${d}px;left:${e.clientX-rect.left-d/2}px;top:${e.clientY-rect.top-d/2}px`;
        this.appendChild(r);
        r.addEventListener('animationend', () => r.remove());
    });
</script>
</body>
</html>

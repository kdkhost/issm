<!DOCTYPE html>
<html lang="pt-BR" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if(session('success'))<meta name="flash-success" content="{{ session('success') }}">@endif
    @if(session('error'))<meta name="flash-error" content="{{ session('error') }}">@endif
    <title>@yield('title', 'Painel Admin') - ISSM Admin</title>
    @php $siteFavicon = \App\Models\Setting::get('site_favicon'); @endphp
    @if($siteFavicon)
    <link rel="icon" type="image/x-icon" href="{{ asset('media/' . $siteFavicon) }}">
    <link rel="apple-touch-icon" href="{{ asset('media/' . $siteFavicon) }}">
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css">
    <style>
        .note-editor.note-frame { border-radius: 8px; border-color: #d1d5db; overflow: hidden; }
        .note-toolbar { background: #f9fafb !important; border-bottom: 1px solid #e5e7eb !important; padding: 6px 8px !important; }
        .note-editable { min-height: 220px; font-family: 'Inter', sans-serif; font-size: 14px; line-height: 1.7; padding: 14px 16px !important; }
        [data-theme="dark"] .note-editor.note-frame { border-color: #4b5563 !important; }
        [data-theme="dark"] .note-toolbar { background: #1f2937 !important; border-color: #374151 !important; }
        [data-theme="dark"] .note-editable { background: #374151 !important; color: #f9fafb !important; }
        [data-theme="dark"] .note-statusbar { background: #1f2937 !important; border-color: #374151 !important; }
        [data-theme="dark"] .note-btn { background: #374151 !important; color: #f9fafb !important; border-color: #4b5563 !important; }
    </style>
    <!-- Prevent dark mode flash -->
    <script>
        (function(){var t=localStorage.getItem('issm-theme')||'light';document.documentElement.setAttribute('data-theme',t);if(t==='dark')document.documentElement.classList.add('dark-mode');})();
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }

        /* ═══ DARK MODE ═══ */
        [data-theme="dark"] { color-scheme: dark; }
        [data-theme="dark"] body { background-color: #111827 !important; color: #f9fafb !important; }
        [data-theme="dark"] .bg-gray-100 { background-color: #111827 !important; }
        [data-theme="dark"] .bg-white { background-color: #1f2937 !important; }
        [data-theme="dark"] .bg-gray-50 { background-color: #1a2535 !important; }
        [data-theme="dark"] .text-gray-800, [data-theme="dark"] .text-gray-900 { color: #f9fafb !important; }
        [data-theme="dark"] .text-gray-700 { color: #e5e7eb !important; }
        [data-theme="dark"] .text-gray-600 { color: #d1d5db !important; }
        [data-theme="dark"] .text-gray-500 { color: #9ca3af !important; }
        [data-theme="dark"] .border-gray-100, [data-theme="dark"] .border-gray-200, [data-theme="dark"] .border-gray-300 { border-color: #374151 !important; }
        [data-theme="dark"] .shadow-sm { box-shadow: 0 1px 4px rgba(0,0,0,0.4) !important; }
        [data-theme="dark"] .shadow-md { box-shadow: 0 2px 8px rgba(0,0,0,0.5) !important; }
        [data-theme="dark"] input:not([type="checkbox"]):not([type="radio"]):not([type="submit"]),
        [data-theme="dark"] textarea,
        [data-theme="dark"] select {
            background-color: #374151 !important;
            border-color: #4b5563 !important;
            color: #f9fafb !important;
        }
        [data-theme="dark"] tr { border-color: #374151 !important; }
        [data-theme="dark"] tr:hover td { background-color: rgba(255,255,255,0.04) !important; }
        [data-theme="dark"] thead { background-color: #1a2535 !important; }
        [data-theme="dark"] .divide-y > * { border-color: #374151 !important; }
        [data-theme="dark"] .bg-green-50 { background-color: rgba(22,163,74,0.15) !important; }
        [data-theme="dark"] .bg-red-50 { background-color: rgba(239,68,68,0.15) !important; }
        [data-theme="dark"] .bg-orange-100 { background-color: rgba(251,146,60,0.2) !important; }
        [data-theme="dark"] .rounded-xl { border: 1px solid #2d3748; }
        [data-theme="dark"] .hover\:bg-gray-50:hover { background-color: rgba(255,255,255,0.04) !important; }
        [data-theme="dark"] .text-green-700 { color: #4ade80 !important; }
        [data-theme="dark"] .text-red-600 { color: #f87171 !important; }
        [data-theme="dark"] .text-blue-600 { color: #60a5fa !important; }

        /* ═══ DRAG & DROP ZONE ═══ */
        .drop-zone {
            border: 2px dashed #d1d5db;
            border-radius: 12px;
            padding: 28px 20px;
            text-align: center;
            cursor: pointer;
            transition: border-color 0.2s, background-color 0.2s;
            background-color: #f9fafb;
            position: relative;
            min-height: 100px;
        }
        .drop-zone:hover, .drop-zone--over {
            border-color: #16a34a;
            background-color: #f0fdf4;
        }
        [data-theme="dark"] .drop-zone { border-color: #4b5563; background-color: #374151; }
        [data-theme="dark"] .drop-zone:hover, [data-theme="dark"] .drop-zone--over { border-color: #22c55e; background-color: rgba(34,197,94,0.08); }
        .drop-zone__input { position: absolute; opacity: 0; width: 1px; height: 1px; pointer-events: none; }
        .drop-zone__preview { text-align: center; }
        .drop-zone__remove { cursor: pointer; }
        .drop-zone--uploading::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0;
            height: 3px;
            background: linear-gradient(90deg, #16a34a, #4ade80);
            border-radius: 0 0 10px 10px;
            animation: upload-progress 2s ease-in-out forwards;
        }
        @@keyframes upload-progress { from { width: 0; } to { width: 100%; } }

        /* ═══ IMPROVED INPUTS ═══ */
        input[type="text"], input[type="email"], input[type="password"], input[type="number"],
        input[type="date"], input[type="datetime-local"], input[type="url"], input[type="tel"],
        textarea, select {
            transition: border-color 0.2s, box-shadow 0.2s !important;
        }
        input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus,
        input[type="number"]:focus, input[type="date"]:focus, input[type="datetime-local"]:focus,
        input[type="url"]:focus, input[type="tel"]:focus, textarea:focus, select:focus {
            outline: none !important;
            border-color: #16a34a !important;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.12) !important;
        }
        [data-theme="dark"] input:focus, [data-theme="dark"] textarea:focus, [data-theme="dark"] select:focus {
            border-color: #22c55e !important;
            box-shadow: 0 0 0 3px rgba(34,197,94,0.15) !important;
        }

        /* ═══ BADGE / PILL ═══ */
        .badge-green { background-color: #dcfce7; color: #166534; padding: 2px 10px; border-radius: 9999px; font-size: 0.75rem; font-weight: 500; }
        .badge-gray  { background-color: #f3f4f6; color: #4b5563;  padding: 2px 10px; border-radius: 9999px; font-size: 0.75rem; font-weight: 500; }
        [data-theme="dark"] .badge-green { background-color: rgba(22,163,74,0.2); color: #4ade80; }
        [data-theme="dark"] .badge-gray  { background-color: #374151; color: #9ca3af; }

        /* ═══ SWEETALERT2 CUSTOM ═══ */
        .swal2-popup { border-radius: 16px !important; font-family: 'Inter', sans-serif !important; }
        [data-theme="dark"] .swal2-popup { background: #1f2937 !important; color: #f9fafb !important; }
        [data-theme="dark"] .swal2-title { color: #f9fafb !important; }
        [data-theme="dark"] .swal2-html-container { color: #d1d5db !important; }

        /* ═══ THEME TOGGLE BUTTON ═══ */
        #theme-toggle {
            width: 36px; height: 36px;
            border-radius: 50%;
            border: none;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            background: #f3f4f6;
            color: #4b5563;
            transition: background 0.2s, color 0.2s;
        }
        #theme-toggle:hover { background: #e5e7eb; }
        [data-theme="dark"] #theme-toggle { background: #374151; color: #fbbf24; }
        [data-theme="dark"] #theme-toggle:hover { background: #4b5563; }

        /* ═══ SIDEBAR DARK MODE ═══ */
        [data-theme="dark"] .admin-sidebar {
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%) !important;
        }
        [data-theme="dark"] .admin-sidebar .border-green-700 { border-color: #1e3a5f !important; }
        [data-theme="dark"] .admin-sidebar .text-green-100 { color: #cbd5e1 !important; }
        [data-theme="dark"] .admin-sidebar .text-green-300 { color: #94a3b8 !important; }
        [data-theme="dark"] .admin-sidebar .text-green-400 { color: #475569 !important; }
        [data-theme="dark"] .admin-sidebar p.text-white,
        [data-theme="dark"] .admin-sidebar span.text-white { color: #f1f5f9 !important; }
        /* Active nav item in dark mode */
        [data-theme="dark"] .admin-sidebar a.bg-green-700 {
            background-color: rgba(59,130,246,0.18) !important;
            border-left: 3px solid #60a5fa;
            color: #bfdbfe !important;
        }
        /* Hover nav items in dark */
        [data-theme="dark"] .admin-sidebar a:not(.bg-green-700):hover {
            background-color: rgba(255,255,255,0.06) !important;
            color: #f1f5f9 !important;
        }
        /* Avatar accent */
        [data-theme="dark"] .admin-sidebar .bg-green-500 { background-color: #1d4ed8 !important; }
        /* Red badge on dark */
        [data-theme="dark"] .admin-sidebar .bg-red-500 { background-color: #dc2626 !important; }

        /* ═══ TOOLTIPS PERSONALIZADOS ═══ */
        [data-tooltip] { position: relative; }
        [data-tooltip]:not(a):not(button):not(input):not(select) { cursor: help; }
        [data-tooltip]::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: calc(100% + 8px);
            left: 50%;
            transform: translateX(-50%) translateY(4px);
            background: #166534;
            color: #fff;
            font-size: 11.5px;
            font-family: 'Inter', sans-serif;
            font-weight: 500;
            padding: 5px 12px;
            border-radius: 8px;
            white-space: nowrap;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.18s ease, transform 0.18s ease;
            z-index: 9999;
            box-shadow: 0 4px 12px rgba(0,0,0,0.18);
            max-width: 220px;
            text-align: center;
        }
        [data-tooltip]::before {
            content: '';
            position: absolute;
            bottom: calc(100% + 2px);
            left: 50%;
            transform: translateX(-50%);
            border: 5px solid transparent;
            border-top-color: #166534;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.18s ease;
            z-index: 9999;
        }
        [data-tooltip]:hover::after { opacity: 1; transform: translateX(-50%) translateY(0); }
        [data-tooltip]:hover::before { opacity: 1; }
        /* Dark mode tooltip */
        [data-theme="dark"] [data-tooltip]::after {
            background: #22c55e;
            color: #052e16;
            box-shadow: 0 4px 12px rgba(0,0,0,0.35);
        }
        [data-theme="dark"] [data-tooltip]::before { border-top-color: #22c55e; }
        /* Tooltip posição direita */
        [data-tooltip][data-tip-pos="right"]::after {
            bottom: auto; top: 50%;
            left: calc(100% + 10px);
            transform: translateY(-50%) translateX(4px);
        }
        [data-tooltip][data-tip-pos="right"]::before {
            bottom: auto; top: 50%;
            left: calc(100% + 2px);
            transform: translateY(-50%);
            border-top-color: transparent; border-right-color: #166534;
        }
        [data-tooltip][data-tip-pos="right"]:hover::after { transform: translateY(-50%) translateX(0); }
        [data-theme="dark"] [data-tooltip][data-tip-pos="right"]::before { border-right-color: #22c55e; }
        /* Tooltip posição esquerda */
        [data-tooltip][data-tip-pos="left"]::after {
            bottom: auto; top: 50%;
            left: auto; right: calc(100% + 10px);
            transform: translateY(-50%) translateX(-4px);
        }
        [data-tooltip][data-tip-pos="left"]::before {
            bottom: auto; top: 50%;
            left: auto; right: calc(100% + 2px);
            transform: translateY(-50%);
            border-top-color: transparent; border-left-color: #166534;
        }
        [data-tooltip][data-tip-pos="left"]:hover::after { transform: translateY(-50%) translateX(0); }
        [data-theme="dark"] [data-tooltip][data-tip-pos="left"]::before { border-left-color: #22c55e; }

        /* ═══ PLACEHOLDER STYLING ═══ */
        ::placeholder { color: #9ca3af !important; font-size: 0.875rem; font-style: italic; }
        [data-theme="dark"] ::placeholder { color: #6b7280 !important; }

        /* ═══ OVERFLOW GLOBAL ═══ */
        html { overflow-x: clip; max-width: 100vw; }
        body { overflow-x: hidden; max-width: 100vw; width: 100%; }
        /* Footer e wrapper: previne scroll horizontal em mobile */
        footer { overflow: hidden; max-width: 100%; box-sizing: border-box; }
        .flex.h-screen.overflow-hidden { max-width: 100vw; }
        /* Remove scrollbar horizontal do main admin e de toda a página */
        #admin-main-content { overflow-x: hidden !important; }
        #admin-main-content::-webkit-scrollbar:horizontal { display: none !important; height: 0 !important; }
        body::-webkit-scrollbar:horizontal { display: none !important; height: 0 !important; }
        * { scrollbar-width: auto; }
        @media (max-width: 1023px) {
            #admin-main-content::-webkit-scrollbar { width: 4px; }
            #admin-main-content { scrollbar-width: thin; }
        }

        /* ═══ RESPONSIVE TABLES ═══ */
        #admin-main-content .table-responsive,
        #admin-main-content table { max-width: 100%; }
        #admin-main-content > * { max-width: 100%; }
        #admin-main-content img,
        #admin-main-content video,
        #admin-main-content iframe { max-width: 100%; }

        /* ═══ DATATABLE INLINE DETAILS ═══ */
        .dt-toggle svg { transition: transform 0.2s ease; }
        .dt-toggle.dt-open svg { transform: rotate(180deg); }
        .dt-detail { transition: background 0.15s; }
        .dt-detail td { border-top: none !important; }
        tr.hover\:bg-gray-50:hover + .dt-detail:not(.hidden) td { background: #f0fdf4; }
    </style>
</head>
<body class="bg-gray-100">

<div class="flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside id="sidebar" class="admin-sidebar w-64 flex-shrink-0 text-white flex flex-col z-30 fixed lg:relative h-full -translate-x-full lg:translate-x-0 transition-transform duration-300">
        <!-- Logo -->
        <div class="p-6 border-b border-green-700">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                @php $siteLogo = \App\Models\Setting::get('site_logo'); @endphp
                @if($siteLogo)
                <img src="{{ asset('media/' . $siteLogo) }}" alt="ISSM" class="h-10 w-auto object-contain rounded" style="max-height:40px;">
                @else
                <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center">
                    <span class="text-white font-black text-sm">ISSM</span>
                </div>
                @endif
                <div>
                    <p class="font-bold text-white text-sm">ISSM Admin</p>
                    <p class="text-green-300 text-xs">Painel Administrativo</p>
                </div>
            </a>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-green-100 hover:bg-green-700 hover:text-white transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-green-700 text-white' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>

            <div class="pt-2 pb-1">
                <p class="text-green-400 text-xs font-semibold uppercase tracking-wider px-3">Conteúdo</p>
            </div>

            <a href="{{ route('admin.banners.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-green-100 hover:bg-green-700 hover:text-white transition-colors {{ request()->routeIs('admin.banners*') ? 'bg-green-700 text-white' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Banners
            </a>

            <a href="{{ route('admin.noticias.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-green-100 hover:bg-green-700 hover:text-white transition-colors {{ request()->routeIs('admin.noticias*') ? 'bg-green-700 text-white' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                Notícias
            </a>

            <a href="{{ route('admin.projetos.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-green-100 hover:bg-green-700 hover:text-white transition-colors {{ request()->routeIs('admin.projetos*') ? 'bg-green-700 text-white' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Projetos
            </a>

            <a href="{{ route('admin.galeria.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-green-100 hover:bg-green-700 hover:text-white transition-colors {{ request()->routeIs('admin.galeria*') ? 'bg-green-700 text-white' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Galeria
            </a>

            <a href="{{ route('admin.paginas.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-green-100 hover:bg-green-700 hover:text-white transition-colors {{ request()->routeIs('admin.paginas*') ? 'bg-green-700 text-white' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Páginas
            </a>

            <div class="pt-2 pb-1">
                <p class="text-green-400 text-xs font-semibold uppercase tracking-wider px-3">Institucional</p>
            </div>

            <a href="{{ route('admin.equipe.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-green-100 hover:bg-green-700 hover:text-white transition-colors {{ request()->routeIs('admin.equipe*') ? 'bg-green-700 text-white' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Equipe
            </a>

            <a href="{{ route('admin.parceiros.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-green-100 hover:bg-green-700 hover:text-white transition-colors {{ request()->routeIs('admin.parceiros*') ? 'bg-green-700 text-white' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Parceiros
            </a>

            <a href="{{ route('admin.ods.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-green-100 hover:bg-green-700 hover:text-white transition-colors {{ request()->routeIs('admin.ods*') ? 'bg-green-700 text-white' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                ODS 2030
            </a>

            <div class="pt-2 pb-1">
                <p class="text-green-400 text-xs font-semibold uppercase tracking-wider px-3">Sistema</p>
            </div>

            <a href="{{ route('admin.analytics.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-green-100 hover:bg-green-700 hover:text-white transition-colors {{ request()->routeIs('admin.analytics*') ? 'bg-green-700 text-white' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Analytics
            </a>

            <a href="{{ route('admin.contatos.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-green-100 hover:bg-green-700 hover:text-white transition-colors {{ request()->routeIs('admin.contatos*') ? 'bg-green-700 text-white' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Mensagens
                @php $newContacts = \App\Models\Contact::where('status','new')->count(); @endphp
                @if($newContacts > 0)<span class="ml-auto bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">{{ $newContacts }}</span>@endif
            </a>

            <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-green-100 hover:bg-green-700 hover:text-white transition-colors {{ request()->routeIs('admin.settings*') ? 'bg-green-700 text-white' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Configurações
            </a>

            <a href="{{ route('admin.ips-manutencao.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-green-100 hover:bg-green-700 hover:text-white transition-colors {{ request()->routeIs('admin.ips-manutencao*') ? 'bg-green-700 text-white' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                IPs Manutenção
            </a>
        </nav>

        <!-- Versao no rodape do sidebar -->
        <div class="px-4 py-3 border-t border-green-700 mt-auto">
            <p class="text-green-500 text-xs text-center">ISSM Admin &bull; v1.0.0</p>
        </div>
    </aside>

    <!-- Overlay for mobile -->
    <div id="sidebar-overlay" class="fixed inset-0 z-20 hidden lg:hidden" style="background:rgba(5,46,22,0.65);backdrop-filter:blur(2px);-webkit-backdrop-filter:blur(2px);transition:opacity .3s;"></div>

    <!-- Main content -->
    <div class="flex-1 flex flex-col overflow-hidden" style="min-width:0;">

        <!-- Top bar -->
        <header class="bg-white shadow-sm z-10 flex-shrink-0" style="overflow:visible;">
            <div class="flex items-center justify-between px-6 py-3">
                <div class="flex items-center gap-3">
                    <button id="sidebar-toggle" class="lg:hidden p-1 rounded text-gray-500 hover:text-gray-700" data-tooltip="Abrir menu" data-tip-pos="right">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div>
                        <h1 class="text-lg font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
                    </div>
                </div>
                {{-- Breadcrumb removido daqui, agora fica abaixo do header --}}
                <div class="flex items-center gap-4">
                    <!-- Dark / Light Toggle -->
                    <button id="theme-toggle" title="Alternar tema" aria-label="Alternar tema claro/escuro" data-tooltip="Alternar tema" data-tip-pos="left">
                        <svg id="theme-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                    </button>
                    @php $maintenanceMode = \App\Models\Setting::get('maintenance_mode', '0'); @endphp
                    @if($maintenanceMode == '1')
                    <span class="bg-orange-100 text-orange-700 text-xs font-medium px-3 py-1 rounded-full flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        Manutenção Ativa
                    </span>
                    @endif
                    <a href="{{ route('home') }}" target="_blank" class="text-sm text-green-700 hover:text-green-900 flex items-center gap-1" data-tooltip="Abrir site em nova aba" data-tip-pos="left">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        Ver Site
                    </a>

                    <!-- User dropdown -->
                    <div class="relative" id="user-dropdown-wrap">
                        <button id="user-dropdown-btn" class="flex items-center gap-2 px-2 py-1 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors focus:outline-none" aria-haspopup="true" aria-expanded="false">
                            <div class="w-8 h-8 rounded-full bg-green-700 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                {{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span class="hidden md:block text-sm font-medium text-gray-700 dark:text-gray-200 max-w-[120px] truncate">{{ Str::words(auth()->user()->name, 2, '') }}</span>
                            <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" id="user-dropdown-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        {{-- Dropdown: renderizado como fixed via JS para evitar clipping --}}
                        <div id="user-dropdown" style="display:none;position:fixed;z-index:9999;width:240px;" class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                            <!-- User info -->
                            <div class="px-4 py-3 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-gray-700 dark:to-gray-700 border-b border-gray-100 dark:border-gray-600">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-green-700 flex items-center justify-center text-white font-bold text-base flex-shrink-0">
                                        {{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-gray-800 dark:text-white text-sm truncate">{{ auth()->user()->name }}</p>
                                        <p class="text-gray-500 dark:text-gray-400 text-xs truncate">{{ auth()->user()->email }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="py-1">
                                <a href="{{ route('admin.profile.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    Meu Perfil
                                </a>
                                <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        Sair do Painel
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- Breadcrumb entre topbar e conteúdo --}}
        @php
            $routeName = request()->route()?->getName() ?? '';
            $breadcrumbs = [['label' => 'Dashboard', 'url' => route('admin.dashboard')]];
            $crumbMap = [
                'admin.banners'      => 'Banners',
                'admin.noticias'     => 'Notícias',
                'admin.projetos'     => 'Projetos',
                'admin.equipe'       => 'Equipe',
                'admin.paginas'      => 'Páginas',
                'admin.parceiros'    => 'Parceiros',
                'admin.galeria'      => 'Galeria',
                'admin.ods'          => 'ODS 2030',
                'admin.contatos'     => 'Mensagens',
                'admin.settings'     => 'Configurações',
                'admin.ips-manutencao' => 'IPs Manutenção',
                'admin.profile'      => 'Meu Perfil',
            ];
            foreach($crumbMap as $prefix => $label) {
                if(str_starts_with($routeName, $prefix)) {
                    $parts = explode('.', $routeName);
                    $lastPart = end($parts);
                    $action = match($lastPart) {
                        'create','store' => 'Novo',
                        'edit','update'  => 'Editar',
                        'show'           => 'Visualizar',
                        default          => null
                    };
                    $indexRoute = $prefix . '.index';
                    $indexUrl = Route::has($indexRoute) ? route($indexRoute) : null;
                    if($action && $indexUrl) {
                        $breadcrumbs[] = ['label' => $label, 'url' => $indexUrl];
                        $breadcrumbs[] = ['label' => $action, 'url' => null];
                    } else {
                        $breadcrumbs[] = ['label' => $label, 'url' => null];
                    }
                    break;
                }
            }
        @endphp
        <div class="bg-white border-b border-gray-100 px-6 py-1.5">
            <nav class="flex items-center gap-1 text-xs text-gray-400" aria-label="Breadcrumb">
                @foreach($breadcrumbs as $i => $crumb)
                    @if($i > 0)<svg class="w-3 h-3 flex-shrink-0 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>@endif
                    @if($crumb['url'] && $i < count($breadcrumbs)-1)
                        <a href="{{ $crumb['url'] }}" class="hover:text-green-700 transition-colors">{{ $crumb['label'] }}</a>
                    @else
                        <span class="{{ $i === count($breadcrumbs)-1 ? 'text-gray-600 font-medium' : '' }}">{{ $crumb['label'] }}</span>
                    @endif
                @endforeach
            </nav>
        </div>

        <!-- Page content -->
        <main class="flex-1 overflow-y-auto overflow-x-hidden p-6" id="admin-main-content" style="max-width:100%;box-sizing:border-box;">
            @yield('content')
        </main>

        <!-- Admin Footer — fixo na base da viewport, fora do scroll -->
        <footer class="flex-shrink-0 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 sm:px-6 py-2" style="overflow:hidden;max-width:100%;box-sizing:border-box;">
            <div class="flex flex-wrap items-center justify-between gap-2 text-xs text-gray-400 dark:text-gray-500" style="max-width:100%;overflow:hidden;">
                <span style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;min-width:0;">&copy; {{ date('Y') }} ISSM &mdash; Painel Administrativo &bull; v1.0.0</span>
                <span class="hidden sm:flex items-center gap-2">
                    <span title="Laravel Framework">Laravel {{ app()->version() }}</span>
                    <span class="text-gray-300 dark:text-gray-600">|</span>
                    <span title="PHP Version">PHP {{ phpversion() }}</span>
                    <span class="text-gray-300 dark:text-gray-600">|</span>
                    <span title="Ambiente">{{ ucfirst(app()->environment()) }}</span>
                </span>
            </div>
        </footer>
    </div>
</div>

<!-- Botão Voltar ao Topo (admin) -->
<button id="admin-back-to-top" title="Voltar ao topo"
    style="display:none;position:fixed;bottom:1.5rem;right:1rem;z-index:9998;width:2.5rem;height:2.5rem;border-radius:50%;background:#15803d;color:white;border:none;box-shadow:0 4px 14px rgba(0,0,0,.25);align-items:center;justify-content:center;cursor:pointer;transition:opacity .25s,transform .25s;opacity:0;"
    aria-label="Voltar ao topo">
    <svg style="width:1.2rem;height:1.2rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/></svg>
</button>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<!-- Toastify -->
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<script>
// ═══════════════════════════════════════════
// DARK / LIGHT THEME
// ═══════════════════════════════════════════
(function() {
    function applyTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('issm-theme', theme);
        var icon = document.getElementById('theme-icon');
        if (icon) {
            if (theme === 'dark') {
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>';
            } else {
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>';
            }
        }
    }

    document.getElementById('theme-toggle')?.addEventListener('click', function() {
        var current = document.documentElement.getAttribute('data-theme') || 'light';
        applyTheme(current === 'dark' ? 'light' : 'dark');
    });

    // Apply on load (already applied in head, but re-sync icon)
    applyTheme(localStorage.getItem('issm-theme') || 'light');
})();

// ═══════════════════════════════════════════
// TOAST NOTIFICATIONS
// ═══════════════════════════════════════════
function showToast(message, type) {
    type = type || 'success';
    var bg = type === 'success'
        ? 'linear-gradient(135deg, #16a34a, #15803d)'
        : type === 'error'
        ? 'linear-gradient(135deg, #dc2626, #b91c1c)'
        : 'linear-gradient(135deg, #2563eb, #1d4ed8)';
    Toastify({
        text: message,
        duration: 4500,
        gravity: 'top',
        position: 'right',
        stopOnFocus: true,
        style: {
            background: bg,
            borderRadius: '12px',
            padding: '12px 20px',
            fontSize: '14px',
            fontFamily: "'Inter', sans-serif",
            boxShadow: '0 8px 24px rgba(0,0,0,0.18)',
            minWidth: '260px',
        },
    }).showToast();
}

(function() {
    var success = document.querySelector('meta[name="flash-success"]');
    var error = document.querySelector('meta[name="flash-error"]');
    if (success && success.content) showToast(success.content, 'success');
    if (error && error.content) showToast(error.content, 'error');
})();

// ═══════════════════════════════════════════
// SWEETALERT2 — DELETE CONFIRMATION
// ═══════════════════════════════════════════
document.querySelectorAll('form[onsubmit*="confirm"]').forEach(function(form) {
    var msg = 'Esta ação não pode ser desfeita.';
    var match = form.getAttribute('onsubmit') && form.getAttribute('onsubmit').match(/confirm\(['"]([^'"]+)['"]\)/);
    if (match) msg = match[1];
    form.removeAttribute('onsubmit');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        var f = this;
        Swal.fire({
            title: 'Confirmar exclusão',
            text: msg,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Sim, excluir',
            cancelButtonText: 'Cancelar',
            reverseButtons: true,
            borderRadius: '16px',
        }).then(function(result) {
            if (result.isConfirmed) f.submit();
        });
    });
});

// Also handle [data-confirm] attribute on buttons inside forms
document.querySelectorAll('[data-confirm]').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        var form = btn.closest('form');
        Swal.fire({
            title: 'Confirmar exclusão',
            text: btn.dataset.confirm || 'Esta ação não pode ser desfeita.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Sim, excluir',
            cancelButtonText: 'Cancelar',
            reverseButtons: true,
        }).then(function(result) {
            if (result.isConfirmed && form) form.submit();
        });
    });
});

// ═══════════════════════════════════════════
// DRAG & DROP FILE UPLOAD
// ═══════════════════════════════════════════
function updateDropPreview(input, preview, label) {
    var file = input.files[0];
    if (!file) return;
    if (file.type && file.type.startsWith('image/')) {
        var reader = new FileReader();
        reader.onload = function(ev) {
            preview.innerHTML =
                '<div style="display:inline-block;position:relative;">' +
                '<img src="' + ev.target.result + '" style="height:100px;border-radius:8px;border:1px solid #d1d5db;object-fit:cover;" />' +
                '<button type="button" class="drop-zone__remove" style="position:absolute;top:-8px;right:-8px;background:#ef4444;color:white;border:none;border-radius:50%;width:20px;height:20px;font-size:14px;line-height:20px;text-align:center;cursor:pointer;" title="Remover">×</button>' +
                '</div>' +
                '<p style="font-size:12px;color:#6b7280;margin-top:6px;">' + file.name + ' (' + (file.size/1024).toFixed(1) + ' KB)</p>';
            preview.style.display = 'block';
            label.style.display = 'none';
            preview.querySelector('.drop-zone__remove').addEventListener('click', function() {
                input.value = '';
                preview.style.display = 'none';
                label.style.display = 'block';
                preview.innerHTML = '';
            });
        };
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = '<p style="font-size:13px;color:#16a34a;font-weight:500;">📎 ' + file.name + '</p>';
        preview.style.display = 'block';
        label.style.display = 'none';
    }
}

function initDropZone(input) {
    if (input.closest('.drop-zone')) return; // already initialized
    var wrapper = document.createElement('div');
    wrapper.className = 'drop-zone';

    var label = document.createElement('div');
    label.className = 'drop-zone__label';
    label.innerHTML =
        '<svg style="width:32px;height:32px;margin:0 auto 8px;display:block;color:#9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>' +
        '</svg>' +
        '<p style="font-size:13px;color:#6b7280;">Arraste e solte ou <span style="color:#16a34a;font-weight:500;cursor:pointer;">clique para selecionar</span></p>' +
        '<p style="font-size:11px;color:#9ca3af;margin-top:4px;">PNG, JPG, GIF até 2MB</p>';

    var preview = document.createElement('div');
    preview.className = 'drop-zone__preview';
    preview.style.display = 'none';

    // Move input into wrapper
    input.parentNode.insertBefore(wrapper, input);
    wrapper.appendChild(label);
    wrapper.appendChild(preview);
    wrapper.appendChild(input);
    input.classList.add('drop-zone__input');

    wrapper.addEventListener('click', function(e) {
        if (e.target !== input && !e.target.classList.contains('drop-zone__remove')) input.click();
    });

    wrapper.addEventListener('dragover', function(e) { e.preventDefault(); wrapper.classList.add('drop-zone--over'); });
    wrapper.addEventListener('dragleave', function() { wrapper.classList.remove('drop-zone--over'); });
    wrapper.addEventListener('drop', function(e) {
        e.preventDefault();
        wrapper.classList.remove('drop-zone--over');
        if (e.dataTransfer.files.length) {
            input.files = e.dataTransfer.files;
            updateDropPreview(input, preview, label);
        }
    });
    input.addEventListener('change', function() { updateDropPreview(input, preview, label); });

    // Show progress animation on parent form submit
    var form = input.closest('form');
    if (form) {
        form.addEventListener('submit', function() {
            if (input.files && input.files.length > 0) {
                wrapper.classList.add('drop-zone--uploading');
            }
        });
    }
}

// Initialize all file inputs (skip ones with data-no-dropzone)
document.querySelectorAll('input[type="file"]').forEach(function(input) {
    if (!input.closest('table') && !input.hasAttribute('data-no-dropzone')) initDropZone(input);
});

// ═══════════════════════════════════════════
// ADMIN SIDEBAR — MOBILE TOGGLE
// ═══════════════════════════════════════════
var sidebar = document.getElementById('sidebar');
var overlay = document.getElementById('sidebar-overlay');
var toggle  = document.getElementById('sidebar-toggle');

toggle?.addEventListener('click', function() {
    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
});

overlay?.addEventListener('click', function() {
    sidebar.classList.add('-translate-x-full');
    overlay.classList.add('hidden');
});

// ═══════════════════════════════════════════
// USER DROPDOWN (position:fixed para evitar clipping)
// ═══════════════════════════════════════════
(function() {
    var btn = document.getElementById('user-dropdown-btn');
    var menu = document.getElementById('user-dropdown');
    var chevron = document.getElementById('user-dropdown-chevron');
    if (!btn || !menu) return;

    function positionMenu() {
        var rect = btn.getBoundingClientRect();
        // Alinha a borda direita do menu com a borda direita do botão
        var menuRight = window.innerWidth - rect.right;
        menu.style.top  = (rect.bottom + 6) + 'px';
        menu.style.right = menuRight + 'px';
        menu.style.left  = 'auto';
    }

    function openMenu() {
        positionMenu();
        menu.style.display = 'block';
        chevron && chevron.classList.add('rotate-180');
        btn.setAttribute('aria-expanded', 'true');
    }
    function closeMenu() {
        menu.style.display = 'none';
        chevron && chevron.classList.remove('rotate-180');
        btn.setAttribute('aria-expanded', 'false');
    }

    btn.addEventListener('click', function(e) {
        e.stopPropagation();
        menu.style.display === 'none' ? openMenu() : closeMenu();
    });

    document.addEventListener('click', function(e) {
        if (!document.getElementById('user-dropdown-wrap')?.contains(e.target)) closeMenu();
    });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeMenu();
    });
    window.addEventListener('resize', function() {
        if (menu.style.display !== 'none') positionMenu();
    });
})();

// ═══════════════════════════════════════════
// BACK TO TOP (admin)
// ═══════════════════════════════════════════
(function() {
    var btn = document.getElementById('admin-back-to-top');
    var main = document.getElementById('admin-main-content');
    if (!btn || !main) return;

    main.addEventListener('scroll', function() {
        if (main.scrollTop > 300) {
            btn.style.display = 'flex';
            btn.style.opacity = '1';
        } else {
            btn.style.opacity = '0';
            setTimeout(function() {
                if (parseFloat(btn.style.opacity) === 0) btn.style.display = 'none';
            }, 280);
        }
    }, { passive: true });

    btn.addEventListener('click', function() {
        main.scrollTo({ top: 0, behavior: 'smooth' });
    });
    btn.addEventListener('mouseenter', function() { this.style.transform = 'scale(1.1)'; });
    btn.addEventListener('mouseleave', function() { this.style.transform = 'scale(1)'; });
})();

// ═══════════════════════════════════════════
// AUTO PLACEHOLDERS
// ═══════════════════════════════════════════
(function() {
    var map = {
        'title':              'Ex: Título do conteúdo',
        'name':               'Ex: Nome completo',
        'role':               'Ex: Coordenador de Projetos',
        'email':              'Ex: email@issm.org.br',
        'linkedin':           'Ex: https://linkedin.com/in/...',
        'url':                'Ex: https://www.parceiro.com.br',
        'button_url':         'Ex: /projetos ou https://...',
        'button_text':        'Ex: Saiba Mais',
        'category':           'Ex: Meio Ambiente, Educação...',
        'location':           'Ex: Serra do Mendanha, RJ',
        'excerpt':            'Breve resumo do conteúdo...',
        'content':            'Digite o conteúdo completo aqui...',
        'subtitle':           'Subtítulo ou texto de apoio...',
        'bio':                'Resumo biográfico do membro...',
        'description':        'Descrição resumida...',
        'meta_title':         'Título para SEO (máx. 60 caracteres)',
        'meta_description':   'Descrição para SEO (máx. 160 caracteres)',
        'meta_keywords':      'palavra-chave1, palavra-chave2...',
        'album':              'Ex: Eventos 2024, Projetos...',
        'order':              '0',
        'ip_address':         'Ex: 192.168.1.100',
        'google_analytics':   'Ex: G-XXXXXXXXXX',
        'contact_email':      'Ex: contato@issm.org.br',
        'contact_phone':      'Ex: (21) 9 9999-9999',
        'contact_address':    'Ex: Serra do Mendanha, Rio de Janeiro - RJ',
        'contact_cep':        'Ex: 23080-000',
        'contact_map_embed':  '<iframe src="https://maps.google.com/..."></iframe>',
        'social_facebook':    'Ex: https://facebook.com/issm',
        'social_instagram':   'Ex: https://instagram.com/issm',
        'social_youtube':     'Ex: https://youtube.com/@issm',
        'social_twitter':     'Ex: https://twitter.com/issm',
        'social_linkedin':    'Ex: https://linkedin.com/company/issm',
        'social_whatsapp':    'Ex: https://wa.me/5521999999999',
        'site_name':          'Ex: ISSM - Instituto Socioambiental Serra do Mendanha',
        'site_description':   'Descrição breve do site...',
        'maintenance_title':  'Ex: Site em Manutenção',
        'maintenance_message':'Mensagem exibida durante a manutenção...',
        'maintenance_email':  'Ex: contato@issm.org.br',
        'mission':            'Descreva a missão da instituição...',
        'vision':             'Descreva a visão estratégica...',
        'values':             'Sustentabilidade, Transparência, Inovação...',
        'about_text':         'Texto sobre a instituição...',
    };

    document.querySelectorAll(
        'input:not([type="checkbox"]):not([type="radio"]):not([type="file"])' +
        ':not([type="submit"]):not([type="hidden"]):not([type="date"])' +
        ':not([type="datetime-local"]), textarea:not(.wysiwyg)'
    ).forEach(function(el) {
        if (el.placeholder && el.placeholder.trim() !== '') return;
        var name = (el.name || '').replace(/\[\]$/, '').replace(/\[.*\]/, '');
        if (map[name]) {
            el.placeholder = map[name];
        } else {
            var lbl = el.id ? document.querySelector('label[for="' + el.id + '"]') : null;
            if (!lbl) lbl = el.closest('div')?.querySelector('label');
            if (lbl) {
                var txt = lbl.textContent.replace(/[*]/g, '').trim();
                if (txt && txt.length < 50) el.placeholder = 'Ex: ' + txt.toLowerCase() + '...';
            }
        }
    });
})();

// ═══════════════════════════════════════════
// AUTO TOOLTIPS em botões de ação
// ═══════════════════════════════════════════
(function() {
    var tooltipRules = [
        { sel: 'a.text-blue-600',  text: 'Editar',     tip: 'Editar registro' },
        { sel: 'button.text-red-600', text: 'Excluir', tip: 'Excluir permanentemente' },
        { sel: 'a.text-red-600',   text: 'Excluir',    tip: 'Excluir permanentemente' },
        { sel: 'a[target="_blank"]', text: 'Ver Site',  tip: 'Abrir site em nova aba' },
    ];
    tooltipRules.forEach(function(rule) {
        document.querySelectorAll(rule.sel).forEach(function(el) {
            var t = el.textContent.trim();
            if ((rule.text === '' || t === rule.text || t.includes(rule.text)) && !el.dataset.tooltip) {
                el.dataset.tooltip = rule.tip;
                el.style.cursor = el.tagName === 'BUTTON' ? 'pointer' : '';
            }
        });
    });
    // View/Show links
    document.querySelectorAll('a.text-green-600').forEach(function(el) {
        if (!el.dataset.tooltip && el.textContent.trim() === 'Ver') el.dataset.tooltip = 'Visualizar detalhes';
    });
    // Add-current IP button
    document.querySelectorAll('form[action*="add-current"] button').forEach(function(el) {
        if (!el.dataset.tooltip) el.dataset.tooltip = 'Adiciona o IP desta sessão';
    });
})();
</script>

@stack('scripts')
<!-- jQuery + Summernote — deve ser o ÚLTIMO script da página -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/lang/summernote-pt-BR.min.js"></script>
<script>
(function initSummernote() {
    if (typeof window.jQuery === 'undefined') {
        console.warn('[Summernote] jQuery não carregado. Editor desativado.');
        return;
    }
    if (typeof jQuery.fn.summernote === 'undefined') {
        console.warn('[Summernote] Plugin não carregado.');
        return;
    }
    var $textareas = jQuery('textarea.wysiwyg');
    if ($textareas.length === 0) return; // sem editor nesta página
    $textareas.each(function() {
        var $el = jQuery(this);
        var h   = parseInt($el.data('height') || 300);
        $el.summernote({
            lang: 'pt-BR',
            height: h,
            tabsize: 2,
            toolbar: [
                ['style',    ['style']],
                ['font',     ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
                ['fontsize', ['fontsize']],
                ['color',    ['color']],
                ['para',     ['ul', 'ol', 'paragraph']],
                ['table',    ['table']],
                ['insert',   ['link', 'picture', 'hr']],
                ['view',     ['fullscreen', 'codeview']]
            ],
            callbacks: {
                onInit: function() {
                    if (document.documentElement.getAttribute('data-theme') === 'dark') {
                        jQuery(this).closest('.note-editor').addClass('dark-editor');
                    }
                }
            }
        });
    });
    // Sincroniza dark mode com o editor ao alternar tema
    var themeBtn = document.getElementById('theme-toggle');
    if (themeBtn) {
        themeBtn.addEventListener('click', function() {
            setTimeout(function() {
                var dark = document.documentElement.getAttribute('data-theme') === 'dark';
                document.querySelectorAll('.note-editor').forEach(function(el) {
                    el.style.borderColor = dark ? '#4b5563' : '';
                });
            }, 60);
        });
    }

})();
</script>
<script>
// ═══ DATATABLE INLINE TOGGLE ═══
document.querySelectorAll('[data-dt-toggle]').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var row = this.closest('tr');
        var detail = row.nextElementSibling;
        if (detail && detail.classList.contains('dt-detail')) {
            var isHidden = detail.classList.toggle('hidden');
            this.classList.toggle('dt-open', !isHidden);
        }
    });
});
</script>
</body>
</html>

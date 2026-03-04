<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Erro') - ISSM</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Inter',sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#14532d 0%,#1b4332 40%,#2d6a4f 100%);color:#fff;overflow:hidden;position:relative}

        /* Animated background circles */
        .err-bg{position:fixed;inset:0;overflow:hidden;pointer-events:none;z-index:0}
        .err-bg span{position:absolute;border-radius:50%;background:rgba(255,255,255,.03);animation:errFloat 20s infinite linear}
        .err-bg span:nth-child(1){width:300px;height:300px;top:-80px;left:-80px;animation-duration:25s}
        .err-bg span:nth-child(2){width:200px;height:200px;bottom:-60px;right:-60px;animation-duration:18s;animation-delay:-5s}
        .err-bg span:nth-child(3){width:150px;height:150px;top:50%;left:60%;animation-duration:22s;animation-delay:-10s}
        .err-bg span:nth-child(4){width:100px;height:100px;bottom:20%;left:15%;animation-duration:15s;animation-delay:-3s}
        @keyframes errFloat{0%{transform:translateY(0) rotate(0deg)}50%{transform:translateY(-30px) rotate(180deg)}100%{transform:translateY(0) rotate(360deg)}}

        /* Main container */
        .err-box{position:relative;z-index:1;text-align:center;max-width:520px;padding:2.5rem;animation:errIn .6s ease}
        @keyframes errIn{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}

        /* Error code */
        .err-code{font-size:8rem;font-weight:800;line-height:1;letter-spacing:-.04em;background:linear-gradient(135deg,#4ade80,#22c55e,#86efac);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;margin-bottom:.25rem;text-shadow:none}

        /* Icon */
        .err-icon{width:4rem;height:4rem;margin:0 auto 1.5rem;background:rgba(255,255,255,.1);border-radius:50%;display:flex;align-items:center;justify-content:center;backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,.15)}
        .err-icon svg{width:2rem;height:2rem;color:#4ade80}

        /* Title */
        .err-title{font-size:1.5rem;font-weight:700;margin-bottom:.75rem;color:#f0fdf4}

        /* Description */
        .err-desc{font-size:.9375rem;line-height:1.6;color:rgba(255,255,255,.7);margin-bottom:2rem}

        /* Buttons */
        .err-actions{display:flex;gap:.75rem;justify-content:center;flex-wrap:wrap}
        .err-btn{display:inline-flex;align-items:center;gap:.5rem;padding:.75rem 1.75rem;border-radius:.75rem;font-size:.875rem;font-weight:600;font-family:inherit;text-decoration:none;transition:all .2s;border:none;cursor:pointer}
        .err-btn--primary{background:#22c55e;color:#14532d}
        .err-btn--primary:hover{background:#4ade80;transform:translateY(-2px);box-shadow:0 8px 24px rgba(34,197,94,.3)}
        .err-btn--secondary{background:rgba(255,255,255,.1);color:#fff;border:1px solid rgba(255,255,255,.2);backdrop-filter:blur(8px)}
        .err-btn--secondary:hover{background:rgba(255,255,255,.18);transform:translateY(-2px)}
        .err-btn svg{width:1rem;height:1rem}

        /* Footer brand */
        .err-brand{position:fixed;bottom:2rem;left:50%;transform:translateX(-50%);font-size:.75rem;color:rgba(255,255,255,.35);letter-spacing:.05em}

        @media(max-width:480px){
            .err-code{font-size:5rem}
            .err-title{font-size:1.25rem}
            .err-box{padding:1.5rem}
        }
    </style>
</head>
<body>
    <div class="err-bg">
        <span></span><span></span><span></span><span></span>
    </div>

    <div class="err-box">
        <div class="err-icon">
            @yield('icon')
        </div>
        <div class="err-code">@yield('code')</div>
        <h1 class="err-title">@yield('heading')</h1>
        <p class="err-desc">@yield('message')</p>
        <div class="err-actions">
            <a href="{{ url('/') }}" class="err-btn err-btn--primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Página Inicial
            </a>
            <button onclick="history.back()" class="err-btn err-btn--secondary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Voltar
            </button>
        </div>
    </div>

    <span class="err-brand">ISSM — Instituto Socioambiental Serra do Mendanha</span>
</body>
</html>

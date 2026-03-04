<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="robots" content="noindex, nofollow">
    <title><?php echo e($title ?? 'Site em Manutenção'); ?></title>
    <?php
        $siteFavicon = \App\Models\Setting::get('site_favicon');
        $siteLogo    = \App\Models\Setting::get('site_logo');
        $siteName    = \App\Models\Setting::get('site_name', 'ISSM');
        $displayLogo = !empty($logo) ? $logo : ($siteLogo ?? '');
        $hasBg       = !empty($bgImage);
        $bgCol       = $bgColor ?? '#14532d';
        $anim        = $animation ?? 'gear';
    ?>
    <?php if($siteFavicon): ?>
    <link rel="icon" href="<?php echo e(asset('media/' . $siteFavicon)); ?>">
    <?php endif; ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            height: 100%; width: 100%;
            overflow: hidden;
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        /* ── Background ── */
        .mnt-bg {
            position: fixed; inset: 0; z-index: 0;
            background: <?php echo e($hasBg ? '#000' : $bgCol); ?>;
        }
        <?php if($hasBg): ?>
        .mnt-bg::before {
            content: '';
            position: absolute; inset: 0;
            background: url('<?php echo e(asset("media/" . $bgImage)); ?>') center/cover no-repeat;
            opacity: .45;
        }
        .mnt-bg::after {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(0,0,0,.7) 0%, rgba(0,40,20,.6) 100%);
        }
        <?php else: ?>
        .mnt-bg::before {
            content: '';
            position: absolute; inset: 0;
            background: radial-gradient(ellipse at 20% 50%, rgba(255,255,255,.07) 0%, transparent 60%),
                        radial-gradient(ellipse at 80% 20%, rgba(255,255,255,.05) 0%, transparent 50%);
        }
        <?php endif; ?>

        /* ── Partículas flutuantes ── */
        .particles { position: fixed; inset: 0; z-index: 1; pointer-events: none; overflow: hidden; }
        .particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,.06);
            animation: floatUp linear infinite;
        }

        /* ── Layout ── */
        .mnt-wrap {
            position: relative; z-index: 10;
            height: 100%; width: 100%;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 20px 16px;
            padding-top: calc(20px + env(safe-area-inset-top, 0px));
            padding-bottom: calc(20px + env(safe-area-inset-bottom, 0px));
        }

        .mnt-card {
            width: 100%; max-width: 520px;
            display: flex; flex-direction: column;
            align-items: center; gap: 0;
            text-align: center;
        }

        /* ── Logo ── */
        .mnt-logo-ring {
            width: 96px; height: 96px;
            border-radius: 28px;
            background: rgba(255,255,255,.12);
            backdrop-filter: blur(12px);
            border: 1.5px solid rgba(255,255,255,.2);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 28px;
            box-shadow: 0 8px 32px rgba(0,0,0,.25);
            flex-shrink: 0;
            animation: pulseRing 3s ease-in-out infinite;
        }
        .mnt-logo-ring img { width: 72px; height: 72px; object-fit: contain; border-radius: 16px; }
        .mnt-logo-ring span { color: #fff; font-weight: 900; font-size: 20px; letter-spacing: -1px; }

        /* ── Animação central ── */
        .mnt-anim { margin-bottom: 24px; flex-shrink: 0; }

        /* Gear */
        .anim-gear { animation: spinGear 4s linear infinite; color: rgba(255,255,255,.55); }
        .anim-gear-inner { animation: spinGear 4s linear infinite reverse; color: rgba(255,255,255,.3); }

        /* Pulse */
        .anim-pulse-ring {
            width: 80px; height: 80px;
            border-radius: 50%;
            border: 3px solid rgba(255,255,255,.4);
            display: flex; align-items: center; justify-content: center;
            position: relative;
            animation: pulseRing 2s ease-in-out infinite;
        }
        .anim-pulse-ring::before, .anim-pulse-ring::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            border: 2px solid rgba(255,255,255,.25);
            animation: pulseExpand 2s ease-out infinite;
        }
        .anim-pulse-ring::before { width: 110%; height: 110%; animation-delay: .3s; }
        .anim-pulse-ring::after  { width: 130%; height: 130%; animation-delay: .6s; }

        /* Wave bars */
        .anim-wave { display: flex; gap: 10px; align-items: flex-end; height: 40px; }
        .anim-wave span {
            display: block; width: 10px; border-radius: 5px;
            background: rgba(255,255,255,.6);
            animation: waveBar 1.2s ease-in-out infinite;
        }
        .anim-wave span:nth-child(1) { animation-delay: 0s; }
        .anim-wave span:nth-child(2) { animation-delay: .15s; }
        .anim-wave span:nth-child(3) { animation-delay: .3s; }
        .anim-wave span:nth-child(4) { animation-delay: .45s; }
        .anim-wave span:nth-child(5) { animation-delay: .6s; }

        /* ── Textos ── */
        .mnt-title {
            font-size: clamp(22px, 5vw, 36px);
            font-weight: 900;
            color: #fff;
            line-height: 1.15;
            margin-bottom: 12px;
            letter-spacing: -.5px;
            text-shadow: 0 2px 12px rgba(0,0,0,.3);
        }
        .mnt-msg {
            font-size: clamp(13px, 3vw, 16px);
            color: rgba(255,255,255,.75);
            line-height: 1.65;
            margin-bottom: 24px;
            max-width: 420px;
        }

        /* ── Barra de progresso ── */
        .mnt-progress-wrap { width: 100%; max-width: 360px; margin-bottom: 24px; }
        .mnt-progress-label {
            display: flex; justify-content: space-between;
            font-size: 11px; font-weight: 600;
            color: rgba(255,255,255,.5);
            margin-bottom: 6px;
            text-transform: uppercase; letter-spacing: .06em;
        }
        .mnt-progress-track {
            height: 6px; border-radius: 3px;
            background: rgba(255,255,255,.12);
            overflow: hidden;
        }
        .mnt-progress-fill {
            height: 100%; border-radius: 3px;
            background: linear-gradient(90deg, rgba(255,255,255,.5), rgba(255,255,255,.9));
            width: <?php echo e($progress ?? 65); ?>%;
            animation: progressIn 1.4s cubic-bezier(0.4,0,0.2,1) forwards;
            box-shadow: 0 0 8px rgba(255,255,255,.4);
        }

        /* ── Countdown ── */
        .mnt-countdown { display: flex; gap: 10px; margin-bottom: 24px; flex-wrap: nowrap; }
        .mnt-cd-box {
            display: flex; flex-direction: column; align-items: center;
            background: rgba(255,255,255,.1);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,.15);
            border-radius: 12px;
            padding: 10px 12px;
            min-width: 56px;
        }
        .mnt-cd-num {
            font-size: clamp(20px, 5vw, 28px);
            font-weight: 900; color: #fff;
            line-height: 1; letter-spacing: -1px;
            font-variant-numeric: tabular-nums;
        }
        .mnt-cd-lbl {
            font-size: 9px; color: rgba(255,255,255,.5);
            font-weight: 600; text-transform: uppercase;
            letter-spacing: .1em; margin-top: 3px;
        }

        /* ── E-mail ── */
        .mnt-email { font-size: 12px; color: rgba(255,255,255,.5); margin-bottom: 20px; }
        .mnt-email a { color: rgba(255,255,255,.8); font-weight: 600; text-decoration: none; }
        .mnt-email a:hover { color: #fff; }

        /* ── Redes sociais ── */
        .mnt-social { display: flex; gap: 10px; margin-bottom: 24px; }
        .mnt-social a {
            width: 40px; height: 40px; border-radius: 11px;
            background: rgba(255,255,255,.1);
            border: 1px solid rgba(255,255,255,.15);
            display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,.7);
            text-decoration: none;
            transition: background .2s, transform .2s, color .2s;
        }
        .mnt-social a:hover { background: rgba(255,255,255,.22); color: #fff; transform: translateY(-2px); }
        .mnt-social a svg { width: 16px; height: 16px; }

        /* ── Link admin ── */
        .mnt-admin {
            font-size: 11px; color: rgba(255,255,255,.3);
            text-decoration: none; transition: color .2s;
        }
        .mnt-admin:hover { color: rgba(255,255,255,.65); }

        /* ── Keyframes ── */
        @keyframes spinGear    { to { transform: rotate(360deg); } }
        @keyframes pulseRing   { 0%,100% { transform: scale(1); } 50% { transform: scale(1.06); } }
        @keyframes pulseExpand { 0% { opacity: .6; transform: scale(.9); } 100% { opacity: 0; transform: scale(1.4); } }
        @keyframes waveBar     { 0%,100% { height: 8px; } 50% { height: 36px; } }
        @keyframes progressIn  { from { width: 0; } }
        @keyframes floatUp     {
            0%   { transform: translateY(0) scale(1); opacity: .6; }
            100% { transform: translateY(-110vh) scale(.5); opacity: 0; }
        }
    </style>
</head>
<body>

    
    <div class="mnt-bg"></div>

    
    <div class="particles" id="particles"></div>

    
    <div class="mnt-wrap">
        <div class="mnt-card">

            
            <div class="mnt-logo-ring">
                <?php if($displayLogo): ?>
                    <img src="<?php echo e(asset('media/' . $displayLogo)); ?>" alt="<?php echo e($siteName); ?>">
                <?php else: ?>
                    <span><?php echo e(mb_strtoupper(mb_substr($siteName, 0, 4))); ?></span>
                <?php endif; ?>
            </div>

            
            <?php if($anim !== 'none'): ?>
            <div class="mnt-anim">
                <?php if($anim === 'gear'): ?>
                <div style="position:relative;width:72px;height:72px;display:flex;align-items:center;justify-content:center;">
                    <svg class="anim-gear" style="width:72px;height:72px;position:absolute;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    </svg>
                    <svg class="anim-gear-inner" style="width:36px;height:36px;position:absolute;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>

                <?php elseif($anim === 'pulse'): ?>
                <div class="anim-pulse-ring">
                    <svg style="width:30px;height:30px;color:rgba(255,255,255,.85);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>

                <?php elseif($anim === 'wave'): ?>
                <div class="anim-wave">
                    <span></span><span></span><span></span><span></span><span></span>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            
            <h1 class="mnt-title"><?php echo e($title ?? 'Site em Manutenção'); ?></h1>

            
            <div class="mnt-msg"><?php echo $message ?? 'Estamos realizando melhorias no nosso site. Em breve estaremos de volta!'; ?></div>

            
            <?php if(($progress ?? 0) > 0): ?>
            <div class="mnt-progress-wrap">
                <div class="mnt-progress-label">
                    <span>Progresso</span>
                    <span><?php echo e($progress); ?>%</span>
                </div>
                <div class="mnt-progress-track">
                    <div class="mnt-progress-fill"></div>
                </div>
            </div>
            <?php endif; ?>

            
            <?php if(!empty($showCountdown) && $showCountdown && !empty($returnDate)): ?>
            <div class="mnt-countdown" id="countdown" data-target="<?php echo e($returnDate); ?>">
                <div class="mnt-cd-box"><span class="mnt-cd-num" id="cd-days">--</span><span class="mnt-cd-lbl">dias</span></div>
                <div class="mnt-cd-box"><span class="mnt-cd-num" id="cd-hours">--</span><span class="mnt-cd-lbl">horas</span></div>
                <div class="mnt-cd-box"><span class="mnt-cd-num" id="cd-mins">--</span><span class="mnt-cd-lbl">min</span></div>
                <div class="mnt-cd-box"><span class="mnt-cd-num" id="cd-secs">--</span><span class="mnt-cd-lbl">seg</span></div>
            </div>
            <?php endif; ?>

            
            <?php if(!empty($email)): ?>
            <p class="mnt-email">
                Em caso de urgência:
                <a href="mailto:<?php echo e($email); ?>"><?php echo e($email); ?></a>
            </p>
            <?php endif; ?>

            
            <?php if(!empty($showSocial) && $showSocial): ?>
            <?php
                $socials = array_filter([
                    'facebook'  => $facebook  ?? '',
                    'instagram' => $instagram ?? '',
                    'youtube'   => $youtube   ?? '',
                    'whatsapp'  => $whatsapp  ?? '',
                ]);
            ?>
            <?php if(count($socials) > 0): ?>
            <div class="mnt-social">
                <?php if(!empty($facebook)): ?>
                <a href="<?php echo e($facebook); ?>" target="_blank" rel="noopener" title="Facebook">
                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                </a>
                <?php endif; ?>
                <?php if(!empty($instagram)): ?>
                <a href="<?php echo e($instagram); ?>" target="_blank" rel="noopener" title="Instagram">
                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                </a>
                <?php endif; ?>
                <?php if(!empty($youtube)): ?>
                <a href="<?php echo e($youtube); ?>" target="_blank" rel="noopener" title="YouTube">
                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                </a>
                <?php endif; ?>
                <?php if(!empty($whatsapp)): ?>
                <?php $wa = preg_replace('/\D/', '', $whatsapp); ?>
                <a href="https://wa.me/55<?php echo e($wa); ?>" target="_blank" rel="noopener" title="WhatsApp">
                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            <?php endif; ?>

            
            <a href="<?php echo e(route('login')); ?>" class="mnt-admin">Acesso Administrativo</a>

        </div>
    </div>

    <script>
    // ── Partículas flutuantes ──────────────────────────────────────────────
    (function() {
        var container = document.getElementById('particles');
        if (!container) return;
        var count = Math.min(20, Math.floor(window.innerWidth / 55));
        for (var i = 0; i < count; i++) {
            var p = document.createElement('div');
            p.className = 'particle';
            var size = Math.random() * 60 + 20;
            p.style.cssText = [
                'width:'  + size + 'px',
                'height:' + size + 'px',
                'left:'   + Math.random() * 100 + '%',
                'top:'    + (55 + Math.random() * 55) + '%',
                'animation-duration:' + (Math.random() * 20 + 12) + 's',
                'animation-delay:-'   + (Math.random() * 20) + 's',
            ].join(';');
            container.appendChild(p);
        }
    })();

    // ── Countdown timer ────────────────────────────────────────────────────
    (function() {
        var box = document.getElementById('countdown');
        if (!box) return;
        var target = new Date(box.getAttribute('data-target')).getTime();
        if (isNaN(target)) return;
        function pad(n) { return String(n).padStart(2, '0'); }
        function tick() {
            var diff = target - Date.now();
            if (diff <= 0) diff = 0;
            var d = Math.floor(diff / 86400000);
            var h = Math.floor((diff % 86400000) / 3600000);
            var m = Math.floor((diff % 3600000)  / 60000);
            var s = Math.floor((diff % 60000)    / 1000);
            document.getElementById('cd-days').textContent  = pad(d);
            document.getElementById('cd-hours').textContent = pad(h);
            document.getElementById('cd-mins').textContent  = pad(m);
            document.getElementById('cd-secs').textContent  = pad(s);
        }
        tick();
        setInterval(tick, 1000);
    })();
    </script>
</body>
</html>
<?php /**PATH /home/issmorg/public_html/resources/views/maintenance.blade.php ENDPATH**/ ?>
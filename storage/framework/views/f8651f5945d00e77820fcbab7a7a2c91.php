
<?php $__env->startSection("title", "Configurações"); ?>
<?php $__env->startSection("page-title", "Configurações do Site"); ?>

<?php $__env->startSection("content"); ?>

<style>
/* ---------- layout geral ---------- */
.cfg-wrap{display:flex;gap:1.5rem;align-items:flex-start;max-width:100%;overflow:hidden}
.cfg-side{width:12rem;flex-shrink:0;position:sticky;top:1.5rem}
.cfg-main{flex:1;min-width:0;max-width:100%;overflow:hidden}

/* ---------- nav sidebar ---------- */
.cfg-nav{background:#fff;border-radius:1rem;border:1px solid #e5e7eb;overflow:hidden}
.cfg-nav-title{font-size:.625rem;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.08em;padding:.75rem 1rem .25rem;margin:0}
.cfg-nav-list{list-style:none;padding:.125rem .5rem .75rem;margin:0}
.cfg-nav-btn{width:100%;display:flex;align-items:center;gap:.625rem;padding:.625rem .75rem;border-radius:.75rem;border:none;background:transparent;cursor:pointer;text-align:left;transition:background .15s,color .15s;font-family:inherit;font-size:.8125rem;font-weight:500;color:#4b5563}
.cfg-nav-btn:hover{background:#f3f4f6}
.cfg-nav-btn.--on{background:#16a34a;color:#fff}
.cfg-nav-btn.--on:hover{background:#15803d}
.cfg-nav-ico{width:1.5rem;height:1.5rem;border-radius:.5rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:background .15s}
.cfg-nav-count{font-size:.6875rem;font-weight:600;font-variant-numeric:tabular-nums;margin-left:auto}
.cfg-nav-btn.--on .cfg-nav-count{color:rgba(255,255,255,.55)}
.cfg-nav-btn:not(.--on) .cfg-nav-count{color:#9ca3af}

/* ---------- save button sidebar ---------- */
.cfg-save-side{margin-top:1rem;width:100%;display:flex;align-items:center;justify-content:center;gap:.5rem;background:#16a34a;color:#fff;font-weight:700;font-size:.8125rem;padding:.875rem 1rem;border-radius:1rem;border:none;cursor:pointer;transition:background .15s,transform .1s,box-shadow .15s;box-shadow:0 4px 12px rgba(22,163,74,.25);font-family:inherit}
.cfg-save-side:hover{background:#15803d;transform:translateY(-1px);box-shadow:0 6px 16px rgba(22,163,74,.3)}
.cfg-save-side:active{background:#166534;transform:translateY(0)}

/* ---------- panel ---------- */
.cfg-panel{display:none}
.cfg-panel.--show{display:block;animation:cfgIn .18s ease}
@keyframes cfgIn{from{opacity:0;transform:translateY(6px)}to{opacity:1;transform:translateY(0)}}

/* ---------- panel header ---------- */
.cfg-phead{display:flex;align-items:center;gap:1rem;margin-bottom:1.5rem}
.cfg-phead-ico{width:3rem;height:3rem;border-radius:1rem;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.cfg-phead h2{font-size:1.25rem;font-weight:700;color:#111827;line-height:1;margin:0}
.cfg-phead p{font-size:.8125rem;color:#9ca3af;margin:.25rem 0 0}

/* ---------- card ---------- */
.cfg-card{background:#fff;border-radius:1rem;border:1px solid #e5e7eb;overflow:hidden;margin-bottom:1.25rem;max-width:100%;box-sizing:border-box}
.cfg-card-head{display:flex;align-items:center;gap:.5rem;padding:.875rem 1.25rem;border-bottom:1px solid #f3f4f6;background:linear-gradient(to right,#f9fafb,#fff)}
.cfg-card-head svg{width:.875rem;height:.875rem;color:#9ca3af;flex-shrink:0}
.cfg-card-head p{font-size:.6875rem;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.07em;margin:0}
.cfg-card-body{padding:1.25rem}

/* ---------- fields grid ---------- */
.cfg-grid{display:grid;grid-template-columns:1fr;gap:1.25rem 1.5rem}
@media(min-width:768px){.cfg-grid{grid-template-columns:repeat(2,1fr)}}
.cfg-wide{grid-column:1/-1}

/* ---------- label ---------- */
.cfg-label{display:block;font-size:.8125rem;font-weight:600;color:#374151;margin-bottom:.5rem}

/* ---------- text input ---------- */
.cfg-input{width:100%;border:1px solid #d1d5db;border-radius:.75rem;padding:.625rem 1rem;font-size:.8125rem;font-family:inherit;background:#f9fafb;transition:border-color .15s,box-shadow .15s,background .15s;outline:none;box-sizing:border-box;max-width:100%}
.cfg-input:focus{border-color:#16a34a;box-shadow:0 0 0 3px rgba(22,163,74,.15);background:#fff}
.cfg-input--iconed{padding-left:2.5rem}
.cfg-input-wrap{position:relative}
.cfg-input-icon{position:absolute;left:0;top:0;bottom:0;width:2.5rem;display:flex;align-items:center;justify-content:center;pointer-events:none}
.cfg-input-icon svg{width:1rem;height:1rem;color:#9ca3af}

/* ---------- textarea ---------- */
.cfg-textarea{width:100%;border:1px solid #d1d5db;border-radius:.75rem;padding:.75rem 1rem;font-size:.8125rem;font-family:inherit;background:#f9fafb;transition:border-color .15s,box-shadow .15s,background .15s;outline:none;box-sizing:border-box;resize:vertical;min-height:100px;max-width:100%}
.cfg-textarea:focus{border-color:#16a34a;box-shadow:0 0 0 3px rgba(22,163,74,.15);background:#fff}

/* ---------- image upload ---------- */
.cfg-img-current{display:flex;align-items:center;gap:1rem;padding:1rem;background:#f9fafb;border-radius:.75rem;border:1px solid #e5e7eb;margin-bottom:.75rem}
.cfg-img-current img{height:3.5rem;max-width:7.5rem;object-fit:contain;border-radius:.5rem;background:#fff;border:1px solid #e5e7eb;padding:.25rem}
.cfg-img-info{flex:1;min-width:0}
.cfg-img-name{font-size:.75rem;font-weight:600;color:#374151;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.cfg-img-hint{font-size:.6875rem;color:#9ca3af;margin:.125rem 0 .75rem}
.cfg-img-remove{display:inline-flex;align-items:center;gap:.375rem;cursor:pointer;font-size:.75rem;font-weight:600;color:#ef4444;border:1px solid #fecaca;background:#fef2f2;padding:.375rem .625rem;border-radius:.5rem;transition:background .15s,border-color .15s}
.cfg-img-remove:hover{background:#fee2e2;border-color:#fca5a5}
.cfg-img-remove input{width:.75rem;height:.75rem;accent-color:#ef4444}
.cfg-upload-zone{display:flex;flex-direction:column;align-items:center;justify-content:center;gap:.75rem;border:2px dashed #d1d5db;border-radius:.75rem;padding:1.5rem;cursor:pointer;transition:border-color .15s,background .15s;text-align:center}
.cfg-upload-zone:hover{border-color:#4ade80;background:rgba(240,253,244,.5)}
.cfg-upload-circle{width:2.5rem;height:2.5rem;border-radius:50%;background:#f3f4f6;display:flex;align-items:center;justify-content:center;transition:background .15s}
.cfg-upload-zone:hover .cfg-upload-circle{background:#dcfce7}
.cfg-upload-circle svg{width:1.25rem;height:1.25rem;color:#9ca3af;transition:color .15s}
.cfg-upload-zone:hover .cfg-upload-circle svg{color:#16a34a}
.cfg-upload-text{font-size:.8125rem;font-weight:500;color:#6b7280;transition:color .15s}
.cfg-upload-zone:hover .cfg-upload-text{color:#15803d}
.cfg-upload-hint{font-size:.6875rem;color:#9ca3af}

/* ---------- toggle / switch ---------- */
.cfg-toggles-grid{display:grid;grid-template-columns:1fr;gap:.75rem}
@media(min-width:640px){.cfg-toggles-grid{grid-template-columns:repeat(2,1fr)}}
@media(min-width:1024px){.cfg-toggles-grid{grid-template-columns:repeat(3,1fr)}}
.cfg-toggle{display:flex;align-items:center;justify-content:space-between;gap:1rem;border:1px solid #e5e7eb;border-radius:.75rem;padding:.875rem 1.25rem;cursor:pointer;transition:background .15s,border-color .15s;background:#f9fafb}
.cfg-toggle:hover{background:#f0fdf4;border-color:#86efac}
.cfg-toggle-label{font-size:.8125rem;font-weight:600;color:#374151;line-height:1.3}
.cfg-toggle:hover .cfg-toggle-label{color:#166534}
.cfg-switch{position:relative;display:inline-flex;align-items:center;flex-shrink:0}
.cfg-switch input{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0}
.cfg-switch-track{width:2.75rem;height:1.5rem;background:#d1d5db;border-radius:1rem;position:relative;transition:background .2s}
.cfg-switch input:checked+.cfg-switch-track{background:#16a34a}
.cfg-switch-track::after{content:'';position:absolute;top:.125rem;left:.125rem;width:1.25rem;height:1.25rem;background:#fff;border-radius:50%;box-shadow:0 1px 3px rgba(0,0,0,.15);transition:transform .2s}
.cfg-switch input:checked+.cfg-switch-track::after{transform:translateX(1.25rem)}

/* ---------- range (partners) ---------- */
.cfg-range{width:100%;height:.375rem;border-radius:1rem;-webkit-appearance:none;appearance:none;background:#e5e7eb;cursor:pointer;outline:none}
.cfg-range::-webkit-slider-thumb{-webkit-appearance:none;width:1rem;height:1rem;border-radius:50%;background:#16a34a;border:2px solid #fff;box-shadow:0 1px 4px rgba(0,0,0,.18);cursor:pointer}
.cfg-range::-moz-range-thumb{width:1rem;height:1rem;border-radius:50%;background:#16a34a;border:2px solid #fff;box-shadow:0 1px 4px rgba(0,0,0,.18);cursor:pointer}
.cfg-range.--blue::-webkit-slider-thumb{background:#2563eb}
.cfg-range.--blue::-moz-range-thumb{background:#2563eb}
.cfg-range.--purple::-webkit-slider-thumb{background:#9333ea}
.cfg-range.--purple::-moz-range-thumb{background:#9333ea}
.cfg-range-ends{display:flex;justify-content:space-between;font-size:.6875rem;color:#9ca3af;margin-top:.375rem}
.cfg-badge{font-size:.6875rem;font-weight:700;padding:.125rem .625rem;border-radius:1rem;display:inline-block}
.cfg-badge.--green{background:#dcfce7;color:#166534}
.cfg-badge.--blue{background:#dbeafe;color:#1e40af}
.cfg-badge.--purple{background:#f3e8ff;color:#6b21a8}

/* ---------- radio cards (animation type) ---------- */
.cfg-radio-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:.75rem}
.cfg-radio-card{position:relative;cursor:pointer}
.cfg-radio-card input{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0}
.cfg-radio-inner{display:flex;flex-direction:column;align-items:center;gap:.5rem;border:2px solid #e5e7eb;border-radius:.75rem;padding:.75rem;transition:border-color .15s,background .15s;background:#fff}
.cfg-radio-card input:checked~.cfg-radio-inner{border-color:#22c55e;background:#f0fdf4}
.cfg-radio-inner svg{width:1.25rem;height:1.25rem;color:#9ca3af;transition:color .15s}
.cfg-radio-card input:checked~.cfg-radio-inner svg{color:#16a34a}
.cfg-radio-inner span{font-size:.6875rem;font-weight:600;color:#6b7280}
.cfg-radio-card input:checked~.cfg-radio-inner span{color:#15803d}
.cfg-radio-check{display:none;position:absolute;top:-.375rem;right:-.375rem;width:1rem;height:1rem;background:#22c55e;border-radius:50%;align-items:center;justify-content:center}
.cfg-radio-card input:checked~.cfg-radio-check{display:flex}
.cfg-radio-check svg{width:.625rem;height:.625rem;color:#fff}

/* ---------- partners preview bar ---------- */
.cfg-preview-bar{border-radius:.75rem;border:1px dashed #d1d5db;background:#f9fafb;padding:.875rem 1.25rem;display:flex;flex-wrap:wrap;align-items:center;gap:1rem;font-size:.8125rem;color:#6b7280;margin-bottom:1.25rem;box-sizing:border-box;max-width:100%}
.cfg-preview-bar strong{color:#111827}
.cfg-preview-bar svg{width:1rem;height:1rem;color:#9ca3af;flex-shrink:0}

/* ---------- toast ---------- */
.cfg-toast{position:fixed;top:1.25rem;right:1.25rem;z-index:50;display:flex;align-items:center;gap:.75rem;background:#fff;border:1px solid #bbf7d0;color:#166534;border-radius:1rem;padding:1rem 1.25rem;box-shadow:0 10px 25px -5px rgba(22,163,74,.12)}
.cfg-toast-icon{width:2rem;height:2rem;border-radius:50%;background:#22c55e;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.cfg-toast-icon svg{width:1rem;height:1rem;color:#fff}
.cfg-toast-text{font-size:.8125rem;font-weight:600}
.cfg-toast-close{margin-left:.75rem;background:none;border:none;color:#9ca3af;cursor:pointer;padding:.25rem;transition:color .15s;font-family:inherit}
.cfg-toast-close:hover{color:#374151}
.cfg-toast-close svg{width:1rem;height:1rem}

/* ---------- save bottom ---------- */
.cfg-save-bottom{margin-top:2rem;padding-top:1.5rem;border-top:1px solid #e5e7eb;display:flex;align-items:center;justify-content:flex-end}
.cfg-save-btn{display:inline-flex;align-items:center;gap:.5rem;background:#15803d;color:#fff;font-weight:600;font-size:.875rem;padding:.75rem 2rem;border-radius:.75rem;border:none;cursor:pointer;transition:background .15s;font-family:inherit}
.cfg-save-btn:hover{background:#166534}
.cfg-save-btn svg{width:1rem;height:1rem}

/* ---------- number input ---------- */
.cfg-num-wrap{display:flex;align-items:center;gap:.5rem}
.cfg-num-input{width:7rem;border:1px solid #d1d5db;border-radius:.75rem;padding:.625rem 1rem;font-size:.8125rem;font-family:inherit;background:#f9fafb;outline:none;transition:border-color .15s,box-shadow .15s;box-sizing:border-box}
.cfg-num-input:focus{border-color:#16a34a;box-shadow:0 0 0 3px rgba(22,163,74,.15);background:#fff}
.cfg-num-unit{font-size:.8125rem;font-weight:500;color:#9ca3af;background:#f3f4f6;border:1px solid #e5e7eb;border-radius:.75rem;padding:.625rem .75rem}

/* ---------- responsive ---------- */
@media(max-width:1024px){
    .cfg-wrap{flex-direction:column}
    .cfg-side{width:100%;position:static}
    .cfg-nav-list{display:flex;flex-wrap:wrap;gap:.25rem;padding:.5rem}
    .cfg-nav-btn{padding:.5rem .75rem;font-size:.75rem}
    .cfg-nav-ico{width:1.25rem;height:1.25rem}
    .cfg-nav-ico svg{width:.75rem;height:.75rem}
    .cfg-save-side{display:none}
}
@media(max-width:767px){
    .cfg-grid{grid-template-columns:1fr!important}
    .cfg-toggles-grid{grid-template-columns:1fr!important}
    .cfg-radio-grid{grid-template-columns:repeat(3,1fr)}
    .cfg-card-body{padding:1rem}
    .cfg-preview-bar{padding:.75rem 1rem;gap:.75rem;font-size:.75rem}
}

/* ══════════ DARK MODE ══════════ */
[data-theme="dark"] .cfg-nav{background:#1f2937;border-color:#374151}
[data-theme="dark"] .cfg-nav-title{color:#6b7280}
[data-theme="dark"] .cfg-nav-btn{color:#d1d5db}
[data-theme="dark"] .cfg-nav-btn:hover{background:#374151}
[data-theme="dark"] .cfg-nav-btn.--on{background:#16a34a;color:#fff}
[data-theme="dark"] .cfg-nav-btn:not(.--on) .cfg-nav-count{color:#6b7280}
[data-theme="dark"] .cfg-phead h2{color:#f9fafb}
[data-theme="dark"] .cfg-phead p{color:#6b7280}
[data-theme="dark"] .cfg-card{background:#1f2937;border-color:#374151}
[data-theme="dark"] .cfg-card-head{background:linear-gradient(to right,#1a2535,#1f2937);border-color:#374151}
[data-theme="dark"] .cfg-card-head p{color:#9ca3af}
[data-theme="dark"] .cfg-card-head svg{color:#6b7280}
[data-theme="dark"] .cfg-label{color:#d1d5db}
[data-theme="dark"] .cfg-input{background:#374151;border-color:#4b5563;color:#f9fafb}
[data-theme="dark"] .cfg-input:focus{background:#374151;border-color:#22c55e;box-shadow:0 0 0 3px rgba(34,197,94,.15)}
[data-theme="dark"] .cfg-input-icon svg{color:#6b7280}
[data-theme="dark"] .cfg-textarea{background:#374151;border-color:#4b5563;color:#f9fafb}
[data-theme="dark"] .cfg-textarea:focus{background:#374151;border-color:#22c55e;box-shadow:0 0 0 3px rgba(34,197,94,.15)}
[data-theme="dark"] .cfg-num-input{background:#374151;border-color:#4b5563;color:#f9fafb}
[data-theme="dark"] .cfg-num-input:focus{background:#374151;border-color:#22c55e}
[data-theme="dark"] .cfg-num-unit{background:#1f2937;border-color:#4b5563;color:#9ca3af}
[data-theme="dark"] .cfg-toggle{background:#1a2535;border-color:#374151}
[data-theme="dark"] .cfg-toggle:hover{background:rgba(22,163,74,.1);border-color:#22c55e}
[data-theme="dark"] .cfg-toggle-label{color:#d1d5db}
[data-theme="dark"] .cfg-toggle:hover .cfg-toggle-label{color:#4ade80}
[data-theme="dark"] .cfg-switch-track{background:#4b5563}
[data-theme="dark"] .cfg-img-current{background:#1a2535;border-color:#374151}
[data-theme="dark"] .cfg-img-current img{background:#374151;border-color:#4b5563}
[data-theme="dark"] .cfg-img-name{color:#d1d5db}
[data-theme="dark"] .cfg-img-remove{background:rgba(239,68,68,.1);border-color:rgba(239,68,68,.3);color:#f87171}
[data-theme="dark"] .cfg-img-remove:hover{background:rgba(239,68,68,.2);border-color:rgba(239,68,68,.5)}
[data-theme="dark"] .cfg-upload-zone{border-color:#4b5563}
[data-theme="dark"] .cfg-upload-zone:hover{border-color:#22c55e;background:rgba(34,197,94,.06)}
[data-theme="dark"] .cfg-upload-circle{background:#374151}
[data-theme="dark"] .cfg-upload-zone:hover .cfg-upload-circle{background:rgba(34,197,94,.15)}
[data-theme="dark"] .cfg-upload-circle svg{color:#6b7280}
[data-theme="dark"] .cfg-upload-text{color:#9ca3af}
[data-theme="dark"] .cfg-upload-zone:hover .cfg-upload-text{color:#4ade80}
[data-theme="dark"] .cfg-range{background:#4b5563}
[data-theme="dark"] .cfg-badge.--green{background:rgba(22,163,74,.2);color:#4ade80}
[data-theme="dark"] .cfg-badge.--blue{background:rgba(37,99,235,.2);color:#60a5fa}
[data-theme="dark"] .cfg-badge.--purple{background:rgba(147,51,234,.2);color:#c084fc}
[data-theme="dark"] .cfg-radio-inner{background:#1a2535;border-color:#374151}
[data-theme="dark"] .cfg-radio-card input:checked~.cfg-radio-inner{background:rgba(34,197,94,.1);border-color:#22c55e}
[data-theme="dark"] .cfg-radio-inner span{color:#9ca3af}
[data-theme="dark"] .cfg-radio-card input:checked~.cfg-radio-inner span{color:#4ade80}
[data-theme="dark"] .cfg-preview-bar{background:#1a2535;border-color:#374151;color:#9ca3af}
[data-theme="dark"] .cfg-preview-bar strong{color:#f9fafb}
[data-theme="dark"] .cfg-toast{background:#1f2937;border-color:#166534;color:#4ade80;box-shadow:0 10px 25px -5px rgba(0,0,0,.4)}
[data-theme="dark"] .cfg-toast-text{color:#4ade80}
[data-theme="dark"] .cfg-toast-close{color:#6b7280}
[data-theme="dark"] .cfg-toast-close:hover{color:#d1d5db}
[data-theme="dark"] .cfg-save-bottom{border-color:#374151}
</style>

<?php
/* ─── Metadados dos grupos (cores em hex para inline style) ─── */
$groupLabels = [
    'general'     => ['label' => 'Geral',           'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z', 'color' => '#475569', 'bg' => '#f1f5f9'],
    'contact'     => ['label' => 'Contato',          'icon' => 'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z', 'color' => '#2563eb', 'bg' => '#eff6ff'],
    'social'      => ['label' => 'Redes Sociais',    'icon' => 'M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9', 'color' => '#9333ea', 'bg' => '#faf5ff'],
    'seo'         => ['label' => 'SEO',              'icon' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z', 'color' => '#d97706', 'bg' => '#fffbeb'],
    'home'        => ['label' => 'Página Inicial',   'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'color' => '#15803d', 'bg' => '#f0fdf4'],
    'maintenance' => ['label' => 'Manutenção',       'icon' => 'M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4', 'color' => '#ea580c', 'bg' => '#fff7ed'],
    'email'       => ['label' => 'E-mail SMTP',      'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'color' => '#0891b2', 'bg' => '#ecfeff'],
    'security'    => ['label' => 'Segurança',        'icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z', 'color' => '#dc2626', 'bg' => '#fef2f2'],
    'partners'    => ['label' => 'Parceiros',        'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'color' => '#0d9488', 'bg' => '#f0fdfa'],
];

/* ─── Placeholders ─── */
$placeholders = [
    'site_name'             => 'Ex: ISSM - Instituto Socioambiental',
    'site_description'      => 'Breve descrição do site...',
    'about_text'            => 'Texto institucional sobre a organização...',
    'mission'               => 'A missão da organização...',
    'vision'                => 'A visão de futuro...',
    'values'                => 'Valores: Sustentabilidade, Inovação...',
    'contact_email'         => 'Ex: contato@issm.org.br',
    'contact_phone'         => 'Ex: (21) 99999-9999',
    'contact_address'       => 'Ex: Serra do Mendanha, RJ',
    'contact_cep'           => 'Ex: 00000-000',
    'contact_map_embed'     => 'Cole aqui o embed do Google Maps...',
    'social_facebook'       => 'Ex: https://facebook.com/issm',
    'social_instagram'      => 'Ex: https://instagram.com/issm',
    'social_twitter'        => 'Ex: https://x.com/issm',
    'social_youtube'        => 'Ex: https://youtube.com/@issm',
    'social_linkedin'       => 'Ex: https://linkedin.com/company/issm',
    'social_whatsapp'       => 'Ex: https://wa.me/5521999999999',
    'meta_title'            => 'Ex: ISSM | Preservação Ambiental',
    'meta_description'      => 'Descrição para mecanismos de busca (até 160 caracteres)...',
    'meta_keywords'         => 'Ex: meio ambiente, ODS, sustentabilidade',
    'meta_author'           => 'Ex: ISSM - Instituto Socioambiental',
    'canonical_url'         => 'Ex: https://issm.org.br',
    'robots_meta'           => 'Ex: index, follow',
    'og_title'              => 'Título para compartilhamento em redes sociais',
    'og_description'        => 'Descrição para Facebook, WhatsApp, LinkedIn...',
    'og_type'               => 'Ex: website, article, organization',
    'og_locale'             => 'Ex: pt_BR',
    'og_site_name'          => 'Ex: ISSM - Instituto Socioambiental',
    'twitter_card'          => 'Ex: summary_large_image ou summary',
    'twitter_title'         => 'Título para Twitter/X Cards',
    'twitter_description'   => 'Descrição para Twitter/X Cards',
    'twitter_handle'        => 'Ex: @issm_oficial',
    'google_analytics'      => 'Ex: G-XXXXXXXXXX',
    'google_tag_manager'    => 'Ex: GTM-XXXXXXX',
    'google_site_verification' => 'Código de verificação do Google Search Console',
    'bing_site_verification'=> 'Código de verificação do Bing Webmaster Tools',
    'maintenance_title'     => 'Ex: Site em Manutenção',
    'maintenance_message'   => 'Mensagem exibida na página de manutenção...',
    'maintenance_email'     => 'Ex: contato@issm.org.br',
    'maintenance_bg_color'  => 'Ex: #14532d',
    'maintenance_animation' => 'gear / pulse / wave / none',
    'maintenance_return_date'=> 'Ex: 2025-12-31T18:00',
    'maintenance_progress'  => 'Ex: 65',
    'recaptcha_site_key'    => 'Ex: 6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI',
    'recaptcha_secret_key'  => 'Ex: 6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe',
    'mail_host'             => 'Ex: mail.issm.org.br',
    'mail_port'             => 'Ex: 465',
    'mail_username'         => 'Ex: contato@issm.org.br',
    'mail_password'         => '••••••••',
    'mail_encryption'       => 'ssl, tls ou vazio',
    'mail_from_address'     => 'Ex: contato@issm.org.br',
    'mail_from_name'        => 'Ex: ISSM - Instituto Socioambiental',
];

/* ─── Ordenar grupos conforme groupLabels ─── */
$sorted = collect();
foreach (array_keys($groupLabels) as $k) { if (isset($settings[$k])) $sorted[$k] = $settings[$k]; }
foreach ($settings as $k => $v) { if (!$sorted->has($k)) $sorted[$k] = $v; }
$firstGroup = $sorted->keys()->first();
?>


<?php if(session('success')): ?>
<div class="cfg-toast" id="cfg-toast">
    <div class="cfg-toast-icon">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
    </div>
    <span class="cfg-toast-text"><?php echo e(session('success')); ?></span>
    <button class="cfg-toast-close" onclick="this.closest('.cfg-toast').remove()" type="button">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
</div>
<script>setTimeout(function(){var t=document.getElementById('cfg-toast');if(t){t.style.transition='opacity .4s,transform .4s';t.style.opacity='0';t.style.transform='translateX(16px)';setTimeout(function(){t.remove()},400)}},4000);</script>
<?php endif; ?>


<form method="POST" action="<?php echo e(route('admin.settings.update')); ?>" enctype="multipart/form-data" id="settings-form">
    <?php echo csrf_field(); ?>

    <div class="cfg-wrap">

        
        <aside class="cfg-side">
            <nav class="cfg-nav">
                <p class="cfg-nav-title">Seções</p>
                <ul class="cfg-nav-list" id="cfg-nav">
                    <?php $__currentLoopData = $sorted; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $m = $groupLabels[$group] ?? ['label'=>ucfirst($group),'icon'=>'','color'=>'#6b7280','bg'=>'#f3f4f6']; ?>
                    <li>
                        <button type="button"
                            data-group="<?php echo e($group); ?>"
                            onclick="cfgSwitch('<?php echo e($group); ?>')"
                            class="cfg-nav-btn <?php echo e($group === $firstGroup ? '--on' : ''); ?>">
                            <span class="cfg-nav-ico" style="background:<?php echo e($group === $firstGroup ? 'rgba(255,255,255,.2)' : $m['bg']); ?>">
                                <svg style="color:<?php echo e($group === $firstGroup ? '#fff' : $m['color']); ?>" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="<?php echo e($m['icon']); ?>"/>
                                </svg>
                            </span>
                            <span style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?php echo e($m['label']); ?></span>
                            <span class="cfg-nav-count"><?php echo e($items->count()); ?></span>
                        </button>
                    </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </nav>
            <button type="submit" class="cfg-save-side">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                Salvar
            </button>
        </aside>

        
        <div class="cfg-main">
            <?php $__currentLoopData = $sorted; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $m        = $groupLabels[$group] ?? ['label'=>ucfirst($group),'icon'=>'','color'=>'#6b7280','bg'=>'#f3f4f6'];
                $booleans = $items->where('type','boolean');
                $others   = $items->where('type','!=','boolean');
            ?>

            <div id="panel-<?php echo e($group); ?>" class="cfg-panel <?php echo e($group === $firstGroup ? '--show' : ''); ?>">

                
                <div class="cfg-phead">
                    <div class="cfg-phead-ico" style="background:<?php echo e($m['bg']); ?>">
                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75" style="color:<?php echo e($m['color']); ?>">
                            <path stroke-linecap="round" stroke-linejoin="round" d="<?php echo e($m['icon']); ?>"/>
                        </svg>
                    </div>
                    <div>
                        <h2><?php echo e($m['label']); ?></h2>
                        <p><?php echo e($items->count()); ?> configuração<?php echo e($items->count()!=1?'ões':''); ?></p>
                    </div>
                </div>

                
                <?php if($group === 'partners'): ?>
                <?php $cv = fn(string $k, $d='') => $items->firstWhere('key',$k)?->value ?? $d; ?>

                
                <div class="cfg-preview-bar">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <span>Velocidade: <strong id="lbl-speed"><?php echo e($cv('partners_carousel_speed','3000')); ?></strong> ms</span>
                    <span>Logos/tela: <strong id="lbl-perview"><?php echo e($cv('partners_carousel_per_view','4')); ?></strong></span>
                    <span>Altura: <strong id="lbl-height"><?php echo e($cv('partners_logo_height','64')); ?></strong> px</span>
                    <strong id="lbl-effect" style="color:#0d9488;text-transform:capitalize"><?php echo e($cv('partners_carousel_effect','slide')); ?></strong>
                </div>

                <div class="cfg-card">
                    <div class="cfg-card-head">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                        <p>Ajustes do Carrossel</p>
                    </div>
                    <div class="cfg-card-body">
                        <div class="cfg-grid">
                            
                            <div>
                                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.75rem">
                                    <label class="cfg-label" style="margin:0">Velocidade do autoplay</label>
                                    <span class="cfg-badge --green" id="badge-speed"><?php echo e($cv('partners_carousel_speed','3000')); ?> ms</span>
                                </div>
                                <input type="range" name="partners_carousel_speed" min="500" max="10000" step="500"
                                       value="<?php echo e($cv('partners_carousel_speed','3000')); ?>" class="cfg-range"
                                       oninput="document.getElementById('badge-speed').textContent=this.value+' ms';document.getElementById('lbl-speed').textContent=this.value;">
                                <div class="cfg-range-ends"><span>500ms</span><span>10s</span></div>
                            </div>
                            
                            <div>
                                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.75rem">
                                    <label class="cfg-label" style="margin:0">Logos por tela</label>
                                    <span class="cfg-badge --blue" id="badge-perview"><?php echo e($cv('partners_carousel_per_view','4')); ?></span>
                                </div>
                                <input type="range" name="partners_carousel_per_view" min="1" max="8" step="1"
                                       value="<?php echo e($cv('partners_carousel_per_view','4')); ?>" class="cfg-range --blue"
                                       oninput="document.getElementById('badge-perview').textContent=this.value;document.getElementById('lbl-perview').textContent=this.value;">
                                <div class="cfg-range-ends"><?php for($i=1;$i<=8;$i++): ?><span><?php echo e($i); ?></span><?php endfor; ?></div>
                            </div>
                            
                            <div>
                                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.75rem">
                                    <label class="cfg-label" style="margin:0">Altura dos logos</label>
                                    <span class="cfg-badge --purple" id="badge-height"><?php echo e($cv('partners_logo_height','64')); ?> px</span>
                                </div>
                                <input type="range" name="partners_logo_height" min="32" max="140" step="4"
                                       value="<?php echo e($cv('partners_logo_height','64')); ?>" class="cfg-range --purple"
                                       oninput="document.getElementById('badge-height').textContent=this.value+' px';document.getElementById('lbl-height').textContent=this.value;">
                                <div class="cfg-range-ends"><span>32px</span><span>140px</span></div>
                            </div>
                            
                            <div>
                                <label class="cfg-label">Tipo de animação</label>
                                <?php $curEffect = $cv('partners_carousel_effect','slide'); ?>
                                <div class="cfg-radio-grid">
                                    <?php $__currentLoopData = ['slide'=>['Slide','M9 5l7 7-7 7'],'fade'=>['Fade','M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4'],'coverflow'=>['Coverflow','M4 6h16M4 12h16M4 18h7']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val=>[$lbl,$ico]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="cfg-radio-card">
                                        <input type="radio" name="partners_carousel_effect" value="<?php echo e($val); ?>" <?php echo e($curEffect===$val?'checked':''); ?>

                                               onchange="document.getElementById('lbl-effect').textContent='<?php echo e($lbl); ?>'">
                                        <div class="cfg-radio-inner">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="<?php echo e($ico); ?>"/></svg>
                                            <span><?php echo e($lbl); ?></span>
                                        </div>
                                        <div class="cfg-radio-check">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                    </label>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <?php $toggles=['partners_carousel_autoplay'=>'Autoplay automático','partners_carousel_loop'=>'Loop infinito','partners_carousel_dots'=>'Mostrar indicadores','partners_carousel_arrows'=>'Mostrar setas']; ?>
                <div class="cfg-card">
                    <div class="cfg-card-head">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p>Comportamento</p>
                    </div>
                    <div class="cfg-card-body">
                        <div class="cfg-toggles-grid" style="grid-template-columns:repeat(2,1fr)">
                            <?php $__currentLoopData = $toggles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tKey=>$tLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $tVal=$items->firstWhere('key',$tKey)?->value??'0'; ?>
                            <input type="hidden" name="<?php echo e($tKey); ?>" value="0">
                            <label class="cfg-toggle">
                                <span class="cfg-toggle-label"><?php echo e($tLabel); ?></span>
                                <span class="cfg-switch">
                                    <input type="checkbox" name="<?php echo e($tKey); ?>" value="1" <?php echo e($tVal=='1'?'checked':''); ?>>
                                    <span class="cfg-switch-track"></span>
                                </span>
                            </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>

                <?php else: ?>
                

                
                <?php if($others->count()): ?>
                <div class="cfg-card">
                    <div class="cfg-card-head">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        <p>Campos</p>
                    </div>
                    <div class="cfg-card-body">
                        <div class="cfg-grid">
                            <?php $__currentLoopData = $others; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $wide    = in_array($s->type,['textarea','image']);
                                $fLabel  = $s->label ?? str_replace('_',' ',ucwords($s->key,'_'));
                                $ph      = $placeholders[$s->key] ?? '';
                                $isUrl   = str_contains($s->key,'url')||str_contains($s->key,'link')||in_array($s->key,['social_facebook','social_instagram','social_youtube','social_linkedin','social_twitter','social_whatsapp']);
                                $isEmail = str_contains($s->key,'email') && !str_starts_with($s->key,'mail_');
                                $isPhone = str_contains($s->key,'phone')||str_contains($s->key,'telefone')||str_contains($s->key,'tel');
                                $hasIcon = $isUrl||$isEmail||$isPhone;
                            ?>
                            <div class="<?php echo e($wide ? 'cfg-wide' : ''); ?>">
                                <label class="cfg-label"><?php echo e($fLabel); ?></label>

                                
                                <?php if($s->type==='textarea'): ?>
                                <textarea name="<?php echo e($s->key); ?>" rows="4" class="cfg-textarea wysiwyg" data-height="150" placeholder="<?php echo e($ph); ?>"><?php echo e($s->value); ?></textarea>

                                
                                <?php elseif($s->type==='image'): ?>
                                <div>
                                    <?php if($s->value): ?>
                                    <div class="cfg-img-current">
                                        <img src="<?php echo e(asset('media/'.$s->value)); ?>" alt="<?php echo e($fLabel); ?>">
                                        <div class="cfg-img-info">
                                            <div class="cfg-img-name"><?php echo e(basename($s->value)); ?></div>
                                            <div class="cfg-img-hint">Imagem atual</div>
                                            <label class="cfg-img-remove">
                                                <input type="checkbox" name="<?php echo e($s->key); ?>_remove" value="1"> Remover
                                            </label>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    <label class="cfg-upload-zone">
                                        <div class="cfg-upload-circle">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                        </div>
                                        <span class="cfg-upload-text" id="lbl-<?php echo e($s->key); ?>"><?php echo e($s->value ? 'Clique para substituir' : 'Selecionar arquivo'); ?></span>
                                        <span class="cfg-upload-hint">PNG · JPG · SVG · WEBP</span>
                                        <input type="file" name="<?php echo e($s->key); ?>_file" accept="image/*" data-no-dropzone="1" style="display:none"
                                               onchange="document.getElementById('lbl-<?php echo e($s->key); ?>').textContent=this.files[0]?.name??'Selecionado'">
                                    </label>
                                    <input type="hidden" name="<?php echo e($s->key); ?>" value="<?php echo e($s->value); ?>">
                                </div>

                                
                                <?php elseif(in_array($s->key,['partners_carousel_speed','partners_logo_height','partners_carousel_per_view'])): ?>
                                <div class="cfg-num-wrap">
                                    <input type="number" name="<?php echo e($s->key); ?>" value="<?php echo e($s->value); ?>" class="cfg-num-input">
                                    <span class="cfg-num-unit"><?php if(str_contains($s->key,'_speed')): ?>ms <?php elseif(str_contains($s->key,'_height')): ?>px <?php else: ?> un <?php endif; ?></span>
                                </div>

                                
                                <?php elseif($s->type==='password'): ?>
                                <div class="cfg-input-wrap">
                                    <span class="cfg-input-icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg></span>
                                    <input type="password" name="<?php echo e($s->key); ?>" value="<?php echo e($s->value); ?>"
                                           class="cfg-input cfg-input--iconed"
                                           placeholder="<?php echo e($ph ?: '••••••••'); ?>"
                                           autocomplete="off">
                                </div>

                                
                                <?php else: ?>
                                <div class="cfg-input-wrap">
                                    <?php if($isUrl): ?>
                                    <span class="cfg-input-icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg></span>
                                    <?php elseif($isEmail): ?>
                                    <span class="cfg-input-icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></span>
                                    <?php elseif($isPhone): ?>
                                    <span class="cfg-input-icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg></span>
                                    <?php endif; ?>
                                    <input type="text" name="<?php echo e($s->key); ?>" value="<?php echo e($s->value); ?>"
                                           class="cfg-input <?php echo e($hasIcon ? 'cfg-input--iconed' : ''); ?>"
                                           placeholder="<?php echo e($ph); ?>">
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                
                <?php if($booleans->count()): ?>
                <div class="cfg-card">
                    <div class="cfg-card-head">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p>Opções de exibição</p>
                    </div>
                    <div class="cfg-card-body">
                        <div class="cfg-toggles-grid">
                            <?php $__currentLoopData = $booleans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $bLabel=$s->label??str_replace('_',' ',ucwords($s->key,'_')); ?>
                            <input type="hidden" name="<?php echo e($s->key); ?>" value="0">
                            <label class="cfg-toggle">
                                <span class="cfg-toggle-label"><?php echo e($bLabel); ?></span>
                                <span class="cfg-switch">
                                    <input type="checkbox" name="<?php echo e($s->key); ?>" value="1" <?php echo e($s->value=='1'?'checked':''); ?>>
                                    <span class="cfg-switch-track"></span>
                                </span>
                            </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php endif; ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            
            <div class="cfg-save-bottom">
                <button type="submit" class="cfg-save-btn">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    Salvar Configurações
                </button>
            </div>
        </div>
    </div>
</form>


<script>
function cfgSwitch(group) {
    // Ocultar todos os painéis
    document.querySelectorAll('.cfg-panel').forEach(function(p) {
        p.classList.remove('--show');
    });
    // Mostrar painel selecionado
    var target = document.getElementById('panel-' + group);
    if (target) target.classList.add('--show');

    // Atualizar botões
    document.querySelectorAll('.cfg-nav-btn').forEach(function(btn) {
        var isActive = btn.getAttribute('data-group') === group;
        btn.classList.toggle('--on', isActive);

        var ico = btn.querySelector('.cfg-nav-ico');
        var svg = ico ? ico.querySelector('svg') : null;
        if (isActive) {
            ico.style.background = 'rgba(255,255,255,.2)';
            if (svg) svg.style.color = '#fff';
        } else {
            var origBg = ico.getAttribute('data-bg');
            var origColor = svg ? svg.getAttribute('data-color') : null;
            if (origBg) ico.style.background = origBg;
            if (origColor && svg) svg.style.color = origColor;
        }
    });
}

// Salvar cores originais
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.cfg-nav-ico').forEach(function(ico) {
        ico.setAttribute('data-bg', ico.style.background || '');
        var svg = ico.querySelector('svg');
        if (svg) svg.setAttribute('data-color', svg.style.color || '');
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.admin", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/issmorg/public_html/resources/views/admin/settings/index.blade.php ENDPATH**/ ?>
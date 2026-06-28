@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Visão geral do sistema')

@section('content')
<style>
.dash-wrap{max-width:100%;overflow:hidden}
.dash-kpis{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:18px;margin-bottom:24px}
.dash-kpi{position:relative;background:#fff;border:1px solid #e5e7eb;border-radius:18px;padding:18px;overflow:hidden;box-shadow:0 12px 30px rgba(15,23,42,.06);min-height:184px}
.dash-kpi::before{content:'';position:absolute;inset:0 0 auto 0;height:4px;background:var(--kpi-color)}
.dash-kpi-head{display:flex;align-items:flex-start;justify-content:space-between;gap:12px}
.dash-kpi-label{font-size:12px;font-weight:800;color:#6b7280;text-transform:uppercase;letter-spacing:.04em;margin:0}
.dash-kpi-value{font-size:34px;line-height:1;font-weight:950;color:#111827;margin:8px 0 0;font-variant-numeric:tabular-nums}
.dash-kpi-icon{width:44px;height:44px;border-radius:14px;display:flex;align-items:center;justify-content:center;background:#f8fafc;color:var(--kpi-color);flex-shrink:0}
.dash-kpi-icon svg{width:22px;height:22px}
.dash-kpi-meta{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-top:12px}
.dash-kpi-trend{display:inline-flex;align-items:center;gap:4px;font-size:12px;font-weight:900;color:var(--kpi-color);background:#f8fafc;border-radius:999px;padding:5px 9px}
.dash-kpi-today{font-size:12px;font-weight:700;color:#6b7280}
.dash-spark{height:52px;margin-top:14px}
.dash-spark svg{width:100%;height:52px;display:block;overflow:visible}
.dash-spark path.area{opacity:.14}
.dash-spark path.line{fill:none;stroke:var(--kpi-color);stroke-width:3;stroke-linecap:round;stroke-linejoin:round;stroke-dasharray:260;stroke-dashoffset:260;animation:dashLine 1.15s ease forwards}
.dash-spark circle{fill:#fff;stroke:var(--kpi-color);stroke-width:2;opacity:0;animation:dashDot .35s ease forwards;animation-delay:.9s}
.dash-kpi-link{display:inline-flex;align-items:center;gap:6px;margin-top:10px;color:var(--kpi-color);font-size:12px;font-weight:900;text-decoration:none}
.dash-kpi-link:hover{text-decoration:underline}
.dash-panels{display:grid;grid-template-columns:1.35fr .65fr;gap:18px;margin-bottom:24px}
.dash-panel{background:#fff;border:1px solid #e5e7eb;border-radius:18px;padding:20px;box-shadow:0 12px 30px rgba(15,23,42,.06)}
.dash-panel-title{font-size:14px;font-weight:950;color:#111827;margin:0 0 14px;display:flex;align-items:center;gap:8px}
.dash-bars{height:170px;display:flex;align-items:flex-end;gap:8px;padding-top:12px}
.dash-bar{flex:1;min-width:10px;height:var(--bar-height);background:linear-gradient(180deg,#22c55e,#15803d);border-radius:10px 10px 4px 4px;position:relative;transform-origin:bottom;animation:dashGrow .75s ease forwards;transform:scaleY(0)}
.dash-bar::after{content:attr(data-tip);position:absolute;left:50%;bottom:calc(100% + 8px);transform:translateX(-50%);background:#111827;color:#fff;border-radius:8px;padding:4px 7px;font-size:10px;font-weight:800;white-space:nowrap;opacity:0;pointer-events:none;transition:opacity .15s}
.dash-bar:hover::after{opacity:1}
.dash-bar-labels{display:flex;justify-content:space-between;margin-top:8px;color:#9ca3af;font-size:11px;font-weight:700}
.dash-ring-wrap{display:flex;align-items:center;gap:18px}
.dash-ring{width:138px;height:138px;border-radius:999px;background:conic-gradient(var(--ring));position:relative;flex-shrink:0;animation:dashSpinIn .85s ease forwards}
.dash-ring::after{content:'';position:absolute;inset:18px;background:#fff;border-radius:999px}
.dash-ring-center{position:absolute;inset:0;z-index:1;display:flex;align-items:center;justify-content:center;flex-direction:column}
.dash-ring-value{font-size:26px;font-weight:950;color:#111827;line-height:1}
.dash-ring-label{font-size:11px;font-weight:800;color:#6b7280;margin-top:2px}
.dash-legend{display:flex;flex-direction:column;gap:8px;min-width:0}
.dash-legend-row{display:flex;align-items:center;gap:8px;font-size:12px;font-weight:800;color:#374151}
.dash-legend-dot{width:10px;height:10px;border-radius:999px;flex-shrink:0}
.dash-maintenance{display:flex;align-items:center;justify-content:space-between;gap:14px;border-radius:18px;padding:16px 18px;background:#fff;border:1px solid #e5e7eb;box-shadow:0 12px 30px rgba(15,23,42,.06);margin-bottom:24px}
.dash-maintenance-left{display:flex;align-items:center;gap:12px;min-width:0}
.dash-maintenance-icon{width:42px;height:42px;border-radius:14px;display:flex;align-items:center;justify-content:center;background:var(--status-bg);color:var(--status-color);flex-shrink:0}
.dash-maintenance-icon svg{width:21px;height:21px}
.dash-maintenance-title{display:block;color:#111827;font-size:14px;font-weight:950;line-height:1.2}
.dash-maintenance-sub{display:block;color:#6b7280;font-size:12px;font-weight:700;margin-top:2px}
.dash-maintenance-status{display:inline-flex;align-items:center;gap:7px;border-radius:999px;padding:8px 12px;background:var(--status-bg);color:var(--status-color);font-size:12px;font-weight:950;white-space:nowrap}
.dash-maintenance-status::before{content:'';width:8px;height:8px;border-radius:999px;background:var(--status-color);box-shadow:0 0 0 4px var(--status-ring)}
.dash-maintenance-link{display:inline-flex;align-items:center;justify-content:center;border-radius:999px;padding:8px 12px;background:#f9fafb;border:1px solid #e5e7eb;color:#374151;font-size:12px;font-weight:950;text-decoration:none;white-space:nowrap}
.dash-maintenance-link:hover{color:var(--status-color);border-color:var(--status-color);text-decoration:none}
.dash-maintenance-actions{display:flex;align-items:center;gap:10px;flex-shrink:0}
.dash-list-card{background:#fff;border:1px solid #e5e7eb;border-radius:18px;padding:20px;box-shadow:0 12px 30px rgba(15,23,42,.06)}
.dash-list-title{font-size:15px;font-weight:950;color:#111827;margin:0 0 12px}
.dash-quick-grid{margin-top:24px;display:grid;grid-template-columns:repeat(6,minmax(0,1fr));gap:14px}
.dash-quick{background:#fff;border:1px solid #e5e7eb;border-radius:16px;padding:16px;text-align:center;box-shadow:0 8px 22px rgba(15,23,42,.05);transition:transform .15s,box-shadow .15s;text-decoration:none}
.dash-quick:hover{transform:translateY(-2px);box-shadow:0 14px 30px rgba(15,23,42,.1)}
@keyframes dashLine{to{stroke-dashoffset:0}}
@keyframes dashDot{to{opacity:1}}
@keyframes dashGrow{to{transform:scaleY(1)}}
@keyframes dashSpinIn{from{transform:rotate(-28deg) scale(.92);opacity:.5}to{transform:rotate(0) scale(1);opacity:1}}
@media(max-width:1200px){.dash-kpis{grid-template-columns:repeat(2,minmax(0,1fr))}.dash-panels{grid-template-columns:1fr}.dash-quick-grid{grid-template-columns:repeat(3,minmax(0,1fr))}}
@media(max-width:640px){.dash-kpis{grid-template-columns:1fr}.dash-ring-wrap{flex-direction:column;align-items:flex-start}.dash-bars{gap:4px}.dash-quick-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
[data-theme="dark"] .dash-kpi,[data-theme="dark"] .dash-panel,[data-theme="dark"] .dash-list-card,[data-theme="dark"] .dash-quick,[data-theme="dark"] .dash-maintenance{background:#1f2937;border-color:#374151;box-shadow:0 12px 30px rgba(0,0,0,.22)}
[data-theme="dark"] .dash-kpi-value,[data-theme="dark"] .dash-panel-title,[data-theme="dark"] .dash-ring-value,[data-theme="dark"] .dash-list-title,[data-theme="dark"] .dash-maintenance-title{color:#f9fafb}
[data-theme="dark"] .dash-kpi-label,[data-theme="dark"] .dash-kpi-today,[data-theme="dark"] .dash-ring-label{color:#9ca3af}
[data-theme="dark"] .dash-kpi-icon,[data-theme="dark"] .dash-kpi-trend{background:rgba(255,255,255,.06)}
[data-theme="dark"] .dash-spark circle{fill:#1f2937}
[data-theme="dark"] .dash-ring::after{background:#1f2937}
[data-theme="dark"] .dash-legend-row{color:#d1d5db}
[data-theme="dark"] .dash-maintenance-sub{color:#9ca3af}
[data-theme="dark"] .dash-maintenance-link{background:#111827;border-color:#374151;color:#d1d5db}
[data-theme="dark"] .dash-maintenance-link:hover{color:var(--status-color);border-color:var(--status-color)}
</style>

@php
    $sparkPoints = function (array $series, int $width = 220, int $height = 52) {
        $max = max(array_column($series, 'value') ?: [0]) ?: 1;
        $lastIndex = max(count($series) - 1, 1);

        return collect($series)->map(function ($point, $index) use ($width, $height, $max, $lastIndex) {
            $x = round(($index / $lastIndex) * $width, 2);
            $y = round($height - (($point['value'] / $max) * ($height - 8)) - 4, 2);
            return "{$x},{$y}";
        })->implode(' ');
    };
    $visitMax = max(array_column($visitSeries, 'value') ?: [0]) ?: 1;
    $mixTotal = max(collect($contentMix)->sum('value'), 1);
    $ringStart = 0;
    $ringParts = collect($contentMix)->map(function ($item) use (&$ringStart, $mixTotal) {
        $pct = round(($item['value'] / $mixTotal) * 100, 2);
        $part = "{$item['color']} {$ringStart}% ".($ringStart + $pct)."%";
        $ringStart += $pct;
        return $part;
    })->implode(', ');
@endphp

<div class="dash-wrap">
    <div class="dash-kpis">
        @foreach($kpis as $kpi)
            @php
                $points = $sparkPoints($kpi['series']);
                $areaPoints = "0,52 {$points} 220,52";
                $lastPair = collect(explode(' ', $points))->last();
                [$cx, $cy] = array_pad(explode(',', $lastPair), 2, 0);
            @endphp
            <div class="dash-kpi" style="--kpi-color:{{ $kpi['color'] }}">
                <div class="dash-kpi-head">
                    <div>
                        <p class="dash-kpi-label">{{ $kpi['label'] }}</p>
                        <p class="dash-kpi-value" data-count-to="{{ $kpi['value'] }}">0</p>
                    </div>
                    <div class="dash-kpi-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $kpi['icon'] }}"/></svg>
                    </div>
                </div>
                <div class="dash-kpi-meta">
                    <span class="dash-kpi-trend">{{ $kpi['trend'] >= 0 ? '+' : '' }}{{ $kpi['trend'] }}% hoje</span>
                    <span class="dash-kpi-today">{{ $kpi['today'] }} novo{{ $kpi['today'] != 1 ? 's' : '' }}</span>
                </div>
                <div class="dash-spark" aria-hidden="true">
                    <svg viewBox="0 0 220 52" preserveAspectRatio="none">
                        <path class="area" fill="{{ $kpi['color'] }}" d="M {{ $areaPoints }} Z"></path>
                        <path class="line" d="M {{ $points }}"></path>
                        <circle cx="{{ $cx }}" cy="{{ $cy }}" r="4"></circle>
                    </svg>
                </div>
                <a href="{{ $kpi['url'] }}" class="dash-kpi-link">{{ $kpi['action'] }} <span>→</span></a>
            </div>
        @endforeach
    </div>

    <div class="dash-maintenance" style="--status-color:{{ $maintenanceMode == "1" ? '#ea580c' : '#16a34a' }};--status-bg:{{ $maintenanceMode == "1" ? '#fff7ed' : '#f0fdf4' }};--status-ring:{{ $maintenanceMode == "1" ? 'rgba(234,88,12,.14)' : 'rgba(22,163,74,.14)' }}">
        <div class="dash-maintenance-left">
            <div class="dash-maintenance-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $maintenanceMode == "1" ? 'M12 9v2m0 4h.01M4.93 19h14.14c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.2 16c-.77 1.33.19 3 1.73 3z' : 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' }}"/></svg>
            </div>
            <div>
                <span class="dash-maintenance-title">Status operacional</span>
                <span class="dash-maintenance-sub">Controle rápido da disponibilidade pública do site</span>
            </div>
        </div>
        <div class="dash-maintenance-actions">
            <span class="dash-maintenance-status">Manutenção {{ $maintenanceMode == "1" ? "ativa" : "desativada" }}</span>
            <a href="{{ route("admin.settings.index") }}" class="dash-maintenance-link">Configurações</a>
        </div>
    </div>

    <div class="dash-panels">
        <div class="dash-panel">
            <h3 class="dash-panel-title">Visitas dos últimos 7 dias</h3>
            <div class="dash-bars">
                @foreach($visitSeries as $index => $day)
                    <div class="dash-bar"
                         style="--bar-height:{{ max(4, round(($day['value'] / $visitMax) * 100)) }}%;animation-delay:{{ $index * 70 }}ms"
                         data-tip="{{ $day['date'] }}: {{ $day['value'] }} visitas"></div>
                @endforeach
            </div>
            <div class="dash-bar-labels">
                <span>{{ $visitSeries[0]['date'] ?? '' }}</span>
                <span>{{ $visitSeries[3]['date'] ?? '' }}</span>
                <span>{{ $visitSeries[6]['date'] ?? '' }}</span>
            </div>
            <p class="text-sm text-gray-500 mt-3">
                Hoje: <strong class="text-gray-900">{{ number_format($todayVisits, 0, ",", ".") }}</strong> visitas,
                <strong class="text-gray-900">{{ number_format($todayUniqueVisitors, 0, ",", ".") }}</strong> visitantes únicos.
            </p>
        </div>

        <div class="dash-panel">
            <h3 class="dash-panel-title">Composição de conteúdo</h3>
            <div class="dash-ring-wrap">
                <div class="dash-ring" style="--ring:{{ $ringParts ?: '#e5e7eb 0% 100%' }}">
                    <div class="dash-ring-center">
                        <span class="dash-ring-value" data-count-to="{{ $mixTotal }}">0</span>
                        <span class="dash-ring-label">itens</span>
                    </div>
                </div>
                <div class="dash-legend">
                    @foreach($contentMix as $item)
                        <div class="dash-legend-row">
                            <span class="dash-legend-dot" style="background:{{ $item['color'] }}"></span>
                            <span>{{ $item['label'] }}: {{ number_format($item['value'], 0, ",", ".") }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="dash-list-card">
            <h3 class="dash-list-title">Últimas Mensagens</h3>
            @forelse($recentContacts as $contact)
                <div class="flex items-start gap-3 py-3 border-b border-gray-100 last:border-0">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0"><span class="text-green-700 font-bold text-sm">{{ substr($contact->name, 0, 1) }}</span></div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2">
                            <p class="font-medium text-gray-900 text-sm truncate">{{ $contact->name }}</p>
                            <span class="text-xs px-2 py-0.5 rounded-full flex-shrink-0 {{ $contact->status === "new" ? "bg-red-100 text-red-600" : ($contact->status === "replied" ? "bg-green-100 text-green-600" : "bg-gray-100 text-gray-600") }}">{{ $contact->status === "new" ? "Nova" : ($contact->status === "replied" ? "Respondida" : "Lida") }}</span>
                        </div>
                        <p class="text-gray-500 text-xs truncate">{{ $contact->subject }}</p>
                    </div>
                    <a href="{{ route("admin.contatos.show", $contact) }}" class="text-green-600 hover:text-green-800 text-xs">Ver</a>
                </div>
            @empty
                <p class="text-gray-400 text-sm">Nenhuma mensagem ainda.</p>
            @endforelse
        </div>

        <div class="dash-list-card">
            <h3 class="dash-list-title">Últimas Notícias</h3>
            @forelse($recentNews as $news)
                <div class="flex items-start gap-3 py-3 border-b border-gray-100 last:border-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0"><svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-gray-900 text-sm truncate">{{ $news->title }}</p>
                        <p class="text-gray-400 text-xs">{{ $news->created_at->format("d/m/Y") }}</p>
                    </div>
                    <a href="{{ route("admin.noticias.edit", $news) }}" class="text-green-600 hover:text-green-800 text-xs">Editar</a>
                </div>
            @empty
                <p class="text-gray-400 text-sm">Nenhuma notícia ainda.</p>
            @endforelse
        </div>
    </div>

    <div class="dash-quick-grid">
        <a href="{{ route("admin.banners.create") }}" class="dash-quick"><div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-2"><svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg></div><p class="text-xs font-medium text-gray-700">Novo Banner</p></a>
        <a href="{{ route("admin.noticias.create") }}" class="dash-quick"><div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2"><svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg></div><p class="text-xs font-medium text-gray-700">Nova Notícia</p></a>
        <a href="{{ route("admin.projetos.create") }}" class="dash-quick"><div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2"><svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg></div><p class="text-xs font-medium text-gray-700">Novo Projeto</p></a>
        <a href="{{ route("admin.galeria.create") }}" class="dash-quick"><div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-2"><svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg></div><p class="text-xs font-medium text-gray-700">Nova Foto</p></a>
        <a href="{{ route("admin.equipe.create") }}" class="dash-quick"><div class="w-10 h-10 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-2"><svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg></div><p class="text-xs font-medium text-gray-700">Novo Membro</p></a>
        <a href="{{ route("admin.settings.index") }}" class="dash-quick"><div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-2"><svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg></div><p class="text-xs font-medium text-gray-700">Configurações</p></a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-count-to]').forEach(function (el) {
        var target = parseInt(el.getAttribute('data-count-to') || '0', 10);
        var duration = 850;
        var start = performance.now();

        function tick(now) {
            var progress = Math.min((now - start) / duration, 1);
            var eased = 1 - Math.pow(1 - progress, 3);
            el.textContent = Math.round(target * eased).toLocaleString('pt-BR');

            if (progress < 1) {
                requestAnimationFrame(tick);
            }
        }

        requestAnimationFrame(tick);
    });
});
</script>
@endsection

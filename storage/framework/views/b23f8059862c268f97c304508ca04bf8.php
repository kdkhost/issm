
<?php $__env->startSection('title', 'Analytics'); ?>
<?php $__env->startSection('page-title', 'Analytics de Visitantes'); ?>

<?php $__env->startSection('breadcrumb'); ?>
<div class="flex items-center gap-2 text-sm text-gray-500 px-6 py-2 border-b border-gray-100">
    <a href="<?php echo e(route('admin.dashboard')); ?>" class="hover:text-green-700">Dashboard</a>
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    <span class="text-gray-800 font-medium">Analytics</span>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<style>
/* ═══ ANALYTICS SCOPED CSS ═══ */
.an-wrap { max-width:100%; overflow:hidden; padding:24px; }
.an-filter { display:flex; align-items:center; gap:8px; flex-wrap:wrap; margin-bottom:24px; }
.an-filter-btn {
    padding:6px 16px; border-radius:8px; font-size:13px; font-weight:500;
    border:1px solid #d1d5db; background:#fff; color:#4b5563; cursor:pointer;
    transition: all .2s;
}
.an-filter-btn:hover { border-color:#16a34a; color:#16a34a; }
.an-filter-btn.--active { background:#16a34a; color:#fff; border-color:#16a34a; }

/* Cards resumo */
.an-cards { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:28px; }
.an-card {
    background:#fff; border-radius:12px; padding:20px 22px;
    box-shadow:0 1px 3px rgba(0,0,0,.06); border:1px solid #e5e7eb;
    display:flex; flex-direction:column; gap:4px;
}
.an-card-label { font-size:12px; font-weight:600; color:#6b7280; text-transform:uppercase; letter-spacing:.5px; }
.an-card-value { font-size:28px; font-weight:700; color:#111827; line-height:1.2; }
.an-card-sub { font-size:12px; color:#9ca3af; }

/* Grid layout */
.an-grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px; }
.an-panel {
    background:#fff; border-radius:12px; padding:22px;
    box-shadow:0 1px 3px rgba(0,0,0,.06); border:1px solid #e5e7eb;
}
.an-panel.--full { grid-column:1 / -1; }
.an-panel-title {
    font-size:14px; font-weight:700; color:#111827; margin-bottom:16px;
    display:flex; align-items:center; gap:8px;
}
.an-panel-title svg { width:18px; height:18px; color:#16a34a; }

/* Bar chart CSS */
.an-bars { display:flex; flex-direction:column; gap:8px; }
.an-bar-row { display:flex; align-items:center; gap:10px; }
.an-bar-label { width:120px; font-size:12px; font-weight:500; color:#374151; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; flex-shrink:0; }
.an-bar-track { flex:1; background:#f3f4f6; border-radius:6px; height:22px; position:relative; overflow:hidden; }
.an-bar-fill { height:100%; border-radius:6px; transition:width .5s ease; min-width:2px; display:flex; align-items:center; padding:0 8px; }
.an-bar-fill span { font-size:11px; font-weight:600; color:#fff; white-space:nowrap; }
.an-bar-count { width:55px; text-align:right; font-size:12px; font-weight:600; color:#374151; flex-shrink:0; }

/* Source badges */
.an-src-badge {
    display:inline-flex; align-items:center; gap:5px;
    padding:3px 10px; border-radius:20px; font-size:12px; font-weight:600;
    color:#fff;
}

/* Mini chart (visits per day) */
.an-chart-area { width:100%; height:200px; display:flex; align-items:flex-end; gap:2px; }
.an-chart-bar {
    flex:1; background:linear-gradient(to top, #16a34a, #4ade80);
    border-radius:3px 3px 0 0; min-height:2px;
    position:relative; cursor:pointer; transition: opacity .2s;
}
.an-chart-bar:hover { opacity:.8; }
.an-chart-bar:hover::after {
    content: attr(data-tip);
    position:absolute; bottom:100%; left:50%; transform:translateX(-50%);
    background:#111827; color:#fff; font-size:10px; padding:3px 8px; border-radius:6px;
    white-space:nowrap; pointer-events:none; margin-bottom:4px; z-index:10;
}
.an-chart-labels { display:flex; justify-content:space-between; margin-top:6px; }
.an-chart-labels span { font-size:10px; color:#9ca3af; }

/* Devices donut (fake with CSS) */
.an-donut-wrap { display:flex; align-items:center; gap:24px; }
.an-donut {
    width:120px; height:120px; border-radius:50%;
    position:relative; flex-shrink:0;
}
.an-donut-center {
    position:absolute; top:50%; left:50%; transform:translate(-50%,-50%);
    width:60px; height:60px; border-radius:50%; background:#fff;
    display:flex; align-items:center; justify-content:center;
    font-size:20px; font-weight:700; color:#111827;
}
.an-legend { display:flex; flex-direction:column; gap:8px; }
.an-legend-item { display:flex; align-items:center; gap:8px; font-size:13px; color:#374151; }
.an-legend-dot { width:10px; height:10px; border-radius:50%; flex-shrink:0; }

/* Recent table */
.an-table { width:100%; border-collapse:collapse; font-size:12px; }
.an-table thead th {
    text-align:left; padding:8px 10px; color:#6b7280; font-weight:600;
    border-bottom:2px solid #e5e7eb; font-size:11px; text-transform:uppercase;
    letter-spacing:.4px;
}
.an-table tbody td { padding:8px 10px; border-bottom:1px solid #f3f4f6; color:#374151; vertical-align:middle; }
.an-table tbody tr:hover td { background:#f9fafb; }
.an-badge {
    display:inline-block; padding:2px 8px; border-radius:9999px; font-size:10px;
    font-weight:600; color:#fff;
}
.an-device-icon { font-size:14px; }

/* Responsive */
@media(max-width:1024px) {
    .an-cards { grid-template-columns:repeat(2,1fr); }
    .an-grid { grid-template-columns:1fr; }
}
@media(max-width:640px) {
    .an-cards { grid-template-columns:1fr; }
    .an-wrap { padding:16px; }
    .an-bar-label { width:80px; }
    .an-donut-wrap { flex-direction:column; }
}

/* Dark mode */
[data-theme="dark"] .an-card { background:#1f2937; border-color:#374151; }
[data-theme="dark"] .an-card-value { color:#f9fafb; }
[data-theme="dark"] .an-card-label { color:#9ca3af; }
[data-theme="dark"] .an-panel { background:#1f2937; border-color:#374151; }
[data-theme="dark"] .an-panel-title { color:#f9fafb; }
[data-theme="dark"] .an-bar-label { color:#d1d5db; }
[data-theme="dark"] .an-bar-track { background:#374151; }
[data-theme="dark"] .an-bar-count { color:#d1d5db; }
[data-theme="dark"] .an-filter-btn { background:#1f2937; border-color:#4b5563; color:#d1d5db; }
[data-theme="dark"] .an-filter-btn.--active { background:#16a34a; color:#fff; border-color:#16a34a; }
[data-theme="dark"] .an-donut-center { background:#1f2937; color:#f9fafb; }
[data-theme="dark"] .an-legend-item { color:#d1d5db; }
[data-theme="dark"] .an-table thead th { color:#9ca3af; border-color:#374151; }
[data-theme="dark"] .an-table tbody td { color:#d1d5db; border-color:#2d3748; }
[data-theme="dark"] .an-table tbody tr:hover td { background:rgba(255,255,255,.03); }
[data-theme="dark"] .an-chart-labels span { color:#6b7280; }
</style>

<div class="an-wrap">

    
    <div class="an-filter">
        <span style="font-size:13px;font-weight:600;color:#6b7280;margin-right:4px;">Período:</span>
        <?php $__currentLoopData = ['today'=>'Hoje','7'=>'7 dias','30'=>'30 dias','90'=>'90 dias','365'=>'1 ano']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $lbl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('admin.analytics.index', ['period' => $val])); ?>"
           class="an-filter-btn <?php echo e($period == $val ? '--active' : ''); ?>"><?php echo e($lbl); ?></a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <div class="an-cards">
        <div class="an-card">
            <span class="an-card-label">Total de Visitas</span>
            <span class="an-card-value"><?php echo e(number_format($totalVisits, 0, ',', '.')); ?></span>
            <span class="an-card-sub">No período selecionado</span>
        </div>
        <div class="an-card">
            <span class="an-card-label">Visitantes Únicos</span>
            <span class="an-card-value"><?php echo e(number_format($uniqueVisitors, 0, ',', '.')); ?></span>
            <span class="an-card-sub">IPs distintos</span>
        </div>
        <div class="an-card">
            <span class="an-card-label">Visitas Hoje</span>
            <span class="an-card-value"><?php echo e(number_format($todayVisits, 0, ',', '.')); ?></span>
            <span class="an-card-sub"><?php echo e($todayUnique); ?> visitantes únicos</span>
        </div>
        <div class="an-card">
            <span class="an-card-label">Bots Detectados</span>
            <span class="an-card-value"><?php echo e(number_format($botsCount, 0, ',', '.')); ?></span>
            <span class="an-card-sub">Excluídos das métricas</span>
        </div>
    </div>

    
    <div class="an-grid" style="margin-bottom:20px;">
        <div class="an-panel --full">
            <div class="an-panel-title">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Visitas por Dia
            </div>
            <?php $maxDay = max(array_values($filledDays) ?: [1]); ?>
            <div class="an-chart-area">
                <?php $__currentLoopData = $filledDays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="an-chart-bar"
                     style="height:<?php echo e($maxDay > 0 ? max(($count/$maxDay)*100, 1) : 1); ?>%;"
                     data-tip="<?php echo e(\Carbon\Carbon::parse($date)->format('d/m')); ?>: <?php echo e($count); ?> visitas"></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div class="an-chart-labels">
                <?php $dayKeys = array_keys($filledDays); ?>
                <span><?php echo e(\Carbon\Carbon::parse($dayKeys[0] ?? '')->format('d/m')); ?></span>
                <?php if(count($dayKeys) > 2): ?>
                <span><?php echo e(\Carbon\Carbon::parse($dayKeys[intdiv(count($dayKeys),2)] ?? '')->format('d/m')); ?></span>
                <?php endif; ?>
                <span><?php echo e(\Carbon\Carbon::parse(end($dayKeys) ?: '')->format('d/m')); ?></span>
            </div>
        </div>
    </div>

    
    <div class="an-grid">
        
        <div class="an-panel">
            <div class="an-panel-title">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                Fontes de Tráfego
            </div>
            <?php $maxSource = $topSources->max('total') ?: 1; ?>
            <div class="an-bars">
                <?php $__empty_1 = true; $__currentLoopData = $topSources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $src): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $info = $sourceLabels[$src->source] ?? ['label' => ucfirst($src->source ?? 'Desconhecido'), 'color' => '#9ca3af', 'icon' => '🔗'];
                    $pct  = round(($src->total / $maxSource) * 100);
                ?>
                <div class="an-bar-row">
                    <span class="an-bar-label" title="<?php echo e($info['label']); ?>"><?php echo e($info['icon']); ?> <?php echo e($info['label']); ?></span>
                    <div class="an-bar-track">
                        <div class="an-bar-fill" style="width:<?php echo e($pct); ?>%;background:<?php echo e($info['color']); ?>;">
                            <?php if($pct > 15): ?><span><?php echo e(round($src->total / $totalVisits * 100, 1)); ?>%</span><?php endif; ?>
                        </div>
                    </div>
                    <span class="an-bar-count"><?php echo e(number_format($src->total, 0, ',', '.')); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p style="font-size:13px;color:#9ca3af;text-align:center;padding:20px 0;">Sem dados ainda</p>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="an-panel">
            <div class="an-panel-title">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Páginas Mais Visitadas
            </div>
            <?php $maxPage = $topPages->max('total') ?: 1; ?>
            <div class="an-bars">
                <?php $__empty_1 = true; $__currentLoopData = $topPages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php $ppct = round(($pg->total / $maxPage) * 100); ?>
                <div class="an-bar-row">
                    <span class="an-bar-label" title="<?php echo e($pg->path); ?>"><?php echo e($pg->path); ?></span>
                    <div class="an-bar-track">
                        <div class="an-bar-fill" style="width:<?php echo e($ppct); ?>%;background:#3b82f6;">
                            <?php if($ppct > 15): ?><span><?php echo e($pg->total); ?></span><?php endif; ?>
                        </div>
                    </div>
                    <span class="an-bar-count"><?php echo e(number_format($pg->total, 0, ',', '.')); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p style="font-size:13px;color:#9ca3af;text-align:center;padding:20px 0;">Sem dados ainda</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <div class="an-grid">
        
        <div class="an-panel">
            <div class="an-panel-title">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                Dispositivos
            </div>
            <?php
                $devTotal = $devices->sum('total') ?: 1;
                $devColors = ['desktop'=>'#3b82f6','mobile'=>'#f59e0b','tablet'=>'#8b5cf6'];
                $devIcons  = ['desktop'=>'🖥️','mobile'=>'📱','tablet'=>'📟'];
                $devLabels = ['desktop'=>'Desktop','mobile'=>'Mobile','tablet'=>'Tablet'];
                // Conic gradient
                $conicParts = []; $acc = 0;
                foreach($devices as $d) {
                    $p = round($d->total / $devTotal * 100, 1);
                    $c = $devColors[$d->device_type] ?? '#9ca3af';
                    $conicParts[] = "$c {$acc}% ".($acc+$p)."%";
                    $acc += $p;
                }
                $conicStr = implode(', ', $conicParts) ?: '#e5e7eb 0% 100%';
            ?>
            <div class="an-donut-wrap">
                <div class="an-donut" style="background:conic-gradient(<?php echo e($conicStr); ?>);">
                    <div class="an-donut-center"><?php echo e($devTotal > 1 ? $devices->count() : 0); ?></div>
                </div>
                <div class="an-legend">
                    <?php $__currentLoopData = $devices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $dp = round($d->total / $devTotal * 100, 1); ?>
                    <div class="an-legend-item">
                        <span class="an-legend-dot" style="background:<?php echo e($devColors[$d->device_type] ?? '#9ca3af'); ?>;"></span>
                        <?php echo e($devIcons[$d->device_type] ?? '❓'); ?> <?php echo e($devLabels[$d->device_type] ?? $d->device_type); ?>

                        — <strong><?php echo e($dp); ?>%</strong> (<?php echo e(number_format($d->total, 0, ',', '.')); ?>)
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

        
        <div class="an-panel">
            <div class="an-panel-title">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                Navegadores &amp; Sistemas
            </div>
            <div style="display:flex;gap:24px;flex-wrap:wrap;">
                
                <div style="flex:1;min-width:140px;">
                    <p style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;margin-bottom:8px;">Navegador</p>
                    <?php
                        $browserTotal = $browsers->sum('total') ?: 1;
                        $bColors = ['Chrome'=>'#4285f4','Firefox'=>'#ff7139','Safari'=>'#006cff','Edge'=>'#0078d7','Opera'=>'#ff1b2d','IE'=>'#1ebbee','Other'=>'#9ca3af'];
                    ?>
                    <?php $__currentLoopData = $browsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $bp = round($b->total / $browserTotal * 100,1); ?>
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
                        <span style="width:70px;font-size:12px;font-weight:500;color:#374151;"><?php echo e($b->browser); ?></span>
                        <div style="flex:1;background:#f3f4f6;border-radius:4px;height:14px;overflow:hidden;">
                            <div style="height:100%;width:<?php echo e($bp); ?>%;background:<?php echo e($bColors[$b->browser] ?? '#9ca3af'); ?>;border-radius:4px;"></div>
                        </div>
                        <span style="font-size:11px;font-weight:600;color:#6b7280;width:40px;text-align:right;"><?php echo e($bp); ?>%</span>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                
                <div style="flex:1;min-width:140px;">
                    <p style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;margin-bottom:8px;">Sistema Operacional</p>
                    <?php
                        $osTotal = $osList->sum('total') ?: 1;
                        $oColors = ['Windows'=>'#0078d4','macOS'=>'#555','Linux'=>'#fcc624','Android'=>'#3ddc84','iOS'=>'#147efb','Chrome OS'=>'#4285f4','Other'=>'#9ca3af'];
                    ?>
                    <?php $__currentLoopData = $osList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $op = round($o->total / $osTotal * 100,1); ?>
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
                        <span style="width:70px;font-size:12px;font-weight:500;color:#374151;"><?php echo e($o->os); ?></span>
                        <div style="flex:1;background:#f3f4f6;border-radius:4px;height:14px;overflow:hidden;">
                            <div style="height:100%;width:<?php echo e($op); ?>%;background:<?php echo e($oColors[$o->os] ?? '#9ca3af'); ?>;border-radius:4px;"></div>
                        </div>
                        <span style="font-size:11px;font-weight:600;color:#6b7280;width:40px;text-align:right;"><?php echo e($op); ?>%</span>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>

    
    <div class="an-grid" style="margin-bottom:20px;">
        <div class="an-panel --full">
            <div class="an-panel-title">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                Top Referrers (Domínios)
            </div>
            <?php $maxRef = $topReferrers->max('total') ?: 1; ?>
            <div class="an-bars">
                <?php $__empty_1 = true; $__currentLoopData = $topReferrers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ref): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php $rpct = round(($ref->total / $maxRef) * 100); ?>
                <div class="an-bar-row">
                    <span class="an-bar-label" title="<?php echo e($ref->referrer_domain); ?>"><?php echo e($ref->referrer_domain); ?></span>
                    <div class="an-bar-track">
                        <div class="an-bar-fill" style="width:<?php echo e($rpct); ?>%;background:#8b5cf6;">
                            <?php if($rpct > 15): ?><span><?php echo e($ref->total); ?></span><?php endif; ?>
                        </div>
                    </div>
                    <span class="an-bar-count"><?php echo e(number_format($ref->total, 0, ',', '.')); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p style="font-size:13px;color:#9ca3af;text-align:center;padding:20px 0;">Sem referrers registrados</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <div class="an-panel --full" style="margin-bottom:20px;">
        <div class="an-panel-title">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Últimas 30 Visitas
        </div>
        <div style="overflow-x:auto;">
            <table class="an-table">
                <thead>
                    <tr>
                        <th>Data/Hora</th>
                        <th>Página</th>
                        <th>Fonte</th>
                        <th>Dispositivo</th>
                        <th>Navegador</th>
                        <th>OS</th>
                        <th>IP</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $recentVisits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $si = $sourceLabels[$v->source] ?? ['label'=>ucfirst($v->source??'?'),'color'=>'#9ca3af','icon'=>'🔗'];
                        $di = ['desktop'=>'🖥️','mobile'=>'📱','tablet'=>'📟'][$v->device_type] ?? '❓';
                    ?>
                    <tr>
                        <td style="white-space:nowrap;"><?php echo e($v->created_at->format('d/m/Y H:i')); ?></td>
                        <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="<?php echo e($v->path); ?>"><?php echo e($v->path); ?></td>
                        <td>
                            <span class="an-badge" style="background:<?php echo e($si['color']); ?>;"><?php echo e($si['icon']); ?> <?php echo e($si['label']); ?></span>
                        </td>
                        <td><span class="an-device-icon"><?php echo e($di); ?></span> <?php echo e(ucfirst($v->device_type)); ?></td>
                        <td><?php echo e($v->browser); ?></td>
                        <td><?php echo e($v->os); ?></td>
                        <td style="font-family:monospace;font-size:11px;"><?php echo e($v->ip_address); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="7" style="text-align:center;padding:30px;color:#9ca3af;">Nenhuma visita registrada ainda</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/issmorg/public_html/resources/views/admin/analytics/index.blade.php ENDPATH**/ ?>
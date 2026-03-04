
<?php $__env->startSection("title", "Galeria - ISSM"); ?>

<?php $__env->startPush("styles"); ?>
<style>
/* ═══ GALERIA ═══ */
.gal-filters {
    display:flex; flex-wrap:wrap; gap:8px; align-items:center;
    justify-content:center; padding:12px 0;
}
.gal-chip {
    padding:7px 18px; border-radius:24px; font-size:13px; font-weight:600;
    border:1.5px solid #d1d5db; background:#fff; color:#4b5563;
    cursor:pointer; text-decoration:none; transition:all .2s;
    display:inline-flex; align-items:center; gap:6px;
}
.gal-chip:hover { border-color:#15803d; color:#15803d; background:#f0fdf4; }
.gal-chip.--active { background:#15803d; color:#fff; border-color:#15803d; box-shadow:0 2px 8px rgba(21,128,61,.25); }
.gal-chip .gal-chip-count {
    display:inline-flex; align-items:center; justify-content:center;
    min-width:20px; height:20px; border-radius:10px; font-size:10px; font-weight:700;
    padding:0 5px;
}
.gal-chip.--active .gal-chip-count { background:rgba(255,255,255,.25); color:#fff; }
.gal-chip:not(.--active) .gal-chip-count { background:#e5e7eb; color:#6b7280; }

/* Grid */
.gal-grid {
    display:grid; grid-template-columns:repeat(4,1fr); gap:12px;
}
@media(max-width:1024px) { .gal-grid { grid-template-columns:repeat(3,1fr); } }
@media(max-width:640px)  { .gal-grid { grid-template-columns:repeat(2,1fr); gap:8px; } }

/* Card */
.gal-card {
    position:relative; overflow:hidden; border-radius:12px;
    background:#f3f4f6; aspect-ratio:4/3; cursor:zoom-in;
    box-shadow:0 1px 4px rgba(0,0,0,.06);
}
.gal-card img {
    width:100%; height:100%; object-fit:cover; display:block;
    transition:transform .4s ease;
}
.gal-card:hover img { transform:scale(1.08); }
.gal-card .gal-overlay {
    position:absolute; inset:0;
    background:linear-gradient(to top, rgba(0,0,0,.7) 0%, transparent 55%);
    opacity:0; transition:opacity .3s;
    display:flex; flex-direction:column; justify-content:flex-end;
    padding:14px 12px;
}
.gal-card:hover .gal-overlay { opacity:1; }
.gal-overlay-title { color:#fff; font-size:.82rem; font-weight:700; line-height:1.3;
    overflow:hidden; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; }
.gal-overlay-album { color:rgba(255,255,255,.7); font-size:.72rem; margin-top:2px; }
.gal-overlay-badge {
    position:absolute; top:10px; right:10px;
    padding:3px 10px; border-radius:20px; font-size:10px; font-weight:700;
    color:#fff; text-transform:uppercase; letter-spacing:.5px;
}
.gal-overlay-badge.--gallery { background:rgba(21,128,61,.85); }
.gal-overlay-badge.--project { background:rgba(59,130,246,.85); }
.gal-overlay-link {
    position:absolute; top:10px; left:10px;
    width:28px; height:28px; border-radius:50%; background:rgba(255,255,255,.2);
    display:flex; align-items:center; justify-content:center;
    opacity:0; transition:opacity .3s; text-decoration:none;
}
.gal-card:hover .gal-overlay-link { opacity:1; }
.gal-overlay-link:hover { background:rgba(255,255,255,.35); }

/* Lightbox */
#lightbox {
    display:none; position:fixed; inset:0; z-index:9999;
    background:rgba(0,0,0,.93); align-items:center; justify-content:center;
}
#lightbox.open { display:flex; }
#lightbox img#lb-img {
    max-width:90vw; max-height:85vh; border-radius:10px; object-fit:contain;
    box-shadow:0 12px 60px rgba(0,0,0,.7); animation:lbIn .2s ease;
}
@keyframes lbIn { from{opacity:0;transform:scale(.93)} to{opacity:1;transform:scale(1)} }
.lb-btn {
    position:absolute; background:rgba(255,255,255,.1); border:none; color:#fff;
    cursor:pointer; transition:background .15s; display:flex; align-items:center; justify-content:center;
}
.lb-btn:hover { background:rgba(255,255,255,.2); }
#lb-close { top:16px; right:20px; font-size:32px; width:40px; height:40px; border-radius:50%; }
#lb-prev, #lb-next { top:50%; transform:translateY(-50%); width:52px; height:52px; border-radius:50%; font-size:26px; }
#lb-prev { left:16px; } #lb-next { right:16px; }
#lb-footer {
    position:absolute; bottom:0; left:0; right:0; padding:14px 20px;
    display:flex; align-items:center; justify-content:space-between;
}
#lb-caption { color:#d1d5db; font-size:.88rem; }
#lb-counter { color:rgba(255,255,255,.5); font-size:.8rem; font-variant-numeric:tabular-nums; }

/* Stats badges */
.gal-stat {
    display:inline-flex; align-items:center; gap:6px;
    background:rgba(255,255,255,.1); padding:6px 14px; border-radius:24px;
    font-size:13px; color:#fff; font-weight:500;
}
.gal-stat svg { width:16px; height:16px; opacity:.8; }

/* Empty state */
.gal-empty {
    display:flex; flex-direction:column; align-items:center; justify-content:center;
    padding:80px 20px; text-align:center;
}
.gal-empty-icon {
    width:80px; height:80px; border-radius:50%; background:#e5e7eb;
    display:flex; align-items:center; justify-content:center; margin-bottom:20px;
}
.gal-empty-icon svg { width:40px; height:40px; color:#9ca3af; }
.gal-empty h3 { font-size:18px; font-weight:700; color:#9ca3af; margin-bottom:4px; }
.gal-empty p { font-size:14px; color:#9ca3af; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection("content"); ?>


<div style="background:linear-gradient(135deg,#166534 0%,#15803d 50%,#059669 100%);padding:56px 0 40px;">
    <div style="max-width:1280px;margin:0 auto;padding:0 16px;">
        <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:#86efac;margin-bottom:16px;">
            <a href="<?php echo e(route('home')); ?>" style="color:#86efac;text-decoration:none;transition:color .2s;">Início</a>
            <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span style="color:#fff;">Galeria</span>
        </div>
        <h1 style="font-size:clamp(2rem,5vw,3rem);font-weight:900;color:#fff;line-height:1.1;margin-bottom:8px;">
            Galeria <span style="color:#86efac;">Completa</span>
        </h1>
        <p style="font-size:16px;color:#bbf7d0;max-width:600px;margin-bottom:20px;">
            Registros fotográficos dos nossos projetos, eventos e ações socioambientais
        </p>
        <div style="display:flex;flex-wrap:wrap;gap:10px;">
            <div class="gal-stat">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <?php echo e($totalGallery); ?> foto<?php echo e($totalGallery != 1 ? 's' : ''); ?> da galeria
            </div>
            <div class="gal-stat">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <?php echo e($totalProjects); ?> imagen<?php echo e($totalProjects != 1 ? 's' : ''); ?> de projetos
            </div>
        </div>
    </div>
</div>


<div style="background:#fff;border-bottom:1px solid #e5e7eb;box-shadow:0 1px 3px rgba(0,0,0,.04);">
    <div style="max-width:1280px;margin:0 auto;padding:0 16px;">
        <div class="gal-filters">
            <?php
                $filterCounts = [
                    'Todos'    => $totalGallery + $totalProjects,
                    'Galeria'  => $totalGallery,
                    'Projetos' => $totalProjects,
                ];
            ?>
            <?php $__currentLoopData = $filterOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $isActive = (!$filter && $opt === 'Todos') || $filter === $opt;
                    $href = $opt === 'Todos'
                        ? route('gallery.index')
                        : route('gallery.index', ['filter' => $opt]);
                ?>
                <a href="<?php echo e($href); ?>" class="gal-chip <?php echo e($isActive ? '--active' : ''); ?>">
                    <?php echo e($opt); ?>

                    <?php if(isset($filterCounts[$opt])): ?>
                    <span class="gal-chip-count"><?php echo e($filterCounts[$opt]); ?></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>


<section style="padding:40px 0 60px;background:#f9fafb;min-height:60vh;">
    <div style="max-width:1280px;margin:0 auto;padding:0 16px;">

        <?php if($allItems->count() > 0): ?>
        <div class="gal-grid" id="gallery-grid">
            <?php $__currentLoopData = $allItems->values(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="gal-card"
                 data-index="<?php echo e($i); ?>"
                 data-src="<?php echo e(asset('media/' . $item->image)); ?>"
                 data-caption="<?php echo e($item->title); ?><?php echo e($item->album ? ' — '.$item->album : ''); ?>">
                <img src="<?php echo e(asset('media/' . $item->image)); ?>" alt="<?php echo e($item->title); ?>" loading="lazy">
                <div class="gal-overlay">
                    <span class="gal-overlay-badge <?php echo e($item->type === 'project' ? '--project' : '--gallery'); ?>">
                        <?php echo e($item->type === 'project' ? 'Projeto' : 'Galeria'); ?>

                    </span>
                    <?php if($item->link): ?>
                    <a href="<?php echo e($item->link); ?>" class="gal-overlay-link" title="Ver projeto" onclick="event.stopPropagation();">
                        <svg style="width:14px;height:14px;color:#fff;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                    <?php endif; ?>
                    <div>
                        <p class="gal-overlay-title"><?php echo e($item->title); ?></p>
                        <?php if($item->album): ?>
                        <span class="gal-overlay-album"><?php echo e($item->album); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php else: ?>
        <div class="gal-empty">
            <div class="gal-empty-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <h3>Nenhuma foto encontrada</h3>
            <p>A galeria está vazia no momento.</p>
        </div>
        <?php endif; ?>
    </div>
</section>


<div id="lightbox" role="dialog" aria-label="Visualizador de imagem">
    <button id="lb-close" class="lb-btn" aria-label="Fechar">&times;</button>
    <button id="lb-prev" class="lb-btn" aria-label="Anterior">&#8249;</button>
    <button id="lb-next" class="lb-btn" aria-label="Próxima">&#8250;</button>
    <img id="lb-img" src="" alt="">
    <div id="lb-footer">
        <p id="lb-caption"></p>
        <span id="lb-counter"></span>
    </div>
</div>

<?php $__env->startPush("scripts"); ?>
<script>
(function(){
    var items = Array.from(document.querySelectorAll('#gallery-grid .gal-card[data-src]'));
    var lb    = document.getElementById('lightbox');
    var img   = document.getElementById('lb-img');
    var cap   = document.getElementById('lb-caption');
    var ctr   = document.getElementById('lb-counter');
    var cur   = 0;

    function open(idx) {
        cur = idx;
        var d = items[idx].dataset;
        img.src = d.src;
        cap.textContent = d.caption || '';
        ctr.textContent = (idx + 1) + ' / ' + items.length;
        lb.classList.add('open');
        document.body.style.overflow = 'hidden';
    }
    function close() {
        lb.classList.remove('open');
        img.src = '';
        document.body.style.overflow = '';
    }
    function nav(dir) {
        cur = (cur + dir + items.length) % items.length;
        var d = items[cur].dataset;
        img.style.animation = 'none';
        requestAnimationFrame(function(){
            img.style.animation = '';
            img.src = d.src;
            cap.textContent = d.caption || '';
            ctr.textContent = (cur + 1) + ' / ' + items.length;
        });
    }

    items.forEach(function(el, i){ el.addEventListener('click', function(){ open(i); }); });
    document.getElementById('lb-close').addEventListener('click', close);
    document.getElementById('lb-prev').addEventListener('click', function(){ nav(-1); });
    document.getElementById('lb-next').addEventListener('click', function(){ nav(1); });
    lb.addEventListener('click', function(e){ if(e.target === lb) close(); });
    document.addEventListener('keydown', function(e){
        if(!lb.classList.contains('open')) return;
        if(e.key === 'Escape') close();
        if(e.key === 'ArrowLeft')  nav(-1);
        if(e.key === 'ArrowRight') nav(1);
    });
})();
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make("layouts.app", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/issmorg/public_html/resources/views/gallery/index.blade.php ENDPATH**/ ?>

<?php $__env->startSection("title", $item->title . " - ISSM"); ?>
<?php $__env->startSection("content"); ?>
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-8">
        <a href="<?php echo e(route("home")); ?>" class="hover:text-green-700">Inicio</a>
        <span>/</span>
        <a href="<?php echo e(route("news.index")); ?>" class="hover:text-green-700">Noticias</a>
        <span>/</span>
        <span class="text-gray-900"><?php echo e(Str::limit($item->title, 40)); ?></span>
    </nav>
    <article>
        <?php if($item->category): ?><span class="text-xs font-semibold text-blue-600 bg-blue-50 px-3 py-1 rounded-full"><?php echo e($item->category); ?></span><?php endif; ?>
        <h1 class="text-3xl lg:text-4xl font-black text-gray-900 mt-4 mb-4"><?php echo e($item->title); ?></h1>
        <div class="flex items-center gap-4 text-sm text-gray-500 mb-8">
            <?php if($item->published_at): ?><span><?php echo e($item->published_at->translatedFormat('d \d\e F \d\e Y')); ?></span><?php endif; ?>
        </div>
        <?php if($item->image): ?><img src="<?php echo e(asset("media/".$item->image)); ?>" alt="<?php echo e($item->title); ?>" class="w-full rounded-2xl mb-8 shadow-md"><?php endif; ?>
        <div class="prose prose-green max-w-none text-gray-700 leading-relaxed"><?php echo $item->content; ?></div>
    </article>
    <div class="mt-12 pt-8 border-t border-gray-200">
        <a href="<?php echo e(route("news.index")); ?>" class="text-green-700 hover:text-green-900 font-medium flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Voltar para Noticias
        </a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.app", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/issmorg/public_html/resources/views/news/show.blade.php ENDPATH**/ ?>
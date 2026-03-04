<?php $__env->startSection("title", ($page->meta_title ?? $page->title) . " - ISSM"); ?>
<?php $__env->startSection("content"); ?>
<div class="bg-green-800 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-black text-white"><?php echo e($page->title); ?></h1>
    </div>
</div>
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="prose prose-green max-w-none text-gray-700 leading-relaxed"><?php echo nl2br(e($page->content)); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.app", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/issmorg/public_html/resources/views/pages/show.blade.php ENDPATH**/ ?>
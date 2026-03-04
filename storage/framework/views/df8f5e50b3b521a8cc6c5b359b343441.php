

<?php $__env->startSection('title', '404 - Página Não Encontrada'); ?>
<?php $__env->startSection('code', '404'); ?>
<?php $__env->startSection('heading', 'Página não encontrada'); ?>
<?php $__env->startSection('message', 'A página que você está procurando pode ter sido removida, renomeada ou está temporariamente indisponível.'); ?>

<?php $__env->startSection('icon'); ?>
<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('errors.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/issmorg/public_html/resources/views/errors/404.blade.php ENDPATH**/ ?>
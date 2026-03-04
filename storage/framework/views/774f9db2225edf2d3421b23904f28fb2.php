<?php $__env->startSection("title", "ODS 2030"); ?>
<?php $__env->startSection("page-title", "Objetivos de Desenvolvimento Sustentavel"); ?>
<?php $__env->startSection("content"); ?>
<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
    <?php $__currentLoopData = $odsList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $od): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="rounded-xl overflow-hidden shadow-sm">
        <div class="p-4 text-white" style="background-color: <?php echo e($od->color); ?>">
            <p class="text-3xl font-black"><?php echo e($od->number); ?></p>
            <p class="text-sm font-medium leading-tight mt-1"><?php echo e($od->title); ?></p>
        </div>
        <div class="bg-white p-3 flex items-center justify-between">
            <span class="text-xs <?php echo e($od->active ? "text-green-600" : "text-gray-400"); ?>"><?php echo e($od->active ? "Ativo" : "Inativo"); ?></span>
            <a href="<?php echo e(route("admin.ods.edit", $od)); ?>" class="text-blue-600 hover:text-blue-800 text-xs">Editar</a>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.admin", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/issmorg/public_html/resources/views/admin/ods/index.blade.php ENDPATH**/ ?>
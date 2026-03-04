
<?php $__env->startSection("title", "Galeria"); ?>
<?php $__env->startSection("page-title", "Galeria de Imagens"); ?>
<?php $__env->startSection("content"); ?>
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-gray-800">Galeria</h2>
    <a href="<?php echo e(route("admin.galeria.create")); ?>" class="bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800 flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>Adicionar Imagem</a>
</div>
<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
    <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <img src="<?php echo e(asset("media/".$item->image)); ?>" alt="<?php echo e($item->title); ?>" class="w-full h-32 object-cover">
        <div class="p-3">
            <p class="text-xs font-medium text-gray-900 truncate"><?php echo e($item->title); ?></p>
            <?php if($item->album): ?><p class="text-xs text-gray-500"><?php echo e($item->album); ?></p><?php endif; ?>
            <div class="flex items-center gap-2 mt-2">
                <a href="<?php echo e(route("admin.galeria.edit", $item)); ?>" class="text-blue-600 hover:text-blue-800 text-xs">Editar</a>
                <form method="POST" action="<?php echo e(route("admin.galeria.destroy", $item)); ?>" onsubmit="return confirm('Excluir?')"><?php echo csrf_field(); ?> <?php echo method_field("DELETE"); ?><button type="submit" class="text-red-600 hover:text-red-800 text-xs">Excluir</button></form>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><div class="col-span-5 text-center py-12 text-gray-400">Nenhuma imagem na galeria.</div><?php endif; ?>
</div>
<div class="mt-4"><?php echo e($items->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.admin", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/issmorg/public_html/resources/views/admin/galeria/index.blade.php ENDPATH**/ ?>
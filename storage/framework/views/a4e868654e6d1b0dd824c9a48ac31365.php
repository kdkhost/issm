
<?php $__env->startSection("title", "Editar Banner"); ?>
<?php $__env->startSection("page-title", "Editar Banner"); ?>
<?php $__env->startSection("content"); ?>
<div class="max-w-2xl">
    <form method="POST" action="<?php echo e(route("admin.banners.update", $banner)); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?> <?php echo method_field("PUT"); ?>
        <div class="bg-white rounded-xl shadow-sm p-6 space-y-5">
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Titulo *</label><input type="text" name="title" value="<?php echo e(old("title", $banner->title)); ?>" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Subtitulo</label><textarea name="subtitle" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"><?php echo e(old("subtitle", $banner->subtitle)); ?></textarea></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Imagem</label><?php if($banner->image): ?><img src="<?php echo e(asset("media/".$banner->image)); ?>" class="h-24 object-cover rounded mb-2"><?php endif; ?><input type="file" name="image" accept="image/*" class="w-full text-sm text-gray-600 border border-gray-300 rounded-lg px-3 py-2"></div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Texto do Botao</label><input type="text" name="button_text" value="<?php echo e(old("button_text", $banner->button_text)); ?>" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">URL do Botao</label><input type="text" name="button_url" value="<?php echo e(old("button_url", $banner->button_url)); ?>" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Ordem</label><input type="number" name="order" value="<?php echo e(old("order", $banner->order)); ?>" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
                <div class="flex items-center gap-3 mt-6"><input type="checkbox" name="active" value="1" id="active" <?php echo e($banner->active ? "checked" : ""); ?> class="w-4 h-4 text-green-600 rounded"><label for="active" class="text-sm font-medium text-gray-700">Ativo</label></div>
            </div>
            <div class="flex justify-between pt-4 border-t border-gray-100">
                <a href="<?php echo e(route("admin.banners.index")); ?>" class="text-gray-600 hover:text-gray-800 font-medium">Cancelar</a>
                <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 font-medium">Atualizar</button>
            </div>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.admin", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/issmorg/public_html/resources/views/admin/banners/edit.blade.php ENDPATH**/ ?>
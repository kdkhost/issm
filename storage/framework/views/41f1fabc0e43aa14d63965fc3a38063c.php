<?php $__env->startSection("title", "Novo Banner"); ?>
<?php $__env->startSection("page-title", "Novo Banner"); ?>
<?php $__env->startSection("content"); ?>
<div class="max-w-2xl">
    <form method="POST" action="<?php echo e(route("admin.banners.store")); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <div class="bg-white rounded-xl shadow-sm p-6 space-y-5">
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Titulo *</label><input type="text" name="title" value="<?php echo e(old("title")); ?>" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"><?php $__errorArgs = ["title"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Subtitulo</label><textarea name="subtitle" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"><?php echo e(old("subtitle")); ?></textarea></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Imagem</label><input type="file" name="image" accept="image/*" class="w-full text-sm text-gray-600 border border-gray-300 rounded-lg px-3 py-2"></div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Texto do Botao</label><input type="text" name="button_text" value="<?php echo e(old("button_text")); ?>" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">URL do Botao</label><input type="text" name="button_url" value="<?php echo e(old("button_url")); ?>" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Ordem</label><input type="number" name="order" value="<?php echo e(old("order", 0)); ?>" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
                <div class="flex items-center gap-3 mt-6"><input type="checkbox" name="active" value="1" id="active" checked class="w-4 h-4 text-green-600 rounded"><label for="active" class="text-sm font-medium text-gray-700">Ativo</label></div>
            </div>
            <div class="flex justify-between pt-4 border-t border-gray-100">
                <a href="<?php echo e(route("admin.banners.index")); ?>" class="text-gray-600 hover:text-gray-800 font-medium">Cancelar</a>
                <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 font-medium">Criar</button>
            </div>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.admin", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/issmorg/public_html/resources/views/admin/banners/create.blade.php ENDPATH**/ ?>
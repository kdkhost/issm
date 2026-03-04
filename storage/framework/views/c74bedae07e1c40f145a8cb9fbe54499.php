<?php $__env->startSection("title", "Novo Parceiro"); ?>
<?php $__env->startSection("page-title", "Novo Parceiro"); ?>
<?php $__env->startSection("content"); ?>
<div class="max-w-lg">
    <form method="POST" action="<?php echo e(route("admin.parceiros.store")); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Nome *</label><input type="text" name="name" value="<?php echo e(old("name")); ?>" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">URL do Site</label><input type="url" name="url" value="<?php echo e(old("url")); ?>" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label><select name="type" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"><option value="partner">Parceiro</option><option value="sponsor">Patrocinador</option><option value="supporter">Apoiador</option></select></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Logo</label><input type="file" name="logo" accept="image/*" class="w-full text-sm text-gray-600 border border-gray-300 rounded-lg px-3 py-2"></div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Ordem</label><input type="number" name="order" value="<?php echo e(old("order", 0)); ?>" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
                <div class="flex items-center gap-3 mt-6"><input type="checkbox" name="active" value="1" id="active" checked class="w-4 h-4 text-green-600 rounded"><label for="active" class="text-sm font-medium text-gray-700">Ativo</label></div>
            </div>
            <div class="flex justify-between pt-4 border-t border-gray-100">
                <a href="<?php echo e(route("admin.parceiros.index")); ?>" class="text-gray-600 hover:text-gray-800 font-medium">Cancelar</a>
                <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 font-medium">Criar</button>
            </div>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.admin", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/issmorg/public_html/resources/views/admin/parceiros/create.blade.php ENDPATH**/ ?>
<?php $__env->startSection("title", "Nova Pagina"); ?>
<?php $__env->startSection("page-title", "Nova Pagina"); ?>
<?php $__env->startSection("content"); ?>
<div class="max-w-3xl">
    <form method="POST" action="<?php echo e(route("admin.paginas.store")); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Titulo *</label><input type="text" name="title" value="<?php echo e(old("title")); ?>" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Conteudo *</label><textarea name="content" rows="12" required class="wysiwyg w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"><?php echo e(old("content")); ?></textarea></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label><input type="text" name="meta_title" value="<?php echo e(old("meta_title")); ?>" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label><textarea name="meta_description" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"><?php echo e(old("meta_description")); ?></textarea></div>
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-2"><input type="checkbox" name="active" value="1" id="active" checked class="w-4 h-4 text-green-600 rounded"><label for="active" class="text-sm font-medium text-gray-700">Ativo</label></div>
                <div class="flex items-center gap-2"><input type="checkbox" name="show_in_menu" value="1" id="show_in_menu" class="w-4 h-4 text-green-600 rounded"><label for="show_in_menu" class="text-sm font-medium text-gray-700">Exibir no Menu</label></div>
            </div>
            <div class="flex justify-between pt-4 border-t border-gray-100">
                <a href="<?php echo e(route("admin.paginas.index")); ?>" class="text-gray-600 hover:text-gray-800 font-medium">Cancelar</a>
                <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 font-medium">Criar</button>
            </div>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.admin", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/issmorg/public_html/resources/views/admin/paginas/create.blade.php ENDPATH**/ ?>
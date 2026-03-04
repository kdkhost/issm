
<?php $__env->startSection("title", "Editar Noticia"); ?>
<?php $__env->startSection("page-title", "Editar Noticia"); ?>
<?php $__env->startSection("content"); ?>
<div class="max-w-4xl">
    <form method="POST" action="<?php echo e(route("admin.noticias.update", $news)); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?> <?php echo method_field("PUT"); ?>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-5">
                <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Titulo *</label><input type="text" name="title" value="<?php echo e(old("title", $news->title)); ?>" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Resumo</label><textarea name="excerpt" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"><?php echo e(old("excerpt", $news->excerpt)); ?></textarea></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Conteudo *</label><textarea name="content" rows="12" required class="wysiwyg w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"><?php echo e(old("content", $news->content)); ?></textarea></div>
                </div>
            </div>
            <div class="space-y-5">
                <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
                    <h3 class="font-semibold text-gray-800">Publicacao</h3>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Data de Publicacao</label><input type="datetime-local" name="published_at" value="<?php echo e(old("published_at", $news->published_at ? $news->published_at->format("Y-m-d\TH:i") : "")); ?>" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Categoria</label><input type="text" name="category" value="<?php echo e(old("category", $news->category)); ?>" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
                    <div class="flex items-center gap-3"><input type="checkbox" name="featured" value="1" id="featured" <?php echo e($news->featured ? "checked" : ""); ?> class="w-4 h-4 text-green-600 rounded"><label for="featured" class="text-sm font-medium text-gray-700">Destaque</label></div>
                    <div class="flex items-center gap-3"><input type="checkbox" name="active" value="1" id="active" <?php echo e($news->active ? "checked" : ""); ?> class="w-4 h-4 text-green-600 rounded"><label for="active" class="text-sm font-medium text-gray-700">Ativo</label></div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6"><h3 class="font-semibold text-gray-800 mb-3">Imagem</h3><?php if($news->image): ?><img src="<?php echo e(asset("media/".$news->image)); ?>" class="w-full h-32 object-cover rounded mb-2"><?php endif; ?><input type="file" name="image" accept="image/*" class="w-full text-sm text-gray-600"></div>
                <div class="flex justify-between"><a href="<?php echo e(route("admin.noticias.index")); ?>" class="text-gray-600 hover:text-gray-800 font-medium">Cancelar</a><button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 font-medium">Atualizar</button></div>
            </div>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.admin", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/issmorg/public_html/resources/views/admin/news/edit.blade.php ENDPATH**/ ?>
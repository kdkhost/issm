
<?php $__env->startSection("title", "Editar Projeto"); ?>
<?php $__env->startSection("page-title", "Editar Projeto"); ?>
<?php $__env->startSection("content"); ?>
<div class="max-w-4xl">
    <form method="POST" action="<?php echo e(route("admin.projetos.update", $project)); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?> <?php echo method_field("PUT"); ?>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-5">
                <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Titulo *</label><input type="text" name="title" value="<?php echo e(old("title", $project->title)); ?>" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Resumo</label><textarea name="excerpt" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"><?php echo e(old("excerpt", $project->excerpt)); ?></textarea></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Conteudo *</label><textarea name="content" rows="10" required class="wysiwyg w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"><?php echo e(old("content", $project->content)); ?></textarea></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-2">ODS Relacionados</label><div class="grid grid-cols-6 gap-2"><?php for($i = 1; $i <= 17; $i++): ?><label class="flex items-center gap-1 cursor-pointer"><input type="checkbox" name="ods_goals[]" value="<?php echo e($i); ?>" <?php echo e(in_array($i, old("ods_goals", $project->ods_goals ?? [])) ? "checked" : ""); ?> class="w-3 h-3 text-green-600 rounded"><span class="ods-<?php echo e($i); ?> text-white text-xs font-bold w-6 h-6 rounded flex items-center justify-center"><?php echo e($i); ?></span></label><?php endfor; ?></div></div>
                </div>
            </div>
            <div class="space-y-5">
                <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
                    <h3 class="font-semibold text-gray-800">Detalhes</h3>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Categoria</label><input type="text" name="category" value="<?php echo e(old("category", $project->category)); ?>" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Status</label><select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"><option value="active" <?php echo e($project->status === "active" ? "selected" : ""); ?>>Ativo</option><option value="completed" <?php echo e($project->status === "completed" ? "selected" : ""); ?>>Concluido</option><option value="planned" <?php echo e($project->status === "planned" ? "selected" : ""); ?>>Planejado</option></select></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Localizacao</label><input type="text" name="location" value="<?php echo e(old("location", $project->location)); ?>" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
                    <div class="grid grid-cols-2 gap-2">
                        <div><label class="block text-sm font-medium text-gray-700 mb-1">Inicio</label><input type="date" name="start_date" value="<?php echo e(old("start_date", $project->start_date ? $project->start_date->format("Y-m-d") : "")); ?>" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
                        <div><label class="block text-sm font-medium text-gray-700 mb-1">Fim</label><input type="date" name="end_date" value="<?php echo e(old("end_date", $project->end_date ? $project->end_date->format("Y-m-d") : "")); ?>" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
                    </div>
                    <div class="flex items-center gap-3"><input type="checkbox" name="featured" value="1" id="featured" <?php echo e($project->featured ? "checked" : ""); ?> class="w-4 h-4 text-green-600 rounded"><label for="featured" class="text-sm font-medium text-gray-700">Destaque</label></div>
                    <div class="flex items-center gap-3"><input type="checkbox" name="active" value="1" id="active" <?php echo e($project->active ? "checked" : ""); ?> class="w-4 h-4 text-green-600 rounded"><label for="active" class="text-sm font-medium text-gray-700">Ativo</label></div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6"><h3 class="font-semibold text-gray-800 mb-3">Imagem</h3><?php if($project->image): ?><img src="<?php echo e(asset("media/".$project->image)); ?>" class="w-full h-32 object-cover rounded mb-2"><?php endif; ?><input type="file" name="image" accept="image/*" class="w-full text-sm text-gray-600"></div>
                <div class="flex justify-between"><a href="<?php echo e(route("admin.projetos.index")); ?>" class="text-gray-600 hover:text-gray-800 font-medium">Cancelar</a><button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 font-medium">Atualizar</button></div>
            </div>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.admin", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/issmorg/public_html/resources/views/admin/projetos/edit.blade.php ENDPATH**/ ?>
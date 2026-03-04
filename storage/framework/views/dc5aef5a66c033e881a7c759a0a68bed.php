<?php $__env->startSection("title", "Projetos"); ?>
<?php $__env->startSection("page-title", "Projetos"); ?>
<?php $__env->startSection("content"); ?>
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-gray-800">Projetos</h2>
    <a href="<?php echo e(route("admin.projetos.create")); ?>" class="bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800 flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>Novo Projeto</a>
</div>
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Título</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden sm:table-cell">Categoria</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden sm:table-cell">Destaque</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            <?php $__empty_1 = true; $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3 font-medium text-gray-900 text-sm"><?php echo e(Str::limit($project->title, 50)); ?></td>
                <td class="px-4 py-3 text-gray-600 text-sm hidden sm:table-cell"><?php echo e($project->category ?? "-"); ?></td>
                <td class="px-4 py-3"><span class="px-2 py-1 rounded-full text-xs font-medium <?php echo e($project->status === "active" ? "bg-green-100 text-green-700" : ($project->status === "completed" ? "bg-blue-100 text-blue-700" : "bg-yellow-100 text-yellow-700")); ?>"><?php echo e($project->status === "active" ? "Ativo" : ($project->status === "completed" ? "Concluído" : "Planejado")); ?></span></td>
                <td class="px-4 py-3 hidden sm:table-cell"><?php if($project->featured): ?><span class="text-yellow-500 text-lg">&#9733;</span><?php else: ?><span class="text-gray-300 text-lg">&#9733;</span><?php endif; ?></td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <div class="flex items-center gap-1">
                        <button type="button" data-dt-toggle class="dt-toggle p-1 rounded text-gray-400 hover:text-green-700 hover:bg-green-50 transition-colors" title="Ver detalhes ocultos">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <a href="<?php echo e(route("admin.projetos.edit", $project)); ?>" class="text-blue-600 hover:text-blue-800 text-sm font-medium px-1">Editar</a>
                        <form method="POST" action="<?php echo e(route("admin.projetos.destroy", $project)); ?>" onsubmit="return confirm('Excluir este projeto?')"><?php echo csrf_field(); ?> <?php echo method_field("DELETE"); ?><button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium px-1">Excluir</button></form>
                    </div>
                </td>
            </tr>
            <tr class="dt-detail hidden">
                <td colspan="5" class="px-4 py-3 bg-green-50 border-b border-green-100">
                    <dl class="grid grid-cols-2 sm:grid-cols-4 gap-x-6 gap-y-2 text-sm">
                        <div><dt class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Categoria</dt><dd class="text-gray-800 mt-0.5"><?php echo e($project->category ?? "-"); ?></dd></div>
                        <div><dt class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Destaque</dt><dd class="text-gray-800 mt-0.5"><?php echo e($project->featured ? "Sim ★" : "Não"); ?></dd></div>
                    </dl>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="5" class="px-6 py-10 text-center text-gray-400">Nenhum projeto cadastrado.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="p-4 border-t border-gray-100"><?php echo e($projects->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.admin", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/issmorg/public_html/resources/views/admin/projetos/index.blade.php ENDPATH**/ ?>
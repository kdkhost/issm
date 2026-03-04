
<?php $__env->startSection("title", "Equipe"); ?>
<?php $__env->startSection("page-title", "Equipe"); ?>
<?php $__env->startSection("content"); ?>
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-gray-800">Membros da Equipe</h2>
    <a href="<?php echo e(route("admin.equipe.create")); ?>" class="bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800 flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>Novo Membro</a>
</div>
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Foto</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nome</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden sm:table-cell">Cargo</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden md:table-cell">Ordem</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            <?php $__empty_1 = true; $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3"><?php if($member->photo): ?><img src="<?php echo e(asset("media/".$member->photo)); ?>" class="w-9 h-9 rounded-full object-cover"><?php else: ?><div class="w-9 h-9 rounded-full bg-green-100 flex items-center justify-center"><span class="text-green-700 font-bold text-sm"><?php echo e(substr($member->name,0,1)); ?></span></div><?php endif; ?></td>
                <td class="px-4 py-3 font-medium text-gray-900 text-sm"><?php echo e($member->name); ?></td>
                <td class="px-4 py-3 text-gray-600 text-sm hidden sm:table-cell"><?php echo e($member->role); ?></td>
                <td class="px-4 py-3 text-gray-600 text-sm hidden md:table-cell"><?php echo e($member->order); ?></td>
                <td class="px-4 py-3"><span class="px-2 py-1 rounded-full text-xs font-medium <?php echo e($member->active ? "bg-green-100 text-green-700" : "bg-gray-100 text-gray-600"); ?>"><?php echo e($member->active ? "Ativo" : "Inativo"); ?></span></td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <div class="flex items-center gap-1">
                        <button type="button" data-dt-toggle class="dt-toggle p-1 rounded text-gray-400 hover:text-green-700 hover:bg-green-50 transition-colors" title="Ver detalhes ocultos">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <a href="<?php echo e(route("admin.equipe.edit", $member)); ?>" class="text-blue-600 hover:text-blue-800 text-sm font-medium px-1">Editar</a>
                        <form method="POST" action="<?php echo e(route("admin.equipe.destroy", $member)); ?>" onsubmit="return confirm('Excluir este membro?')"><?php echo csrf_field(); ?> <?php echo method_field("DELETE"); ?><button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium px-1">Excluir</button></form>
                    </div>
                </td>
            </tr>
            <tr class="dt-detail hidden">
                <td colspan="6" class="px-4 py-3 bg-green-50 border-b border-green-100">
                    <dl class="grid grid-cols-2 sm:grid-cols-4 gap-x-6 gap-y-2 text-sm">
                        <div><dt class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Cargo</dt><dd class="text-gray-800 mt-0.5"><?php echo e($member->role); ?></dd></div>
                        <div><dt class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Ordem</dt><dd class="text-gray-800 mt-0.5"><?php echo e($member->order); ?></dd></div>
                    </dl>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="6" class="px-6 py-10 text-center text-gray-400">Nenhum membro cadastrado.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="p-4 border-t border-gray-100"><?php echo e($members->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.admin", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/issmorg/public_html/resources/views/admin/equipe/index.blade.php ENDPATH**/ ?>
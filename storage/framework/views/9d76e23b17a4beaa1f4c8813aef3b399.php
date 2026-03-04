<?php $__env->startSection("title", "IPs de Manutencao"); ?>
<?php $__env->startSection("page-title", "IPs Liberados para Manutencao"); ?>
<?php $__env->startSection("content"); ?>
<div class="flex justify-between items-center mb-6">
    <div class="flex items-center gap-3">
        <h2 class="text-xl font-bold text-gray-800">IPs Liberados</h2>
        <form method="POST" action="<?php echo e(route("admin.ips-manutencao.add-current")); ?>"><?php echo csrf_field(); ?><button type="submit" class="bg-blue-600 text-white px-3 py-1.5 rounded-lg hover:bg-blue-700 text-sm">+ Adicionar meu IP atual</button></form>
    </div>
    <a href="<?php echo e(route("admin.ips-manutencao.create")); ?>" class="bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800 flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>Adicionar IP</a>
</div>
<div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
    <p class="text-blue-800 text-sm"><strong>Seu IP atual:</strong> <?php echo e(request()->ip()); ?> | <strong>Como funciona:</strong> Quando o modo manutencao esta ativo, apenas os IPs listados aqui (e administradores logados) conseguem ver o site.</p>
</div>
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
    <table class="w-full" style="min-width:540px;">
        <thead class="bg-gray-50 border-b border-gray-200"><tr>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">Endereço IP</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">Descrição</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">Status</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">Adicionado</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">Ações</th>
        </tr></thead>
        <tbody class="divide-y divide-gray-100">
            <?php $__empty_1 = true; $__currentLoopData = $ips; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ip): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 font-mono font-medium text-gray-900 whitespace-nowrap"><?php echo e($ip->ip_address); ?></td>
                <td class="px-6 py-4 text-gray-600 text-sm"><?php echo e($ip->description ?? "-"); ?></td>
                <td class="px-6 py-4 whitespace-nowrap"><span class="px-2 py-1 rounded-full text-xs font-medium <?php echo e($ip->active ? "bg-green-100 text-green-700" : "bg-gray-100 text-gray-600"); ?>"><?php echo e($ip->active ? "Ativo" : "Inativo"); ?></span></td>
                <td class="px-6 py-4 text-gray-500 text-sm whitespace-nowrap"><?php echo e($ip->created_at->format("d/m/Y")); ?></td>
                <td class="px-6 py-4 whitespace-nowrap"><div class="flex items-center gap-3">
                    <a href="<?php echo e(route("admin.ips-manutencao.edit", $ip)); ?>" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Editar</a>
                    <form method="POST" action="<?php echo e(route("admin.ips-manutencao.destroy", $ip)); ?>" onsubmit="return confirm('Remover este IP?')"><?php echo csrf_field(); ?> <?php echo method_field("DELETE"); ?><button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">Remover</button></form>
                </div></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="5" class="px-6 py-10 text-center text-gray-400">Nenhum IP cadastrado.</td></tr><?php endif; ?>
        </tbody>
    </table>
    </div>
    <div class="p-4 border-t border-gray-100"><?php echo e($ips->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.admin", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/issmorg/public_html/resources/views/admin/maintenance-ips/index.blade.php ENDPATH**/ ?>
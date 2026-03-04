<?php $__env->startSection("title", "Editar IP"); ?>
<?php $__env->startSection("page-title", "Editar IP Liberado"); ?>
<?php $__env->startSection("content"); ?>
<div class="max-w-lg">
    <form method="POST" action="<?php echo e(route("admin.ips-manutencao.update", $ip)); ?>">
        <?php echo csrf_field(); ?> <?php echo method_field("PUT"); ?>
        <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Endereco IP *</label><input type="text" name="ip_address" value="<?php echo e(old("ip_address", $ip->ip_address)); ?>" required class="w-full border border-gray-300 rounded-lg px-3 py-2 font-mono focus:outline-none focus:ring-2 focus:ring-green-500"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Descricao</label><input type="text" name="description" value="<?php echo e(old("description", $ip->description)); ?>" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
            <div class="flex items-center gap-3"><input type="checkbox" name="active" value="1" id="active" <?php echo e($ip->active ? "checked" : ""); ?> class="w-4 h-4 text-green-600 rounded"><label for="active" class="text-sm font-medium text-gray-700">IP Ativo</label></div>
            <div class="flex justify-between pt-4 border-t border-gray-100">
                <a href="<?php echo e(route("admin.ips-manutencao.index")); ?>" class="text-gray-600 hover:text-gray-800 font-medium">Cancelar</a>
                <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 font-medium">Atualizar</button>
            </div>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.admin", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/issmorg/public_html/resources/views/admin/maintenance-ips/edit.blade.php ENDPATH**/ ?>
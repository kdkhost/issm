<?php $__env->startSection("title", "Mensagem"); ?>
<?php $__env->startSection("page-title", "Detalhes da Mensagem"); ?>
<?php $__env->startSection("content"); ?>
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div><p class="text-xs text-gray-500">Nome</p><p class="font-semibold text-gray-900"><?php echo e($contact->name); ?></p></div>
            <div><p class="text-xs text-gray-500">E-mail</p><p class="font-semibold text-gray-900"><?php echo e($contact->email); ?></p></div>
            <div><p class="text-xs text-gray-500">Telefone</p><p class="font-semibold text-gray-900"><?php echo e($contact->phone ?? "-"); ?></p></div>
            <div><p class="text-xs text-gray-500">Data</p><p class="font-semibold text-gray-900"><?php echo e(optional($contact->created_at)->format("d/m/Y H:i") ?? "-"); ?></p></div>
        </div>
        <div class="mb-4"><p class="text-xs text-gray-500 mb-1">Assunto</p><p class="font-semibold text-gray-900"><?php echo e($contact->subject); ?></p></div>
        <div><p class="text-xs text-gray-500 mb-1">Mensagem</p><div class="bg-gray-50 rounded-lg p-4 text-gray-700 leading-relaxed"><?php echo e($contact->message); ?></div></div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-bold text-gray-800 mb-4">Anotacoes</h3>
        <form method="POST" action="<?php echo e(url('admin/contatos/' . $contact->getKey())); ?>">
            <?php echo csrf_field(); ?> <?php echo method_field("PUT"); ?>
            <div class="mb-4"><label class="block text-sm font-medium text-gray-700 mb-1">Status</label><select name="status" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"><option value="new" <?php echo e($contact->status === "new" ? "selected" : ""); ?>>Nova</option><option value="read" <?php echo e($contact->status === "read" ? "selected" : ""); ?>>Lida</option><option value="replied" <?php echo e($contact->status === "replied" ? "selected" : ""); ?>>Respondida</option></select></div>
            <div class="mb-4"><label class="block text-sm font-medium text-gray-700 mb-1">Anotacoes internas</label><textarea name="reply" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"><?php echo e($contact->reply); ?></textarea></div>
            <div class="flex justify-between"><a href="<?php echo e(route("admin.contatos.index")); ?>" class="text-gray-600 hover:text-gray-800 font-medium">Voltar</a><button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 font-medium">Salvar</button></div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.admin", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/issmorg/public_html/resources/views/admin/contatos/show.blade.php ENDPATH**/ ?>
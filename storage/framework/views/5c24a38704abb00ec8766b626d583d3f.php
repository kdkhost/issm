
<?php $__env->startSection("title", "Editar Membro"); ?>
<?php $__env->startSection("page-title", "Editar Membro da Equipe"); ?>
<?php $__env->startSection("content"); ?>
<div class="max-w-2xl">
    <form method="POST" action="<?php echo e(route("admin.equipe.update", $member)); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?> <?php echo method_field("PUT"); ?>
        <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Nome *</label><input type="text" name="name" value="<?php echo e(old("name", $member->name)); ?>" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Cargo *</label><input type="text" name="role" value="<?php echo e(old("role", $member->role)); ?>" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
            </div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Biografia</label><textarea name="bio" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"><?php echo e(old("bio", $member->bio)); ?></textarea></div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium text-gray-700 mb-1">E-mail</label><input type="email" name="email" value="<?php echo e(old("email", $member->email)); ?>" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">LinkedIn</label><input type="url" name="linkedin" value="<?php echo e(old("linkedin", $member->linkedin)); ?>" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp <span class="text-gray-400 font-normal">(widget suporte)</span></label><input type="text" name="whatsapp" value="<?php echo e(old("whatsapp", $member->whatsapp)); ?>" placeholder="Ex: 21999999999" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Telefone Suporte <span class="text-gray-400 font-normal">(ligação)</span></label><input type="text" name="phone_support" value="<?php echo e(old("phone_support", $member->phone_support)); ?>" placeholder="Ex: (21) 9 9999-9999" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
            </div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Foto</label><?php if($member->photo): ?><img src="<?php echo e(asset("media/".$member->photo)); ?>" class="w-16 h-16 rounded-full object-cover mb-2"><?php endif; ?><input type="file" name="photo" accept="image/*" class="w-full text-sm text-gray-600 border border-gray-300 rounded-lg px-3 py-2"></div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Ordem</label><input type="number" name="order" value="<?php echo e(old("order", $member->order)); ?>" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
                <div class="flex flex-col gap-2 mt-1">
                    <div class="flex items-center gap-3 mt-5"><input type="checkbox" name="active" value="1" id="active" <?php echo e($member->active ? "checked" : ""); ?> class="w-4 h-4 text-green-600 rounded"><label for="active" class="text-sm font-medium text-gray-700">Ativo</label></div>
                    <div class="flex items-center gap-3"><input type="checkbox" name="support_active" value="1" id="support_active" <?php echo e($member->support_active ? "checked" : ""); ?> class="w-4 h-4 text-green-600 rounded"><label for="support_active" class="text-sm font-medium text-gray-700">Exibir no widget de suporte</label></div>
                </div>
            </div>
            <div class="flex justify-between pt-4 border-t border-gray-100">
                <a href="<?php echo e(route("admin.equipe.index")); ?>" class="text-gray-600 hover:text-gray-800 font-medium">Cancelar</a>
                <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 font-medium">Atualizar</button>
            </div>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.admin", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/issmorg/public_html/resources/views/admin/equipe/edit.blade.php ENDPATH**/ ?>
<?php $__env->startSection("title", "Novo Membro"); ?>
<?php $__env->startSection("page-title", "Novo Membro da Equipe"); ?>
<?php $__env->startSection("content"); ?>
<div class="max-w-2xl">
    <form method="POST" action="<?php echo e(route("admin.equipe.store")); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Nome *</label><input type="text" name="name" value="<?php echo e(old("name")); ?>" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Cargo *</label><input type="text" name="role" value="<?php echo e(old("role")); ?>" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
            </div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Biografia</label><textarea name="bio" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"><?php echo e(old("bio")); ?></textarea></div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium text-gray-700 mb-1">E-mail</label><input type="email" name="email" value="<?php echo e(old("email")); ?>" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">LinkedIn</label><input type="url" name="linkedin" value="<?php echo e(old("linkedin")); ?>" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp <span class="text-gray-400 font-normal">(widget suporte)</span></label><input type="text" name="whatsapp" value="<?php echo e(old("whatsapp")); ?>" placeholder="Ex: 21999999999" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Telefone Suporte <span class="text-gray-400 font-normal">(ligação)</span></label><input type="text" name="phone_support" value="<?php echo e(old("phone_support")); ?>" placeholder="Ex: (21) 9 9999-9999" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
            </div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Foto</label><input type="file" name="photo" accept="image/*" class="w-full text-sm text-gray-600 border border-gray-300 rounded-lg px-3 py-2"></div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Ordem</label><input type="number" name="order" value="<?php echo e(old("order", 0)); ?>" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
                <div class="flex flex-col gap-2 mt-1">
                    <div class="flex items-center gap-3 mt-5"><input type="checkbox" name="active" value="1" id="active" checked class="w-4 h-4 text-green-600 rounded"><label for="active" class="text-sm font-medium text-gray-700">Ativo</label></div>
                    <div class="flex items-center gap-3"><input type="checkbox" name="support_active" value="1" id="support_active" class="w-4 h-4 text-green-600 rounded"><label for="support_active" class="text-sm font-medium text-gray-700">Exibir no widget de suporte</label></div>
                </div>
            </div>
            <div class="flex justify-between pt-4 border-t border-gray-100">
                <a href="<?php echo e(route("admin.equipe.index")); ?>" class="text-gray-600 hover:text-gray-800 font-medium">Cancelar</a>
                <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 font-medium">Criar</button>
            </div>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.admin", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/issmorg/public_html/resources/views/admin/equipe/create.blade.php ENDPATH**/ ?>
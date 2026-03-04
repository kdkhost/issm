<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('page-title', 'Dashboard'); ?>
<?php $__env->startSection('page-subtitle', 'Visao geral do sistema'); ?>
<?php $__env->startSection('content'); ?>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div><p class="text-gray-500 text-sm">Noticias</p><p class="text-3xl font-black text-gray-900"><?php echo e($stats["news"]); ?></p></div>
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center"><svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg></div>
        </div>
        <a href="<?php echo e(route("admin.noticias.index")); ?>" class="text-green-600 text-xs mt-2 block hover:underline">Gerenciar noticias</a>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div><p class="text-gray-500 text-sm">Projetos</p><p class="text-3xl font-black text-gray-900"><?php echo e($stats["projects"]); ?></p></div>
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center"><svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg></div>
        </div>
        <a href="<?php echo e(route("admin.projetos.index")); ?>" class="text-blue-600 text-xs mt-2 block hover:underline">Gerenciar projetos</a>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-red-500">
        <div class="flex items-center justify-between">
            <div><p class="text-gray-500 text-sm">Mensagens Novas</p><p class="text-3xl font-black text-gray-900"><?php echo e($stats["contacts"]); ?></p></div>
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center"><svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div>
        </div>
        <a href="<?php echo e(route("admin.contatos.index")); ?>" class="text-red-600 text-xs mt-2 block hover:underline">Ver mensagens</a>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-orange-500">
        <div class="flex items-center justify-between">
            <div><p class="text-gray-500 text-sm">Manutencao</p><p class="text-xl font-black text-gray-900"><?php echo e($maintenanceMode == "1" ? "ATIVA" : "Desativada"); ?></p></div>
            <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center"><svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg></div>
        </div>
        <a href="<?php echo e(route("admin.settings.index")); ?>" class="text-orange-600 text-xs mt-2 block hover:underline">Configurar</a>
    </div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-bold text-gray-800 mb-4">Ultimas Mensagens</h3>
        <?php $__empty_1 = true; $__currentLoopData = $recentContacts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="flex items-start gap-3 py-3 border-b border-gray-100 last:border-0">
            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0"><span class="text-green-700 font-bold text-sm"><?php echo e(substr($contact->name, 0, 1)); ?></span></div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between gap-2">
                    <p class="font-medium text-gray-900 text-sm truncate"><?php echo e($contact->name); ?></p>
                    <span class="text-xs px-2 py-0.5 rounded-full flex-shrink-0 <?php echo e($contact->status === "new" ? "bg-red-100 text-red-600" : ($contact->status === "replied" ? "bg-green-100 text-green-600" : "bg-gray-100 text-gray-600")); ?>"><?php echo e($contact->status === "new" ? "Nova" : ($contact->status === "replied" ? "Respondida" : "Lida")); ?></span>
                </div>
                <p class="text-gray-500 text-xs truncate"><?php echo e($contact->subject); ?></p>
            </div>
            <a href="<?php echo e(route("admin.contatos.show", $contact)); ?>" class="text-green-600 hover:text-green-800 text-xs">Ver</a>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p class="text-gray-400 text-sm">Nenhuma mensagem ainda.</p>
        <?php endif; ?>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-bold text-gray-800 mb-4">Ultimas Noticias</h3>
        <?php $__empty_1 = true; $__currentLoopData = $recentNews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $news): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="flex items-start gap-3 py-3 border-b border-gray-100 last:border-0">
            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0"><svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
            <div class="flex-1 min-w-0">
                <p class="font-medium text-gray-900 text-sm truncate"><?php echo e($news->title); ?></p>
                <p class="text-gray-400 text-xs"><?php echo e($news->created_at->format("d/m/Y")); ?></p>
            </div>
            <a href="<?php echo e(route("admin.noticias.edit", $news)); ?>" class="text-green-600 hover:text-green-800 text-xs">Editar</a>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p class="text-gray-400 text-sm">Nenhuma noticia ainda.</p>
        <?php endif; ?>
    </div>
</div>
<div class="mt-6 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4">
    <a href="<?php echo e(route("admin.banners.create")); ?>" class="bg-white rounded-xl shadow-sm p-4 text-center hover:shadow-md transition-shadow"><div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-2"><svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg></div><p class="text-xs font-medium text-gray-700">Novo Banner</p></a>
    <a href="<?php echo e(route("admin.noticias.create")); ?>" class="bg-white rounded-xl shadow-sm p-4 text-center hover:shadow-md transition-shadow"><div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2"><svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg></div><p class="text-xs font-medium text-gray-700">Nova Noticia</p></a>
    <a href="<?php echo e(route("admin.projetos.create")); ?>" class="bg-white rounded-xl shadow-sm p-4 text-center hover:shadow-md transition-shadow"><div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2"><svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg></div><p class="text-xs font-medium text-gray-700">Novo Projeto</p></a>
    <a href="<?php echo e(route("admin.galeria.create")); ?>" class="bg-white rounded-xl shadow-sm p-4 text-center hover:shadow-md transition-shadow"><div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-2"><svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg></div><p class="text-xs font-medium text-gray-700">Nova Foto</p></a>
    <a href="<?php echo e(route("admin.equipe.create")); ?>" class="bg-white rounded-xl shadow-sm p-4 text-center hover:shadow-md transition-shadow"><div class="w-10 h-10 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-2"><svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg></div><p class="text-xs font-medium text-gray-700">Novo Membro</p></a>
    <a href="<?php echo e(route("admin.settings.index")); ?>" class="bg-white rounded-xl shadow-sm p-4 text-center hover:shadow-md transition-shadow"><div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-2"><svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg></div><p class="text-xs font-medium text-gray-700">Configuracoes</p></a>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/issmorg/public_html/resources/views/admin/dashboard.blade.php ENDPATH**/ ?>
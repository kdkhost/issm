<form method="POST" action="{{ route("admin.cms.blocks.update", $cmsBlock) }}" class="space-y-4">
    @csrf @method("PUT")
    <div class="flex items-center justify-between border-b border-gray-100 pb-4">
        <h3 class="text-lg font-bold text-gray-800">Editar Bloco</h3>
        <button type="button" onclick="this.closest('#block-edit-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    <input type="hidden" name="section_id" value="{{ $cmsBlock->cms_section_id }}">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Título</label>
            <input type="text" name="title" value="{{ old("title", $cmsBlock->title) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
            <select name="type" id="edit-block-type" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="text" {{ $cmsBlock->type == "text" ? "selected" : "" }}>Texto</option>
                <option value="html" {{ $cmsBlock->type == "html" ? "selected" : "" }}>HTML</option>
                <option value="image" {{ $cmsBlock->type == "image" ? "selected" : "" }}>Imagem</option>
                <option value="video" {{ $cmsBlock->type == "video" ? "selected" : "" }}>Vídeo</option>
                <option value="code" {{ $cmsBlock->type == "code" ? "selected" : "" }}>Código</option>
            </select>
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Conteúdo</label>
        <textarea name="content" rows="6" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 summernote">{{ old("content", $cmsBlock->content) }}</textarea>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Imagem</label>
            <input type="file" name="image_file" accept="image/*" data-auto-upload="{{ route("admin.cms.media.upload") }}" data-url-name="image" data-existing-url="{{ old("image", $cmsBlock->image) }}" data-hint="PNG, JPG, WebP até 5MB">
            <input type="hidden" name="image" value="{{ old("image", $cmsBlock->image) }}">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">URL do Vídeo</label>
            <input type="text" name="video_url" value="{{ old("video_url", $cmsBlock->video_url) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="https://youtube.com/...">
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Link URL</label>
            <input type="text" name="link_url" value="{{ old("link_url", $cmsBlock->link_url) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Texto do Link</label>
            <input type="text" name="link_text" value="{{ old("link_text", $cmsBlock->link_text) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Configurações (JSON)</label>
        <textarea name="settings" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 font-mono text-xs">{{ old("settings", is_string($cmsBlock->settings) ? $cmsBlock->settings : json_encode($cmsBlock->settings ?? [], JSON_PRETTY_PRINT)) }}</textarea>
    </div>
    <div class="flex items-center gap-4">
        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1" id="edit_block_active" {{ $cmsBlock->is_active ? "checked" : "" }} class="w-4 h-4 text-green-600 rounded">
            <label for="edit_block_active" class="text-sm font-medium text-gray-700">Ativo</label>
        </div>
    </div>
    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
        <button type="button" onclick="this.closest('#block-edit-modal').classList.add('hidden')" class="text-gray-600 hover:text-gray-800 font-medium">Cancelar</button>
        <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 font-medium">Salvar</button>
    </div>
</form>
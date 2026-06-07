{{-- @autor marcelo-brad rj --}}
{{-- @contato Tel: 21 981325441 | Email: contato@kdkhost.com.br | Telegram: @MARCELO_BRAD | Instagram: @marcelobradrj | WhatsApp: 21981325441 --}}
@extends("layouts.admin")
@section("title", "Blocos")
@section("page-title", "Gerenciar Blocos")
@push("styles")
<style>
.sortable-ghost { opacity: 0.4; background: #f0fdf4; }
.sortable-chosen { box-shadow: 0 4px 12px rgba(0,0,0,0.12); }
[data-theme="dark"] .sortable-ghost { background: #1e3a5f; }
</style>
@endpush
@push("scripts")
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
$(function() {
    var el = document.getElementById("block-list");
    if (el) {
        new Sortable(el, {
            handle: ".drag-handle",
            animation: 200,
            ghostClass: "sortable-ghost",
            chosenClass: "sortable-chosen",
            onEnd: function(evt) {
                var ids = [];
                el.querySelectorAll("li[data-id]").forEach(function(li) {
                    ids.push(li.dataset.id);
                });
                fetch("{{ route("admin.cms.blocks.reorder") }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({ order: ids })
                }).then(function(r) {
                    if (r.ok) showToast("Ordem atualizada!", "success");
                    else showToast("Erro ao reordenar.", "error");
                });
            }
        });
    }
});
</script>
@endpush
@section("content")
<div class="flex justify-between items-center mb-6">
    <div>
        <a href="{{ route("admin.cms.sections.index") }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Voltar para seções
        </a>
    </div>
    <button type="button" onclick="document.getElementById('block-create-form').classList.toggle('hidden')" class="bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800 flex items-center gap-2 text-sm font-medium">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Novo Bloco
    </button>
</div>
<div id="block-create-form" class="hidden mb-6 bg-white rounded-xl shadow-sm p-6">
    <form method="POST" action="{{ route("admin.cms.blocks.store") }}">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Título</label>
                <input type="text" name="title" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Título do bloco">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                <select name="type" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="text">Texto</option>
                    <option value="html">HTML</option>
                    <option value="image">Imagem</option>
                    <option value="video">Vídeo</option>
                    <option value="code">Código</option>
                </select>
            </div>
            <div class="flex items-end gap-4 pb-2">
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" id="new_block_active" checked class="w-4 h-4 text-green-600 rounded">
                    <label for="new_block_active" class="text-sm font-medium text-gray-700">Ativo</label>
                </div>
                <button type="submit" class="bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800 text-sm font-medium">Criar</button>
                <button type="button" onclick="this.closest('#block-create-form').classList.add('hidden')" class="text-gray-500 hover:text-gray-700 text-sm font-medium">Cancelar</button>
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1 mt-3">Conteúdo</label>
            <textarea name="content" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Conteúdo do bloco"></textarea>
        </div>
    </form>
</div>
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <ul id="block-list" class="divide-y divide-gray-100">
        @forelse($blocks as $block)
        <li data-id="{{ $block->id }}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors">
            <div class="drag-handle cursor-grab text-gray-400 hover:text-gray-600" data-tooltip="Arrastar para reordenar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-2 py-0.5 rounded text-xs font-medium
                    {{ $block->type == "text" ? "bg-blue-100 text-blue-700" : "" }}
                    {{ $block->type == "html" ? "bg-purple-100 text-purple-700" : "" }}
                    {{ $block->type == "image" ? "bg-pink-100 text-pink-700" : "" }}
                    {{ $block->type == "video" ? "bg-yellow-100 text-yellow-700" : "" }}
                    {{ $block->type == "code" ? "bg-gray-100 text-gray-700" : "" }}">
                    {{ ucfirst($block->type) }}
                </span>
            </div>
            <div class="flex-1 min-w-0">
                <span class="font-medium text-gray-900 text-sm">{{ $block->title ?? "Sem título" }}</span>
                @if($block->content)
                <p class="text-xs text-gray-500 truncate">{{ Str::limit(strip_tags($block->content), 60) }}</p>
                @endif
            </div>
            <div class="flex items-center gap-2">
                <form method="POST" action="{{ route("admin.cms.blocks.toggle-status", $block) }}">
                    @csrf
                    <button type="submit" class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors {{ $block->is_active ? "bg-green-600" : "bg-gray-300" }}">
                        <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white transition-transform {{ $block->is_active ? "translate-x-[18px]" : "translate-x-1" }}"></span>
                    </button>
                </form>
                <button type="button" onclick="editBlock({{ $block->id }})" class="text-blue-600 hover:text-blue-800 text-sm font-medium px-1" data-tooltip="Editar">Editar</button>
                <form method="POST" action="{{ route("admin.cms.blocks.destroy", $block) }}" class="inline">
                    @csrf @method("DELETE")
                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium px-1" data-confirm="Excluir este bloco?" data-tooltip="Excluir">Excluir</button>
                </form>
            </div>
        </li>
        @empty
        <li class="px-6 py-10 text-center text-gray-400">Nenhum bloco cadastrado.</li>
        @endforelse
    </ul>
</div>

<template id="block-edit-modal-tpl">
    <div id="block-edit-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 hidden" style="backdrop-filter:blur(2px);">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6 space-y-4" id="block-edit-content"></div>
        </div>
    </div>
</template>

@push("scripts")
<script>
function editBlock(id) {
    fetch("{{ route("admin.cms.blocks.edit", "ID_PLACEHOLDER") }}".replace("ID_PLACEHOLDER", id))
        .then(function(r) { return r.text(); })
        .then(function(html) {
            var modal = document.getElementById("block-edit-modal");
            if (!modal) {
                var tpl = document.getElementById("block-edit-modal-tpl");
                document.body.insertAdjacentHTML("beforeend", tpl.innerHTML);
                modal = document.getElementById("block-edit-modal");
            }
            document.getElementById("block-edit-content").innerHTML = html;
            modal.classList.remove("hidden");
        });
}
$(document).on("click", "#block-edit-modal", function(e) {
    if (e.target === this) this.classList.add("hidden");
});
</script>
@endpush
@endsection

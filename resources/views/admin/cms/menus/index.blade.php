{{-- @autor marcelo-brad rj --}}
{{-- @contato Tel: 21 981325441 | Email: contato@kdkhost.com.br | Telegram: @MARCELO_BRAD | Instagram: @marcelobradrj | WhatsApp: 21981325441 --}}
@extends("layouts.admin")
@section("title", "Menus")
@section("page-title", "Gerenciar Menus")
@push("styles")
<style>
.sortable-ghost { opacity: 0.4; background: #f0fdf4; }
.sortable-chosen { box-shadow: 0 4px 12px rgba(0,0,0,0.12); }
[data-theme="dark"] .sortable-ghost { background: #1e3a5f; }
.menu-tree ul { padding-left: 24px; }
.menu-tree li { list-style: none; }
.menu-tree .menu-item-row {
    display: flex; align-items: center; gap: 8px;
    padding: 8px 12px; border-radius: 8px;
    transition: background 0.15s;
}
.menu-tree .menu-item-row:hover { background: #f9fafb; }
[data-theme="dark"] .menu-tree .menu-item-row:hover { background: #2d3748; }
</style>
@endpush
@push("scripts")
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
$(function() {
    initSortable(document.getElementById("menu-root"));

    function initSortable(el) {
        if (!el) return;
        new Sortable(el, {
            handle: ".menu-drag-handle",
            animation: 200,
            ghostClass: "sortable-ghost",
            chosenClass: "sortable-chosen",
            onEnd: function() {
                updateMenuOrder();
            }
        });
        el.querySelectorAll(".menu-children").forEach(function(child) {
            initSortable(child);
        });
    }

    function updateMenuOrder() {
        var data = buildMenuTree(document.getElementById("menu-root"));
        fetch("{{ route("admin.cms.menus.items.reorder") }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                "Accept": "application/json"
            },
            body: JSON.stringify({ items: data })
        }).then(function(r) {
            if (r.ok) showToast("Ordem atualizada!", "success");
            else showToast("Erro ao reordenar.", "error");
        });
    }

    function buildMenuTree(el) {
        var items = [];
        el.querySelectorAll(":scope > li").forEach(function(li) {
            var id = li.dataset.id;
            var children = li.querySelector(":scope > .menu-children");
            var item = { id: id };
            if (children && children.children.length > 0) {
                item.children = buildMenuTree(children);
            }
            items.push(item);
        });
        return items;
    }

    $(".edit-menu-item").on("click", function() {
        var id = $(this).data("id");
        var label = $(this).data("label");
        var url = $(this).data("url");
        var icon = $(this).data("icon");
        var target = $(this).data("target");
        var parent = $(this).data("parent");
        $("#edit_item_id").val(id);
        $("#edit_label").val(label);
        $("#edit_url").val(url);
        $("#edit_icon").val(icon);
        $("#edit_target").val(target);
        $("#edit_parent_id").val(parent);
        $("#edit-menu-modal").removeClass("hidden");
    });

    $(".close-menu-modal").on("click", function() {
        $("#edit-menu-modal").addClass("hidden");
    });
});
</script>
@endpush
@section("content")
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-gray-800">Estrutura do Menu</h2>
    <button type="button" onclick="document.getElementById('add-item-modal').classList.remove('hidden')" class="bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800 flex items-center gap-2 text-sm font-medium">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Novo Item
    </button>
</div>

<div class="bg-white rounded-xl shadow-sm p-6">
    @if($menuItems->count() > 0)
    <ul id="menu-root" class="menu-tree space-y-1">
        @foreach($menuItems->whereNull("parent_id") as $item)
            @include("admin.cms.menus._menu_item", ["item" => $item, "depth" => 0])
        @endforeach
    </ul>
    @else
    <div class="text-center py-10 text-gray-400">
        <p>Nenhum item de menu cadastrado.</p>
        <p class="text-sm mt-1">Clique em "Novo Item" para adicionar.</p>
    </div>
    @endif
</div>

<div id="add-item-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40" style="backdrop-filter:blur(2px);">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800">Novo Item de Menu</h3>
            <button type="button" onclick="this.closest('#add-item-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route("admin.cms.menus.store") }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Label *</label>
                    <input type="text" name="label" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Ex: Sobre Nós">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">URL *</label>
                    <input type="text" name="url" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Ex: /sobre ou https://...">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ícone (opcional)</label>
                        <input type="text" name="icon" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="heroicons name">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Abrir em</label>
                        <select name="target" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="_self">Mesma aba</option>
                            <option value="_blank">Nova aba</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Item Pai</label>
                    <select name="parent_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">Nenhum (item raiz)</option>
                        @foreach($allItems as $parent)
                        <option value="{{ $parent->id }}">{{ $parent->label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" id="new_menu_active" checked class="w-4 h-4 text-green-600 rounded">
                    <label for="new_menu_active" class="text-sm font-medium text-gray-700">Ativo</label>
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="this.closest('#add-item-modal').classList.add('hidden')" class="text-gray-600 hover:text-gray-800 font-medium">Cancelar</button>
                    <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 font-medium">Adicionar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="edit-menu-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40" style="backdrop-filter:blur(2px);">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800">Editar Item de Menu</h3>
            <button type="button" class="close-menu-modal text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route("admin.cms.menus.update", "ITEM_ID") }}" id="edit-menu-form">
            @csrf @method("PUT")
            <input type="hidden" name="id" id="edit_item_id">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Label *</label>
                    <input type="text" name="label" id="edit_label" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">URL *</label>
                    <input type="text" name="url" id="edit_url" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ícone</label>
                        <input type="text" name="icon" id="edit_icon" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Abrir em</label>
                        <select name="target" id="edit_target" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="_self">Mesma aba</option>
                            <option value="_blank">Nova aba</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Item Pai</label>
                    <select name="parent_id" id="edit_parent_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">Nenhum (item raiz)</option>
                        @foreach($allItems as $parent)
                        <option value="{{ $parent->id }}">{{ $parent->label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" class="close-menu-modal text-gray-600 hover:text-gray-800 font-medium">Cancelar</button>
                    <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 font-medium">Salvar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById("edit-menu-form")?.addEventListener("submit", function(e) {
    e.preventDefault();
    var id = document.getElementById("edit_item_id").value;
    this.action = "{{ route("admin.cms.menus.update", "ID") }}".replace("ID", id);
    this.submit();
});
</script>
@endsection

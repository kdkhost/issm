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
<script>
function initMenuSortable() {
    var root = document.getElementById("menu-root");
    if (!root || typeof Sortable === "undefined") return;

    function initSortable(el) {
        if (!el || el.sortableInstance) return;
        el.sortableInstance = new Sortable(el, {
            group: "menu-items",
            handle: ".menu-drag-handle",
            animation: 200,
            easing: "cubic-bezier(0.25, 0.46, 0.45, 0.94)",
            ghostClass: "sortable-ghost",
            chosenClass: "sortable-chosen",
            dragClass: "sortable-drag",
            fallbackOnBody: true,
            swapThreshold: 0.65,
            onEnd: function() {
                updateMenuOrder();
            }
        });
        el.querySelectorAll(".menu-children").forEach(function(child) {
            initSortable(child);
        });
    }

    initSortable(root);
}

function showToast(msg, type) {
    if (typeof Toastify !== "undefined") {
        var bg = type === "success" ? "#28a745" : type === "error" ? "#dc3545" : "#2563eb";
        Toastify({ text: msg, duration: 3000, gravity: "top", position: "right", style: { background: bg }, close: true }).showToast();
    } else if (type === "error") {
        console.warn("Erro: " + msg);
    }
}

function updateMenuOrder() {
    var root = document.getElementById("menu-root");
    var menuIdEl = document.getElementById("menu_id");
    if (!root || !menuIdEl) return;
    var menuId = menuIdEl.value;
    if (!menuId) return;
    var data = buildMenuTree(root);
    if (!data.length) return;
    fetch("{{ route("admin.cms.menus.items.reorder") }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.content || "",
            "Accept": "application/json"
        },
        body: JSON.stringify({ items: data })
    }).then(function(r) {
        if (r.ok) showToast("Ordem atualizada!", "success");
        else showToast("Erro ao reordenar.", "error");
    }).catch(function() {
        showToast("Erro de conexão.", "error");
    });
}

function buildMenuTree(el) {
    var items = [];
    var lis = el.querySelectorAll(":scope > li");
    for (var i = 0; i < lis.length; i++) {
        var li = lis[i];
        var id = li.getAttribute("data-id");
        if (!id) continue;
        var childrenEl = li.querySelector(":scope > .menu-children");
        var item = { id: parseInt(id, 10) };
        if (childrenEl && childrenEl.children.length > 0) {
            item.children = buildMenuTree(childrenEl);
        }
        items.push(item);
    }
    return items;
}

document.addEventListener("DOMContentLoaded", function() {
    var s = document.createElement("script");
    s.src = "https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js";
    s.onload = function() { initMenuSortable(); };
    s.onerror = function() { console.error("SortableJS não carregou"); };
    document.head.appendChild(s);
});

$(function() {
    $(".edit-menu-item").on("click", function() {
        var id = $(this).data("id");
        var title = $(this).data("title");
        var url = $(this).data("url");
        var icon = $(this).data("icon");
        var target = $(this).data("target");
        var parent = $(this).data("parent");
        var active = $(this).data("active");
        $("#edit_item_id").val(id);
        $("#edit_title").val(title);
        $("#edit_url").val(url);
        $("#edit_icon").val(icon);
        $("#edit_target").val(target);
        $("#edit_parent_id").val(parent);
        $("#edit_is_active").prop("checked", active !== false);
        $("#edit-menu-item-modal").removeClass("hidden");
    });
});
</script>
@endpush
@section("content")
<div class="flex justify-between items-center mb-6">
    <div class="flex items-center gap-4">
        <h2 class="text-xl font-bold text-gray-800">Gerenciar Menus</h2>
        <form method="GET" action="{{ route("admin.cms.menus.index") }}" id="menu-selector-form" class="flex items-center gap-2">
            <label for="menu_id" class="text-sm text-gray-600">Menu:</label>
            <select name="menu_id" id="menu_id" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 bg-white">
                @foreach($menus as $menu)
                <option value="{{ $menu->id }}" {{ $selectedMenu && $selectedMenu->id == $menu->id ? 'selected' : '' }}>
                    {{ $menu->name }} ({{ $menu->location }})
                </option>
                @endforeach
            </select>
        </form>
    </div>
    <div class="flex items-center gap-2">
        <button type="button" onclick="document.getElementById('add-menu-modal').classList.remove('hidden')" class="text-gray-600 hover:text-gray-800 px-3 py-2 rounded-lg text-sm font-medium border border-gray-300">
            + Novo Menu
        </button>
        <button type="button" onclick="document.getElementById('add-item-modal').classList.remove('hidden')" class="bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800 flex items-center gap-2 text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Novo Item
        </button>
    </div>
</div>

@if($selectedMenu)
<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-4 pb-4 border-b border-gray-100">
        <div>
            <h3 class="text-lg font-bold text-gray-800">{{ $selectedMenu->name }}</h3>
            <p class="text-sm text-gray-500">{{ $selectedMenu->description }}</p>
        </div>
        <div class="flex items-center gap-3 text-sm">
            <button type="button" onclick="document.getElementById('edit-menu-modal').classList.remove('hidden')" class="text-blue-600 hover:text-blue-800 font-medium">
                Editar Menu
            </button>
            <form method="POST" action="{{ route("admin.cms.menus.destroy", $selectedMenu) }}" class="inline">
                @csrf @method("DELETE")
                <button type="submit" data-confirm="Excluir este menu e todos os seus itens?" class="text-red-600 hover:text-red-800 font-medium">Excluir Menu</button>
            </form>
        </div>
    </div>
    @if($menuItems->count() > 0)
    <ul id="menu-root" class="menu-tree space-y-1 mt-4">
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
@else
<div class="bg-white rounded-xl shadow-sm p-6 text-center py-10 text-gray-400">
    <p>Nenhum menu disponível.</p>
</div>
@endif

{{-- Modal: Novo Menu --}}
<div id="add-menu-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40" style="backdrop-filter:blur(2px);">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800">Novo Menu</h3>
            <button type="button" onclick="this.closest('#add-menu-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route("admin.cms.menus.store") }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome *</label>
                    <input type="text" name="name" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Ex: Menu Principal">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Slug (URL amigável)</label>
                    <input type="text" name="slug" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="main-menu">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Localização</label>
                    <select name="location" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="header">Cabeçalho (header)</option>
                        <option value="sidebar">Sidebar Mobile (sidebar)</option>
                        <option value="bottom">Barra Inferior (bottom)</option>
                        <option value="footer">Rodapé (footer)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                    <textarea name="description" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Descrição opcional"></textarea>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 text-green-600 rounded">
                    <label class="text-sm font-medium text-gray-700">Ativo</label>
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="this.closest('#add-menu-modal').classList.add('hidden')" class="text-gray-600 hover:text-gray-800 font-medium">Cancelar</button>
                    <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 font-medium">Criar Menu</button>
                </div>
            </div>
        </form>
    </div>
</div>

@if($selectedMenu)
{{-- Modal: Editar Menu --}}
<div id="edit-menu-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40" style="backdrop-filter:blur(2px);">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800">Editar Menu</h3>
            <button type="button" onclick="this.closest('#edit-menu-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route("admin.cms.menus.update", $selectedMenu) }}">
            @csrf @method("PUT")
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome *</label>
                    <input type="text" name="name" value="{{ $selectedMenu->name }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                    <input type="text" name="slug" value="{{ $selectedMenu->slug }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Localização</label>
                    <select name="location" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="header" {{ $selectedMenu->location == 'header' ? 'selected' : '' }}>Cabeçalho (header)</option>
                        <option value="sidebar" {{ $selectedMenu->location == 'sidebar' ? 'selected' : '' }}>Sidebar Mobile (sidebar)</option>
                        <option value="bottom" {{ $selectedMenu->location == 'bottom' ? 'selected' : '' }}>Barra Inferior (bottom)</option>
                        <option value="footer" {{ $selectedMenu->location == 'footer' ? 'selected' : '' }}>Rodapé (footer)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                    <textarea name="description" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">{{ $selectedMenu->description }}</textarea>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" {{ $selectedMenu->is_active ? 'checked' : '' }} class="w-4 h-4 text-green-600 rounded">
                    <label class="text-sm font-medium text-gray-700">Ativo</label>
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="this.closest('#edit-menu-modal').classList.add('hidden')" class="text-gray-600 hover:text-gray-800 font-medium">Cancelar</button>
                    <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 font-medium">Salvar</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif

<div id="add-item-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40" style="backdrop-filter:blur(2px);">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800">Novo Item de Menu</h3>
            <button type="button" onclick="this.closest('#add-item-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route("admin.cms.menus.items.add") }}">
            @csrf
            <input type="hidden" name="cms_menu_id" value="{{ $selectedMenu ? $selectedMenu->id : '' }}">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Título *</label>
                    <input type="text" name="title" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Ex: Sobre Nós">
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
                        <option value="{{ $parent->id }}">{{ $parent->title }}</option>
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

<div id="edit-menu-item-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40" style="backdrop-filter:blur(2px);">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800">Editar Item de Menu</h3>
            <button type="button" onclick="document.getElementById('edit-menu-item-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route("admin.cms.menus.items.update", "ITEM_ID") }}" id="edit-menu-item-form">
            @csrf @method("PUT")
            <input type="hidden" name="id" id="edit_item_id">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Título *</label>
                    <input type="text" name="title" id="edit_title" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
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
                        <option value="{{ $parent->id }}">{{ $parent->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" id="edit_is_active" checked class="w-4 h-4 text-green-600 rounded">
                    <label for="edit_is_active" class="text-sm font-medium text-gray-700">Ativo</label>
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="document.getElementById('edit-menu-item-modal').classList.add('hidden')" class="text-gray-600 hover:text-gray-800 font-medium">Cancelar</button>
                    <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 font-medium">Salvar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById("edit-menu-item-form")?.addEventListener("submit", function(e) {
    e.preventDefault();
    var id = document.getElementById("edit_item_id").value;
    this.action = "{{ route("admin.cms.menus.items.update", "ID") }}".replace("ID", id);
    this.submit();
});
</script>
@endsection

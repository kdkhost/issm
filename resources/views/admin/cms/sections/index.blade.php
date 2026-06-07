{{-- @autor marcelo-brad rj --}}
{{-- @contato Tel: 21 981325441 | Email: contato@kdkhost.com.br | Telegram: @MARCELO_BRAD | Instagram: @marcelobradrj | WhatsApp: 21981325441 --}}
@extends("layouts.admin")
@section("title", "Seções")
@section("page-title", "Gerenciar Seções")
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
    var el = document.getElementById("section-list");
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
                fetch("{{ route("admin.cms.sections.reorder") }}", {
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
        <a href="{{ route("admin.cms.pages.index") }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Voltar para páginas
        </a>
    </div>
    <button type="button" onclick="document.getElementById('inline-create-form').classList.toggle('hidden')" class="bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800 flex items-center gap-2 text-sm font-medium">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Nova Seção
    </button>
</div>
<div id="inline-create-form" class="hidden mb-6 bg-white rounded-xl shadow-sm p-6">
    <form method="POST" action="{{ route("admin.cms.sections.store") }}">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nome *</label>
                <input type="text" name="name" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Ex: Hero Section">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                <select name="type" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="hero">Hero</option>
                    <option value="banner">Banner</option>
                    <option value="text">Texto</option>
                    <option value="cards">Cards</option>
                    <option value="gallery">Galeria</option>
                    <option value="faq">FAQ</option>
                    <option value="cta">CTA</option>
                    <option value="contact">Contato</option>
                    <option value="custom">Personalizado</option>
                </select>
            </div>
            <div class="flex items-end gap-4 pb-2">
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" id="new_is_active" checked class="w-4 h-4 text-green-600 rounded">
                    <label for="new_is_active" class="text-sm font-medium text-gray-700">Ativo</label>
                </div>
                <button type="submit" class="bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800 text-sm font-medium">Criar</button>
                <button type="button" onclick="this.closest('#inline-create-form').classList.add('hidden')" class="text-gray-500 hover:text-gray-700 text-sm font-medium">Cancelar</button>
            </div>
        </div>
    </form>
</div>
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <ul id="section-list" class="divide-y divide-gray-100">
        @forelse($sections as $section)
        <li data-id="{{ $section->id }}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors">
            <div class="drag-handle cursor-grab text-gray-400 hover:text-gray-600" data-tooltip="Arrastar para reordenar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2">
                    <span class="font-medium text-gray-900 text-sm">{{ $section->name }}</span>
                    <span class="badge-gray text-xs">{{ ucfirst($section->type) }}</span>
                    @if(!$section->is_active)<span class="text-xs text-gray-400">(Inativo)</span>@endif
                </div>
                <p class="text-xs text-gray-500 truncate">{{ Str::limit($section->description ?? "Sem descrição", 60) }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route("admin.cms.blocks.index") }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium px-1" data-tooltip="Gerenciar blocos">Blocos</a>
                <form method="POST" action="{{ route("admin.cms.sections.destroy", $section) }}" class="inline">
                    @csrf @method("DELETE")
                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium px-1" data-confirm="Excluir esta seção e todos os seus blocos?" data-tooltip="Excluir">Excluir</button>
                </form>
            </div>
        </li>
        @empty
        <li class="px-6 py-10 text-center text-gray-400">Nenhuma seção cadastrada.</li>
        @endforelse
    </ul>
</div>
@endsection

{{-- @autor marcelo-brad rj --}}
{{-- @contato Tel: 21 981325441 | Email: contato@kdkhost.com.br | Telegram: @MARCELO_BRAD | Instagram: @marcelobradrj | WhatsApp: 21981325441 --}}
@extends("layouts.admin")
@section("title", "Mídia")
@section("page-title", "Gerenciar Mídia")
@push("scripts")
<script>
$(function() {
    $(".media-delete").on("click", function(e) {
        e.preventDefault();
        var form = $(this).closest("form");
        Swal.fire({
            title: "Confirmar exclusão",
            text: "Esta mídia será excluída permanentemente.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dc2626",
            cancelButtonColor: "#6b7280",
            confirmButtonText: "Sim, excluir",
            cancelButtonText: "Cancelar",
            reverseButtons: true,
            borderRadius: "16px",
        }).then(function(result) {
            if (result.isConfirmed) form.submit();
        });
    });

    $(".edit-media-btn").on("click", function() {
        var id = $(this).data("id");
        var name = $(this).data("name");
        var alt = $(this).data("alt");
        $("#edit_media_id").val(id);
        $("#edit_name").val(name);
        $("#edit_alt").val(alt);
        $("#edit-media-modal").removeClass("hidden");
    });

    $("#edit-media-modal .close-modal, #edit-media-modal .cancel-modal").on("click", function() {
        $("#edit-media-modal").addClass("hidden");
    });
});
</script>
@endpush
@section("content")
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <form method="GET" action="{{ route("admin.cms.media.index") }}" class="flex flex-col sm:flex-row gap-3">
        <input type="text" name="search" value="{{ request("search") }}" placeholder="Buscar mídia..." class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
        <select name="type" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            <option value="">Todos os tipos</option>
            <option value="image" {{ request("type") == "image" ? "selected" : "" }}>Imagens</option>
            <option value="video" {{ request("type") == "video" ? "selected" : "" }}>Vídeos</option>
            <option value="document" {{ request("type") == "document" ? "selected" : "" }}>Documentos</option>
            <option value="audio" {{ request("type") == "audio" ? "selected" : "" }}>Áudios</option>
            <option value="other" {{ request("type") == "other" ? "selected" : "" }}>Outros</option>
        </select>
        <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 text-sm font-medium">Filtrar</button>
        @if(request("search") || request("type"))
        <a href="{{ route("admin.cms.media.index") }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium self-center">Limpar</a>
        @endif
    </form>
    <button type="button" onclick="document.getElementById('upload-modal').classList.remove('hidden')" class="bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800 flex items-center gap-2 text-sm font-medium">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
        Upload
    </button>
</div>

<div id="upload-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40" style="backdrop-filter:blur(2px);">
    <div class="bg-white rounded-2xl shadow-2xl max-w-xl w-full mx-4 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800">Upload de Mídia</h3>
            <button type="button" onclick="this.closest('#upload-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route("admin.cms.media.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Arquivo *</label>
                    <input type="file" name="file" required accept="image/*,video/*,.pdf,.doc,.docx,.mp3,.wav">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome (opcional)</label>
                    <input type="text" name="name" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Nome para identificar">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Texto Alternativo (ALT)</label>
                    <input type="text" name="alt_text" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Descrição para acessibilidade">
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="this.closest('#upload-modal').classList.add('hidden')" class="text-gray-600 hover:text-gray-800 font-medium">Cancelar</button>
                    <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 font-medium">Enviar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 p-4">
        @forelse($media as $item)
        <div class="group relative bg-gray-50 rounded-lg overflow-hidden border border-gray-200 hover:shadow-md transition-shadow">
            @if(str_starts_with($item->mime_type ?? $item->type, "image/") || $item->type == "image")
            <img src="{{ asset("media/" . $item->filename) }}" alt="{{ $item->alt_text ?? $item->name }}" class="w-full h-32 object-cover">
            @elseif(str_starts_with($item->mime_type ?? $item->type, "video/") || $item->type == "video")
            <div class="w-full h-32 flex items-center justify-center bg-gray-800 text-white">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            @else
            <div class="w-full h-32 flex items-center justify-center bg-gray-100 text-gray-400">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            @endif
            <div class="p-2">
                <p class="text-xs font-medium text-gray-800 truncate" title="{{ $item->name ?? $item->filename }}">{{ $item->name ?? $item->filename }}</p>
                <p class="text-xs text-gray-400">{{ $item->created_at->format("d/m/Y") }}</p>
            </div>
            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                <button type="button" class="edit-media-btn text-white bg-blue-600 hover:bg-blue-700 p-1.5 rounded text-xs"
                    data-id="{{ $item->id }}"
                    data-name="{{ $item->name }}"
                    data-alt="{{ $item->alt_text }}"
                    title="Editar">Editar</button>
                <button type="button" class="text-white bg-green-600 hover:bg-green-700 p-1.5 rounded text-xs"
                    onclick="navigator.clipboard.writeText('{{ asset("media/" . $item->filename) }}');showToast('URL copiada!','success');"
                    title="Copiar URL">URL</button>
                <form method="POST" action="{{ route("admin.cms.media.destroy", $item) }}" class="inline">
                    @csrf @method("DELETE")
                    <button type="submit" class="media-delete text-white bg-red-600 hover:bg-red-700 p-1.5 rounded text-xs" title="Excluir">Excluir</button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-10 text-gray-400">Nenhuma mídia encontrada.</div>
        @endforelse
    </div>
    <div class="p-4 border-t border-gray-100">{{ $media->appends(request()->query())->links() }}</div>
</div>

<div id="edit-media-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40" style="backdrop-filter:blur(2px);">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800">Editar Mídia</h3>
            <button type="button" class="close-modal text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route("admin.cms.media.update", "ID_PLACEHOLDER") }}" id="edit-media-form">
            @csrf @method("PUT")
            <input type="hidden" name="id" id="edit_media_id">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
                    <input type="text" name="name" id="edit_name" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Texto Alternativo (ALT)</label>
                    <input type="text" name="alt_text" id="edit_alt" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" class="cancel-modal text-gray-600 hover:text-gray-800 font-medium">Cancelar</button>
                    <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 font-medium">Salvar</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
document.getElementById("edit-media-form")?.addEventListener("submit", function(e) {
    e.preventDefault();
    var id = document.getElementById("edit_media_id").value;
    this.action = "{{ route("admin.cms.media.update", "ID") }}".replace("ID", id);
    this.submit();
});
</script>
@endsection

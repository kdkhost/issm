@extends("layouts.admin")
@section("title", "Novo Álbum")
@section("page-title", "Novo Álbum da Galeria")

@push("styles")
<style>
    .gallery-initial-dropzone {
        border: 2px dashed #d1d5db;
        border-radius: 12px;
        background: #f9fafb;
        padding: 22px 16px;
        text-align: center;
        cursor: pointer;
        transition: border-color .18s ease, background-color .18s ease;
    }

    .gallery-initial-dropzone:hover,
    .gallery-initial-dropzone.is-over {
        border-color: #16a34a;
        background: #f0fdf4;
    }

    .gallery-initial-file {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 8px 10px;
        background: #fff;
    }

    [data-theme="dark"] .gallery-initial-dropzone {
        border-color: #4b5563;
        background: #374151;
    }

    [data-theme="dark"] .gallery-initial-dropzone:hover,
    [data-theme="dark"] .gallery-initial-dropzone.is-over {
        border-color: #22c55e;
        background: rgba(34, 197, 94, .08);
    }

    [data-theme="dark"] .gallery-initial-file {
        border-color: #374151;
        background: #1f2937;
    }
</style>
@endpush

@section("content")
<div class="max-w-5xl">
    <form method="POST" action="{{ route("admin.galeria.store") }}" enctype="multipart/form-data" id="galleryAlbumForm">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-5">
                <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
                    <h3 class="font-semibold text-gray-800">Dados do álbum</h3>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Título *</label>
                        <input type="text" name="title" value="{{ old("title") }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                        @error("title")<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                        <textarea name="description" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">{{ old("description") }}</textarea>
                        @error("description")<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
                    <h3 class="font-semibold text-gray-800">Evento</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Data do evento</label>
                            <input type="date" name="event_date" value="{{ old("event_date") }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            @error("event_date")<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Local do evento</label>
                            <input type="text" name="event_location" value="{{ old("event_location") }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            @error("event_location")<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
                    <h3 class="font-semibold text-gray-800">Projetos vinculados</h3>
                    @include("admin.galeria._project-checkboxes", [
                        "projects" => $projects,
                        "selectedProjectIds" => old("project_ids", []),
                    ])
                </div>
            </div>

            <div class="space-y-5">
                <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
                    <h3 class="font-semibold text-gray-800">Publicação</h3>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ordem</label>
                        <input type="number" name="sort_order" value="{{ old("sort_order", 0) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                        @error("sort_order")<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="active" value="1" id="active" {{ old("active", "1") ? "checked" : "" }} class="w-4 h-4 text-green-600 rounded">
                        <label for="active" class="text-sm font-medium text-gray-700">Ativo</label>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
                    <h3 class="font-semibold text-gray-800">Dimensão ideal</h3>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Largura</label>
                            <input type="number" name="ideal_image_width" value="{{ old("ideal_image_width", 1600) }}" min="320" max="8000" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            @error("ideal_image_width")<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Altura</label>
                            <input type="number" name="ideal_image_height" value="{{ old("ideal_image_height", 1200) }}" min="240" max="8000" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            @error("ideal_image_height")<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
                    <h3 class="font-semibold text-gray-800">Imagem</h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Imagem de capa</label>
                        <input type="file" name="cover_image" accept="image/*" class="w-full text-sm text-gray-600">
                        @error("cover_image")<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fotos iniciais</label>
                        <div id="gallery-initial-dropzone" class="gallery-initial-dropzone">
                            <input type="file" id="gallery-initial-input" name="images[]" accept="image/*" multiple data-no-dropzone class="hidden">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-9 h-9 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 16a4 4 0 01.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M12 12v9m0-9l-3 3m3-3l3 3"/></svg>
                                <div>
                                    <p class="font-semibold text-gray-800">Arraste várias fotos aqui</p>
                                    <p class="text-xs text-gray-500">ou clique para selecionar múltiplas imagens de uma só vez</p>
                                </div>
                            </div>
                        </div>
                        <div id="gallery-initial-files" class="space-y-2 mt-3"></div>
                        @error("images.*")<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="flex justify-between">
                    <a href="{{ route("admin.galeria.index") }}" class="text-gray-600 hover:text-gray-800 font-medium">Cancelar</a>
                    <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 font-medium">Salvar</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push("scripts")
<script>
(function() {
    var zone = document.getElementById('gallery-initial-dropzone');
    var input = document.getElementById('gallery-initial-input');
    var list = document.getElementById('gallery-initial-files');
    var maxBytes = {{ (int) $uploadLimitMb }} * 1024 * 1024;
    var bag = new DataTransfer();

    if (!zone || !input || !list) return;

    function toast(message, type) {
        if (typeof showToast === 'function') {
            showToast(message, type || 'success');
        } else {
            alert(message);
        }
    }

    function escapeHtml(text) {
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function formatSize(bytes) {
        return (bytes / 1024 / 1024).toFixed(2).replace('.', ',') + ' MB';
    }

    function fileSignature(file) {
        return [file.name.toLowerCase(), file.size, file.lastModified].join('|');
    }

    function hasQueuedFile(file) {
        var signature = fileSignature(file);

        return Array.from(bag.files).some(function(current) {
            return fileSignature(current) === signature;
        });
    }

    function syncInput() {
        input.files = bag.files;
    }

    function renderList() {
        list.innerHTML = '';

        if (bag.files.length === 0) {
            return;
        }

        Array.from(bag.files).forEach(function(file, index) {
            var row = document.createElement('div');
            row.className = 'gallery-initial-file';
            row.innerHTML =
                '<div class="min-w-0">' +
                    '<p class="text-sm font-semibold text-gray-800 truncate">' + escapeHtml(file.name) + '</p>' +
                    '<p class="text-xs text-gray-500">' + formatSize(file.size) + '</p>' +
                '</div>' +
                '<button type="button" class="text-red-600 hover:text-red-800 text-xs font-semibold" data-remove="' + index + '">Remover</button>';
            list.appendChild(row);
        });
    }

    function rebuildWithout(removeIndex) {
        var next = new DataTransfer();
        Array.from(bag.files).forEach(function(file, index) {
            if (index !== removeIndex) next.items.add(file);
        });
        bag = next;
        syncInput();
        renderList();
    }

    function addFiles(files) {
        Array.from(files).forEach(function(file) {
            if (!file.type.match(/^image\//)) {
                toast('Arquivo ignorado: ' + file.name + ' não é imagem.', 'error');
                return;
            }

            if (file.size > maxBytes) {
                toast('Arquivo ignorado: ' + file.name + ' passa do limite de {{ $uploadLimitMb }} MB.', 'error');
                return;
            }

            if (hasQueuedFile(file)) {
                toast('Arquivo ignorado: ' + file.name + ' ja foi selecionado.', 'error');
                return;
            }

            bag.items.add(file);
        });

        syncInput();
        renderList();
    }

    zone.addEventListener('click', function() {
        input.click();
    });

    zone.addEventListener('dragover', function(event) {
        event.preventDefault();
        zone.classList.add('is-over');
    });

    zone.addEventListener('dragleave', function() {
        zone.classList.remove('is-over');
    });

    zone.addEventListener('drop', function(event) {
        event.preventDefault();
        zone.classList.remove('is-over');
        addFiles(event.dataTransfer.files || []);
    });

    input.addEventListener('change', function() {
        addFiles(input.files || []);
    });

    list.addEventListener('click', function(event) {
        var button = event.target.closest('[data-remove]');
        if (!button) return;
        rebuildWithout(parseInt(button.dataset.remove, 10));
    });
})();
</script>
@endpush

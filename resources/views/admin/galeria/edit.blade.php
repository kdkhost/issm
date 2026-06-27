@extends("layouts.admin")
@section("title", "Editar Álbum")
@section("page-title", "Editar Álbum da Galeria")

@push("styles")
<style>
    .gallery-upload-zone {
        border: 2px dashed #86efac;
        border-radius: 14px;
        background: #f0fdf4;
        padding: 28px;
        text-align: center;
        cursor: pointer;
        transition: border-color .2s, background .2s, transform .2s;
    }
    .gallery-upload-zone.is-over {
        border-color: #15803d;
        background: #dcfce7;
        transform: translateY(-1px);
    }
    [data-theme="dark"] .gallery-upload-zone {
        background: rgba(34, 197, 94, .08);
        border-color: #22c55e;
    }
    .upload-row {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 12px;
        background: #fff;
    }
    [data-theme="dark"] .upload-row {
        background: #1f2937;
        border-color: #374151;
    }
</style>
@endpush

@section("content")
@php
    $selectedProjects = old("project_ids", $album->projects->pluck("id")->map(fn($id) => (string) $id)->toArray());
    $cover = $album->coverImagePath();
@endphp

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-800">{{ $album->title }}</h2>
        <p class="text-sm text-gray-500 mt-1">Gerencie o evento, os projetos vinculados e as fotos exibidas no site.</p>
    </div>
    <div class="flex flex-wrap gap-2">
        <button type="button" data-toggle-album data-url="{{ route("admin.galeria.toggle", $album) }}" class="px-4 py-2 rounded-lg text-sm font-semibold {{ $album->active ? "bg-amber-100 text-amber-800 hover:bg-amber-200" : "bg-green-100 text-green-800 hover:bg-green-200" }}">
            {{ $album->active ? "Desativar álbum" : "Ativar álbum" }}
        </button>
        <a href="{{ route("admin.galeria.index") }}" class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 text-sm font-semibold">Voltar</a>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <div class="xl:col-span-2 space-y-6">
        <form method="POST" action="{{ route("admin.galeria.update", $album) }}" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm p-6 space-y-5">
            @csrf
            @method("PUT")

            <div class="flex items-center justify-between gap-3">
                <div>
                    <h3 class="font-bold text-gray-900">Dados do evento</h3>
                    <p class="text-sm text-gray-500">Essas informações organizam a galeria pública por álbum/evento.</p>
                </div>
                <span data-album-status class="px-2 py-1 rounded-full text-xs font-semibold {{ $album->active ? "bg-green-100 text-green-700" : "bg-gray-100 text-gray-600" }}">
                    {{ $album->active ? "Ativo" : "Inativo" }}
                </span>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nome do álbum / evento *</label>
                <input type="text" name="title" value="{{ old("title", $album->title) }}" required>
                @error("title")<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                <textarea name="description" rows="4">{{ old("description", $album->description) }}</textarea>
                @error("description")<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data do evento</label>
                    <input type="date" name="event_date" value="{{ old("event_date", optional($album->event_date)->format("Y-m-d")) }}">
                    @error("event_date")<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Local do evento</label>
                    <input type="text" name="event_location" value="{{ old("event_location", $album->event_location) }}">
                    @error("event_location")<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Projetos vinculados</label>
                <select name="project_ids[]" multiple size="7">
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" @selected(in_array((string) $project->id, $selectedProjects, true))>{{ $project->title }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">Os projetos selecionados aparecem junto ao álbum na galeria pública.</p>
                @error("project_ids")<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ordem</label>
                    <input type="number" name="sort_order" value="{{ old("sort_order", $album->sort_order) }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Largura ideal</label>
                    <input type="number" name="ideal_image_width" value="{{ old("ideal_image_width", $album->ideal_image_width) }}" min="320" max="8000" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Altura ideal</label>
                    <input type="number" name="ideal_image_height" value="{{ old("ideal_image_height", $album->ideal_image_height) }}" min="240" max="8000" required>
                </div>
                <div class="flex items-center gap-3 md:pt-7">
                    <input type="checkbox" name="active" value="1" id="active" {{ old("active", $album->active) ? "checked" : "" }}>
                    <label for="active" class="text-sm font-medium text-gray-700">Álbum ativo</label>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Imagem de capa</label>
                @if($cover)
                    <img src="{{ asset("media/" . $cover) }}" alt="{{ $album->title }}" class="w-full max-w-sm h-36 object-cover rounded-lg mb-3">
                @endif
                <input type="file" name="cover_image" accept="image/*">
                <p class="text-xs text-gray-500 mt-1">Se nenhuma capa for enviada, a primeira foto ativa será usada como capa.</p>
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-100">
                <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 font-medium">Salvar alterações</button>
            </div>
        </form>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-5">
                <div>
                    <h3 class="font-bold text-gray-900">Upload múltiplo de fotos</h3>
                    <p class="text-sm text-gray-500">Arraste várias imagens ou selecione arquivos. Até 3 uploads são enviados ao mesmo tempo.</p>
                </div>
                <button type="button" id="reload-after-upload" class="hidden px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-sm font-semibold">
                    Atualizar lista
                </button>
            </div>

            <div id="gallery-upload-zone" class="gallery-upload-zone">
                <input type="file" id="gallery-upload-input" accept="image/*" multiple data-no-dropzone class="hidden">
                <div class="flex flex-col items-center gap-3">
                    <svg class="w-12 h-12 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 16a4 4 0 01.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M12 12v9m0-9l-3 3m3-3l3 3"/></svg>
                    <div>
                        <p class="font-semibold text-gray-800">Solte as imagens aqui</p>
                        <p class="text-sm text-gray-500">Dimensão ideal: {{ number_format($album->ideal_image_width, 0, ",", ".") }} x {{ number_format($album->ideal_image_height, 0, ",", ".") }} px. Limite: {{ $uploadLimitMb }} MB por imagem.</p>
                    </div>
                </div>
            </div>

            <div id="upload-queue" class="space-y-3 mt-5"></div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-bold text-gray-900 mb-4">Resumo</h3>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between gap-4"><dt class="text-gray-500">Total de fotos</dt><dd class="font-semibold text-gray-900">{{ $album->photos->count() }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-gray-500">Fotos ativas</dt><dd class="font-semibold text-green-700">{{ $album->photos->where("active", true)->count() }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-gray-500">Projetos vinculados</dt><dd class="font-semibold text-blue-700">{{ $album->projects->count() }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-gray-500">Evento</dt><dd class="font-semibold text-gray-900">{{ optional($album->event_date)->format("d/m/Y") ?? "-" }}</dd></div>
            </dl>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-bold text-gray-900 mb-4">Projetos do evento</h3>
            @forelse($album->projects as $project)
                <a href="{{ route("admin.projetos.edit", $project) }}" class="block px-3 py-2 rounded-lg bg-blue-50 text-blue-800 text-sm font-medium mb-2 hover:bg-blue-100">{{ $project->title }}</a>
            @empty
                <p class="text-sm text-gray-500">Nenhum projeto vinculado.</p>
            @endforelse
        </div>
    </div>
</div>

<div class="mt-6 bg-white rounded-xl shadow-sm p-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
        <div>
            <h3 class="font-bold text-gray-900">Fotos do álbum</h3>
            <p class="text-sm text-gray-500">Apenas fotos ativas dentro de álbuns ativos aparecem na galeria pública.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse($album->photos as $photo)
            <div class="border border-gray-100 rounded-xl overflow-hidden" data-photo-card="{{ $photo->id }}">
                <div class="relative h-44 bg-gray-100">
                    <img src="{{ asset("media/" . $photo->image) }}" alt="{{ $photo->title }}" class="w-full h-full object-cover">
                    <span data-photo-status="{{ $photo->id }}" class="absolute top-3 right-3 px-2 py-1 rounded-full text-xs font-semibold {{ $photo->active ? "bg-green-100 text-green-700" : "bg-gray-100 text-gray-600" }}">
                        {{ $photo->active ? "Ativa" : "Inativa" }}
                    </span>
                </div>

                <div class="p-4">
                    <form method="POST" action="{{ route("admin.galeria.photos.update", [$album, $photo]) }}" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        @method("PUT")

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Título</label>
                            <input type="text" name="title" value="{{ old("photos.{$photo->id}.title", $photo->title) }}" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                            <textarea name="description" rows="2">{{ old("photos.{$photo->id}.description", $photo->description) }}</textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ordem</label>
                                <input type="number" name="sort_order" value="{{ old("photos.{$photo->id}.sort_order", $photo->sort_order) }}">
                            </div>
                            <div class="flex items-center gap-2 pt-7">
                                <input type="checkbox" name="active" value="1" id="photo-active-{{ $photo->id }}" {{ $photo->active ? "checked" : "" }}>
                                <label for="photo-active-{{ $photo->id }}" class="text-sm font-medium text-gray-700">Ativa</label>
                            </div>
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ $photo->width && $photo->height ? $photo->width . " x " . $photo->height . " px" : "Dimensão não identificada" }}
                            @if($photo->size_kb)
                                • {{ number_format($photo->size_kb / 1024, 2, ",", ".") }} MB
                            @endif
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Substituir imagem</label>
                            <input type="file" name="image" accept="image/*" data-no-dropzone>
                        </div>
                        <div class="flex flex-wrap gap-2 pt-2 border-t border-gray-100">
                            <button type="submit" class="px-3 py-2 rounded-lg bg-green-700 text-white hover:bg-green-800 text-sm font-semibold">Salvar foto</button>
                            <button type="button" data-toggle-photo data-url="{{ route("admin.galeria.photos.toggle", [$album, $photo]) }}" data-id="{{ $photo->id }}" class="px-3 py-2 rounded-lg text-sm font-semibold {{ $photo->active ? "bg-amber-100 text-amber-800" : "bg-green-100 text-green-800" }}">
                                {{ $photo->active ? "Desativar" : "Ativar" }}
                            </button>
                        </div>
                    </form>
                    <form method="POST" action="{{ route("admin.galeria.photos.destroy", [$album, $photo]) }}" class="mt-2">
                        @csrf
                        @method("DELETE")
                        <button type="submit" data-confirm="Excluir esta foto?" class="text-red-600 hover:text-red-800 text-sm font-medium">Excluir foto</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="md:col-span-2 xl:col-span-3 text-center py-12 text-gray-400">Nenhuma foto cadastrada neste álbum.</div>
        @endforelse
    </div>
</div>
@endsection

@push("scripts")
<script>
(function() {
    var token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    var uploadUrl = @json(route("admin.galeria.photos.store", $album));
    var idealWidth = {{ (int) $album->ideal_image_width }};
    var idealHeight = {{ (int) $album->ideal_image_height }};
    var maxBytes = {{ (int) $uploadLimitMb }} * 1024 * 1024;
    var zone = document.getElementById('gallery-upload-zone');
    var input = document.getElementById('gallery-upload-input');
    var queueEl = document.getElementById('upload-queue');
    var reloadBtn = document.getElementById('reload-after-upload');
    var queue = [];
    var activeUploads = 0;
    var maxConcurrent = 3;
    var nextId = 1;

    function toast(message, type) {
        if (typeof showToast === 'function') {
            showToast(message, type || 'success');
        } else if (window.Notify) {
            (type === 'error' ? window.Notify.error : window.Notify.success)(message);
        }
    }

    function formatBytes(bytes) {
        return (bytes / 1024 / 1024).toFixed(2).replace('.', ',') + ' MB';
    }

    function formatEta(seconds) {
        if (!isFinite(seconds) || seconds < 1) return 'menos de 1s';
        if (seconds < 60) return Math.ceil(seconds) + 's';
        return Math.floor(seconds / 60) + 'min ' + Math.ceil(seconds % 60) + 's';
    }

    function makeRow(item) {
        var row = document.createElement('div');
        row.className = 'upload-row';
        row.innerHTML =
            '<div class="flex items-start justify-between gap-3">' +
                '<div class="min-w-0">' +
                    '<p class="font-semibold text-gray-900 truncate">' + item.file.name + '</p>' +
                    '<p class="text-xs text-gray-500" data-upload-meta>Calculando dimensão...</p>' +
                '</div>' +
                '<span class="text-xs font-semibold text-gray-500" data-upload-status>Aguardando</span>' +
            '</div>' +
            '<div class="mt-3 h-2 bg-gray-100 rounded-full overflow-hidden">' +
                '<div class="h-full bg-green-600 rounded-full" data-upload-bar style="width:0%"></div>' +
            '</div>' +
            '<p class="text-xs text-gray-500 mt-2" data-upload-detail>Na fila de envio.</p>';
        item.row = row;
        item.meta = row.querySelector('[data-upload-meta]');
        item.status = row.querySelector('[data-upload-status]');
        item.bar = row.querySelector('[data-upload-bar]');
        item.detail = row.querySelector('[data-upload-detail]');
        queueEl.appendChild(row);
    }

    function inspectImage(item) {
        var img = new Image();
        var objectUrl = URL.createObjectURL(item.file);
        img.onload = function() {
            URL.revokeObjectURL(objectUrl);
            var message = img.width + ' x ' + img.height + ' px • ' + formatBytes(item.file.size);
            if (img.width < idealWidth || img.height < idealHeight) {
                message += ' • abaixo do ideal (' + idealWidth + ' x ' + idealHeight + ' px)';
                item.meta.className = 'text-xs text-amber-700';
            }
            item.meta.textContent = message;
        };
        img.onerror = function() {
            URL.revokeObjectURL(objectUrl);
            item.meta.textContent = 'Não foi possível ler a dimensão da imagem.';
        };
        img.src = objectUrl;
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

            var item = { id: nextId++, file: file, state: 'pending' };
            queue.push(item);
            makeRow(item);
            inspectImage(item);
        });

        processQueue();
    }

    function processQueue() {
        while (activeUploads < maxConcurrent) {
            var item = queue.find(function(entry) { return entry.state === 'pending'; });
            if (!item) return;
            uploadItem(item);
        }
    }

    function uploadItem(item) {
        activeUploads++;
        item.state = 'uploading';
        item.startedAt = Date.now();
        item.status.textContent = 'Enviando';
        item.status.className = 'text-xs font-semibold text-blue-700';
        item.detail.textContent = 'Iniciando upload...';

        var formData = new FormData();
        formData.append('image', item.file);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', uploadUrl, true);
        xhr.setRequestHeader('X-CSRF-TOKEN', token);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.setRequestHeader('Accept', 'application/json');

        xhr.upload.addEventListener('progress', function(event) {
            if (!event.lengthComputable) return;
            var percent = Math.round((event.loaded / event.total) * 100);
            var elapsed = Math.max((Date.now() - item.startedAt) / 1000, 0.1);
            var rate = event.loaded / elapsed;
            var remaining = (event.total - event.loaded) / rate;
            item.bar.style.width = percent + '%';
            item.detail.textContent = percent + '% enviado • restante: ' + formatEta(remaining);
        });

        xhr.onload = function() {
            activeUploads--;
            if (xhr.status >= 200 && xhr.status < 300) {
                item.state = 'done';
                item.bar.style.width = '100%';
                item.status.textContent = 'Concluído';
                item.status.className = 'text-xs font-semibold text-green-700';
                item.detail.textContent = 'Upload concluído. Atualize a lista para gerenciar a foto.';
                reloadBtn.classList.remove('hidden');
            } else {
                item.state = 'error';
                item.status.textContent = 'Erro';
                item.status.className = 'text-xs font-semibold text-red-700';
                try {
                    var response = JSON.parse(xhr.responseText);
                    item.detail.textContent = response.message || 'Erro ao enviar imagem.';
                } catch (e) {
                    item.detail.textContent = 'Erro ' + xhr.status + ' ao enviar imagem.';
                }
            }
            processQueue();
        };

        xhr.onerror = function() {
            activeUploads--;
            item.state = 'error';
            item.status.textContent = 'Erro';
            item.status.className = 'text-xs font-semibold text-red-700';
            item.detail.textContent = 'Falha de conexão durante o upload.';
            processQueue();
        };

        xhr.send(formData);
    }

    if (zone && input) {
        zone.addEventListener('click', function() { input.click(); });
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
            input.value = '';
        });
    }

    if (reloadBtn) {
        reloadBtn.addEventListener('click', function() {
            window.location.reload();
        });
    }

    function bindToggle(selector, statusSelector, activeText, inactiveText, activeBtnText, inactiveBtnText) {
        document.querySelectorAll(selector).forEach(function(button) {
            button.addEventListener('click', function() {
                var btn = this;
                btn.disabled = true;

                fetch(btn.dataset.url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(function(response) { return response.json(); })
                .then(function(data) {
                    if (!data.success) throw new Error(data.message || 'Não foi possível alterar o status.');

                    var status = statusSelector ? document.querySelector(statusSelector.replace('__ID__', btn.dataset.id || '')) : document.querySelector('[data-album-status]');
                    if (status) {
                        status.textContent = data.active ? activeText : inactiveText;
                        status.className = 'px-2 py-1 rounded-full text-xs font-semibold ' + (data.active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600');
                        if (status.hasAttribute('data-photo-status')) {
                            status.className = 'absolute top-3 right-3 px-2 py-1 rounded-full text-xs font-semibold ' + (data.active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600');
                        }
                    }

                    btn.textContent = data.active ? activeBtnText : inactiveBtnText;
                    btn.className = 'px-3 py-2 rounded-lg text-sm font-semibold ' + (data.active ? 'bg-amber-100 text-amber-800' : 'bg-green-100 text-green-800');
                    if (selector === '[data-toggle-album]') {
                        btn.className = 'px-4 py-2 rounded-lg text-sm font-semibold ' + (data.active ? 'bg-amber-100 text-amber-800 hover:bg-amber-200' : 'bg-green-100 text-green-800 hover:bg-green-200');
                    }
                    toast(data.message);
                })
                .catch(function(error) {
                    toast(error.message, 'error');
                })
                .finally(function() {
                    btn.disabled = false;
                });
            });
        });
    }

    bindToggle('[data-toggle-album]', null, 'Ativo', 'Inativo', 'Desativar álbum', 'Ativar álbum');
    bindToggle('[data-toggle-photo]', '[data-photo-status="__ID__"]', 'Ativa', 'Inativa', 'Desativar', 'Ativar');
})();
</script>
@endpush

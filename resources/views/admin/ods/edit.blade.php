@extends("layouts.admin")
@section("title", "Editar ODS")
@section("page-title", "Editar ODS " . $od->number)

@push('styles')
<style>
.ods-admin-card {
    position: relative;
    min-height: 152px;
    overflow: hidden;
}

.ods-admin-card::before {
    content: "";
    position: absolute;
    inset: 0;
    background:
        linear-gradient(180deg, rgba(0, 0, 0, 0.18) 0%, rgba(0, 0, 0, 0.32) 100%),
        var(--ods-color, #15803d);
}

.ods-admin-card::after {
    content: "";
    position: absolute;
    inset: 0;
    background-image: var(--ods-image, none);
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    opacity: var(--ods-image-opacity, 0.34);
}

.ods-admin-card__content {
    position: relative;
    z-index: 1;
}

.ods-upload {
    position: relative;
    border-radius: 12px;
}

.ods-upload__input {
    position: absolute;
    inset: 0;
    opacity: 0;
    cursor: pointer;
    z-index: 3;
}

.ods-upload__preview {
    position: relative;
    min-height: 140px;
    border-radius: 12px;
    overflow: hidden;
    border: 2px dashed #e5e7eb;
    background: #f9fafb;
    transition: all 0.2s ease;
    padding: 12px;
}

.ods-upload:hover .ods-upload__preview,
.ods-upload.is-dragover .ods-upload__preview {
    border-color: #10b981;
    background: #f0fdf4;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}

.ods-upload__preview img {
    width: auto;
    max-width: 100%;
    height: auto;
    max-height: 120px;
    object-fit: contain;
    display: block;
    margin: 0 auto;
}

.ods-upload__placeholder {
    min-height: 140px;
    padding: 16px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
}

.ods-upload__placeholder svg {
    width: 40px;
    height: 40px;
    margin-bottom: 8px;
}

.ods-upload__meta {
    position: absolute;
    inset-inline: 12px;
    bottom: 12px;
    padding: 8px 10px;
    border-radius: 8px;
    background: rgba(0, 0, 0, 0.7);
    color: #fff;
    backdrop-filter: blur(8px);
}

#upload-image-wrap {
    min-height: 140px;
    padding: 16px 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.ods-upload__progress {
    height: 6px;
    border-radius: 999px;
    background: #e5e7eb;
    overflow: hidden;
}

.ods-upload__progress-bar {
    width: 0%;
    height: 100%;
    border-radius: inherit;
    background: linear-gradient(90deg, #10b981 0%, #34d399 100%);
    transition: width 0.15s linear;
}

.ods-upload__error {
    display: none;
}

.ods-upload__error.is-visible {
    display: block;
}

[data-theme="dark"] .ods-upload {
}

[data-theme="dark"] .ods-upload__progress {
    background: #374151;
}

[data-theme="dark"] .ods-upload__preview {
    border-color: #4b5563;
    background: #1f2937;
}

[data-theme="dark"] .ods-upload:hover .ods-upload__preview,
[data-theme="dark"] .ods-upload.is-dragover .ods-upload__preview {
    border-color: #10b981;
    background: #1f2937;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
}
</style>
@endpush

@section("content")
@php
    $iconUrl = $od->icon_url ? $od->icon_url . '?v=' . optional($od->updated_at)->timestamp : null;
    $maxUploadMb = \App\Models\Setting::uploadLimitMb('image');
    $odsImageOpacity = max(0, min((int) \App\Models\Setting::get('ods_card_image_opacity', 34), 100));
@endphp
<div class="max-w-5xl">
    <form id="ods-edit-form" method="POST" action="{{ route("admin.ods.update", $od) }}" enctype="multipart/form-data">
        @csrf
        @method("PUT")

        <div class="bg-white rounded-xl shadow-sm p-6 space-y-6">
            <div
                id="ods-card-preview"
                class="ods-admin-card rounded-3xl p-6 text-white text-center"
                style="--ods-color: {{ old('color', $od->color) }}; --ods-image: {{ $iconUrl ? "url('{$iconUrl}')" : 'none' }}; --ods-image-opacity: {{ number_format($odsImageOpacity / 100, 2, '.', '') }};"
            >
                <div class="ods-admin-card__content">
                    <p class="text-5xl font-black leading-none">{{ $od->number }}</p>
                    <p id="ods-card-preview-title" class="mt-3 font-semibold text-3xl">{{ old("title", $od->title) }}</p>
                </div>
            </div>

            <div class="grid gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Titulo</label>
                    <input id="title" type="text" name="title" value="{{ old("title", $od->title) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descricao</label>
                    <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">{{ old("description", $od->description) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cor</label>
                    <input id="color" type="color" name="color" value="{{ old("color", $od->color) }}" class="w-full h-10 border border-gray-300 rounded-lg px-1 py-1">
                </div>

                <div>
                    <div class="flex items-center justify-between gap-3 mb-2">
                        <label class="block text-sm font-medium text-gray-700">Imagem do card</label>
                        <span id="upload-status-text" class="text-xs text-gray-400">Pronto</span>
                    </div>
                    <p class="mb-3 text-xs text-gray-500">Limite de arquivo definido em Configurações.</p>

                    <div id="ods-upload" class="ods-upload">
                        <input id="icon" class="ods-upload__input" type="file" name="icon" accept="image/*" data-no-dropzone>

                        <div class="ods-upload__preview">
                            <div id="upload-image-wrap" class="{{ $iconUrl ? '' : 'hidden' }}">
                                <img id="upload-preview-image" src="{{ $iconUrl ?? '' }}" alt="Preview da imagem do card">
                                <div class="ods-upload__meta">
                                    <div class="flex items-center justify-between gap-3 text-xs sm:text-sm">
                                        <span id="upload-file-name" class="truncate">{{ $iconUrl ? basename($od->icon) : 'Nenhum arquivo selecionado' }}</span>
                                        <span id="upload-file-size">{{ $iconUrl ? 'Imagem atual' : '' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div id="upload-placeholder" class="ods-upload__placeholder {{ $iconUrl ? 'hidden' : '' }}">
                                <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M7 16a4 4 0 01-.88-7.903A5 5 0 0115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                <p class="font-medium text-gray-700 text-sm">Arraste e solte ou clique para selecionar</p>
                                <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF até {{ $maxUploadMb }}MB</p>
                            </div>
                        </div>

                        <div class="mt-3 space-y-2">
                            <div class="ods-upload__progress">
                                <div id="upload-progress-bar" class="ods-upload__progress-bar"></div>
                            </div>
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span id="upload-progress-label">0%</span>
                                <span id="upload-time-remaining">Aguardando upload</span>
                            </div>
                            <p id="upload-error" class="ods-upload__error text-sm text-red-600"></p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox" name="active" value="1" id="active" {{ $od->active ? "checked" : "" }} class="w-4 h-4 text-green-600 rounded">
                    <label for="active" class="text-sm font-medium text-gray-700">Ativo</label>
                </div>
            </div>

            <div class="flex justify-between pt-4 border-t border-gray-100">
                <a href="{{ route("admin.ods.index") }}" class="text-gray-600 hover:text-gray-800 font-medium">Cancelar</a>
                <button id="submit-button" type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 font-medium">Atualizar</button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const maxUploadMb = {{ $maxUploadMb }};
    const maxUploadBytes = maxUploadMb * 1024 * 1024;
    const form = document.getElementById('ods-edit-form');
    const fileInput = document.getElementById('icon');
    const uploadBox = document.getElementById('ods-upload');
    const imageWrap = document.getElementById('upload-image-wrap');
    const previewImage = document.getElementById('upload-preview-image');
    const placeholder = document.getElementById('upload-placeholder');
    const fileName = document.getElementById('upload-file-name');
    const fileSize = document.getElementById('upload-file-size');
    const progressBar = document.getElementById('upload-progress-bar');
    const progressLabel = document.getElementById('upload-progress-label');
    const timeRemaining = document.getElementById('upload-time-remaining');
    const errorBox = document.getElementById('upload-error');
    const statusText = document.getElementById('upload-status-text');
    const titleInput = document.getElementById('title');
    const colorInput = document.getElementById('color');
    const cardPreview = document.getElementById('ods-card-preview');
    const cardPreviewTitle = document.getElementById('ods-card-preview-title');
    const submitButton = document.getElementById('submit-button');
    let previewUrl = null;

    function removeLegacyDropZone() {
        const legacyWrapper = fileInput.closest('.drop-zone');
        if (!legacyWrapper) return;

        const parent = legacyWrapper.parentNode;
        if (!parent) return;

        parent.insertBefore(fileInput, legacyWrapper);
        legacyWrapper.remove();
    }

    function formatBytes(bytes) {
        if (!bytes) return '0 B';
        const units = ['B', 'KB', 'MB', 'GB'];
        let size = bytes;
        let unit = 0;
        while (size >= 1024 && unit < units.length - 1) {
            size /= 1024;
            unit += 1;
        }
        return `${size.toFixed(size >= 10 || unit === 0 ? 0 : 1)} ${units[unit]}`;
    }

    function formatTime(seconds) {
        if (!isFinite(seconds) || seconds < 0) return 'Calculando tempo restante';
        if (seconds < 1) return 'Menos de 1s restante';
        if (seconds < 60) return `${Math.ceil(seconds)}s restantes`;
        const minutes = Math.floor(seconds / 60);
        const remSeconds = Math.ceil(seconds % 60);
        return `${minutes}min ${remSeconds}s restantes`;
    }

    function setPreview(src, nameText, sizeText) {
        if (!src) return;
        previewImage.src = src;
        imageWrap.classList.remove('hidden');
        placeholder.classList.add('hidden');
        if (nameText) fileName.textContent = nameText;
        if (sizeText) fileSize.textContent = sizeText;
        cardPreview.style.setProperty('--ods-image', `url('${src}')`);
    }

    function clearError() {
        errorBox.textContent = '';
        errorBox.classList.remove('is-visible');
    }

    function showError(message) {
        errorBox.textContent = message;
        errorBox.classList.add('is-visible');
    }

    function resetProgress() {
        progressBar.style.width = '0%';
        progressLabel.textContent = '0%';
        timeRemaining.textContent = 'Aguardando upload';
    }

    function updateCardTitle() {
        cardPreviewTitle.textContent = titleInput.value.trim() || 'Sem titulo';
    }

    function updateCardColor() {
        cardPreview.style.setProperty('--ods-color', colorInput.value || '#15803d');
    }

    function handleFileSelection(file) {
        clearError();

        if (!file) {
            return;
        }

        if (!file.type.startsWith('image/')) {
            showError('Selecione um arquivo de imagem valido.');
            return;
        }

        if (file.size > maxUploadBytes) {
            showError(`A imagem deve ter no maximo ${maxUploadMb}MB.`);
            return;
        }

        if (previewUrl) {
            URL.revokeObjectURL(previewUrl);
        }

        previewUrl = URL.createObjectURL(file);
        setPreview(previewUrl, file.name, formatBytes(file.size));
        statusText.textContent = 'Nova imagem pronta para envio';
        resetProgress();
    }

    ['dragenter', 'dragover'].forEach((eventName) => {
        uploadBox.addEventListener(eventName, (event) => {
            event.preventDefault();
            uploadBox.classList.add('is-dragover');
        });
    });

    ['dragleave', 'dragend', 'drop'].forEach((eventName) => {
        uploadBox.addEventListener(eventName, (event) => {
            event.preventDefault();
            uploadBox.classList.remove('is-dragover');
        });
    });

    uploadBox.addEventListener('drop', (event) => {
        const file = event.dataTransfer && event.dataTransfer.files ? event.dataTransfer.files[0] : null;
        if (file) {
            fileInput.files = event.dataTransfer.files;
            handleFileSelection(file);
        }
    });

    fileInput.addEventListener('change', (event) => {
        handleFileSelection(event.target.files ? event.target.files[0] : null);
    });

    titleInput.addEventListener('input', updateCardTitle);
    colorInput.addEventListener('input', updateCardColor);

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        clearError();

        const xhr = new XMLHttpRequest();
        const formData = new FormData(form);
        const startedAt = Date.now();

        submitButton.disabled = true;
        submitButton.classList.add('opacity-70', 'cursor-not-allowed');
        submitButton.textContent = 'Enviando...';
        statusText.textContent = 'Enviando alteracoes';
        timeRemaining.textContent = 'Iniciando upload';

        xhr.open('POST', form.action, true);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        xhr.upload.addEventListener('progress', (progressEvent) => {
            if (!progressEvent.lengthComputable) return;

            const percent = Math.round((progressEvent.loaded / progressEvent.total) * 100);
            const elapsed = Math.max((Date.now() - startedAt) / 1000, 0.1);
            const speed = progressEvent.loaded / elapsed;
            const remainingBytes = progressEvent.total - progressEvent.loaded;
            const remainingSeconds = speed > 0 ? remainingBytes / speed : 0;

            progressBar.style.width = `${percent}%`;
            progressLabel.textContent = `${percent}%`;
            timeRemaining.textContent = percent >= 100 ? 'Finalizando upload' : formatTime(remainingSeconds);
        });

        xhr.onreadystatechange = function () {
            if (xhr.readyState !== XMLHttpRequest.DONE) return;

            submitButton.disabled = false;
            submitButton.classList.remove('opacity-70', 'cursor-not-allowed');
            submitButton.textContent = 'Atualizar';

            let response = {};
            try {
                response = xhr.responseText ? JSON.parse(xhr.responseText) : {};
            } catch (e) {
                response = {};
            }

            if (xhr.status >= 200 && xhr.status < 300) {
                progressBar.style.width = '100%';
                progressLabel.textContent = '100%';
                timeRemaining.textContent = 'Upload concluido';
                statusText.textContent = 'Imagem salva e preview mantido';

                if (response.od) {
                    if (response.od.icon_url) {
                        const finalImageUrl = response.od.icon_url + (response.od.icon_url.includes('?') ? '&' : '?') + 't=' + Date.now();
                        setPreview(finalImageUrl, fileInput.files && fileInput.files[0] ? fileInput.files[0].name : 'Imagem salva', 'Imagem atualizada');
                    }
                    if (response.od.title) {
                        titleInput.value = response.od.title;
                        updateCardTitle();
                    }
                    if (response.od.color) {
                        colorInput.value = response.od.color;
                        updateCardColor();
                    }
                }

                if (window.Toastify) {
                    Toastify({
                        text: response.message || 'ODS atualizado com sucesso!',
                        duration: 3500,
                        gravity: 'top',
                        position: 'right',
                        style: { background: '#15803d', borderRadius: '12px' },
                    }).showToast();
                }
                return;
            }

            progressBar.style.width = '0%';
            progressLabel.textContent = '0%';
            timeRemaining.textContent = 'Falha no upload';
            statusText.textContent = 'Corrija os erros para tentar novamente';

            if (xhr.status === 422 && response.errors) {
                const firstError = Object.values(response.errors).flat()[0] || 'Verifique os dados enviados.';
                showError(firstError);
            } else {
                showError(response.message || 'Nao foi possivel concluir o upload da imagem.');
            }
        };

        xhr.send(formData);
    });

    removeLegacyDropZone();
    updateCardTitle();
    updateCardColor();
})();
</script>
@endpush

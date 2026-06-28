@extends("layouts.admin")
@section("title", "Galeria")
@section("page-title", "Galeria de Eventos")

@push("styles")
<style>
    .gallery-stat-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 18px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, .04);
    }

    .gallery-analytics-panel {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        padding: 20px;
        box-shadow: 0 1px 4px rgba(15, 23, 42, .05);
        margin-bottom: 24px;
    }

    .gallery-analytics-title {
        color: #111827;
        font-size: 16px;
        font-weight: 900;
        margin: 0;
    }

    .gallery-analytics-subtitle {
        color: #6b7280;
        font-size: 12px;
        font-weight: 700;
        margin-top: 3px;
    }

    .gallery-analytics-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
        margin-top: 18px;
    }

    .gallery-analytics-card {
        border-radius: 14px;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        padding: 13px;
    }

    .gallery-analytics-card span {
        display: block;
        color: #6b7280;
        font-size: 11px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    .gallery-analytics-card strong {
        display: block;
        color: #111827;
        font-size: 23px;
        font-weight: 950;
        margin-top: 5px;
    }

    .gallery-analytics-lists {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
        margin-top: 18px;
    }

    .gallery-analytics-list {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        overflow: hidden;
    }

    .gallery-analytics-list h3 {
        margin: 0;
        padding: 12px 14px;
        background: #f9fafb;
        color: #111827;
        font-size: 13px;
        font-weight: 900;
        border-bottom: 1px solid #e5e7eb;
    }

    .gallery-analytics-row {
        display: block;
        padding: 11px 14px;
        border-bottom: 1px solid #f3f4f6;
        text-decoration: none;
    }

    .gallery-analytics-row:last-child {
        border-bottom: 0;
    }

    .gallery-analytics-row strong {
        display: block;
        color: #111827;
        font-size: 13px;
        line-height: 1.35;
    }

    .gallery-analytics-row span {
        display: block;
        color: #6b7280;
        font-size: 11px;
        font-weight: 700;
        margin-top: 3px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .gallery-analytics-row:hover {
        background: #f9fafb;
    }

    .gallery-analytics-empty {
        padding: 16px 14px;
        color: #6b7280;
        font-size: 12px;
        font-weight: 700;
    }

    .gallery-folder-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
    }

    .gallery-folder {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(15, 23, 42, .05);
        transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
    }

    .gallery-folder:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 28px rgba(15, 23, 42, .12);
        border-color: #bbf7d0;
    }

    .gallery-folder-cover {
        position: relative;
        height: 190px;
        background: #e5e7eb;
        overflow: hidden;
    }

    .gallery-folder-cover img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform .35s ease;
    }

    .gallery-folder:hover .gallery-folder-cover img {
        transform: scale(1.04);
    }

    .gallery-folder-empty {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #dcfce7, #f0fdf4);
        color: #15803d;
    }

    .gallery-folder-empty svg {
        width: 58px;
        height: 58px;
    }

    .gallery-folder-label,
    .gallery-folder-status {
        position: absolute;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        border-radius: 999px;
        padding: 6px 11px;
        font-size: 12px;
        font-weight: 800;
    }

    .gallery-folder-label {
        left: 12px;
        top: 12px;
        background: rgba(17, 24, 39, .78);
        color: #fff;
    }

    .gallery-folder-status {
        right: 12px;
        top: 12px;
    }

    .gallery-folder-count {
        position: absolute;
        right: 12px;
        bottom: 12px;
        background: #15803d;
        color: #fff;
        border-radius: 999px;
        padding: 6px 11px;
        font-size: 12px;
        font-weight: 800;
        box-shadow: 0 5px 16px rgba(21, 128, 61, .24);
    }

    .gallery-folder-body {
        padding: 16px;
    }

    .gallery-folder-title {
        margin: 0;
        color: #111827;
        font-size: 1rem;
        font-weight: 900;
        line-height: 1.25;
    }

    .gallery-folder-meta {
        color: #6b7280;
        font-size: .82rem;
        margin-top: 6px;
    }

    .gallery-folder-projects {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        margin-top: 12px;
        min-height: 24px;
    }

    .gallery-folder-project {
        display: inline-flex;
        border-radius: 999px;
        background: #eff6ff;
        color: #1d4ed8;
        padding: 4px 8px;
        font-size: 11px;
        font-weight: 800;
    }

    .gallery-folder-info {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 8px;
        margin-top: 14px;
    }

    .gallery-folder-info-item {
        border-radius: 10px;
        background: #f9fafb;
        padding: 9px;
    }

    .gallery-folder-info-item span {
        display: block;
        color: #6b7280;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    .gallery-folder-info-item strong {
        display: block;
        color: #111827;
        font-size: 14px;
        margin-top: 2px;
    }

    .gallery-folder-actions {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
        margin-top: 14px;
        padding-top: 14px;
        border-top: 1px solid #e5e7eb;
    }

    .gallery-toggle-btn {
        font-size: 0.875rem;
        font-weight: 700;
        padding: 0 0.25rem;
    }

    .gallery-toggle-btn.is-active {
        color: #b45309;
    }

    .gallery-toggle-btn.is-inactive {
        color: #15803d;
    }

    @media (max-width: 1180px) {
        .gallery-folder-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .gallery-analytics-grid,
        .gallery-analytics-lists {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 720px) {
        .gallery-folder-grid,
        .gallery-folder-info,
        .gallery-analytics-grid,
        .gallery-analytics-lists {
            grid-template-columns: 1fr;
        }
    }

    [data-theme="dark"] .gallery-stat-card,
    [data-theme="dark"] .gallery-folder,
    [data-theme="dark"] .gallery-folder-info-item,
    [data-theme="dark"] .gallery-analytics-panel,
    [data-theme="dark"] .gallery-analytics-card,
    [data-theme="dark"] .gallery-analytics-list {
        background: #1f2937;
        border-color: #374151;
    }

    [data-theme="dark"] .gallery-folder-title,
    [data-theme="dark"] .gallery-folder-info-item strong,
    [data-theme="dark"] .gallery-analytics-title,
    [data-theme="dark"] .gallery-analytics-card strong,
    [data-theme="dark"] .gallery-analytics-list h3,
    [data-theme="dark"] .gallery-analytics-row strong {
        color: #f9fafb;
    }

    [data-theme="dark"] .gallery-folder-meta,
    [data-theme="dark"] .gallery-folder-info-item span,
    [data-theme="dark"] .gallery-analytics-subtitle,
    [data-theme="dark"] .gallery-analytics-card span,
    [data-theme="dark"] .gallery-analytics-row span {
        color: #9ca3af;
    }

    [data-theme="dark"] .gallery-analytics-list h3 {
        background: #111827;
        border-color: #374151;
    }

    [data-theme="dark"] .gallery-analytics-row {
        border-color: #374151;
    }

    [data-theme="dark"] .gallery-analytics-row:hover {
        background: #111827;
    }

    [data-theme="dark"] .gallery-analytics-empty {
        color: #9ca3af;
    }

    [data-theme="dark"] .gallery-folder-actions {
        border-color: #374151;
    }

    [data-theme="dark"] .gallery-folder-empty {
        background: rgba(34, 197, 94, .08);
        color: #4ade80;
    }

    [data-theme="dark"] .gallery-toggle-btn.is-active {
        color: #fbbf24;
    }

    [data-theme="dark"] .gallery-toggle-btn.is-inactive {
        color: #4ade80;
    }
</style>
@endpush

@section("content")
@php
    $formatNumber = fn ($value) => number_format((int) $value, 0, ",", ".");
@endphp

<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-800">Pastas de álbuns</h2>
        <p class="text-sm text-gray-500 mt-1">Mesma organização da galeria pública, com controles administrativos por álbum.</p>
    </div>
    <a href="{{ route("admin.galeria.create") }}" class="bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800 inline-flex items-center justify-center gap-2 font-semibold text-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Novo álbum
    </a>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    <div class="gallery-stat-card">
        <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Álbuns</p>
        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $formatNumber($stats["albums"] ?? 0) }}</p>
    </div>
    <div class="gallery-stat-card">
        <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Álbuns ativos</p>
        <p class="text-2xl font-bold text-green-700 mt-1">{{ $formatNumber($stats["active_albums"] ?? 0) }}</p>
    </div>
    <div class="gallery-stat-card">
        <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Fotos</p>
        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $formatNumber($stats["photos"] ?? 0) }}</p>
    </div>
    <div class="gallery-stat-card">
        <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Fotos ativas</p>
        <p class="text-2xl font-bold text-green-700 mt-1">{{ $formatNumber($stats["active_photos"] ?? 0) }}</p>
    </div>
</div>

@php
    $analyticsEvents = $analytics["events"] ?? [];
    $analyticsCards = [
        "gallery_index" => "Visitas a galeria",
        "album_view" => "Visitas a albuns",
        "album_click" => "Cliques em albuns",
        "photo_view" => "Visualizacoes de fotos",
        "photo_click" => "Cliques em fotos",
        "photo_share" => "Compartilhamentos",
        "download_click" => "Cliques em download",
        "photo_download" => "Downloads confirmados",
    ];
@endphp

<section class="gallery-analytics-panel">
    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-2">
        <div>
            <h2 class="gallery-analytics-title">Analytics da galeria</h2>
            <p class="gallery-analytics-subtitle">{{ $analytics["period_label"] ?? "Ultimos 30 dias" }}</p>
        </div>
        <p class="gallery-analytics-subtitle md:text-right">
            Registra visitas, cliques, compartilhamentos, downloads, usuario logado, IP, sessao e origem.
        </p>
    </div>

    <div class="gallery-analytics-grid">
        @foreach($analyticsCards as $eventKey => $eventLabel)
            <div class="gallery-analytics-card">
                <span>{{ $eventLabel }}</span>
                <strong>{{ $formatNumber($analyticsEvents[$eventKey] ?? 0) }}</strong>
            </div>
        @endforeach
    </div>

    <div class="gallery-analytics-lists">
        <div class="gallery-analytics-list">
            <h3>Albuns mais acessados</h3>
            @forelse(($analytics["top_albums"] ?? collect()) as $topAlbum)
                <a href="{{ route("admin.galeria.edit", $topAlbum) }}" class="gallery-analytics-row">
                    <strong>{{ $topAlbum->title }}</strong>
                    <span>{{ $formatNumber($topAlbum->analytics_events_count ?? 0) }} eventos registrados</span>
                </a>
            @empty
                <div class="gallery-analytics-empty">Sem eventos de album no periodo.</div>
            @endforelse
        </div>

        <div class="gallery-analytics-list">
            <h3>Fotos em destaque</h3>
            @forelse(($analytics["top_photos"] ?? collect()) as $topPhoto)
                @if($topPhoto->album)
                    <a href="{{ route("admin.galeria.edit", $topPhoto->album) }}#fotos-album" class="gallery-analytics-row">
                        <strong>{{ $topPhoto->title ?: "Foto sem titulo" }}</strong>
                        <span>{{ $topPhoto->album->title }} - {{ $formatNumber($topPhoto->analytics_events_count ?? 0) }} eventos, {{ $formatNumber($topPhoto->downloads_count ?? 0) }} downloads</span>
                    </a>
                @else
                    <div class="gallery-analytics-row">
                        <strong>{{ $topPhoto->title ?: "Foto sem titulo" }}</strong>
                        <span>{{ $formatNumber($topPhoto->analytics_events_count ?? 0) }} eventos, {{ $formatNumber($topPhoto->downloads_count ?? 0) }} downloads</span>
                    </div>
                @endif
            @empty
                <div class="gallery-analytics-empty">Sem eventos de foto no periodo.</div>
            @endforelse
        </div>

        <div class="gallery-analytics-list">
            <h3>Ultimos downloads</h3>
            @forelse(($analytics["recent_downloads"] ?? collect()) as $download)
                <div class="gallery-analytics-row">
                    <strong>{{ optional($download->photo)->title ?: data_get($download->metadata, "file_name", "Foto baixada") }}</strong>
                    <span>{{ optional($download->album)->title ?: "Album nao identificado" }}</span>
                    <span>
                        {{ optional($download->occurred_at)->format("d/m/Y H:i") }}
                        - {{ $download->user ? ($download->user->name . " (" . $download->user->email . ")") : ("Visitante - " . ($download->ip_address ?: "IP nao identificado")) }}
                    </span>
                </div>
            @empty
                <div class="gallery-analytics-empty">Nenhum download registrado ainda.</div>
            @endforelse
        </div>
    </div>
</section>

@if($albums->count())
    <div class="gallery-folder-grid">
        @foreach($albums as $album)
            @php
                $cover = $album->cover_image ?: $album->cover_photo_image;
                $eventDate = optional($album->event_date)->format("d/m/Y");
                $dimension = $formatNumber($album->ideal_image_width) . " x " . $formatNumber($album->ideal_image_height);
            @endphp
            <article class="gallery-folder">
                <div class="gallery-folder-cover">
                    @if($cover)
                        <img src="{{ asset("media/" . $cover) }}" alt="{{ $album->title }}" loading="lazy" decoding="async">
                    @else
                        <div class="gallery-folder-empty">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M3 7a2 2 0 012-2h5l2 2h7a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/></svg>
                        </div>
                    @endif
                    <span class="gallery-folder-label">
                        <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7a2 2 0 012-2h5l2 2h7a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/></svg>
                        Pasta
                    </span>
                    <span data-album-status="{{ $album->id }}" class="gallery-folder-status {{ $album->active ? "bg-green-100 text-green-700" : "bg-gray-100 text-gray-600" }}">
                        {{ $album->active ? "Ativo" : "Inativo" }}
                    </span>
                    <span class="gallery-folder-count">{{ $formatNumber($album->active_photos_count) }} ativa{{ $album->active_photos_count != 1 ? "s" : "" }}</span>
                </div>

                <div class="gallery-folder-body">
                    <h3 class="gallery-folder-title">{{ $album->title }}</h3>
                    <p class="gallery-folder-meta">
                        {{ $eventDate ?: "Data não informada" }}
                        @if($album->event_location)
                            <span class="mx-1">•</span>{{ Str::limit($album->event_location, 48) }}
                        @endif
                    </p>

                    <div class="gallery-folder-projects">
                        @forelse($album->projects->take(3) as $project)
                            <span class="gallery-folder-project">{{ $project->title }}</span>
                        @empty
                            <span class="text-xs text-gray-500">Nenhum projeto vinculado</span>
                        @endforelse
                        @if($album->projects_count > 3)
                            <span class="gallery-folder-project">+{{ $album->projects_count - 3 }}</span>
                        @endif
                    </div>

                    <div class="gallery-folder-info">
                        <div class="gallery-folder-info-item">
                            <span>Fotos</span>
                            <strong>{{ $formatNumber($album->photos_count) }}</strong>
                        </div>
                        <div class="gallery-folder-info-item">
                            <span>Projetos</span>
                            <strong>{{ $formatNumber($album->projects_count) }}</strong>
                        </div>
                        <div class="gallery-folder-info-item">
                            <span>Dimensão</span>
                            <strong>{{ $dimension }}</strong>
                        </div>
                    </div>

                    <div class="gallery-folder-actions">
                        <a href="{{ route("admin.galeria.edit", $album) }}" class="text-blue-600 hover:text-blue-800 text-sm font-bold">Editar</a>
                        <a href="{{ route("gallery.index", ["album" => $album->slug]) }}" target="_blank" class="text-green-700 hover:text-green-800 text-sm font-bold">Ver no site</a>
                        <button type="button" class="gallery-toggle-btn {{ $album->active ? "is-active" : "is-inactive" }}" data-toggle-album data-url="{{ route("admin.galeria.toggle", $album) }}" data-id="{{ $album->id }}">
                            {{ $album->active ? "Desativar" : "Ativar" }}
                        </button>
                        <form method="POST" action="{{ route("admin.galeria.destroy", $album) }}" class="inline">
                            @csrf
                            @method("DELETE")
                            <button type="submit" data-confirm="Excluir este álbum e todas as fotos?" class="text-red-600 hover:text-red-800 text-sm font-bold">Excluir</button>
                        </form>
                    </div>
                </div>
            </article>
        @endforeach
    </div>

    <div class="mt-6">{{ $albums->links() }}</div>
@else
    <div class="bg-white rounded-xl shadow-sm p-12 text-center text-gray-400">
        Nenhum álbum cadastrado na galeria.
    </div>
@endif
@endsection

@push("scripts")
<script>
(function() {
    var token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    function toast(message, type) {
        if (typeof showToast === 'function') {
            showToast(message, type || 'success');
        } else if (window.Notify) {
            (type === 'error' ? window.Notify.error : window.Notify.success)(message);
        } else {
            alert(message);
        }
    }

    function applyStatus(status, active) {
        status.textContent = active ? 'Ativo' : 'Inativo';
        status.className = 'gallery-folder-status ' + (active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600');
    }

    function applyButton(button, active) {
        button.textContent = active ? 'Desativar' : 'Ativar';
        button.className = 'gallery-toggle-btn ' + (active ? 'is-active' : 'is-inactive');
    }

    document.querySelectorAll('[data-toggle-album]').forEach(function(button) {
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

                var status = document.querySelector('[data-album-status="' + btn.dataset.id + '"]');
                if (status) applyStatus(status, data.active);

                applyButton(btn, data.active);
                toast(data.message, 'success');
            })
            .catch(function(error) {
                toast(error.message, 'error');
            })
            .finally(function() {
                btn.disabled = false;
            });
        });
    });
})();
</script>
@endpush

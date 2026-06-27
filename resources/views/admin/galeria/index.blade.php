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

    .gallery-thumb {
        width: 76px;
        height: 56px;
        border-radius: 10px;
        object-fit: cover;
        border: 1px solid #e5e7eb;
        background: #f3f4f6;
    }

    .gallery-thumb-empty {
        width: 76px;
        height: 56px;
        border-radius: 10px;
        border: 1px dashed #d1d5db;
        background: #f9fafb;
        color: #9ca3af;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .gallery-toggle-btn {
        font-size: 0.875rem;
        font-weight: 600;
        padding: 0 0.25rem;
    }

    .gallery-toggle-btn.is-active {
        color: #b45309;
    }

    .gallery-toggle-btn.is-inactive {
        color: #15803d;
    }

    [data-theme="dark"] .gallery-stat-card,
    [data-theme="dark"] .gallery-thumb-empty {
        background: #1f2937;
        border-color: #374151;
    }

    [data-theme="dark"] .gallery-thumb {
        border-color: #374151;
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
        <h2 class="text-xl font-bold text-gray-800">Álbuns da galeria</h2>
        <p class="text-sm text-gray-500 mt-1">Organize os álbuns por evento, projetos vinculados e fotos exibidas no site.</p>
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

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full" style="min-width: 920px;">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Capa</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Álbum / evento</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fotos</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Projetos</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden lg:table-cell">Dimensão ideal</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($albums as $album)
                    @php
                        $cover = $album->coverImagePath();
                        $eventDate = optional($album->event_date)->format("d/m/Y");
                        $projects = $album->projects->pluck("title");
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3">
                            @if($cover)
                                <img src="{{ asset("media/" . $cover) }}" alt="{{ $album->title }}" class="gallery-thumb">
                            @else
                                <div class="gallery-thumb-empty" aria-label="Álbum sem capa">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <p class="font-semibold text-gray-900 text-sm">{{ Str::limit($album->title, 62) }}</p>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $eventDate ?: "Data não informada" }}
                                @if($album->event_location)
                                    <span class="mx-1">•</span>{{ Str::limit($album->event_location, 42) }}
                                @endif
                            </p>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <p class="font-semibold text-gray-900">{{ $formatNumber($album->photos_count) }}</p>
                            <p class="text-xs text-green-700">{{ $formatNumber($album->active_photos_count) }} ativas</p>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <p class="font-semibold text-gray-900">{{ $formatNumber($album->projects_count) }}</p>
                            <p class="text-xs text-gray-500">{{ $album->projects_count === 1 ? "vinculado" : "vinculados" }}</p>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600 hidden lg:table-cell">
                            {{ $formatNumber($album->ideal_image_width) }} x {{ $formatNumber($album->ideal_image_height) }} px
                        </td>
                        <td class="px-4 py-3">
                            <span data-album-status="{{ $album->id }}" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $album->active ? "bg-green-100 text-green-700" : "bg-gray-100 text-gray-600" }}">
                                {{ $album->active ? "Ativo" : "Inativo" }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center gap-1">
                                <button type="button" data-dt-toggle class="dt-toggle p-1 rounded text-gray-400 hover:text-green-700 hover:bg-green-50 transition-colors" title="Ver detalhes ocultos">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <a href="{{ route("admin.galeria.edit", $album) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium px-1">Editar</a>
                                <button type="button" class="gallery-toggle-btn {{ $album->active ? "is-active" : "is-inactive" }}" data-toggle-album data-url="{{ route("admin.galeria.toggle", $album) }}" data-id="{{ $album->id }}">
                                    {{ $album->active ? "Desativar" : "Ativar" }}
                                </button>
                                <form method="POST" action="{{ route("admin.galeria.destroy", $album) }}" class="inline">
                                    @csrf
                                    @method("DELETE")
                                    <button type="submit" data-confirm="Excluir este álbum e todas as fotos?" class="text-red-600 hover:text-red-800 text-sm font-medium px-1">Excluir</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <tr class="dt-detail hidden">
                        <td colspan="7" class="px-4 py-4 bg-green-50 border-b border-green-100">
                            <dl class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-x-6 gap-y-3 text-sm">
                                <div>
                                    <dt class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Descrição</dt>
                                    <dd class="text-gray-800 mt-0.5">{{ $album->description ? Str::limit($album->description, 150) : "-" }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Projetos vinculados</dt>
                                    <dd class="text-gray-800 mt-0.5">{{ $projects->isNotEmpty() ? $projects->join(", ") : "-" }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Ordem</dt>
                                    <dd class="text-gray-800 mt-0.5">{{ $album->sort_order ?? 0 }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Criado em</dt>
                                    <dd class="text-gray-800 mt-0.5">{{ optional($album->created_at)->format("d/m/Y H:i") ?? "-" }}</dd>
                                </div>
                            </dl>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                            Nenhum álbum cadastrado na galeria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4 border-t border-gray-100">{{ $albums->links() }}</div>
</div>
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
        status.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold ' + (active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600');
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

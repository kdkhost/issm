@extends("layouts.admin")
@section("title", "Galeria")
@section("page-title", "Galeria de Eventos")

@section("content")
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-800">Álbuns por evento</h2>
        <p class="text-sm text-gray-500 mt-1">Organize fotos por álbum, evento e projetos relacionados.</p>
    </div>
    <a href="{{ route("admin.galeria.create") }}" class="bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800 inline-flex items-center justify-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Novo álbum
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
    @forelse($albums as $album)
        @php $cover = $album->coverImagePath(); @endphp
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
            <div class="relative h-44 bg-gray-100">
                @if($cover)
                    <img src="{{ asset("media/" . $cover) }}" alt="{{ $album->title }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                @endif
                <span class="absolute top-3 right-3 px-2 py-1 rounded-full text-xs font-semibold {{ $album->active ? "bg-green-100 text-green-700" : "bg-gray-100 text-gray-600" }}" data-album-status="{{ $album->id }}">
                    {{ $album->active ? "Ativo" : "Inativo" }}
                </span>
            </div>

            <div class="p-4">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h3 class="font-bold text-gray-900 leading-tight">{{ $album->title }}</h3>
                        <p class="text-xs text-gray-500 mt-1">
                            @if($album->event_date)
                                {{ $album->event_date->format("d/m/Y") }}
                            @else
                                Data do evento não informada
                            @endif
                            @if($album->event_location)
                                • {{ $album->event_location }}
                            @endif
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-2 mt-4 text-center">
                    <div class="rounded-lg bg-gray-50 p-2">
                        <p class="text-lg font-bold text-gray-900">{{ $album->photos_count }}</p>
                        <p class="text-[11px] text-gray-500 uppercase">Fotos</p>
                    </div>
                    <div class="rounded-lg bg-green-50 p-2">
                        <p class="text-lg font-bold text-green-700">{{ $album->active_photos_count }}</p>
                        <p class="text-[11px] text-gray-500 uppercase">Ativas</p>
                    </div>
                    <div class="rounded-lg bg-blue-50 p-2">
                        <p class="text-lg font-bold text-blue-700">{{ $album->projects_count }}</p>
                        <p class="text-[11px] text-gray-500 uppercase">Projetos</p>
                    </div>
                </div>

                @if($album->description)
                    <p class="text-sm text-gray-600 mt-4">{{ Str::limit($album->description, 120) }}</p>
                @endif

                <div class="flex flex-wrap items-center gap-2 mt-4 pt-4 border-t border-gray-100">
                    <a href="{{ route("admin.galeria.edit", $album) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Gerenciar</a>
                    <button type="button" class="text-sm font-medium {{ $album->active ? "text-amber-700" : "text-green-700" }}" data-toggle-album data-url="{{ route("admin.galeria.toggle", $album) }}" data-id="{{ $album->id }}">
                        {{ $album->active ? "Desativar" : "Ativar" }}
                    </button>
                    <form method="POST" action="{{ route("admin.galeria.destroy", $album) }}">
                        @csrf
                        @method("DELETE")
                        <button type="submit" data-confirm="Excluir este álbum e todas as fotos?" class="text-red-600 hover:text-red-800 text-sm font-medium">Excluir</button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="md:col-span-2 xl:col-span-3 bg-white rounded-xl shadow-sm p-12 text-center text-gray-400">
            Nenhum álbum cadastrado na galeria.
        </div>
    @endforelse
</div>

<div class="mt-6">{{ $albums->links() }}</div>
@endsection

@push("scripts")
<script>
(function() {
    var token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

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
                if (status) {
                    status.textContent = data.active ? 'Ativo' : 'Inativo';
                    status.className = 'absolute top-3 right-3 px-2 py-1 rounded-full text-xs font-semibold ' + (data.active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600');
                }

                btn.textContent = data.active ? 'Desativar' : 'Ativar';
                btn.className = 'text-sm font-medium ' + (data.active ? 'text-amber-700' : 'text-green-700');
                if (typeof showToast === 'function') {
                    showToast(data.message, 'success');
                } else if (window.Notify) {
                    window.Notify.success(data.message);
                }
            })
            .catch(function(error) {
                if (typeof showToast === 'function') {
                    showToast(error.message, 'error');
                } else if (window.Notify) {
                    window.Notify.error(error.message);
                } else {
                    alert(error.message);
                }
            })
            .finally(function() {
                btn.disabled = false;
            });
        });
    });
})();
</script>
@endpush

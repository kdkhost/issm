@extends("layouts.admin")
@section("title", "Editor de Menu do Site")
@section("page-title", "Editor de Menu do Site")

@section("content")
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
    {{-- Editor --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center text-green-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-900">Reorganizar Menu do Site</h2>
                <p class="text-sm text-gray-500">Arraste os itens para alterar a ordem no menu do frontend.</p>
            </div>
        </div>

        <div id="menu-sortable" class="space-y-2">
            @foreach($items as $item)
            <div class="sortable-item bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 flex items-center gap-3 cursor-move hover:bg-green-50 hover:border-green-300 transition-colors" data-id="{{ $item->id }}">
                <div class="text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                </div>
                <div style="width:40px;height:40px;border-radius:12px;background:{{ $item->icon_bg_color }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    {!! $item->icon_svg !!}
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-900 text-sm">{{ $item->label }}</p>
                    <p class="text-xs text-gray-500">{{ $item->route_name }}</p>
                </div>
                @if($item->is_button)
                <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full font-medium">Botão</span>
                @endif
                <div class="text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-6 flex items-center justify-between">
            <p class="text-xs text-gray-500">Arraste os itens para reordenar — a mudança é salva automaticamente.</p>
            <span id="save-status" class="text-xs font-medium text-gray-400 flex items-center gap-1.5 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span id="save-status-text">Pronto</span>
            </span>
        </div>
    </div>

    {{-- Preview do site --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center text-blue-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-900">Preview do Menu</h2>
                <p class="text-sm text-gray-500">Atualiza automaticamente ao reordenar.</p>
            </div>
        </div>
        <div class="flex-1 border border-gray-200 rounded-lg overflow-hidden" style="min-height:400px;">
            <iframe id="site-preview" src="{{ route('home') }}" class="w-full h-full" style="min-height:400px;border:none;" loading="lazy"></iframe>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const el = document.getElementById('menu-sortable');
    function setStatus(type, text) {
        const wrap = document.getElementById('save-status');
        const label = document.getElementById('save-status-text');
        label.textContent = text;
        if (type === 'saving') {
            wrap.className = 'text-xs font-medium text-amber-600 flex items-center gap-1.5 transition-colors';
            wrap.querySelector('svg').innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/><animateTransform attributeName="transform" type="rotate" from="0 12 12" to="360 12 12" dur="1s" repeatCount="indefinite"/>';
        } else if (type === 'saved') {
            wrap.className = 'text-xs font-medium text-green-600 flex items-center gap-1.5 transition-colors';
            wrap.querySelector('svg').innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>';
        } else {
            wrap.className = 'text-xs font-medium text-gray-400 flex items-center gap-1.5 transition-colors';
            wrap.querySelector('svg').innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>';
        }
    }

    function saveOrder() {
        setStatus('saving', 'Salvando...');
        const items = [];
        el.querySelectorAll('.sortable-item').forEach(function (row, index) {
            items.push({ id: parseInt(row.dataset.id), sort_order: index + 1 });
        });

        fetch('{{ route("admin.frontend-menu.update-order") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ order: items })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                setStatus('saved', 'Salvo!');
                if (typeof Toastify !== 'undefined') {
                    Toastify({ text: data.message, duration: 2500, gravity: 'top', position: 'right', style: { background: '#16a34a' } }).showToast();
                }
                setTimeout(function () { setStatus('idle', 'Pronto'); }, 2000);
                // Recarrega preview do site sem refresh da página
                const preview = document.getElementById('site-preview');
                if (preview) {
                    preview.src = preview.src;
                }
            } else {
                setStatus('idle', 'Erro');
                alert('Erro ao salvar ordem.');
            }
        })
        .catch(err => {
            console.error(err);
            setStatus('idle', 'Erro');
            alert('Erro ao salvar ordem.');
        });
    }

    const sortable = Sortable.create(el, {
        animation: 200,
        handle: '.sortable-item',
        ghostClass: 'bg-green-100',
        onEnd: function () {
            saveOrder();
        }
    });
});
</script>
@endpush

@extends("layouts.admin")
@section("title", "Editor de Menu")
@section("page-title", "Editor de Menu do Painel")

@section("content")
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center text-green-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-900">Reorganizar Menu</h2>
                <p class="text-sm text-gray-500">Arraste os itens para alterar a ordem no sidebar.</p>
            </div>
        </div>

        <div id="menu-sortable" class="space-y-2">
            @foreach($items as $item)
            <div class="sortable-item bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 flex items-center gap-3 cursor-move hover:bg-green-50 hover:border-green-300 transition-colors" data-id="{{ $item->id }}">
                <div class="text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-900 text-sm">{{ $item->label }}</p>
                    <p class="text-xs text-gray-500">{{ $item->route_name }}</p>
                </div>
                @if($item->group_label)
                <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full font-medium">{{ $item->group_label }}</span>
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

        fetch('{{ route("admin.menu.update-order") }}', {
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

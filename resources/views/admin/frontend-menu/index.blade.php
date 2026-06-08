@extends("layouts.admin")
@section("title", "Editor de Menu do Site")
@section("page-title", "Editor de Menu do Site")

@section("content")
<div class="max-w-3xl mx-auto">
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
            <p class="text-xs text-gray-500">Arraste os itens e a ordem será salva automaticamente.</p>
            <button id="save-btn" class="bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded-lg font-medium text-sm transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Salvar Ordem
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const el = document.getElementById('menu-sortable');
    const sortable = Sortable.create(el, {
        animation: 200,
        handle: '.sortable-item',
        ghostClass: 'bg-green-100',
        onEnd: function () {
            document.getElementById('save-btn').classList.add('ring-2', 'ring-green-500');
        }
    });

    document.getElementById('save-btn').addEventListener('click', function () {
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
                if (typeof Toastify !== 'undefined') {
                    Toastify({ text: data.message, duration: 3000, gravity: 'top', position: 'right', style: { background: '#16a34a' } }).showToast();
                } else {
                    alert(data.message);
                }
                document.getElementById('save-btn').classList.remove('ring-2', 'ring-green-500');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Erro ao salvar ordem.');
        });
    });
});
</script>
@endpush

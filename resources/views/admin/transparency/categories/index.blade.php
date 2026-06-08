@extends('layouts.admin')
@section('title', 'Categorias - Portal da Transparencia')
@section('page-title', 'Gerenciar Categorias')

@section('content')
<style>
.cat-wrap{max-width:900px}
.cat-card{background:#fff;border-radius:1rem;border:1px solid #e5e7eb;overflow:hidden;margin-bottom:1.25rem;box-shadow:0 1px 3px rgba(0,0,0,.04)}
.cat-head{display:flex;align-items:center;gap:1rem;padding:1rem 1.25rem;border-bottom:1px solid #f3f4f6;background:linear-gradient(to right,#f9fafb,#fff)}
.cat-head h3{font-size:1rem;font-weight:700;color:#111827;margin:0;flex:1}
.cat-body{padding:1.25rem}
.cat-form{display:flex;gap:.5rem;margin-bottom:1.5rem;flex-wrap:wrap}
.cat-input{flex:1;min-width:120px;border:1px solid #d1d5db;border-radius:.5rem;padding:.5rem .75rem;font-size:.8125rem}
.cat-input--sm{min-width:80px;max-width:100px}
.cat-btn{background:#16a34a;color:#fff;font-weight:600;font-size:.8125rem;padding:.5rem 1rem;border-radius:.5rem;border:none;cursor:pointer}
.cat-btn:hover{background:#15803d}
.cat-grid{display:flex;flex-direction:column;gap:.5rem}
.cat-item{display:flex;align-items:center;gap:1rem;padding:.75rem 1rem;background:#f9fafb;border-radius:.5rem;border:1px solid #e5e7eb;cursor:grab;transition:box-shadow .15s,transform .15s}
.cat-item:hover{box-shadow:0 2px 8px rgba(0,0,0,.06)}
.cat-item.sortable-ghost{opacity:.4;background:#e5e7eb}
.cat-item.sortable-drag{cursor:grabbing;transform:scale(1.02);box-shadow:0 4px 12px rgba(0,0,0,.1);z-index:10}
.cat-drag-handle{display:flex;align-items:center;justify-content:center;width:1.5rem;height:1.5rem;color:#9ca3af;cursor:grab;flex-shrink:0}
.cat-drag-handle:hover{color:#374151}
.cat-name{flex:1;font-weight:600;color:#374151}
.cat-id{font-size:.6875rem;color:#9ca3af;font-family:monospace}
.cat-badge{display:inline-block;font-size:.6875rem;font-weight:700;padding:.25rem .5rem;border-radius:1rem}
.cat-badge--green{background:#dcfce7;color:#166534}
.cat-badge--gray{background:#f3f4f6;color:#6b7280}
.cat-actions{display:flex;gap:.5rem}
.cat-action{font-size:.75rem;font-weight:600;padding:.375rem .75rem;border-radius:.375rem;border:none;cursor:pointer}
.cat-action--edit{background:#dbeafe;color:#1e40af}
.cat-action--del{background:#fee2e2;color:#ef4444}
.cat-empty{text-align:center;padding:2rem;color:#6b7280;font-size:.875rem}
.cat-save-order{background:#16a34a;color:#fff;font-size:.8125rem;font-weight:600;padding:.5rem 1rem;border-radius:.5rem;border:none;cursor:pointer;margin-bottom:1rem;display:none}
.cat-save-order.visible{display:inline-block}
.cat-hint{font-size:.75rem;color:#6b7280;margin-bottom:.75rem}
[data-theme="dark"] .cat-card{background:#1f2937;border-color:#374151}
[data-theme="dark"] .cat-head{background:linear-gradient(to right,#1a2535,#1f2937);border-color:#374151}
[data-theme="dark"] .cat-head h3{color:#f9fafb}
[data-theme="dark"] .cat-item{background:#1a2535;border-color:#374151}
[data-theme="dark"] .cat-name{color:#d1d5db}
[data-theme="dark"] .cat-input{background:#374151;border-color:#4b5563;color:#f9fafb}
[data-theme="dark"] .cat-hint{color:#9ca3af}
</style>

<div class="cat-wrap">

    <div class="cat-card">
        <div class="cat-head">
            <h3>Nova Categoria</h3>
        </div>
        <div class="cat-body">
            <form method="POST" action="{{ route('admin.transparency-categories.store') }}" class="cat-form">
                @csrf
                <input type="text" name="name" placeholder="Nome da categoria" class="cat-input" required maxlength="255">
                <input type="number" name="sort_order" placeholder="Ordem" value="0" class="cat-input cat-input--sm">
                <button type="submit" class="cat-btn">Criar Categoria</button>
            </form>
        </div>
    </div>

    <div class="cat-card">
        <div class="cat-head">
            <h3>Categorias Cadastradas</h3>
        </div>
        <div class="cat-body">
            @if($categories->isEmpty())
                <div class="cat-empty">Nenhuma categoria cadastrada.</div>
            @else
                <p class="cat-hint">Arraste os itens para reorganizar a ordem das categorias.</p>
                <button type="button" id="btn-save-order" class="cat-save-order">Salvar Ordem</button>
                <div id="cat-grid" class="cat-grid">
                    @foreach($categories as $cat)
                    <div class="cat-item" data-id="{{ $cat->id }}">
                        <div class="cat-drag-handle">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                        </div>
                        <div style="flex:1;min-width:0">
                            <div class="cat-name">{{ $cat->name }}</div>
                            @if($cat->google_drive_folder_id)
                                <span class="cat-id">{{ $cat->google_drive_folder_id }}</span>
                            @endif
                        </div>
                        <div style="display:flex;align-items:center;gap:.5rem">
                            @if($cat->google_drive_folder_id)
                                <span class="cat-badge cat-badge--green">Drive</span>
                            @else
                                <span class="cat-badge cat-badge--gray">Local</span>
                            @endif
                            <form method="POST" action="{{ route('admin.transparency-categories.update', $cat) }}" style="display:inline" onsubmit="var nome=prompt('Nome:', '{{ $cat->name }}'); if(!nome) return false; this.querySelector('input[name=name]').value=nome; return true;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="name" value="">
                                <button type="submit" class="cat-action cat-action--edit">Renomear</button>
                            </form>
                            <form method="POST" action="{{ route('admin.transparency-categories.destroy', $cat) }}" style="display:inline" onsubmit="return confirm('Remover categoria? Os documentos serao desvinculados.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="cat-action cat-action--del">Remover</button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var grid = document.getElementById('cat-grid');
    if (!grid) return;

    var sortable = Sortable.create(grid, {
        animation: 150,
        handle: '.cat-drag-handle',
        ghostClass: 'sortable-ghost',
        dragClass: 'sortable-drag',
        onEnd: function() {
            document.getElementById('btn-save-order').classList.add('visible');
        }
    });

    document.getElementById('btn-save-order').addEventListener('click', function() {
        var order = [];
        grid.querySelectorAll('.cat-item').forEach(function(item) {
            order.push(item.dataset.id);
        });

        fetch('{{ route("admin.transparency-categories.update-order") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ order: order })
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.success) {
                document.getElementById('btn-save-order').classList.remove('visible');
                alert('Ordem salva com sucesso!');
            }
        })
        .catch(function(e) {
            console.error(e);
            alert('Erro ao salvar ordem.');
        });
    });
});
</script>
@endsection

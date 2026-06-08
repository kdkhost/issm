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
.cat-table{width:100%;border-collapse:collapse}
.cat-table th{font-size:.6875rem;text-transform:uppercase;color:#6b7280;font-weight:700;padding:.5rem .75rem;text-align:left;background:#f9fafb}
.cat-table td{padding:.75rem .75rem;font-size:.875rem;border-top:1px solid #f3f4f6}
.cat-name{font-weight:600;color:#374151}
.cat-id{font-size:.6875rem;color:#9ca3af;font-family:monospace}
.cat-badge{display:inline-block;font-size:.6875rem;font-weight:700;padding:.25rem .5rem;border-radius:1rem}
.cat-badge--green{background:#dcfce7;color:#166534}
.cat-badge--gray{background:#f3f4f6;color:#6b7280}
.cat-actions{display:flex;gap:.5rem}
.cat-action{font-size:.75rem;font-weight:600;padding:.375rem .75rem;border-radius:.375rem;border:none;cursor:pointer}
.cat-action--edit{background:#dbeafe;color:#1e40af}
.cat-action--del{background:#fee2e2;color:#ef4444}
.cat-empty{text-align:center;padding:2rem;color:#6b7280;font-size:.875rem}
[data-theme="dark"] .cat-card{background:#1f2937;border-color:#374151}
[data-theme="dark"] .cat-head{background:linear-gradient(to right,#1a2535,#1f2937);border-color:#374151}
[data-theme="dark"] .cat-head h3{color:#f9fafb}
[data-theme="dark"] .cat-table th{background:#1a2535;color:#9ca3af}
[data-theme="dark"] .cat-table td{border-color:#374151}
[data-theme="dark"] .cat-name{color:#d1d5db}
[data-theme="dark"] .cat-input{background:#374151;border-color:#4b5563;color:#f9fafb}
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
                <table class="cat-table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Drive</th>
                            <th>Ordem</th>
                            <th>Status</th>
                            <th style="text-align:right">Acoes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $cat)
                        <tr>
                            <td>
                                <span class="cat-name">{{ $cat->name }}</span>
                                @if($cat->google_drive_folder_id)
                                    <br><span class="cat-id">{{ $cat->google_drive_folder_id }}</span>
                                @endif
                            </td>
                            <td>
                                @if($cat->google_drive_folder_id)
                                    <span class="cat-badge cat-badge--green">Sincronizado</span>
                                @else
                                    <span class="cat-badge cat-badge--gray">Local</span>
                                @endif
                            </td>
                            <td>{{ $cat->sort_order }}</td>
                            <td>
                                @if($cat->active)
                                    <span class="cat-badge cat-badge--green">Ativa</span>
                                @else
                                    <span class="cat-badge cat-badge--gray">Inativa</span>
                                @endif
                            </td>
                            <td style="text-align:right">
                                <form method="POST" action="{{ route('admin.transparency-categories.update', $cat) }}" style="display:inline" onsubmit="var nome=prompt('Nome:', '{{ $cat->name }}'); if(!nome) return false; this.querySelector('input[name=name]').value=nome; var ord=prompt('Ordem:', '{{ $cat->sort_order }}'); if(ord===null) return false; this.querySelector('input[name=sort_order]').value=ord; return true;">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="name" value="">
                                    <input type="hidden" name="sort_order" value="">
                                    <button type="submit" class="cat-action cat-action--edit">Editar</button>
                                </form>
                                <form method="POST" action="{{ route('admin.transparency-categories.destroy', $cat) }}" style="display:inline" onsubmit="return confirm('Remover categoria? Os documentos serao desvinculados.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="cat-action cat-action--del">Remover</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

</div>
@endsection

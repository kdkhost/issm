@extends('layouts.admin')
@section('title', 'Pastas do Google Drive')
@section('page-title', 'Gerenciar Pastas do Google Drive')

@section('content')
<style>
.df-wrap{max-width:900px}
.df-card{background:#fff;border-radius:1rem;border:1px solid #e5e7eb;overflow:hidden;margin-bottom:1.25rem}
.df-head{display:flex;align-items:center;gap:1rem;padding:1rem 1.25rem;border-bottom:1px solid #f3f4f6;background:linear-gradient(to right,#f9fafb,#fff)}
.df-head h3{font-size:1rem;font-weight:700;color:#111827;margin:0;flex:1}
.df-body{padding:1.25rem}
.df-alert{background:#fef3c7;border:1px solid #fde68a;border-radius:.75rem;padding:1rem;margin-bottom:1.5rem;font-size:.8125rem;color:#92400e}
.df-alert strong{color:#78350f}
.df-form{display:flex;gap:.5rem;margin-bottom:1.5rem;flex-wrap:wrap}
.df-input{flex:1;min-width:200px;border:1px solid #d1d5db;border-radius:.5rem;padding:.5rem .75rem;font-size:.8125rem}
.df-btn{background:#16a34a;color:#fff;font-weight:600;font-size:.8125rem;padding:.5rem 1rem;border-radius:.5rem;border:none;cursor:pointer}
.df-btn:hover{background:#15803d}
.df-list{display:flex;flex-direction:column;gap:.5rem}
.df-item{display:flex;align-items:center;gap:1rem;padding:.75rem 1rem;background:#f9fafb;border-radius:.5rem;border:1px solid #e5e7eb}
.df-item svg{width:1.25rem;height:1.25rem;color:#d97706;flex-shrink:0}
.df-name{flex:1;font-size:.875rem;font-weight:600;color:#374151}
.df-id{font-size:.6875rem;color:#9ca3af;font-family:monospace}
.df-actions{display:flex;gap:.5rem}
.df-action{font-size:.75rem;font-weight:600;padding:.375rem .75rem;border-radius:.375rem;border:none;cursor:pointer}
.df-action--edit{background:#dbeafe;color:#1e40af}
.df-action--del{background:#fee2e2;color:#ef4444}
.df-empty{text-align:center;padding:2rem;color:#6b7280;font-size:.875rem}
.df-empty svg{width:3rem;height:3rem;color:#d1d5db;margin-bottom:.5rem}
[data-theme="dark"] .df-card{background:#1f2937;border-color:#374151}
[data-theme="dark"] .df-head{background:linear-gradient(to right,#1a2535,#1f2937);border-color:#374151}
[data-theme="dark"] .df-head h3{color:#f9fafb}
[data-theme="dark"] .df-item{background:#1a2535;border-color:#374151}
[data-theme="dark"] .df-name{color:#d1d5db}
[data-theme="dark"] .df-alert{background:rgba(180,83,9,.15);border-color:rgba(251,191,36,.3);color:#fcd34d}
[data-theme="dark"] .df-input{background:#374151;border-color:#4b5563;color:#f9fafb}
</style>

<div class="df-wrap">

    <div class="df-alert">
        <strong>Aviso:</strong> para criar, renomear ou deletar pastas, a <strong>Service Account</strong> precisa ter permissao de <strong>Editor</strong> (nao apenas Leitor) na pasta raiz do Google Drive. Atualize o compartilhamento no Drive se necessario.
    </div>

    <div class="df-card">
        <div class="df-head">
            <h3>Nova Pasta</h3>
        </div>
        <div class="df-body">
            <form method="POST" action="{{ route('admin.drive-folders.store') }}" class="df-form">
                @csrf
                <input type="text" name="name" placeholder="Nome da nova pasta (ex: 2026 - Relatorios)" class="df-input" required maxlength="255">
                <button type="submit" class="df-btn">Criar Pasta</button>
            </form>
        </div>
    </div>

    <div class="df-card">
        <div class="df-head">
            <h3>Pastas Existentes</h3>
        </div>
        <div class="df-body">
            @if(empty($folders))
                <div class="df-empty">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                    <p>Nenhuma pasta encontrada na raiz do Google Drive.</p>
                </div>
            @else
                <div class="df-list">
                    @foreach($folders as $folder)
                    <div class="df-item">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                        <span class="df-name">{{ $folder->getName() }}</span>
                        <span class="df-id">{{ $folder->getId() }}</span>
                        <div class="df-actions">
                            <form method="POST" action="{{ route('admin.drive-folders.update', $folder->getId()) }}" style="display:inline" onsubmit="var nome=prompt('Novo nome:', '{{ $folder->getName() }}'); if(!nome) return false; this.querySelector('input[name=name]').value=nome; return true;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="name" value="">
                                <button type="submit" class="df-action df-action--edit">Renomear</button>
                            </form>
                            <form method="POST" action="{{ route('admin.drive-folders.destroy', $folder->getId()) }}" style="display:inline" onsubmit="return confirm('Mover esta pasta para a lixeira do Google Drive?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="df-action df-action--del">Deletar</button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

</div>
@endsection

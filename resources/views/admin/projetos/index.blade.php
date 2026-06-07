@extends("layouts.admin")
@section("title", "Projetos")
@section("page-title", "Projetos")
@push('styles')
<style>
    .projects-container {
        background: white;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    [data-theme="dark"] .projects-container {
        background: #1f2937;
        border-color: #374151;
    }

    .projects-header {
        padding: 1.5rem;
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    [data-theme="dark"] .projects-header {
        background: linear-gradient(135deg, #1a2535 0%, #111827 100%);
        border-bottom-color: #374151;
    }

    .projects-header-content h2 {
        font-size: 1.3rem;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }

    [data-theme="dark"] .projects-header-content h2 {
        color: #f9fafb;
    }

    .projects-header-content p {
        font-size: 0.875rem;
        color: #6b7280;
        margin: 0.25rem 0 0 0;
    }

    [data-theme="dark"] .projects-header-content p {
        color: #9ca3af;
    }

    .btn-new-project {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.65rem 1.25rem;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white !important;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 4px 6px rgba(16, 185, 129, 0.2);
    }

    .btn-new-project:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 12px rgba(16, 185, 129, 0.3);
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
    }

    .projects-table {
        width: 100%;
        border-collapse: collapse;
    }

    .projects-table thead {
        background: #f9fafb;
        border-bottom: 2px solid #e5e7eb;
    }

    [data-theme="dark"] .projects-table thead {
        background: #1a2535;
        border-bottom-color: #374151;
    }

    .projects-table th {
        padding: 1rem 1.5rem;
        text-align: left;
        font-size: 0.75rem;
        font-weight: 700;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        background: linear-gradient(180deg, #fafbfc 0%, #f3f4f6 100%);
    }

    [data-theme="dark"] .projects-table th {
        color: #9ca3af;
        background: linear-gradient(180deg, #1a2535 0%, #111827 100%);
    }

    .projects-table tbody tr {
        border-bottom: 1px solid #f3f4f6;
        transition: all 0.2s;
    }

    [data-theme="dark"] .projects-table tbody tr {
        border-bottom-color: #374151;
    }

    .projects-table tbody tr:hover {
        background: #fafbfc;
    }

    [data-theme="dark"] .projects-table tbody tr:hover {
        background: rgba(255, 255, 255, 0.02);
    }

    .projects-table td {
        padding: 1.25rem 1.5rem;
        font-size: 0.875rem;
        color: #374151;
    }

    [data-theme="dark"] .projects-table td {
        color: #d1d5db;
    }

    .project-title-cell {
        font-weight: 600;
        color: #111827;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    [data-theme="dark"] .project-title-cell {
        color: #f9fafb;
    }

    .project-featured-star {
        font-size: 1.2rem;
        cursor: pointer;
        transition: transform 0.2s;
    }

    .project-featured-star:hover {
        transform: scale(1.2);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0.875rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        width: fit-content;
    }

    .status-badge::before {
        content: '';
        display: inline-block;
        width: 0.5rem;
        height: 0.5rem;
        border-radius: 50%;
        background: currentColor;
        opacity: 0.7;
    }

    .status-active {
        background: #d1fae5;
        color: #065f46;
    }

    [data-theme="dark"] .status-active {
        background: rgba(16, 185, 129, 0.2);
        color: #34d399;
    }

    .status-completed {
        background: #dbeafe;
        color: #0c4a6e;
    }

    [data-theme="dark"] .status-completed {
        background: rgba(59, 130, 246, 0.2);
        color: #60a5fa;
    }

    .status-planned {
        background: #fef3c7;
        color: #78350f;
    }

    [data-theme="dark"] .status-planned {
        background: rgba(245, 158, 11, 0.2);
        color: #fbbf24;
    }

    .project-actions {
        display: flex;
        gap: 0.5rem;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2rem;
        height: 2rem;
        border-radius: 6px;
        border: 1px solid transparent;
        background: white;
        color: #6b7280;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        font-size: 0;
    }

    .action-btn svg {
        width: 1rem;
        height: 1rem;
    }

    .action-btn:hover {
        transform: translateY(-2px);
    }

    .action-btn.edit {
        border-color: #bfdbfe;
        background: #dbeafe;
        color: #0c4a6e;
    }

    [data-theme="dark"] .action-btn.edit {
        background: rgba(59, 130, 246, 0.1);
        border-color: #1e3a8a;
        color: #60a5fa;
    }

    .action-btn.edit:hover {
        background: #bfdbfe;
        box-shadow: 0 4px 8px rgba(3, 102, 214, 0.1);
    }

    [data-theme="dark"] .action-btn.edit:hover {
        background: rgba(59, 130, 246, 0.2);
    }

    .action-btn.delete {
        border-color: #fecaca;
        background: #fee2e2;
        color: #7c2d12;
    }

    [data-theme="dark"] .action-btn.delete {
        background: rgba(239, 68, 68, 0.1);
        border-color: #7f1d1d;
        color: #f87171;
    }

    .action-btn.delete:hover {
        background: #fecaca;
        box-shadow: 0 4px 8px rgba(220, 38, 38, 0.1);
    }

    [data-theme="dark"] .action-btn.delete:hover {
        background: rgba(239, 68, 68, 0.2);
    }

    .projects-footer {
        padding: 1.5rem;
        background: #fafbfc;
        border-top: 1px solid #e5e7eb;
    }

    [data-theme="dark"] .projects-footer {
        background: #1a2535;
        border-top-color: #374151;
    }

    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
    }

    .empty-state-icon {
        width: 4rem;
        height: 4rem;
        color: #d1d5db;
        margin: 0 auto 1rem;
    }

    [data-theme="dark"] .empty-state-icon {
        color: #4b5563;
    }

    .empty-state-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    [data-theme="dark"] .empty-state-title {
        color: #f9fafb;
    }

    .empty-state-text {
        font-size: 0.875rem;
        color: #6b7280;
    }

    [data-theme="dark"] .empty-state-text {
        color: #9ca3af;
    }
</style>
@endpush
@section("content")
<div class="projects-container">
    <div class="projects-header">
        <div class="projects-header-content">
            <h2>Projetos</h2>
            <p>Gestione e organize seus projetos</p>
        </div>
        <a href="{{ route("admin.projetos.create") }}" class="btn-new-project" title="Criar novo projeto">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Novo Projeto
        </a>
    </div>

    @if($projects->count() > 0)
    <table class="projects-table">
        <thead>
            <tr>
                <th style="width: 35%">Título</th>
                <th style="width: 15%">Categoria</th>
                <th style="width: 15%">Status</th>
                <th style="width: 10%; text-align: center;">Destaque</th>
                <th style="width: 25%; text-align: right;">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($projects as $project)
            <tr>
                <td>
                    <div class="project-title-cell">
                        <span>{{ Str::limit($project->title, 50) }}</span>
                    </div>
                </td>
                <td>
                    @if($project->category)
                        <span class="text-gray-700">{{ $project->category }}</span>
                    @else
                        <span class="text-gray-400 italic">—</span>
                    @endif
                </td>
                <td>
                    <span class="status-badge status-{{ $project->status }}">
                        {{ $project->status === 'active' ? 'Ativo' : ($project->status === 'completed' ? 'Concluído' : 'Planejado') }}
                    </span>
                </td>
                <td style="text-align: center;">
                    <span class="project-featured-star" title="{{ $project->featured ? 'Projeto em destaque' : 'Não em destaque' }}">
                        {{ $project->featured ? '⭐' : '☆' }}
                    </span>
                </td>
                <td>
                    <div class="project-actions" style="justify-content: flex-end;">
                        <a href="{{ route("admin.projetos.edit", $project) }}" class="action-btn edit" title="Editar projeto">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        <form method="POST" action="{{ route("admin.projetos.destroy", $project) }}" style="display: inline;">
                            @csrf
                            @method("DELETE")
                            <button type="submit" data-confirm="Tem certeza que deseja excluir este projeto?" class="action-btn delete" title="Excluir projeto">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="projects-footer">
        {{ $projects->links() }}
    </div>
    @else
    <div class="empty-state">
        <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
        <div class="empty-state-title">Nenhum projeto cadastrado</div>
        <p class="empty-state-text">Comece criando um novo projeto clicando no botão acima</p>
    </div>
    @endif
</div>
@endsection

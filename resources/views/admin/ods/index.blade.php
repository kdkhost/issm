@extends("layouts.admin")
@section("title", "ODS 2030")
@section("page-title", "Objetivos de Desenvolvimento Sustentavel")
@push('styles')
<style>
    .ods-card {
        border-radius: 12px;
        overflow: hidden;
        transition: box-shadow 0.2s, transform 0.2s;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        border: 1px solid #e5e7eb;
    }

    .ods-card:hover {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        transform: translateY(-4px);
    }

    .ods-card-content {
        padding: 1.5rem;
        color: white;
        position: relative;
        min-height: 140px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .ods-number {
        font-size: 2.5rem;
        font-weight: 900;
        line-height: 1;
    }

    .ods-title {
        font-size: 0.95rem;
        font-weight: 600;
        line-height: 1.4;
        margin-top: 1rem;
    }

    .ods-footer {
        background: #fafafa;
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        border-top: 1px solid #e5e7eb;
    }

    .ods-status {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
    }

    .ods-status.active {
        background: #dcfce7;
        color: #15803d;
    }

    .ods-status.inactive {
        background: #f3f4f6;
        color: #6b7280;
    }

    .ods-edit-link {
        font-size: 0.75rem;
        font-weight: 600;
        color: #2563eb;
        text-decoration: none;
        transition: color 0.2s;
    }

    .ods-edit-link:hover {
        color: #1d4ed8;
    }
</style>
@endpush
@section("content")
<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
    @foreach($odsList as $od)
    <div class="ods-card">
        <div class="ods-card-content" style="background-color: {{ $od->color }}">
            <div>
                <p class="ods-number">{{ $od->number }}</p>
                <p class="ods-title">{{ $od->title }}</p>
            </div>
        </div>
        <div class="ods-footer">
            <span class="ods-status {{ $od->active ? 'active' : 'inactive' }}">
                {{ $od->active ? 'Ativo' : 'Inativo' }}
            </span>
            <a href="{{ route("admin.ods.edit", $od) }}" class="ods-edit-link">Editar</a>
        </div>
    </div>
    @endforeach
</div>
@endsection

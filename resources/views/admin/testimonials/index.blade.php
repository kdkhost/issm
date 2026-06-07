@extends('layouts.admin')
@section('title', 'Depoimentos')
@section('page-title', 'Gerenciar Depoimentos')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <p class="text-gray-500 text-sm">Gerencie os depoimentos exibidos no site.</p>
    </div>
    <a href="{{ route('admin.depoimentos.create') }}" class="inline-flex items-center gap-2 bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded-xl font-semibold transition-all shadow-lg shadow-green-900/20">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Novo Depoimento
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider w-20">Foto</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Autor</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Cargo/Papel</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Ordem</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider w-32 text-center">Status</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider w-32 text-right">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($testimonials as $item)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4">
                        @if($item->photo)
                            <img src="{{ asset('media/' . $item->photo) }}" class="w-10 h-10 rounded-full object-cover border border-gray-100">
                        @else
                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-bold text-gray-900">{{ $item->name }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-gray-600">{{ $item->role ?: '-' }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm font-medium text-gray-500">#{{ $item->order }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <button onclick="toggleActive({{ $item->id }}, this)" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition-all {{ $item->active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $item->active ? 'bg-green-600' : 'bg-gray-400' }}"></span>
                            {{ $item->active ? 'Ativo' : 'Inativo' }}
                        </button>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.depoimentos.edit', $item) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Editar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form action="{{ route('admin.depoimentos.destroy', $item) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" data-confirm="Tem certeza que deseja excluir este depoimento?" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Excluir">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">Nenhum depoimento encontrado.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($testimonials->hasPages())
    <div class="px-6 py-4 border-t border-gray-50 bg-gray-50/50">
        {{ $testimonials->links() }}
    </div>
    @endif
</div>

@push('scripts')
<script>
function toggleActive(id, btn) {
    fetch(`{{ url('admin/depoimentos') }}/${id}/toggle`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.active) {
            btn.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition-all bg-green-100 text-green-700';
            btn.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-green-600"></span>Ativo';
            showToast('Depoimento ativado!', 'success');
        } else {
            btn.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition-all bg-gray-100 text-gray-500';
            btn.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>Inativo';
            showToast('Depoimento desativado!', 'info');
        }
    });
}
</script>
@endpush
@endsection

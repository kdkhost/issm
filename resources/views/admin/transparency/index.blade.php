@extends("layouts.admin")
@section("title", "Portal da Transparência")
@section("page-title", "Portal da Transparência")
@section("content")
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-gray-800">Documentos da Transparência</h2>
    <a href="{{ route("admin.transparencia.create") }}" class="bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800 flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>Novo Documento</a>
</div>
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Título</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Categoria</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ano</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($documents as $doc)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3 font-medium text-gray-900 text-sm">{{ $doc->title }}</td>
                <td class="px-4 py-3 text-gray-600 text-sm">{{ $doc->category }}</td>
                <td class="px-4 py-3 text-gray-600 text-sm">{{ $doc->year }}</td>
                <td class="px-4 py-3"><span class="px-2 py-1 rounded-full text-xs font-medium {{ $doc->active ? "bg-green-100 text-green-700" : "bg-gray-100 text-gray-600" }}">{{ $doc->active ? "Ativo" : "Inativo" }}</span></td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <div class="flex items-center gap-1">
                        <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="text-green-600 hover:text-green-800 text-sm font-medium px-1">Ver</a>
                        <a href="{{ route("admin.transparencia.edit", $doc) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium px-1">Editar</a>
                        <form method="POST" action="{{ route("admin.transparencia.destroy", $doc) }}">@csrf @method("DELETE")<button type="submit" data-confirm="Excluir este documento?" class="text-red-600 hover:text-red-800 text-sm font-medium px-1">Excluir</button></form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-6 py-10 text-center text-gray-400">Nenhum documento cadastrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4 border-t border-gray-100">{{ $documents->links() }}</div>
</div>
@endsection

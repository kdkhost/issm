@extends("layouts.admin")
@section("title", "Banners")
@section("page-title", "Banners")
@section("content")
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-gray-800">Banners</h2>
    <a href="{{ route("admin.banners.create") }}" class="bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800 flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>Novo Banner</a>
</div>
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Imagem</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Título</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden sm:table-cell">Ordem</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($banners as $banner)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3">@if($banner->image)<img src="{{ asset("media/".$banner->image) }}" class="h-10 w-16 object-cover rounded">@else<div class="h-10 w-16 bg-gray-100 rounded flex items-center justify-center text-gray-400 text-xs">Sem img</div>@endif</td>
                <td class="px-4 py-3 font-medium text-gray-900 text-sm">{{ $banner->title }}</td>
                <td class="px-4 py-3 text-gray-600 text-sm hidden sm:table-cell">{{ $banner->order }}</td>
                <td class="px-4 py-3"><span class="px-2 py-1 rounded-full text-xs font-medium {{ $banner->active ? "bg-green-100 text-green-700" : "bg-gray-100 text-gray-600" }}">{{ $banner->active ? "Ativo" : "Inativo" }}</span></td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <div class="flex items-center gap-1">
                        <button type="button" data-dt-toggle class="dt-toggle p-1 rounded text-gray-400 hover:text-green-700 hover:bg-green-50 transition-colors" title="Ver detalhes ocultos">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <a href="{{ route("admin.banners.edit", $banner) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium px-1">Editar</a>
                        <form method="POST" action="{{ route("admin.banners.destroy", $banner) }}" onsubmit="return confirm('Excluir este banner?')">@csrf @method("DELETE")<button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium px-1">Excluir</button></form>
                    </div>
                </td>
            </tr>
            <tr class="dt-detail hidden">
                <td colspan="5" class="px-4 py-3 bg-green-50 border-b border-green-100">
                    <dl class="grid grid-cols-2 sm:grid-cols-4 gap-x-6 gap-y-2 text-sm">
                        <div><dt class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Ordem</dt><dd class="text-gray-800 mt-0.5">{{ $banner->order }}</dd></div>
                    </dl>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-6 py-10 text-center text-gray-400">Nenhum banner cadastrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4 border-t border-gray-100">{{ $banners->links() }}</div>
</div>
@endsection

{{-- @autor marcelo-brad rj --}}
{{-- @contato Tel: 21 981325441 | Email: contato@kdkhost.com.br | Telegram: @MARCELO_BRAD | Instagram: @marcelobradrj | WhatsApp: 21981325441 --}}
@extends("layouts.admin")
@section("title", "CMS - Páginas")
@section("page-title", "Gerenciar Páginas CMS")
@section("content")
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div class="flex flex-col sm:flex-row gap-3">
        <form method="GET" action="{{ route("admin.cms.pages.index") }}" class="flex flex-col sm:flex-row gap-3">
            <input type="text" name="search" value="{{ request("search") }}" placeholder="Buscar páginas..." class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="">Todos os status</option>
                <option value="draft" {{ request("status") == "draft" ? "selected" : "" }}>Rascunho</option>
                <option value="published" {{ request("status") == "published" ? "selected" : "" }}>Publicado</option>
                <option value="active" {{ request("status") == "active" ? "selected" : "" }}>Ativo</option>
                <option value="inactive" {{ request("status") == "inactive" ? "selected" : "" }}>Inativo</option>
            </select>
            <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 text-sm font-medium">Filtrar</button>
            @if(request("search") || request("status"))
            <a href="{{ route("admin.cms.pages.index") }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium self-center">Limpar</a>
            @endif
        </form>
    </div>
    <a href="{{ route("admin.cms.pages.create") }}" class="bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800 flex items-center gap-2 text-sm font-medium self-start">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Nova Página CMS
    </a>
</div>
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Título</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden sm:table-cell">Slug</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden md:table-cell">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden lg:table-cell">Publicação</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ativo</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($pages as $page)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 font-medium text-gray-900 text-sm">{{ Str::limit($page->title, 40) }}</td>
                    <td class="px-4 py-3 text-gray-600 text-sm font-mono hidden sm:table-cell">/{{ $page->slug }}</td>
                    <td class="px-4 py-3 hidden md:table-cell">
                        @if($page->status == "published")
                            <span class="badge-green">Publicado</span>
                        @else
                            <span class="badge-gray">Rascunho</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-600 text-sm hidden lg:table-cell">{{ $page->published_at ? $page->published_at->format("d/m/Y H:i") : "-" }}</td>
                    <td class="px-4 py-3">
                        <form method="POST" action="{{ route("admin.cms.pages.toggle", $page) }}">
                            @csrf @method("PATCH")
                            <button type="submit" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $page->is_active ? "bg-green-600" : "bg-gray-300" }}" data-tooltip="{{ $page->is_active ? "Desativar" : "Ativar" }}">
                                <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $page->is_active ? "translate-x-6" : "translate-x-1" }}"></span>
                            </button>
                        </form>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="flex items-center gap-1">
                            <a href="{{ route("admin.cms.pages.show", $page) }}" class="text-green-600 hover:text-green-800 text-sm font-medium px-1" data-tooltip="Visualizar">Ver</a>
                            <a href="{{ route("admin.cms.pages.edit", $page) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium px-1" data-tooltip="Editar">Editar</a>
                            <form method="POST" action="{{ route("admin.cms.pages.destroy", $page) }}" class="inline">
                                @csrf @method("DELETE")
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium px-1" data-confirm="Excluir esta página permanentemente?" data-tooltip="Excluir">Excluir</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-10 text-center text-gray-400">Nenhuma página CMS encontrada.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-gray-100">{{ $pages->appends(request()->query())->links() }}</div>
</div>
@endsection

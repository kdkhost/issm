@extends("layouts.admin")
@section("title", "CMS Páginas Públicas")
@section("page-title", "CMS das Páginas Públicas")

@section("content")
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-800">Páginas Públicas Reais</h2>
        <p class="text-sm text-gray-500 mt-1">Somente páginas existentes no sistema — URLs e views preservadas.</p>
    </div>
    <button type="button" disabled class="bg-gray-300 text-gray-500 px-4 py-2 rounded-lg cursor-not-allowed flex items-center gap-2" title="Criação restrita — apenas páginas reais mapeadas">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Nova Página
    </button>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Página</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase hidden md:table-cell">URL</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase hidden lg:table-cell">View</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase hidden sm:table-cell">Campos</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($pages as $page)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3">
                    <div class="font-medium text-gray-900 text-sm">{{ $page->admin_label }}</div>
                    <div class="text-xs text-gray-500">{{ $page->controller ? class_basename($page->controller) : '-' }}</div>
                </td>
                <td class="px-4 py-3 text-sm text-gray-600 hidden md:table-cell">
                    <code class="text-xs bg-gray-100 px-1.5 py-0.5 rounded">{{ $page->route_uri }}</code>
                </td>
                <td class="px-4 py-3 text-xs text-gray-500 hidden lg:table-cell">{{ $page->view_path }}</td>
                <td class="px-4 py-3 text-sm hidden sm:table-cell">
                    <span class="text-green-700 font-medium">{{ $page->fields_count }}</span>
                    @if($page->pending_count > 0)
                    <span class="text-orange-600 text-xs">({{ $page->pending_count }} pend.)</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    @if($page->needs_review)
                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-700">Revisão</span>
                    @elseif($page->is_editable)
                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Editável</span>
                    @else
                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">Modelo</span>
                    @endif
                    @if($page->use_custom_html)
                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700 ml-1" title="Usando HTML personalizado">HTML</span>
                    @endif
                    @if($page->cache_enabled)
                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 ml-1">Cache</span>
                    @endif
                </td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <div class="flex items-center gap-1 flex-wrap">
                        @if($page->is_editable)
                        <a href="{{ route('admin.cms-public-pages.edit', $page) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium px-1">Editar</a>
                        <a href="{{ route('admin.cms-public-pages.edit-full-html', $page) }}" class="text-amber-600 hover:text-amber-800 text-sm font-medium px-1">HTML</a>
                        <a href="{{ route('admin.cms-public-pages.seo', $page) }}" class="text-purple-600 hover:text-purple-800 text-sm font-medium px-1">SEO</a>
                        @endif
                        @if($page->publicUrl())
                        <a href="{{ $page->publicUrl() }}" target="_blank" class="text-green-600 hover:text-green-800 text-sm font-medium px-1">Ver</a>
                        @endif
                        <form method="POST" action="{{ route('admin.cms-public-pages.clear-cache', $page) }}" class="inline">
                            @csrf
                            <button type="submit" data-confirm="Limpar cache desta página?" class="text-gray-500 hover:text-gray-700 text-sm font-medium px-1">Cache</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-10 text-center text-gray-400">
                    Nenhuma página mapeada. Execute: <code class="bg-gray-100 px-2 py-1 rounded text-sm">php artisan cms:map-public-pages</code>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($pages->isNotEmpty())
<div class="mt-4 text-xs text-gray-500">
    Último mapeamento: {{ $pages->max('last_mapped_at')?->format('d/m/Y H:i') ?? 'Nunca' }}
</div>
@endif
@endsection

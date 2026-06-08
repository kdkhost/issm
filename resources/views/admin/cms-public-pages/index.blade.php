@extends("layouts.admin")
@section("title", "CMS Paginas Publicas")
@section("page-title", "Gerenciar Conteudo do Site")
@section("page-subtitle", "Edite textos, titulos e SEO das paginas publicas")

@section("content")

@php
$editableCount = $pages->where("is_editable", true)->count();
$totalFields = $pages->sum("fields_count");
$pendingFields = $pages->sum("pending_count");
$filledPercent = $totalFields > 0 ? round((($totalFields - $pendingFields) / $totalFields) * 100) : 0;
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-green-500 flex items-center gap-4">
        <div class="w-11 h-11 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
        </div>
        <div>
            <p class="text-gray-500 text-xs font-medium uppercase tracking-wider">Paginas</p>
            <p class="text-2xl font-black text-gray-900">{{ $pages->count() }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-blue-500 flex items-center gap-4">
        <div class="w-11 h-11 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        </div>
        <div>
            <p class="text-gray-500 text-xs font-medium uppercase tracking-wider">Editaveis</p>
            <p class="text-2xl font-black text-gray-900">{{ $editableCount }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-purple-500 flex items-center gap-4">
        <div class="w-11 h-11 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
        </div>
        <div>
            <p class="text-gray-500 text-xs font-medium uppercase tracking-wider">Campos</p>
            <p class="text-2xl font-black text-gray-900">{{ $totalFields }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-amber-500 flex items-center gap-4">
        <div class="w-11 h-11 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <p class="text-gray-500 text-xs font-medium uppercase tracking-wider">Pendentes</p>
            <p class="text-2xl font-black text-gray-900">{{ $pendingFields }}</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
    @forelse($pages as $page)
    @php
        $total = $page->fields_count;
        $pending = $page->pending_count;
        $done = $total - $pending;
        $pct = $total > 0 ? round(($done / $total) * 100) : 0;
    @endphp
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
        <div class="px-5 pt-5 pb-3">
            <div class="flex items-start justify-between mb-3">
                <div class="flex-1 min-w-0">
                    <h3 class="font-bold text-gray-900 text-base truncate" title="{{ $page->admin_label }}">{{ $page->admin_label }}</h3>
                    <p class="text-xs text-gray-400 mt-0.5 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                        {{ $page->view_path }}
                    </p>
                </div>
                <div class="flex flex-col items-end gap-1 ml-3 flex-shrink-0">
                    @if($page->needs_review)
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-orange-100 text-orange-700 uppercase tracking-wide">Revisao</span>
                    @elseif($page->is_editable)
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-700 uppercase tracking-wide">Editavel</span>
                    @else
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700 uppercase tracking-wide">Modelo</span>
                    @endif
                    @if($page->cache_enabled)
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-500 uppercase tracking-wide">Cache</span>
                    @endif
                </div>
            </div>
            <a href="{{ $page->publicUrl() }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs bg-gray-50 border border-gray-200 text-gray-600 rounded-lg px-2.5 py-1 hover:bg-green-50 hover:text-green-700 hover:border-green-200 transition-all mb-3 max-w-full truncate">
                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                {{ $page->route_uri }}
            </a>
        </div>
        <div class="px-5 pb-4">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-xs text-gray-500 font-medium">Progresso</span>
                <span class="text-xs font-bold {{ $pct == 100 ? 'text-green-600' : ($pct > 50 ? 'text-blue-600' : 'text-amber-600') }}">{{ $pct }}%</span>
            </div>
            <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full rounded-full transition-all duration-500 {{ $pct == 100 ? 'bg-green-500' : ($pct > 50 ? 'bg-blue-500' : 'bg-amber-500') }}" style="width:{{ $pct }}%"></div>
            </div>
            <p class="text-[11px] text-gray-400 mt-1.5">{{ $done }} de {{ $total }} campos preenchidos</p>
        </div>
        <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex items-center gap-2 flex-wrap">
            @if($page->is_editable)
                <a href="{{ route('admin.cms-public-pages.edit', $page) }}" class="flex-1 min-w-0 flex items-center justify-center gap-1.5 bg-white border border-gray-200 text-gray-700 text-xs font-semibold px-3 py-2 rounded-lg hover:bg-blue-50 hover:text-blue-700 hover:border-blue-200 transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Editar
                </a>
                <a href="{{ route('admin.cms-public-pages.seo', $page) }}" class="flex-1 min-w-0 flex items-center justify-center gap-1.5 bg-white border border-gray-200 text-gray-700 text-xs font-semibold px-3 py-2 rounded-lg hover:bg-purple-50 hover:text-purple-700 hover:border-purple-200 transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    SEO
                </a>
            @endif
            @if($page->publicUrl())
                <a href="{{ $page->publicUrl() }}" target="_blank" class="flex-1 min-w-0 flex items-center justify-center gap-1.5 bg-white border border-gray-200 text-gray-700 text-xs font-semibold px-3 py-2 rounded-lg hover:bg-green-50 hover:text-green-700 hover:border-green-200 transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Ver
                </a>
            @endif
            <form method="POST" action="{{ route('admin.cms-public-pages.clear-cache', $page) }}" class="inline">
                @csrf
                <button type="submit" data-confirm="Limpar cache desta pagina?" class="flex-1 min-w-0 flex items-center justify-center gap-1.5 bg-white border border-gray-200 text-gray-700 text-xs font-semibold px-3 py-2 rounded-lg hover:bg-gray-100 transition-all cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Cache
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="col-span-full bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
        </div>
        <h3 class="text-lg font-bold text-gray-700 mb-1">Nenhuma pagina mapeada</h3>
        <p class="text-sm text-gray-500">Execute o comando abaixo para descobrir paginas automaticamente:</p>
        <code class="inline-block mt-3 bg-gray-100 text-gray-700 text-sm px-3 py-1.5 rounded-lg font-mono">php artisan cms:map-public-pages</code>
    </div>
    @endforelse
</div>

@if($pages->isNotEmpty())
<div class="mt-5 text-xs text-gray-400 flex items-center gap-1.5">
    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    Ultimo mapeamento: {{ $pages->max('last_mapped_at')?->format('d/m/Y H:i') ?? 'Nunca' }}
</div>
@endif
@endsection

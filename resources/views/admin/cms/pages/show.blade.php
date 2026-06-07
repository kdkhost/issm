{{-- @autor marcelo-brad rj --}}
{{-- @contato Tel: 21 981325441 | Email: contato@kdkhost.com.br | Telegram: @MARCELO_BRAD | Instagram: @marcelobradrj | WhatsApp: 21981325441 --}}
@extends("layouts.admin")
@section("title", $page->title)
@section("page-title", $page->title)
@section("content")
<div class="max-w-4xl">
    <div class="bg-white rounded-xl shadow-sm p-6 space-y-5">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $page->title }}</h2>
                <p class="text-sm text-gray-500 mt-1">Slug: <code class="font-mono text-green-700">/{{ $page->slug }}</code></p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route("admin.cms.pages.edit", $page) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm font-medium flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Editar
                </a>
                @if($page->status == "published")
                <a href="{{ route("cms.page", $page->slug) }}" target="_blank" class="bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800 text-sm font-medium flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Pré-visualizar
                </a>
                @endif
            </div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-4 bg-gray-50 rounded-lg">
            <div>
                <dt class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Status</dt>
                <dd class="text-gray-800 mt-0.5 font-medium">
                    @if($page->status == "published")
                        <span class="badge-green">Publicado</span>
                    @else
                        <span class="badge-gray">Rascunho</span>
                    @endif
                </dd>
            </div>
            <div>
                <dt class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Ativo</dt>
                <dd class="text-gray-800 mt-0.5">{{ $page->is_active ? "Sim" : "Não" }}</dd>
            </div>
            <div>
                <dt class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Template</dt>
                <dd class="text-gray-800 mt-0.5">{{ ucfirst($page->template ?? "Padrão") }}</dd>
            </div>
            <div>
                <dt class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Layout</dt>
                <dd class="text-gray-800 mt-0.5">{{ ucfirst($page->layout ?? "Padrão") }}</dd>
            </div>
            <div>
                <dt class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Publicação</dt>
                <dd class="text-gray-800 mt-0.5">{{ $page->published_at ? $page->published_at->format("d/m/Y H:i") : "-" }}</dd>
            </div>
            <div>
                <dt class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Exibir Menu</dt>
                <dd class="text-gray-800 mt-0.5">{{ isset($page->show_in_menu) && $page->show_in_menu ? "Sim" : "Não" }}</dd>
            </div>
            <div>
                <dt class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Criado em</dt>
                <dd class="text-gray-800 mt-0.5">{{ $page->created_at->format("d/m/Y H:i") }}</dd>
            </div>
            <div>
                <dt class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Atualizado em</dt>
                <dd class="text-gray-800 mt-0.5">{{ $page->updated_at->format("d/m/Y H:i") }}</dd>
            </div>
        </div>
        @if($page->content)
        <div>
            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-2">Conteúdo</h3>
            <div class="prose prose-sm max-w-none text-gray-700 border rounded-lg p-4 bg-gray-50">
                {!! $page->content !!}
            </div>
        </div>
        @endif
        <div class="flex items-center gap-2 pt-4 border-t border-gray-100">
            <a href="{{ route("admin.cms.pages.index") }}" class="text-gray-600 hover:text-gray-800 font-medium">Voltar</a>
            <a href="{{ route("admin.cms.seo.edit", $page) }}" class="text-blue-600 hover:text-blue-800 font-medium ml-auto flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Gerenciar SEO
            </a>
            <a href="{{ route("admin.cms.sections.index", $page) }}" class="text-purple-600 hover:text-purple-800 font-medium flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                Seções
            </a>
        </div>
    </div>
</div>
@endsection

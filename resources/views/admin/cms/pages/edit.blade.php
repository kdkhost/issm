{{-- @autor marcelo-brad rj --}}
{{-- @contato Tel: 21 981325441 | Email: contato@kdkhost.com.br | Telegram: @MARCELO_BRAD | Instagram: @marcelobradrj | WhatsApp: 21981325441 --}}
@extends("layouts.admin")
@section("title", "Editar Página CMS")
@section("page-title", "Editar Página CMS")
@push("scripts")
<script>
$(function() {
    $("#title").on("keyup change", function() {
        var slug = $(this).val()
            .toLowerCase()
            .replace(/[^a-z0-9-]/g, "-")
            .replace(/-+/g, "-")
            .replace(/^-|-$/g, "");
        $("#slug").val(slug);
    });
});
</script>
@endpush
@section("content")
<div class="max-w-4xl">
    <form method="POST" action="{{ route("admin.cms.pages.update", $cmsPage) }}" enctype="multipart/form-data">
        @csrf @method("PUT")
        <div class="bg-white rounded-xl shadow-sm p-6 space-y-5">
            <div class="flex items-center justify-end gap-2 mb-2">
                <a href="{{ route("admin.cms.seo.edit", $cmsPage) }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    SEO
                </a>
                <a href="{{ route("admin.cms.versions.index", ["page", $cmsPage->id]) }}" class="text-sm text-purple-600 hover:text-purple-800 font-medium flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Versões
                </a>
                @if($cmsPage->status == "published")
                <a href="{{ route("cms.page", $cmsPage->slug) }}" target="_blank" class="text-sm text-green-600 hover:text-green-800 font-medium flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Pré-visualizar
                </a>
                @endif
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Título *</label>
                    <input type="text" name="title" id="title" value="{{ old("title", $cmsPage->title) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    @error("title")<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Slug *</label>
                    <input type="text" name="slug" id="slug" value="{{ old("slug", $cmsPage->slug) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    @error("slug")<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Conteúdo *</label>
                <textarea name="content" rows="14" class="wysiwyg w-full border border-gray-300 rounded-lg px-3 py-2">{{ old("content", $cmsPage->content) }}</textarea>
                @error("content")<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="draft" {{ old("status", $cmsPage->status) == "draft" ? "selected" : "" }}>Rascunho</option>
                        <option value="published" {{ old("status", $cmsPage->status) == "published" ? "selected" : "" }}>Publicado</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data de Publicação</label>
                    <input type="datetime-local" name="published_at" value="{{ old("published_at", $cmsPage->published_at ? $cmsPage->published_at->format("Y-m-d\TH:i") : "") }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Template</label>
                    <select name="template" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="default" {{ old("template", $cmsPage->template) == "default" ? "selected" : "" }}>Padrão</option>
                        <option value="full" {{ old("template", $cmsPage->template) == "full" ? "selected" : "" }}>Largura Total</option>
                        <option value="sidebar" {{ old("template", $cmsPage->template) == "sidebar" ? "selected" : "" }}>Com Sidebar</option>
                        <option value="landing" {{ old("template", $cmsPage->template) == "landing" ? "selected" : "" }}>Landing Page</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Layout</label>
                    <select name="layout" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="default" {{ old("layout", $cmsPage->layout) == "default" ? "selected" : "" }}>Padrão</option>
                        <option value="full-width" {{ old("layout", $cmsPage->layout) == "full-width" ? "selected" : "" }}>Largura Total</option>
                        <option value="boxed" {{ old("layout", $cmsPage->layout) == "boxed" ? "selected" : "" }}>Boxed</option>
                    </select>
                </div>
                <div class="flex items-end gap-6 pb-2">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" id="is_active" {{ old("is_active", $cmsPage->is_active) ? "checked" : "" }} class="w-4 h-4 text-green-600 rounded">
                        <label for="is_active" class="text-sm font-medium text-gray-700">Ativo</label>
                    </div>
                </div>
            </div>
            <div class="flex justify-between pt-4 border-t border-gray-100">
                <a href="{{ route("admin.cms.pages.index") }}" class="text-gray-600 hover:text-gray-800 font-medium">Cancelar</a>
                <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 font-medium">Atualizar Página</button>
            </div>
        </div>
    </form>

    {{-- Sections & Blocks --}}
    <div class="bg-white rounded-xl shadow-sm p-6 mt-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800">Seções e Blocos</h3>
        </div>
        @php $cmsSections = $cmsPage->sections()->orderBy('sort_order')->get(); @endphp
        @if($cmsSections->isNotEmpty())
            <div class="space-y-4">
                @foreach($cmsSections as $section)
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div class="bg-gray-50 px-4 py-3 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="text-xs text-gray-400 font-mono">#{{ $section->sort_order }}</span>
                            <h4 class="font-semibold text-gray-800">{{ $section->title }}</h4>
                            @if($section->description)
                            <span class="text-xs text-gray-500 hidden sm:inline">{{ $section->description }}</span>
                            @endif
                            @if(!$section->is_active)
                            <span class="text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded">Inativo</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-2 text-xs">
                            <span class="text-gray-400">{{ $section->blocks->count() }} bloco(s)</span>
                            <a href="{{ route('admin.cms.blocks.index') }}?section_id={{ $section->id }}" class="text-blue-600 hover:text-blue-800 font-medium">Gerenciar</a>
                        </div>
                    </div>
                    @if($section->blocks->isNotEmpty())
                    <div class="divide-y divide-gray-100">
                        @foreach($section->blocks as $block)
                        <div class="px-4 py-2 flex items-center justify-between text-sm">
                            <div class="flex items-center gap-2">
                                <span class="inline-block w-1.5 h-1.5 rounded-full
                                    @if($block->type == 'hero') bg-purple-500
                                    @elseif($block->type == 'text') bg-blue-500
                                    @elseif($block->type == 'cards') bg-orange-500
                                    @elseif($block->type == 'cta') bg-green-500
                                    @elseif($block->type == 'gallery') bg-pink-500
                                    @elseif($block->type == 'contact') bg-teal-500
                                    @elseif($block->type == 'faq') bg-yellow-500
                                    @elseif($block->type == 'banner') bg-indigo-500
                                    @else bg-gray-400
                                    @endif">
                                </span>
                                <span class="text-xs font-mono text-gray-400">{{ $block->type }}</span>
                                <span class="text-gray-700">{{ Str::limit($block->title, 40) }}</span>
                            </div>
                            @if(!$block->is_active)
                            <span class="text-xs text-gray-400 italic">Inativo</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-gray-400">
                <p>Nenhuma seção configurada para esta página.</p>
                <p class="text-sm mt-1">As seções aparecerão aqui depois de configuradas no menu <strong>CMS > Seções</strong>.</p>
            </div>
        @endif
    </div>
</div>
@endsection

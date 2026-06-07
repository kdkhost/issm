{{-- @autor marcelo-brad rj --}}
{{-- @contato Tel: 21 981325441 | Email: contato@kdkhost.com.br | Telegram: @MARCELO_BRAD | Instagram: @marcelobradrj | WhatsApp: 21981325441 --}}
@extends("layouts.admin")
@section("title", "Nova Página CMS")
@section("page-title", "Nova Página CMS")
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
    <form method="POST" action="{{ route("admin.cms.pages.store") }}" enctype="multipart/form-data">
        @csrf
        <div class="bg-white rounded-xl shadow-sm p-6 space-y-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Título *</label>
                    <input type="text" name="title" id="title" value="{{ old("title") }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    @error("title")<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Slug *</label>
                    <input type="text" name="slug" id="slug" value="{{ old("slug") }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    @error("slug")<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Conteúdo *</label>
                <textarea name="content" rows="14" class="wysiwyg w-full border border-gray-300 rounded-lg px-3 py-2">{{ old("content") }}</textarea>
                @error("content")<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="draft" {{ old("status") == "draft" ? "selected" : "" }}>Rascunho</option>
                        <option value="published" {{ old("status") == "published" ? "selected" : "" }}>Publicado</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data de Publicação</label>
                    <input type="datetime-local" name="published_at" value="{{ old("published_at") }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Template</label>
                    <select name="template" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="default" {{ old("template") == "default" ? "selected" : "" }}>Padrão</option>
                        <option value="full" {{ old("template") == "full" ? "selected" : "" }}>Largura Total</option>
                        <option value="sidebar" {{ old("template") == "sidebar" ? "selected" : "" }}>Com Sidebar</option>
                        <option value="landing" {{ old("template") == "landing" ? "selected" : "" }}>Landing Page</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Layout</label>
                    <select name="layout" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="default" {{ old("layout") == "default" ? "selected" : "" }}>Padrão</option>
                        <option value="full-width" {{ old("layout") == "full-width" ? "selected" : "" }}>Largura Total</option>
                        <option value="boxed" {{ old("layout") == "boxed" ? "selected" : "" }}>Boxed</option>
                    </select>
                </div>
                <div class="flex items-end gap-6 pb-2">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" id="is_active" {{ old("is_active", "1") ? "checked" : "" }} class="w-4 h-4 text-green-600 rounded">
                        <label for="is_active" class="text-sm font-medium text-gray-700">Ativo</label>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="show_in_menu" value="1" id="show_in_menu" {{ old("show_in_menu") ? "checked" : "" }} class="w-4 h-4 text-green-600 rounded">
                        <label for="show_in_menu" class="text-sm font-medium text-gray-700">Exibir no Menu</label>
                    </div>
                </div>
            </div>
            <div class="flex justify-between pt-4 border-t border-gray-100">
                <a href="{{ route("admin.cms.pages.index") }}" class="text-gray-600 hover:text-gray-800 font-medium">Cancelar</a>
                <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 font-medium">Criar Página</button>
            </div>
        </div>
    </form>
</div>
@endsection

@extends("layouts.admin")
@section("title", "Editar Noticia")
@section("page-title", "Editar Noticia")
@section("content")
<div class="max-w-4xl">
    <form method="POST" action="{{ route("admin.noticias.update", $news) }}" enctype="multipart/form-data">
        @csrf @method("PUT")
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-5">
                <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Titulo *</label><input type="text" name="title" value="{{ old("title", $news->title) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><label class="block text-sm font-medium text-gray-700 mb-1">Palavra em Destaque <small class="text-gray-400 font-normal">(opcional)</small></label><input type="text" name="title_highlight" value="{{ old("title_highlight", $news->title_highlight) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Ex: Sustentável"></div>
                        <div><label class="block text-sm font-medium text-gray-700 mb-1">Cor do Destaque</label><div class="flex items-center gap-2"><input type="color" name="title_highlight_color" value="{{ old("title_highlight_color", $news->title_highlight_color ?? "#86efac") }}" class="w-10 h-10 rounded cursor-pointer border-0 p-0"><input type="text" name="title_highlight_color" value="{{ old("title_highlight_color", $news->title_highlight_color ?? "#86efac") }}" class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="#86efac"></div></div>
                    </div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Resumo</label><textarea name="excerpt" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">{{ old("excerpt", $news->excerpt) }}</textarea></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Conteudo *</label><textarea name="content" rows="12" required class="wysiwyg w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">{{ old("content", $news->content) }}</textarea></div>
                </div>
            </div>
            <div class="space-y-5">
                <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
                    <h3 class="font-semibold text-gray-800">Publicacao</h3>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Data de Publicacao</label><input type="datetime-local" name="published_at" value="{{ old("published_at", $news->published_at ? $news->published_at->format("Y-m-d\TH:i") : "") }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Categoria</label><input type="text" name="category" value="{{ old("category", $news->category) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
                    <div class="flex items-center gap-3"><input type="checkbox" name="featured" value="1" id="featured" {{ $news->featured ? "checked" : "" }} class="w-4 h-4 text-green-600 rounded"><label for="featured" class="text-sm font-medium text-gray-700">Destaque</label></div>
                    <div class="flex items-center gap-3"><input type="checkbox" name="active" value="1" id="active" {{ $news->active ? "checked" : "" }} class="w-4 h-4 text-green-600 rounded"><label for="active" class="text-sm font-medium text-gray-700">Ativo</label></div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6"><h3 class="font-semibold text-gray-800 mb-3">Imagem</h3>@if($news->image)<img src="{{ asset("media/".$news->image) }}" class="w-full h-32 object-cover rounded mb-2">@endif<input type="file" name="image" accept="image/*" class="w-full text-sm text-gray-600"></div>
                <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
                    <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        SEO
                    </h3>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Meta Título</label><input type="text" name="meta_title" value="{{ old("meta_title", $news->meta_title) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="SEO title (padrao: titulo da noticia)"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Meta Descrição</label><textarea name="meta_description" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="SEO description (padrao: resumo)">{{ old("meta_description", $news->meta_description) }}</textarea></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Palavras-chave</label><input type="text" name="meta_keywords" value="{{ old("meta_keywords", $news->meta_keywords) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="keyword1, keyword2, keyword3"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">OG Título</label><input type="text" name="og_title" value="{{ old("og_title", $news->og_title) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Facebook/WhatsApp title (padrao: meta titulo)"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">OG Descrição</label><textarea name="og_description" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Facebook/WhatsApp description (padrao: meta descricao)">{{ old("og_description", $news->og_description) }}</textarea></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">OG Imagem</label>
                        @if($news->og_image)<img src="{{ asset("media/".$news->og_image) }}" class="w-full h-24 object-cover rounded mb-2 border border-gray-200">@endif
                        <input type="file" name="og_image" accept="image/*" class="w-full text-sm text-gray-600"><p class="text-xs text-gray-400 mt-1">Imagem para compartilhamento social (1200x630px). Padrao: imagem da noticia.</p>
                    </div>
                </div>
                <div class="flex justify-between"><a href="{{ route("admin.noticias.index") }}" class="text-gray-600 hover:text-gray-800 font-medium">Cancelar</a><button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 font-medium">Atualizar</button></div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.querySelectorAll('input[type="color"]').forEach(colorInput => {
    const name = colorInput.name;
    const textInput = document.querySelector('input[type="text"][name="' + name + '"');
    if (textInput) {
        colorInput.addEventListener('input', () => textInput.value = colorInput.value);
        textInput.addEventListener('input', () => colorInput.value = textInput.value);
    }
});
</script>
@endpush
@endsection

@extends("layouts.admin")
@section("title", "Editar Notícia")
@section("page-title", "Editar Notícia")
@section("content")
<div class="max-w-5xl">
    <form method="POST" action="{{ route("admin.noticias.update", $news) }}" enctype="multipart/form-data" id="newsForm">
        @csrf @method("PUT")
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-5">
                <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Título *</label><input type="text" name="title" id="title" value="{{ old("title", $news->title) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><label class="block text-sm font-medium text-gray-700 mb-1">Palavra em Destaque <small class="text-gray-400 font-normal">(opcional)</small></label><input type="text" name="title_highlight" value="{{ old("title_highlight", $news->title_highlight) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Ex: Sustentável"></div>
                        <div><label class="block text-sm font-medium text-gray-700 mb-1">Cor do Destaque</label><div class="flex items-center gap-2"><input type="color" name="title_highlight_color" value="{{ old("title_highlight_color", $news->title_highlight_color ?? "#86efac") }}" class="w-10 h-10 rounded cursor-pointer border-0 p-0"><input type="text" name="title_highlight_color" value="{{ old("title_highlight_color", $news->title_highlight_color ?? "#86efac") }}" class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="#86efac"></div></div>
                    </div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Resumo</label><textarea name="excerpt" id="excerpt" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">{{ old("excerpt", $news->excerpt) }}</textarea></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Conteúdo *</label><textarea name="content" id="content" rows="12" required class="wysiwyg w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">{{ old("content", $news->content) }}</textarea></div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
                    <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        SEO
                    </h3>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Meta Título</label><input type="text" name="meta_title" id="meta_title" value="{{ old("meta_title", $news->meta_title) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="SEO title (padrão: título da notícia)"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Meta Descrição</label><textarea name="meta_description" id="meta_description" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="SEO description (padrão: resumo)">{{ old("meta_description", $news->meta_description) }}</textarea></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Palavras-chave</label><input type="text" name="meta_keywords" value="{{ old("meta_keywords", $news->meta_keywords) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="keyword1, keyword2, keyword3"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">OG Título</label><input type="text" name="og_title" id="og_title" value="{{ old("og_title", $news->og_title) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Facebook/WhatsApp title (padrão: meta título)"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">OG Descrição</label><textarea name="og_description" id="og_description" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Facebook/WhatsApp description (padrão: meta descrição)">{{ old("og_description", $news->og_description) }}</textarea></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">OG Imagem</label>
                        @if($news->og_image)<img src="{{ asset("media/".$news->og_image) }}" class="w-full h-24 object-cover rounded mb-2 border border-gray-200" id="existingOgImage">@endif
                        <input type="file" name="og_image" id="og_image" accept="image/*" class="w-full text-sm text-gray-600"><p class="text-xs text-gray-400 mt-1">Imagem para compartilhamento social (1200x630px). Padrão: imagem da notícia.</p>
                    </div>
                </div>
            </div>
            <div class="space-y-5">
                <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
                    <h3 class="font-semibold text-gray-800">Publicação</h3>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Data de Publicação</label><input type="datetime-local" name="published_at" value="{{ old("published_at", $news->published_at ? $news->published_at->format("Y-m-d\TH:i") : "") }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Categoria</label><input type="text" name="category" value="{{ old("category", $news->category) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
                    <div class="flex items-center gap-3"><input type="checkbox" name="featured" value="1" id="featured" {{ $news->featured ? "checked" : "" }} class="w-4 h-4 text-green-600 rounded"><label for="featured" class="text-sm font-medium text-gray-700">Destaque</label></div>
                    <div class="flex items-center gap-3"><input type="checkbox" name="active" value="1" id="active" {{ $news->active ? "checked" : "" }} class="w-4 h-4 text-green-600 rounded"><label for="active" class="text-sm font-medium text-gray-700">Ativo</label></div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6"><h3 class="font-semibold text-gray-800 mb-3">Imagem</h3>@if($news->image)<img src="{{ asset("media/".$news->image) }}" class="w-full h-32 object-cover rounded mb-2" id="existingMainImage">@endif<input type="file" name="image" id="image" accept="image/*" class="w-full text-sm text-gray-600"></div>
                <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
                    <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Preview de Compartilhamento
                    </h3>
                    <div id="seoPreview">
                        <div class="mb-4">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Google</p>
                            <div class="bg-white border border-gray-200 rounded-lg p-3 text-sm">
                                <p class="text-[#1a0dab] text-base font-medium leading-tight truncate" id="googleTitle">{{ $news->meta_title ?? $news->title }}</p>
                                <p class="text-[#006621] text-xs leading-tight truncate">https://issm.org.br/noticias/url-da-noticia</p>
                                <p class="text-[#545454] text-xs leading-tight mt-0.5 line-clamp-2" id="googleDesc">{{ $news->meta_description ?? strip_tags($news->excerpt ?? $news->title) }}</p>
                            </div>
                        </div>
                        <div class="mb-4">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Facebook / Meta</p>
                            <div class="bg-[#f0f2f5] border border-gray-200 rounded-lg overflow-hidden">
                                <div class="bg-gray-200 h-32 flex items-center justify-center text-gray-400 text-xs" id="fbImagePreview">
                                    @if($news->og_image)<img src="{{ asset("media/".$news->og_image) }}" class="w-full h-full object-cover">@elseif($news->image)<img src="{{ asset("media/".$news->image) }}" class="w-full h-full object-cover">@else Pré-visualização da imagem @endif
                                </div>
                                <div class="p-3 bg-white border-t border-gray-200">
                                    <p class="text-[#606770] text-[10px] uppercase tracking-wider">issm.org.br</p>
                                    <p class="font-semibold text-[#1d2129] text-sm leading-tight mt-0.5" id="fbTitle">{{ $news->og_title ?? ($news->meta_title ?? $news->title) }}</p>
                                    <p class="text-[#606770] text-xs leading-tight mt-1 line-clamp-2" id="fbDesc">{{ $news->og_description ?? ($news->meta_description ?? strip_tags($news->excerpt ?? $news->title)) }}</p>
                                </div>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">WhatsApp</p>
                            <div class="bg-[#e5ddd5] border border-gray-200 rounded-lg overflow-hidden">
                                <div class="bg-gray-200 h-24 flex items-center justify-center text-gray-400 text-xs" id="waImagePreview">
                                    @if($news->og_image)<img src="{{ asset("media/".$news->og_image) }}" class="w-full h-full object-cover">@elseif($news->image)<img src="{{ asset("media/".$news->image) }}" class="w-full h-full object-cover">@else Imagem @endif
                                </div>
                                <div class="p-3 bg-[#f0f0f0]">
                                    <p class="font-semibold text-[#111b21] text-sm leading-tight" id="waTitle">{{ $news->og_title ?? ($news->meta_title ?? $news->title) }}</p>
                                    <p class="text-[#667781] text-xs leading-tight mt-1 line-clamp-2" id="waDesc">{{ $news->og_description ?? ($news->meta_description ?? strip_tags($news->excerpt ?? $news->title)) }}</p>
                                    <p class="text-[#667781] text-[10px] mt-1">issm.org.br</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-between"><a href="{{ route("admin.noticias.index") }}" class="text-gray-600 hover:text-gray-800 font-medium">Cancelar</a><button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 font-medium">Atualizar</button></div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
(function() {
    const newsData = {
        title: @json($news->title),
        excerpt: @json(strip_tags($news->excerpt ?? '')),
        meta_title: @json($news->meta_title ?? ''),
        meta_description: @json($news->meta_description ?? ''),
        og_title: @json($news->og_title ?? ''),
        og_description: @json($news->og_description ?? ''),
        og_image: @json($news->og_image ?? ''),
        image: @json($news->image ?? '')
    };

    const title = document.getElementById('title');
    const excerpt = document.getElementById('excerpt');
    const metaTitle = document.getElementById('meta_title');
    const metaDesc = document.getElementById('meta_description');
    const ogTitle = document.getElementById('og_title');
    const ogDesc = document.getElementById('og_description');
    const ogImageInput = document.getElementById('og_image');
    const imageInput = document.getElementById('image');

    const googleTitle = document.getElementById('googleTitle');
    const googleDesc = document.getElementById('googleDesc');
    const fbTitle = document.getElementById('fbTitle');
    const fbDesc = document.getElementById('fbDesc');
    const fbImagePreview = document.getElementById('fbImagePreview');
    const waTitle = document.getElementById('waTitle');
    const waDesc = document.getElementById('waDesc');
    const waImagePreview = document.getElementById('waImagePreview');

    function stripHtml(html) {
        const tmp = document.createElement('div');
        tmp.innerHTML = html;
        return tmp.textContent || tmp.innerText || '';
    }

    function getVal(input) {
        return input ? input.value.trim() : '';
    }

    function updatePreview() {
        const t = getVal(title);
        const e = getVal(excerpt);
        const mt = getVal(metaTitle);
        const md = getVal(metaDesc);
        const ot = getVal(ogTitle);
        const od = getVal(ogDesc);

        const effectiveTitle = mt || t || 'Título da notícia';
        const effectiveDesc = md || e || 'Descrição da notícia aparecerá aqui...';
        const effectiveOgTitle = ot || mt || t || 'Título para compartilhamento';
        const effectiveOgDesc = od || md || e || 'Descrição para compartilhamento aparecerá aqui...';

        googleTitle.textContent = effectiveTitle;
        googleDesc.textContent = effectiveDesc;
        if (effectiveTitle.length > 60) { googleTitle.textContent = effectiveTitle.substring(0, 60) + '...'; }
        if (effectiveDesc.length > 160) { googleDesc.textContent = effectiveDesc.substring(0, 160) + '...'; }

        fbTitle.textContent = effectiveOgTitle;
        fbDesc.textContent = effectiveOgDesc;
        waTitle.textContent = effectiveOgTitle;
        waDesc.textContent = effectiveOgDesc;
    }

    function loadImage(src, previewEl) {
        if (previewEl.querySelector('img')) {
            previewEl.querySelector('img').src = src;
        } else {
            previewEl.innerHTML = '<img src="' + src + '" class="w-full h-full object-cover">';
            previewEl.style.background = 'none';
        }
    }

    function handleFilePreview(input, previewEl) {
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                loadImage(e.target.result, previewEl);
            };
            reader.readAsDataURL(file);
        }
    }

    function getCurrentOgSrc() {
        if (ogImageInput.files.length > 0) {
            return URL.createObjectURL(ogImageInput.files[0]);
        }
        if (newsData.og_image) return '/media/' + newsData.og_image;
        if (imageInput.files.length > 0) return URL.createObjectURL(imageInput.files[0]);
        if (newsData.image) return '/media/' + newsData.image;
        return null;
    }

    function updateImagePreviews() {
        let src = null;
        if (ogImageInput.files.length > 0) {
            src = URL.createObjectURL(ogImageInput.files[0]);
        } else if (newsData.og_image) {
            src = '/media/' + newsData.og_image;
        } else if (imageInput.files.length > 0) {
            src = URL.createObjectURL(imageInput.files[0]);
        } else if (newsData.image) {
            src = '/media/' + newsData.image;
        }

        if (src) {
            [fbImagePreview, waImagePreview].forEach(el => loadImage(src, el));
        }
    }

    title.addEventListener('input', updatePreview);
    excerpt.addEventListener('input', updatePreview);
    metaTitle.addEventListener('input', updatePreview);
    metaDesc.addEventListener('input', updatePreview);
    ogTitle.addEventListener('input', updatePreview);
    ogDesc.addEventListener('input', updatePreview);
    ogImageInput.addEventListener('change', updateImagePreviews);
    imageInput.addEventListener('change', updateImagePreviews);
})();
</script>
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

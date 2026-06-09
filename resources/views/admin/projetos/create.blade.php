@extends("layouts.admin")
@section("title", "Novo Projeto")
@section("page-title", "Novo Projeto")
@section("content")
<div class="max-w-5xl">
    <form method="POST" action="{{ route("admin.projetos.store") }}" enctype="multipart/form-data" id="projectForm">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-5">
                <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Título *</label><input type="text" name="title" id="title" value="{{ old("title") }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Resumo</label><textarea name="excerpt" id="excerpt" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">{{ old("excerpt") }}</textarea></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Conteúdo *</label><textarea name="content" id="content" rows="10" required class="wysiwyg w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">{{ old("content") }}</textarea></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-2">ODS Relacionados</label><div class="grid grid-cols-6 gap-2">@for($i = 1; $i <= 17; $i++)<label class="flex items-center gap-1 cursor-pointer"><input type="checkbox" name="ods_goals[]" value="{{ $i }}" {{ in_array($i, old("ods_goals", [])) ? "checked" : "" }} class="w-3 h-3 text-green-600 rounded"><span class="ods-{{ $i }} text-white text-xs font-bold w-6 h-6 rounded flex items-center justify-center">{{ $i }}</span></label>@endfor</div></div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
                    <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        SEO
                    </h3>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Meta Título</label><input type="text" name="meta_title" id="meta_title" value="{{ old("meta_title") }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="SEO title (padrão: título do projeto)"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Meta Descrição</label><textarea name="meta_description" id="meta_description" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="SEO description (padrão: resumo)">{{ old("meta_description") }}</textarea></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Palavras-chave</label><input type="text" name="meta_keywords" value="{{ old("meta_keywords") }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="keyword1, keyword2, keyword3"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">OG Título</label><input type="text" name="og_title" id="og_title" value="{{ old("og_title") }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Facebook/WhatsApp title (padrão: meta título)"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">OG Descrição</label><textarea name="og_description" id="og_description" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Facebook/WhatsApp description (padrão: meta descrição)">{{ old("og_description") }}</textarea></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">OG Imagem</label><input type="file" name="og_image" id="og_image" accept="image/*" class="w-full text-sm text-gray-600"><p class="text-xs text-gray-400 mt-1">Imagem para compartilhamento social (1200x630px). Padrão: imagem do projeto.</p></div>
                </div>
            </div>
            <div class="space-y-5">
                <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
                    <h3 class="font-semibold text-gray-800">Detalhes</h3>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Categoria</label><input type="text" name="category" value="{{ old("category") }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Status</label><select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"><option value="active" selected>Ativo</option><option value="completed">Concluído</option><option value="planned">Planejado</option></select></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Localização</label><input type="text" name="location" value="{{ old("location") }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
                    <div class="grid grid-cols-2 gap-2">
                        <div><label class="block text-sm font-medium text-gray-700 mb-1">Início</label><input type="date" name="start_date" value="{{ old("start_date") }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
                        <div><label class="block text-sm font-medium text-gray-700 mb-1">Fim</label><input type="date" name="end_date" value="{{ old("end_date") }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
                    </div>
                    <div class="flex items-center gap-3"><input type="checkbox" name="featured" value="1" id="featured" class="w-4 h-4 text-green-600 rounded"><label for="featured" class="text-sm font-medium text-gray-700">Destaque</label></div>
                    <div class="flex items-center gap-3"><input type="checkbox" name="active" value="1" id="active" checked class="w-4 h-4 text-green-600 rounded"><label for="active" class="text-sm font-medium text-gray-700">Ativo</label></div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6"><h3 class="font-semibold text-gray-800 mb-3">Imagem</h3><input type="file" name="image" id="image" accept="image/*" class="w-full text-sm text-gray-600"></div>
                <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
                    <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Preview de Compartilhamento
                    </h3>
                    <div id="seoPreview">
                        <div class="mb-4">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Google</p>
                            <div class="bg-white border border-gray-200 rounded-lg p-3 text-sm">
                                <p class="text-[#1a0dab] text-base font-medium leading-tight truncate" id="googleTitle">Título do projeto</p>
                                <p class="text-[#006621] text-xs leading-tight truncate">https://issm.org.br/projetos/url-do-projeto</p>
                                <p class="text-[#545454] text-xs leading-tight mt-0.5 line-clamp-2" id="googleDesc">Descrição do projeto aparecerá aqui...</p>
                            </div>
                        </div>
                        <div class="mb-4">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Facebook / Meta</p>
                            <div class="bg-[#f0f2f5] border border-gray-200 rounded-lg overflow-hidden">
                                <div class="bg-gray-200 h-32 flex items-center justify-center text-gray-400 text-xs" id="fbImagePreview">Pré-visualização da imagem</div>
                                <div class="p-3 bg-white border-t border-gray-200">
                                    <p class="text-[#606770] text-[10px] uppercase tracking-wider">issm.org.br</p>
                                    <p class="font-semibold text-[#1d2129] text-sm leading-tight mt-0.5" id="fbTitle">Título para compartilhamento</p>
                                    <p class="text-[#606770] text-xs leading-tight mt-1 line-clamp-2" id="fbDesc">Descrição para compartilhamento aparecerá aqui...</p>
                                </div>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">WhatsApp</p>
                            <div class="bg-[#e5ddd5] border border-gray-200 rounded-lg overflow-hidden">
                                <div class="bg-gray-200 h-24 flex items-center justify-center text-gray-400 text-xs" id="waImagePreview">Imagem</div>
                                <div class="p-3 bg-[#f0f0f0]">
                                    <p class="font-semibold text-[#111b21] text-sm leading-tight" id="waTitle">Título do link</p>
                                    <p class="text-[#667781] text-xs leading-tight mt-1 line-clamp-2" id="waDesc">Descrição do link...</p>
                                    <p class="text-[#667781] text-[10px] mt-1">issm.org.br</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-between"><a href="{{ route("admin.projetos.index") }}" class="text-gray-600 hover:text-gray-800 font-medium">Cancelar</a><button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 font-medium">Criar</button></div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
(function() {
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

        const effectiveTitle = mt || t || 'Título do projeto';
        const effectiveDesc = md || e || 'Descrição do projeto aparecerá aqui...';
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

    function handleFilePreview(input, previewEl) {
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewEl.innerHTML = '<img src="' + e.target.result + '" class="w-full h-full object-cover">';
                previewEl.style.background = 'none';
            };
            reader.readAsDataURL(file);
        }
    }

    function handleImagePreviews() {
        const ogFile = ogImageInput.files[0];
        const mainFile = imageInput.files[0];
        const previewFile = ogFile || mainFile;

        if (previewFile) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const html = '<img src="' + e.target.result + '" class="w-full h-full object-cover">';
                fbImagePreview.innerHTML = html;
                fbImagePreview.style.background = 'none';
                waImagePreview.innerHTML = html;
                waImagePreview.style.background = 'none';
            };
            reader.readAsDataURL(previewFile);
        }
    }

    title.addEventListener('input', updatePreview);
    excerpt.addEventListener('input', updatePreview);
    metaTitle.addEventListener('input', updatePreview);
    metaDesc.addEventListener('input', updatePreview);
    ogTitle.addEventListener('input', updatePreview);
    ogDesc.addEventListener('input', updatePreview);
    ogImageInput.addEventListener('change', handleImagePreviews);
    imageInput.addEventListener('change', handleImagePreviews);

    updatePreview();
})();
</script>
@endpush
@endsection

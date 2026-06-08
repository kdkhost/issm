@extends("layouts.admin")
@section("title", "Nova Pagina")
@section("page-title", "Nova Pagina")

@section("content")
<div class="mb-4">
    <a href="{{ route('admin.paginas.index') }}" class="text-sm text-green-700 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">&larr; Voltar</a>
</div>

<form id="pageForm" method="POST" action="{{ route('admin.paginas.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="grid grid-cols-1 xl:grid-cols-5 gap-5">

        {{-- Formulario --}}
        <div class="xl:col-span-3 space-y-4">

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 space-y-4 border border-gray-100 dark:border-gray-700">
                <h3 class="font-bold text-gray-800 dark:text-gray-100 text-sm">Conteudo da Pagina</h3>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Titulo *</label>
                    <input id="ptitle" type="text" name="title" value="{{ old('title') }}" required class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Conteudo *</label>
                    <textarea name="content" rows="10" required class="wysiwyg w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500">{{ old('content') }}</textarea>
                </div>
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-2"><input type="checkbox" name="active" value="1" id="active" checked class="w-4 h-4 text-green-600 rounded"><label for="active" class="text-sm font-medium text-gray-700 dark:text-gray-300">Ativo</label></div>
                    <div class="flex items-center gap-2"><input type="checkbox" name="show_in_menu" value="1" id="show_in_menu" class="w-4 h-4 text-green-600 rounded"><label for="show_in_menu" class="text-sm font-medium text-gray-700 dark:text-gray-300">Exibir no Menu</label></div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 space-y-4 border border-gray-100 dark:border-gray-700">
                <h3 class="font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2 text-sm">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Meta Tags (Google)
                </h3>
                <div>
                    <label class="flex justify-between text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><span>Meta Title</span><span id="ct" class="text-xs">0 / 60</span></label>
                    <input id="mt" type="text" name="meta_title" value="{{ old('meta_title') }}" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="flex justify-between text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><span>Meta Description</span><span id="cd" class="text-xs">0 / 160</span></label>
                    <textarea id="md" name="meta_description" rows="3" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 resize-none">{{ old('meta_description') }}</textarea>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 space-y-4 border border-gray-100 dark:border-gray-700">
                <h3 class="font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2 text-sm">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    Open Graph (Redes Sociais)
                </h3>
                <div>
                    <label class="flex justify-between text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><span>OG Title</span><span id="cot" class="text-xs">0 / 60</span></label>
                    <input id="ot" type="text" name="og_title" value="{{ old('og_title') }}" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="flex justify-between text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><span>OG Description</span><span id="cod" class="text-xs">0 / 200</span></label>
                    <textarea id="od" name="og_description" rows="2" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 resize-none">{{ old('og_description') }}</textarea>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 block">OG Image</label>
                    <input id="oi" type="text" name="og_image" value="{{ old('og_image') }}" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500" placeholder="media/banners/imagem.jpg">
                    <p class="text-xs text-gray-500 mt-1">Dimensao ideal: 1200 x 630px</p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 space-y-4 border border-gray-100 dark:border-gray-700">
                <h3 class="font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2 text-sm">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Configuracoes Avancadas
                </h3>
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 block">URL Canonica</label>
                    <input id="cu" type="url" name="canonical_url" value="{{ old('canonical_url') }}" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 block">Robots Meta</label>
                    <select id="rm" name="robots_meta" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500">
                        <option value="index, follow" {{ old('robots_meta', 'index, follow') == 'index, follow' ? 'selected' : '' }}>index, follow</option>
                        <option value="index, nofollow" {{ old('robots_meta', '') == 'index, nofollow' ? 'selected' : '' }}>index, nofollow</option>
                        <option value="noindex, follow" {{ old('robots_meta', '') == 'noindex, follow' ? 'selected' : '' }}>noindex, follow</option>
                        <option value="noindex, nofollow" {{ old('robots_meta', '') == 'noindex, nofollow' ? 'selected' : '' }}>noindex, nofollow</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.paginas.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors text-sm">Cancelar</a>
                <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 font-medium text-sm transition-colors">Criar</button>
            </div>
        </div>

        {{-- Preview + Score --}}
        <div class="xl:col-span-2 space-y-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-100 dark:border-gray-700 sticky top-4">
                <h3 class="font-bold text-gray-800 dark:text-gray-100 mb-3 text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Pontuacao SEO
                </h3>
                <div class="flex items-center gap-4 mb-3">
                    <div class="relative w-16 h-16 flex items-center justify-center">
                        <svg class="w-16 h-16 transform -rotate-90" viewBox="0 0 36 36">
                            <path class="text-gray-200 dark:text-gray-600" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="3"/>
                            <path id="ring" class="text-gray-300" stroke-dasharray="0, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="3"/>
                        </svg>
                        <span id="sv" class="absolute text-xl font-bold text-gray-400">0</span>
                    </div>
                    <div>
                        <p id="sl" class="text-sm font-medium text-gray-700 dark:text-gray-300">Preencha os campos SEO</p>
                        <p class="text-xs text-gray-500">de 100 pontos</p>
                    </div>
                </div>
                <div id="tips" class="space-y-1 text-xs"></div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-100 dark:border-gray-700">
                <h4 class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Google</h4>
                <div class="font-sans">
                    <div id="pgt" class="text-[#1a0dab] dark:text-[#8ab4f8] text-base leading-tight truncate hover:underline cursor-pointer">Titulo da Pagina</div>
                    <div id="pgu" class="text-xs text-[#006621] dark:text-[#34a853] mt-0.5">{{ url('/') }}/pagina/slug</div>
                    <p id="pgd" class="text-sm text-[#4d5156] dark:text-[#bdc1c6] leading-snug mt-1 line-clamp-2">Descricao...</p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-100 dark:border-gray-700">
                <h4 class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Facebook / LinkedIn</h4>
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                    <div id="pfi" class="bg-gray-200 dark:bg-gray-700 h-28 bg-cover bg-center" style="background-image: url('{{ asset('media/' . \App\Models\Setting::get('og_image', 'logo.png')) }}')"></div>
                    <div class="p-3 bg-white dark:bg-gray-900">
                        <p id="pfu" class="text-[10px] text-gray-500 dark:text-gray-400 uppercase truncate">{{ strtoupper(parse_url(url('/'), PHP_URL_HOST)) }}</p>
                        <h5 id="pft" class="text-sm font-bold text-gray-900 dark:text-gray-100 truncate mt-0.5">Titulo da Pagina</h5>
                        <p id="pfd" class="text-xs text-gray-500 dark:text-gray-400 leading-snug mt-0.5 line-clamp-2">Descricao...</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-100 dark:border-gray-700">
                <h4 class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">WhatsApp / Telegram</h4>
                <div class="flex gap-3 bg-[#e1ffc7] dark:bg-[#1a2e1a] p-3 rounded-lg rounded-tl-none">
                    <div id="pwi" class="w-12 h-12 rounded bg-gray-300 dark:bg-gray-600 bg-cover bg-center shrink-0" style="background-image: url('{{ asset('media/' . \App\Models\Setting::get('og_image', 'logo.png')) }}')"></div>
                    <div class="min-w-0">
                        <p id="pwt" class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">Titulo da Pagina</p>
                        <p id="pwd" class="text-xs text-gray-600 dark:text-gray-300 line-clamp-2">Descricao...</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate mt-0.5">{{ url('/') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
(function() {
    const d = document;
    const el = {
        mt: d.getElementById('mt'), md: d.getElementById('md'),
        ot: d.getElementById('ot'), od: d.getElementById('od'), oi: d.getElementById('oi'),
        cu: d.getElementById('cu'), rm: d.getElementById('rm'),
        ct: d.getElementById('ct'), cd: d.getElementById('cd'),
        cot: d.getElementById('cot'), cod: d.getElementById('cod'),
        ring: d.getElementById('ring'), sv: d.getElementById('sv'), sl: d.getElementById('sl'), tips: d.getElementById('tips'),
        pgt: d.getElementById('pgt'), pgd: d.getElementById('pgd'),
        pfi: d.getElementById('pfi'), pft: d.getElementById('pft'), pfd: d.getElementById('pfd'),
        pwi: d.getElementById('pwi'), pwt: d.getElementById('pwt'), pwd: d.getElementById('pwd'),
    };
    const base = '{{ url('/') }}', defImg = '{{ asset("media/" . \App\Models\Setting::get("og_image", "logo.png")) }}';

    function cnt(elm, len, min, max) {
        elm.textContent = len + ' / ' + max;
        elm.className = 'text-xs ' + (len === 0 ? 'text-gray-400' : (len < min || len > max) ? 'text-red-500 font-bold' : 'text-green-500 font-bold');
    }

    function score() {
        let s = 0, t = [];
        const mt = el.mt.value.trim(), md = el.md.value.trim(), ot = el.ot.value.trim();
        const od = el.od.value.trim(), oi = el.oi.value.trim(), cu = el.cu.value.trim(), rm = el.rm.value.trim();

        if (mt) { s += 15; } else { t.push('Adicione um Meta Title'); }
        if (mt.length >= 50 && mt.length <= 60) { s += 10; } else if (mt) { t.push('Meta Title ideal: 50-60 chars (atual: ' + mt.length + ')'); }
        if (md) { s += 15; } else { t.push('Adicione uma Meta Description'); }
        if (md.length >= 120 && md.length <= 160) { s += 10; } else if (md) { t.push('Meta Description ideal: 120-160 chars (atual: ' + md.length + ')'); }
        if (ot) { s += 10; } else { t.push('Adicione um OG Title'); }
        if (od) { s += 10; } else { t.push('Adicione uma OG Description'); }
        if (oi) { s += 15; } else { t.push('Adicione uma OG Image (1200x630)'); }
        if (cu) { s += 5; } else { t.push('Adicione URL Canonica'); }
        if (rm) { s += 5; } else { t.push('Defina Robots Meta'); }

        s = Math.min(s, 100);
        el.ring.setAttribute('stroke-dasharray', s + ', 100');
        el.sv.textContent = s;
        const col = s >= 80 ? 'text-green-500' : (s >= 50 ? 'text-yellow-500' : 'text-red-500');
        el.sv.className = 'absolute text-xl font-bold ' + col;
        el.ring.className = col;
        el.sl.textContent = s >= 80 ? 'Excelente' : (s >= 50 ? 'Bom' : 'Precisa melhorar');
        el.tips.innerHTML = t.length === 0
            ? '<p class="text-green-600 dark:text-green-400 flex items-center gap-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>SEO otimo!</p>'
            : t.map(x => '<p class="text-gray-600 dark:text-gray-400 flex items-start gap-1"><svg class="w-3 h-3 mt-0.5 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg><span>' + x + '</span></p>').join('');
    }

    function preview() {
        const mt = el.mt.value.trim() || 'Titulo da Pagina';
        const md = el.md.value.trim() || 'Descricao...';
        const ot = el.ot.value.trim() || mt;
        const od = el.od.value.trim() || md;
        const img = el.oi.value.trim() ? (base + '/media/' + el.oi.value.trim()) : defImg;
        el.pgt.textContent = mt; el.pgd.textContent = md;
        el.pfi.style.backgroundImage = 'url(' + img + ')'; el.pft.textContent = ot; el.pfd.textContent = od;
        el.pwi.style.backgroundImage = 'url(' + img + ')'; el.pwt.textContent = ot; el.pwd.textContent = od;
    }

    function run() {
        cnt(el.ct, el.mt.value.length, 50, 60);
        cnt(el.cd, el.md.value.length, 120, 160);
        cnt(el.cot, el.ot.value.length, 50, 60);
        cnt(el.cod, el.od.value.length, 100, 200);
        score(); preview();
    }

    ['input', 'change', 'keyup'].forEach(e => {
        el.mt.addEventListener(e, run); el.md.addEventListener(e, run);
        el.ot.addEventListener(e, run); el.od.addEventListener(e, run);
        el.oi.addEventListener(e, run); el.cu.addEventListener(e, run); el.rm.addEventListener(e, run);
    });
    run();
})();
</script>
@endpush

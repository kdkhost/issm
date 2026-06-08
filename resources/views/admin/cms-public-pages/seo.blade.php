@extends("layouts.admin")
@section("title", "SEO — " . $page->admin_label)
@section("page-title", "SEO: " . $page->admin_label)

@section("content")
@php
    $seoScore = $page->seo?->seo_score ?? 0;
    $scoreColor = $seoScore >= 80 ? 'text-green-500' : ($seoScore >= 50 ? 'text-yellow-500' : 'text-red-500');
    $scoreRing  = $seoScore >= 80 ? 'text-green-500' : ($seoScore >= 50 ? 'text-yellow-500' : 'text-red-500');
@endphp

<div class="mb-6 flex justify-between items-center">
    <div>
        <a href="{{ route('admin.cms-public-pages.index') }}" class="text-sm text-green-700 hover:text-green-900">&larr; Voltar</a>
        <p class="text-xs text-gray-500 mt-1">View: {{ $page->view_path }} | URL: {{ $page->route_uri }}</p>
    </div>
    @if($page->publicUrl())
    <a href="{{ $page->publicUrl() }}" target="_blank" class="text-sm text-blue-600 hover:text-blue-800 flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
        Visualizar pagina
    </a>
    @endif
</div>

{{-- Abas --}}
<div class="flex gap-1 mb-6 border-b border-gray-200">
    <a href="{{ route('admin.cms-public-pages.edit', $page) }}" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-300">
        Campos/Secoes
    </a>
    <a href="{{ route('admin.cms-public-pages.edit-full-html', $page) }}" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-300">
        HTML Completo
    </a>
    @if($page->seo_enabled)
    <a href="{{ route('admin.cms-public-pages.seo', $page) }}" class="px-4 py-2 text-sm font-medium text-green-700 border-b-2 border-green-700 bg-green-50 rounded-t-lg">
        SEO
    </a>
    @endif
</div>

<form id="seoForm" method="POST" action="{{ route('admin.cms-public-pages.update-seo', $page) }}">
    @csrf @method('PUT')

    <div class="grid grid-cols-1 xl:grid-cols-5 gap-6">

        {{-- Formulario --}}
        <div class="xl:col-span-3 space-y-0">

            <div class="bg-white rounded-xl shadow-sm mb-6 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-bold text-gray-800">Meta Tags (Google)</h3>
                </div>
                <div class="p-6 space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1 flex justify-between"><span>Meta Title</span><span id="ct" style="color:#9ca3af;font-weight:400">0 / 60</span></label>
                        <input id="mt" name="meta_title" type="text" value="{{ old('meta_title', $page->seo?->meta_title) }}" placeholder="Titulo para SEO (max. 60 caracteres)">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1 flex justify-between"><span>Meta Description</span><span id="cd" style="color:#9ca3af;font-weight:400">0 / 160</span></label>
                        <textarea id="md" name="meta_description" rows="3" placeholder="Descricao para SEO (max. 160 caracteres)">{{ old('meta_description', $page->seo?->meta_description) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Meta Keywords</label>
                        <input id="mk" name="meta_keywords" type="text" value="{{ old('meta_keywords', $page->seo?->meta_keywords) }}" placeholder="palavra-chave1, palavra-chave2...">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1 flex justify-between"><span>SEO Tags Persistentes</span><span id="cst" style="color:#9ca3af;font-weight:400">0 tags</span></label>
                        <input id="st" name="seo_tags" type="text" value="{{ old('seo_tags', $page->seo?->seo_tags) }}" placeholder="#issm, #meioambiente, #sustentabilidade, #ods2030, #floresta, #preservacao">
                        <p class="text-xs text-gray-500 mt-1">Adicione hashtags separadas por virgula. Essas tags sao combinadas com as keywords para melhorar a indexacao do Google.</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm mb-6 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-bold text-gray-800">Open Graph (Redes Sociais)</h3>
                </div>
                <div class="p-6 space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1 flex justify-between"><span>OG Title</span><span id="cot" style="color:#9ca3af;font-weight:400">0 / 60</span></label>
                        <input id="ot" name="og_title" type="text" value="{{ old('og_title', $page->seo?->og_title) }}" placeholder="Ex: og titulo">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1 flex justify-between"><span>OG Description</span><span id="cod" style="color:#9ca3af;font-weight:400">0 / 200</span></label>
                        <textarea id="od" name="og_description" rows="2" placeholder="Ex: og descricao">{{ old('og_description', $page->seo?->og_description) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">OG Image</label>
                        <input id="oi" name="og_image" type="text" value="{{ old('og_image', $page->seo?->og_image) }}" placeholder="media/banners/imagem.jpg">
                        <p class="text-xs text-gray-500 mt-1">Dimensao ideal: 1200 x 630px</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm mb-6 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-bold text-gray-800">Configuracoes Avancadas</h3>
                </div>
                <div class="p-6 space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">URL Canonica</label>
                        <input id="cu" name="canonical_url" type="url" value="{{ old('canonical_url', $page->seo?->canonical_url ?? $page->publicUrl()) }}">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Robots Meta</label>
                        <select id="rm" name="robots_meta">
                            <option value="index, follow" {{ old('robots_meta', $page->seo?->robots_meta ?? 'index, follow') == 'index, follow' ? 'selected' : '' }}>index, follow</option>
                            <option value="index, nofollow" {{ old('robots_meta', $page->seo?->robots_meta ?? '') == 'index, nofollow' ? 'selected' : '' }}>index, nofollow</option>
                            <option value="noindex, follow" {{ old('robots_meta', $page->seo?->robots_meta ?? '') == 'noindex, follow' ? 'selected' : '' }}>noindex, follow</option>
                            <option value="noindex, nofollow" {{ old('robots_meta', $page->seo?->robots_meta ?? '') == 'noindex, nofollow' ? 'selected' : '' }}>noindex, nofollow</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 mb-6">
                <a href="{{ route('admin.cms-public-pages.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-sm">Voltar</a>
                <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 font-medium text-sm">Salvar SEO</button>
            </div>
        </div>

        {{-- Preview + Score --}}
        <div class="xl:col-span-2 space-y-4">
            <div class="bg-white rounded-xl shadow-sm p-5 sticky top-4">
                <h3 class="font-bold text-gray-800 mb-3 text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Pontuacao SEO
                </h3>
                <div class="flex items-center gap-4 mb-3">
                    <div class="relative w-16 h-16 flex items-center justify-center">
                        <svg class="w-16 h-16 transform -rotate-90" viewBox="0 0 36 36">
                            <path class="text-gray-200" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="3"/>
                            <path id="ring" class="{{ $scoreRing }}" stroke-dasharray="{{ $seoScore }}, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="3"/>
                        </svg>
                        <span id="sv" class="absolute text-xl font-bold {{ $scoreColor }}">{{ $seoScore }}</span>
                    </div>
                    <div>
                        <p id="sl" class="text-sm font-medium text-gray-700">{{ $seoScore >= 80 ? 'Excelente' : ($seoScore >= 50 ? 'Bom' : 'Precisa melhorar') }}</p>
                        <p class="text-xs text-gray-500">de 100 pontos</p>
                    </div>
                </div>
                <div id="tips" class="space-y-1 text-xs"></div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-4">
                <h4 class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Google</h4>
                <div class="font-sans">
                    <div id="pgt" class="text-[#1a0dab] text-base leading-tight truncate hover:underline cursor-pointer">{{ $page->seo?->meta_title ?: $page->title ?: 'Titulo' }}</div>
                    <div id="pgu" class="text-xs text-[#006621] mt-0.5">{{ url('/') }}{{ $page->publicUrl() ? parse_url($page->publicUrl(), PHP_URL_PATH) : '' }}</div>
                    <p id="pgd" class="text-sm text-[#4d5156] leading-snug mt-1 line-clamp-2">{{ $page->seo?->meta_description ?: 'Descricao...' }}</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-4">
                <h4 class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Facebook / LinkedIn</h4>
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div id="pfi" class="bg-gray-200 h-28 bg-cover bg-center" style="background-image: url('{{ $page->seo?->og_image ? asset('media/' . $page->seo?->og_image) : asset('media/' . \App\Models\Setting::get('og_image', 'logo.png')) }}')"></div>
                    <div class="p-3 bg-white">
                        <p id="pfu" class="text-[10px] text-gray-500 uppercase truncate">{{ strtoupper(parse_url(url('/'), PHP_URL_HOST)) }}</p>
                        <h5 id="pft" class="text-sm font-bold text-gray-900 truncate mt-0.5">{{ $page->seo?->og_title ?: $page->seo?->meta_title ?: $page->title ?: 'Titulo' }}</h5>
                        <p id="pfd" class="text-xs text-gray-500 leading-snug mt-0.5 line-clamp-2">{{ $page->seo?->og_description ?: $page->seo?->meta_description ?: 'Descricao...' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-4">
                <h4 class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">WhatsApp / Telegram</h4>
                <div class="flex gap-3 bg-[#e1ffc7] p-3 rounded-lg rounded-tl-none">
                    <div id="pwi" class="w-12 h-12 rounded bg-gray-300 bg-cover bg-center shrink-0" style="background-image: url('{{ $page->seo?->og_image ? asset('media/' . $page->seo?->og_image) : asset('media/' . \App\Models\Setting::get('og_image', 'logo.png')) }}')"></div>
                    <div class="min-w-0">
                        <p id="pwt" class="text-sm font-medium text-gray-900 truncate">{{ $page->seo?->og_title ?: $page->seo?->meta_title ?: $page->title ?: 'Titulo' }}</p>
                        <p id="pwd" class="text-xs text-gray-600 line-clamp-2">{{ $page->seo?->og_description ?: $page->seo?->meta_description ?: 'Descricao...' }}</p>
                        <p class="text-xs text-gray-500 truncate mt-0.5">{{ url('/') }}</p>
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
        mt: d.getElementById('mt'), md: d.getElementById('md'), mk: d.getElementById('mk'), st: d.getElementById('st'),
        ot: d.getElementById('ot'), od: d.getElementById('od'), oi: d.getElementById('oi'),
        cu: d.getElementById('cu'), rm: d.getElementById('rm'),
        ct: d.getElementById('ct'), cd: d.getElementById('cd'), cst: d.getElementById('cst'),
        cot: d.getElementById('cot'), cod: d.getElementById('cod'),
        ring: d.getElementById('ring'), sv: d.getElementById('sv'), sl: d.getElementById('sl'), tips: d.getElementById('tips'),
        pgt: d.getElementById('pgt'), pgd: d.getElementById('pgd'),
        pfi: d.getElementById('pfi'), pft: d.getElementById('pft'), pfd: d.getElementById('pfd'),
        pwi: d.getElementById('pwi'), pwt: d.getElementById('pwt'), pwd: d.getElementById('pwd'),
    };
    const base = '{{ url('/') }}', defImg = '{{ asset("media/" . \App\Models\Setting::get("og_image", "logo.png")) }}', title = '{{ $page->title }}';

    function cnt(elm, len, min, max) {
        elm.textContent = len + ' / ' + max;
        elm.className = 'text-xs ' + (len === 0 ? 'text-gray-400' : (len < min || len > max) ? 'text-red-500 font-bold' : 'text-green-500 font-bold');
    }

    function score() {
        let s = 0, t = [];
        const mt = el.mt.value.trim(), md = el.md.value.trim(), ot = el.ot.value.trim();
        const od = el.od.value.trim(), oi = el.oi.value.trim(), mk = el.mk.value.trim();
        const st = el.st.value.trim(), cu = el.cu.value.trim(), rm = el.rm.value.trim();

        if (mt) { s += 15; } else { t.push('Adicione um Meta Title'); }
        if (mt.length >= 50 && mt.length <= 60) { s += 10; } else if (mt) { t.push('Meta Title ideal: 50-60 chars (atual: ' + mt.length + ')'); }
        if (md) { s += 15; } else { t.push('Adicione uma Meta Description'); }
        if (md.length >= 120 && md.length <= 160) { s += 10; } else if (md) { t.push('Meta Description ideal: 120-160 chars (atual: ' + md.length + ')'); }
        if (ot) { s += 10; } else { t.push('Adicione um OG Title'); }
        if (od) { s += 10; } else { t.push('Adicione uma OG Description'); }
        if (oi) { s += 15; } else { t.push('Adicione uma OG Image (1200x630)'); }
        if (mk) { s += 5; } else { t.push('Adicione Meta Keywords'); }
        if (st) { s += 5; } else { t.push('Adicione SEO Tags persistentes'); }
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
        const mt = el.mt.value.trim() || title || 'Titulo';
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
        const tagCount = el.st.value.split(',').filter(x => x.trim()).length;
        el.cst.textContent = tagCount + ' tag' + (tagCount !== 1 ? 's' : '');
        el.cst.style.color = tagCount > 0 ? '#16a34a' : '#9ca3af';
        cnt(el.cot, el.ot.value.length, 50, 60);
        cnt(el.cod, el.od.value.length, 100, 200);
        score(); preview();
    }

    ['input', 'change', 'keyup'].forEach(e => {
        el.mt.addEventListener(e, run); el.md.addEventListener(e, run);
        el.ot.addEventListener(e, run); el.od.addEventListener(e, run);
        el.oi.addEventListener(e, run); el.mk.addEventListener(e, run); el.st.addEventListener(e, run);
        el.cu.addEventListener(e, run); el.rm.addEventListener(e, run);
    });
    run();
})();
</script>
@endpush

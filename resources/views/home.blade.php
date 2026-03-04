@extends("layouts.app")
@section("title", "Inicio - ISSM")

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
<style>
/* Carrossel de Parceiros */
.partners-swiper { padding: 48px 4px 48px; overflow: visible !important; }
.partners-swiper .swiper-wrapper { overflow: visible !important; }
.partners-swiper .swiper-slide { display: flex; justify-content: center; align-items: center; overflow: visible !important; }
.partners-swiper .swiper-pagination-bullet { background: #d1fae5; opacity: 1; width: 8px; height: 8px; }
.partners-swiper .swiper-pagination-bullet-active { background: #15803d; width: 24px; border-radius: 4px; }
.partners-swiper .swiper-button-prev,
.partners-swiper .swiper-button-next { color: #15803d; width: 32px; height: 32px; }
.partners-swiper .swiper-button-prev::after,
.partners-swiper .swiper-button-next::after { font-size: 14px; }
.partner-logo-wrap { display:flex; align-items:center; justify-content:center;
    filter: grayscale(100%); transition: filter .3s ease, transform .3s ease; }
.partner-logo-wrap:hover { filter: grayscale(0%); transform: scale(1.06); }

/* Tooltip customizado de parceiros */
.partner-tooltip-wrap { position: relative; }
.partner-tooltip {
    pointer-events: none;
    position: absolute;
    bottom: calc(100% + 10px);
    left: 50%;
    transform: translateX(-50%) translateY(4px);
    white-space: nowrap;
    background: #111827;
    color: #f9fafb;
    font-size: 12px;
    font-weight: 600;
    line-height: 1;
    padding: 6px 12px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
    opacity: 0;
    transition: opacity .2s ease, transform .2s ease;
    z-index: 50;
    box-shadow: 0 4px 12px rgba(0,0,0,.25);
}
.partner-tooltip::after {
    content: '';
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    border: 5px solid transparent;
    border-top-color: #111827;
}
.partner-tooltip--nolink { background: #374151; }
.partner-tooltip--nolink::after { border-top-color: #374151; }
.partner-tooltip-icon { width: 12px; height: 12px; flex-shrink: 0; color: #6ee7b7; }
.partner-tooltip-wrap:hover .partner-tooltip {
    opacity: 1;
    transform: translateX(-50%) translateY(0);
}
/* Cursor pointer só quando há link */
a.partner-logo-wrap { cursor: pointer; }
</style>
@endpush

@section("content")

@if($banners->count() > 0)
<section class="relative overflow-hidden">
    <div id="banner-slider">
        @foreach($banners as $index => $banner)
        <div class="banner-slide {{ $index > 0 ? "hidden" : "" }} relative min-h-[500px] lg:min-h-[600px] flex items-center {{ !$banner->image ? "hero-gradient" : "" }}"
             @if($banner->image) style="background-image:url({{ asset("media/".$banner->image) }});background-size:cover;background-position:center;" @endif>
            @if($banner->image)<div class="absolute inset-0 bg-black/50"></div>@endif
            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
                <h1 class="text-4xl lg:text-6xl font-black text-white leading-tight mb-4">{{ $banner->title }}</h1>
                @if($banner->subtitle)<p class="text-xl text-green-100 mb-8 max-w-2xl">{{ $banner->subtitle }}</p>@endif
                @if($banner->button_text && $banner->button_url)
                <a href="{{ $banner->button_url }}" class="inline-block bg-white text-green-800 font-bold px-8 py-3 rounded-full hover:bg-green-50 shadow-lg">{{ $banner->button_text }}</a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @if($banners->count() > 1)
    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-20">
        @foreach($banners as $index => $banner)
        <button class="banner-dot w-3 h-3 rounded-full {{ $index === 0 ? "bg-white" : "bg-white/50" }}" data-index="{{ $index }}"></button>
        @endforeach
    </div>
    @endif
</section>
@else
@php
    $heroBg = \App\Models\Setting::get('hero_bg_image');
    $heroOpacity = (int) (\App\Models\Setting::get('hero_overlay_opacity') ?: 70);
    $heroOpacity = max(0, min(100, $heroOpacity));
    $heroAlpha = round($heroOpacity / 100, 2);
@endphp
<section
    style="position:relative;min-height:500px;display:flex;align-items:center;overflow:hidden;
    @if($heroBg)
        background-image:url({{ asset('media/'.$heroBg) }});background-size:cover;background-position:center;
    @else
        background:linear-gradient(135deg, #14532d 0%, #15803d 50%, #059669 100%);
    @endif
    ">
    {{-- Degrade overlay (sempre presente quando tem imagem) --}}
    @if($heroBg)
    <div style="position:absolute;inset:0;background:linear-gradient(135deg,
        rgba(20,83,45,{{ $heroAlpha }}),
        rgba(21,128,61,{{ $heroAlpha * 0.88 }}),
        rgba(5,150,105,{{ $heroAlpha * 0.75 }}));z-index:1;"></div>
    @endif
    <div style="position:relative;z-index:10;max-width:80rem;margin:0 auto;padding:5rem 1rem;text-align:center;width:100%;">
        <h1 class="text-4xl lg:text-6xl font-black text-white leading-tight mb-4">Instituto Socioambiental<br>Serra do Mendanha</h1>
        <p class="text-xl text-green-100 mb-8">Comprometidos com a preservacao ambiental e o desenvolvimento sustentavel</p>
        <a href="#sobre" class="inline-block bg-white text-green-800 font-bold px-8 py-3 rounded-full hover:bg-green-50 shadow-lg">Conheca o ISSM</a>
    </div>
</section>
@endif

<section class="bg-green-800 text-white py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div><p class="text-3xl font-black">17</p><p class="text-green-300 text-sm">ODS Alinhados</p></div>
            <div><p class="text-3xl font-black">2030</p><p class="text-green-300 text-sm">Meta Global</p></div>
            <div><p class="text-3xl font-black">{{ $featuredProjects->count() + 10 }}+</p><p class="text-green-300 text-sm">Projetos Ativos</p></div>
            <div><p class="text-3xl font-black">RJ</p><p class="text-green-300 text-sm">Serra do Mendanha</p></div>
        </div>
    </div>
</section>

@if($settings["show_about"])
<section id="sobre" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <span class="text-green-600 font-semibold text-sm uppercase tracking-wider">Quem Somos</span>
                <h2 class="text-3xl lg:text-4xl font-black text-gray-900 mt-2 mb-6">Instituto Socioambiental<br><span class="text-green-700">Serra do Mendanha</span></h2>
                <p class="text-gray-600 leading-relaxed mb-6">{{ $settings["about_text"] }}</p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-green-50 rounded-xl p-4 border-l-4 border-green-600"><h4 class="font-bold text-green-800 mb-2">Missao</h4><p class="text-gray-600 text-sm">{{ $settings["mission"] }}</p></div>
                    <div class="bg-blue-50 rounded-xl p-4 border-l-4 border-blue-600"><h4 class="font-bold text-blue-800 mb-2">Visao</h4><p class="text-gray-600 text-sm">{{ $settings["vision"] }}</p></div>
                    <div class="bg-yellow-50 rounded-xl p-4 border-l-4 border-yellow-600"><h4 class="font-bold text-yellow-800 mb-2">Valores</h4><p class="text-gray-600 text-sm">{{ $settings["values"] }}</p></div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-green-100 to-green-200 rounded-2xl p-8 text-center">
                <div class="w-32 h-32 bg-gradient-to-br from-green-700 to-green-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-xl" style="overflow:hidden;">
                    @php $odsLogo = \App\Models\Setting::get('site_logo'); @endphp
                    @if($odsLogo)
                    <img src="{{ asset('media/' . $odsLogo) }}" alt="ISSM" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                    @else
                    <span class="text-white font-black text-4xl">ISSM</span>
                    @endif
                </div>
                <h3 class="text-2xl font-black text-green-800 mb-2">ODS 2030</h3>
                <p class="text-green-700">Metas para um mundo melhor</p>
                <div class="grid grid-cols-5 gap-2 mt-6">
                    @foreach(range(1,17) as $n)
                    <div class="ods-{{ $n }} w-8 h-8 rounded flex items-center justify-center text-white text-xs font-bold">{{ $n }}</div>
                    @endforeach
                    <div class="w-8 h-8"></div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

@if($odsList->count() > 0)
<section id="ods" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="text-green-600 font-semibold text-sm uppercase tracking-wider">Agenda 2030</span>
            <h2 class="text-3xl lg:text-4xl font-black text-gray-900 mt-2">Objetivos de Desenvolvimento<br><span class="text-green-700">Sustentavel - ODS</span></h2>
            <p class="text-gray-600 mt-4 max-w-2xl mx-auto">O ISSM atua alinhado com os 17 Objetivos de Desenvolvimento Sustentavel da ONU, contribuindo para um mundo mais justo e sustentavel ate 2030.</p>
        </div>
        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-9 gap-3">
            @foreach($odsList as $ods)
            <div class="rounded-xl p-3 text-white text-center card-hover cursor-default" style="background-color: {{ $ods->color }}" title="{{ $ods->title }}">
                <p class="text-2xl font-black">{{ $ods->number }}</p>
                <p class="text-xs font-medium leading-tight mt-1">{{ $ods->title }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($featuredProjects->count() > 0)
<section id="projetos" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-end mb-12">
            <div>
                <span class="text-green-600 font-semibold text-sm uppercase tracking-wider">O que fazemos</span>
                <h2 class="text-3xl lg:text-4xl font-black text-gray-900 mt-2">Nossos <span class="text-green-700">Projetos</span></h2>
            </div>
            <a href="{{ route("projects.index") }}" class="text-green-700 hover:text-green-900 font-medium flex items-center gap-1">Ver todos <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($featuredProjects as $project)
            <article class="bg-white rounded-2xl shadow-md overflow-hidden card-hover border border-gray-100">
                @if($project->image)<img src="{{ asset("media/".$project->image) }}" alt="{{ $project->title }}" class="w-full h-48 object-cover">@else<div class="w-full h-48 bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center"><svg class="w-16 h-16 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>@endif
                <div class="p-6">
                    @if($project->category)<span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded-full">{{ $project->category }}</span>@endif
                    <h3 class="text-xl font-bold text-gray-900 mt-3 mb-2">{{ $project->title }}</h3>
                    <p class="text-gray-600 text-sm leading-relaxed mb-4">{{ $project->excerpt ?? Str::limit(strip_tags($project->content), 120) }}</p>
                    @if($project->ods_goals)
                    <div class="flex flex-wrap gap-1 mb-4">
                        @foreach(array_slice($project->ods_goals, 0, 5) as $odsNum)
                        <span class="ods-{{ $odsNum }} text-white text-xs font-bold w-6 h-6 rounded flex items-center justify-center">{{ $odsNum }}</span>
                        @endforeach
                    </div>
                    @endif
                    <a href="{{ route("projects.show", $project->slug) }}" class="text-green-700 hover:text-green-900 font-medium text-sm flex items-center gap-1">Saiba mais <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></a>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($latestNews->count() > 0)
<section id="noticias" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-end mb-12">
            <div>
                <span class="text-green-600 font-semibold text-sm uppercase tracking-wider">Fique por dentro</span>
                <h2 class="text-3xl lg:text-4xl font-black text-gray-900 mt-2">Ultimas <span class="text-green-700">Noticias</span></h2>
            </div>
            <a href="{{ route("news.index") }}" class="text-green-700 hover:text-green-900 font-medium flex items-center gap-1">Ver todas <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($latestNews as $news)
            <article class="bg-white rounded-2xl shadow-md overflow-hidden card-hover border border-gray-100">
                @if($news->image)<img src="{{ asset("media/".$news->image) }}" alt="{{ $news->title }}" class="w-full h-48 object-cover">@else<div class="w-full h-48 bg-gradient-to-br from-blue-50 to-green-50 flex items-center justify-center"><svg class="w-16 h-16 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg></div>@endif
                <div class="p-6">
                    @if($news->category)<span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">{{ $news->category }}</span>@endif
                    <h3 class="text-xl font-bold text-gray-900 mt-3 mb-2">{{ $news->title }}</h3>
                    <p class="text-gray-600 text-sm leading-relaxed mb-4">{{ $news->excerpt ?? Str::limit(strip_tags($news->content), 120) }}</p>
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-400">{{ $news->published_at ? $news->published_at->format("d/m/Y") : "" }}</span>
                        <a href="{{ route("news.show", $news->slug) }}" class="text-green-700 hover:text-green-900 font-medium text-sm flex items-center gap-1">Ler mais <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></a>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($teamMembers->count() > 0)
<section id="equipe" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="text-green-600 font-semibold text-sm uppercase tracking-wider">Pessoas</span>
            <h2 class="text-3xl lg:text-4xl font-black text-gray-900 mt-2">Nossa <span class="text-green-700">Equipe</span></h2>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
            @foreach($teamMembers as $member)
            <div class="text-center">
                <div class="w-24 h-24 mx-auto rounded-full overflow-hidden mb-3 shadow-md">
                    @if($member->photo)<img src="{{ asset("media/".$member->photo) }}" alt="{{ $member->name }}" class="w-full h-full object-cover">@else<div class="w-full h-full bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center"><span class="text-green-700 font-black text-2xl">{{ substr($member->name, 0, 1) }}</span></div>@endif
                </div>
                <h4 class="font-bold text-gray-900 text-sm">{{ $member->name }}</h4>
                <p class="text-green-600 text-xs">{{ $member->role }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($galleryItems->count() > 0)
<section id="galeria" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-end mb-12">
            <div>
                <span class="text-green-600 font-semibold text-sm uppercase tracking-wider">Registros</span>
                <h2 class="text-3xl lg:text-4xl font-black text-gray-900 mt-2">Nossa <span class="text-green-700">Galeria</span></h2>
            </div>
            <a href="{{ route('gallery.index') }}" class="text-green-700 hover:text-green-900 font-medium flex items-center gap-1">Ver tudo <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($galleryItems->take(4) as $item)
            <a href="{{ route('gallery.index') }}" class="group overflow-hidden rounded-xl shadow-md block">
                <img src="{{ asset('media/'.$item->image) }}" alt="{{ $item->title }}"
                     class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
            </a>
            @endforeach
        </div>
        @if($galleryItems->count() > 4)
        <div class="text-center mt-8">
            <a href="{{ route('gallery.index') }}" class="inline-flex items-center gap-2 bg-green-700 hover:bg-green-800 text-white font-semibold px-8 py-3 rounded-full transition-colors shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Ver galeria completa ({{ $galleryItems->count() }}+ fotos)
            </a>
        </div>
        @endif
    </div>
</section>
@endif

@if($partners->count() > 0)
@php
    $pAutoplay = \App\Models\Setting::get('partners_carousel_autoplay', '1') == '1';
    $pSpeed    = (int)(\App\Models\Setting::get('partners_carousel_speed', '3000'));
    $pLoop     = \App\Models\Setting::get('partners_carousel_loop', '1') == '1';
    $pDots     = \App\Models\Setting::get('partners_carousel_dots', '1') == '1';
    $pArrows   = \App\Models\Setting::get('partners_carousel_arrows', '0') == '1';
    $pPerView  = max(1, (int)(\App\Models\Setting::get('partners_carousel_per_view', '4')));
    $pEffect   = in_array(\App\Models\Setting::get('partners_carousel_effect','slide'), ['slide','fade','coverflow']) ? \App\Models\Setting::get('partners_carousel_effect','slide') : 'slide';
    $pLogoH    = max(40, (int)(\App\Models\Setting::get('partners_logo_height', '64')));
    if ($pEffect === 'fade') $pPerView = 1;
@endphp
<section id="parceiros" class="py-16 bg-white" style="overflow-x:clip; overflow-y:visible;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-2xl font-black text-gray-700">Nossos <span class="text-green-700">Parceiros</span></h2>
        </div>
        <div class="partners-swiper swiper" id="partners-swiper">
            <div class="swiper-wrapper">
                @foreach($partners as $partner)
                @php $partnerUrl = $partner->url ?? null; @endphp
                <div class="swiper-slide flex items-center justify-center">
                    @if($partner->logo)
                        @if($partnerUrl)
                        <a href="{{ $partnerUrl }}"
                           target="_blank" rel="noopener noreferrer"
                           class="partner-logo-wrap partner-tooltip-wrap">
                            <img src="{{ asset('media/' . $partner->logo) }}"
                                 alt="{{ $partner->name }}"
                                 style="height:{{ $pLogoH }}px; width:160px; object-fit:contain;">
                            <span class="partner-tooltip">
                                <svg xmlns="http://www.w3.org/2000/svg" class="partner-tooltip-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                {{ $partner->name }}
                            </span>
                        </a>
                        @else
                        <div class="partner-logo-wrap partner-tooltip-wrap" style="cursor:default;">
                            <img src="{{ asset('media/' . $partner->logo) }}"
                                 alt="{{ $partner->name }}"
                                 style="height:{{ $pLogoH }}px; width:160px; object-fit:contain;">
                            <span class="partner-tooltip partner-tooltip--nolink">
                                {{ $partner->name }}
                            </span>
                        </div>
                        @endif
                    @else
                    <div class="partner-logo-wrap bg-gray-100 rounded-lg px-6 text-gray-600 font-semibold"
                         style="height:{{ $pLogoH }}px;">
                        {{ $partner->name }}
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            @if($pDots)
            <div class="swiper-pagination partners-dots"></div>
            @endif
            @if($pArrows)
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
            @endif
        </div>
    </div>
</section>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
(function() {
    var effect      = '{{ $pEffect }}';
    var speed       = {{ $pSpeed }};
    var autoplay    = {{ $pAutoplay ? 'true' : 'false' }};
    var loopWanted  = {{ $pLoop ? 'true' : 'false' }};
    var perView     = {{ $pPerView }};
    var dots        = {{ $pDots ? 'true' : 'false' }};
    var arrows      = {{ $pArrows ? 'true' : 'false' }};
    var totalSlides = {{ $partners->count() }};

    // Limita perView ao total de slides existentes, mas sem penalizar por causa do loop.
    // O loop é tratado separadamente com canLoop.
    function safePerView(desired) {
        return Math.max(1, Math.min(desired, totalSlides));
    }

    var pv0   = safePerView(Math.min(2, perView));
    var pv640 = safePerView(Math.min(3, perView));
    var pv1024= safePerView(perView);

    // Loop só funciona no Swiper 11 se houver slides suficientes (>= slidesPerView * 2)
    // e o usuário tiver habilitado o loop nas configurações.
    var canLoop = loopWanted && totalSlides >= pv1024 * 2;

    var swiperConfig = {
        effect: effect,
        loop: canLoop,
        rewind: !canLoop,   // rewind como fallback elegante quando não há loop
        speed: 600,
        centeredSlides: false,
        grabCursor: true,
        autoplay: autoplay ? { delay: speed, disableOnInteraction: false, pauseOnMouseEnter: true } : false,
        pagination: dots ? { el: '.partners-dots', clickable: true, dynamicBullets: false } : false,
        navigation: arrows ? { nextEl: '.partners-swiper .swiper-button-next', prevEl: '.partners-swiper .swiper-button-prev' } : false,
        breakpoints: {
            0:    { slidesPerView: pv0,    spaceBetween: 24 },
            640:  { slidesPerView: pv640,  spaceBetween: 32 },
            1024: { slidesPerView: pv1024, spaceBetween: 40 }
        }
    };

    if (effect === 'fade' || effect === 'coverflow') {
        delete swiperConfig.breakpoints;
        swiperConfig.slidesPerView = 1;
        swiperConfig.spaceBetween = 0;
    }

    new Swiper('#partners-swiper', swiperConfig);
})();
</script>
@endpush
@endif

@if($settings["show_contact"])
<section id="contato" class="py-20 bg-green-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <div>
                <span class="text-green-300 font-semibold text-sm uppercase tracking-wider">Fale Conosco</span>
                <h2 class="text-3xl lg:text-4xl font-black text-white mt-2 mb-6">Entre em <span class="text-green-300">Contato</span></h2>
                <p class="text-green-200 mb-8">Tem alguma duvida, sugestao ou quer saber mais sobre nossos projetos? Entre em contato conosco!</p>
                <div class="space-y-4">
                    @if($settings["contact_email"])<div class="flex items-center gap-3"><div class="w-10 h-10 bg-green-700 rounded-full flex items-center justify-center flex-shrink-0"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div><span class="text-green-100">{{ $settings["contact_email"] }}</span></div>@endif
                    @if($settings["contact_phone"])<div class="flex items-center gap-3"><div class="w-10 h-10 bg-green-700 rounded-full flex items-center justify-center flex-shrink-0"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg></div><span class="text-green-100">{{ $settings["contact_phone"] }}</span></div>@endif
                    @if($settings["contact_address"])<div class="flex items-center gap-3"><div class="w-10 h-10 bg-green-700 rounded-full flex items-center justify-center flex-shrink-0"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div><span class="text-green-100">{{ $settings["contact_address"] }}</span></div>@endif
                </div>
                <div class="flex gap-4 mt-8">
                    @if($settings["social_facebook"])<a href="{{ $settings["social_facebook"] }}" target="_blank" class="w-10 h-10 bg-green-700 rounded-full flex items-center justify-center hover:bg-green-600 transition-colors"><svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>@endif
                    @if($settings["social_instagram"])<a href="{{ $settings["social_instagram"] }}" target="_blank" class="w-10 h-10 bg-green-700 rounded-full flex items-center justify-center hover:bg-green-600 transition-colors"><svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg></a>@endif
                    @if($settings["social_youtube"])<a href="{{ $settings["social_youtube"] }}" target="_blank" class="w-10 h-10 bg-green-700 rounded-full flex items-center justify-center hover:bg-green-600 transition-colors"><svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M23.495 6.205a3.007 3.007 0 00-2.088-2.088c-1.87-.501-9.396-.501-9.396-.501s-7.507-.01-9.396.501A3.007 3.007 0 00.527 6.205a31.247 31.247 0 00-.522 5.805 31.247 31.247 0 00.522 5.783 3.007 3.007 0 002.088 2.088c1.868.502 9.396.502 9.396.502s7.506 0 9.396-.502a3.007 3.007 0 002.088-2.088 31.247 31.247 0 00.5-5.783 31.247 31.247 0 00-.5-5.805zM9.609 15.601V8.408l6.264 3.602z"/></svg></a>@endif
                </div>
            </div>
            <div>
                @if(session("success"))<div class="bg-green-700 border border-green-500 text-white px-4 py-3 rounded-lg mb-4">{{ session("success") }}</div>@endif
                <form action="{{ route("contact.store") }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div><input type="text" name="name" placeholder="Seu nome *" required class="w-full bg-green-800 border border-green-700 text-white placeholder-green-400 rounded-lg px-4 py-3 focus:outline-none focus:border-green-400" value="{{ old("name") }}">@error("name")<p class="text-red-300 text-xs mt-1">{{ $message }}</p>@enderror</div>
                        <div><input type="email" name="email" placeholder="Seu e-mail *" required class="w-full bg-green-800 border border-green-700 text-white placeholder-green-400 rounded-lg px-4 py-3 focus:outline-none focus:border-green-400" value="{{ old("email") }}">@error("email")<p class="text-red-300 text-xs mt-1">{{ $message }}</p>@enderror</div>
                    </div>
                    <div><input type="tel" name="phone" placeholder="Telefone" class="w-full bg-green-800 border border-green-700 text-white placeholder-green-400 rounded-lg px-4 py-3 focus:outline-none focus:border-green-400" value="{{ old("phone") }}"></div>
                    <div><input type="text" name="subject" placeholder="Assunto *" required class="w-full bg-green-800 border border-green-700 text-white placeholder-green-400 rounded-lg px-4 py-3 focus:outline-none focus:border-green-400" value="{{ old("subject") }}">@error("subject")<p class="text-red-300 text-xs mt-1">{{ $message }}</p>@enderror</div>
                    <div><textarea name="message" placeholder="Sua mensagem *" required rows="5" class="w-full bg-green-800 border border-green-700 text-white placeholder-green-400 rounded-lg px-4 py-3 focus:outline-none focus:border-green-400">{{ old("message") }}</textarea>@error("message")<p class="text-red-300 text-xs mt-1">{{ $message }}</p>@enderror</div>
                    @php $recaptchaKey = \App\Models\Setting::get('recaptcha_site_key'); @endphp
                    @if($recaptchaKey)
                    <div class="flex justify-center sm:justify-start">
                        <div class="g-recaptcha" data-sitekey="{{ $recaptchaKey }}" data-theme="dark"></div>
                    </div>
                    @error('g-recaptcha-response')<p class="text-red-300 text-xs -mt-2">Por favor, confirme que você não é um robô.</p>@enderror
                    @endif
                    <button type="submit" class="w-full bg-white text-green-900 font-bold py-3 rounded-lg hover:bg-green-50 transition-colors">Enviar Mensagem</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endif

<script>
const slides = document.querySelectorAll(".banner-slide");
const dots = document.querySelectorAll(".banner-dot");
let current = 0;
if (slides.length > 1) {
    setInterval(() => {
        slides[current].classList.add("hidden");
        if(dots[current]) dots[current].classList.replace("bg-white", "bg-white/50");
        current = (current + 1) % slides.length;
        slides[current].classList.remove("hidden");
        if(dots[current]) dots[current].classList.replace("bg-white/50", "bg-white");
    }, 5000);
    dots.forEach((dot, i) => {
        dot.addEventListener("click", () => {
            slides[current].classList.add("hidden");
            if(dots[current]) dots[current].classList.replace("bg-white", "bg-white/50");
            current = i;
            slides[current].classList.remove("hidden");
            if(dots[current]) dots[current].classList.replace("bg-white/50", "bg-white");
        });
    });
}
</script>
@endsection

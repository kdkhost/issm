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

/* Animações e estilo ODS */
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(12px); }
    to { opacity: 1; transform: translateY(0); }
}

.ods-fadeup {
    animation: fadeUp 0.5s ease-out forwards;
    opacity: 0;
}

.ods-bg-shape {
    position: absolute;
    border-radius: 50%;
    filter: blur(64px);
    opacity: 0.45;
    transform: translateZ(0);
}

.ods-card {
    transition: transform 200ms ease, box-shadow 200ms ease;
}

.ods-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 18px 35px rgba(0,0,0,.12);
}

.ods-showcase {
    position: relative;
    overflow: hidden;
    padding: 36px 24px 28px;
}

.ods-showcase-shell {
    max-width: 1760px;
    margin: 0 auto;
}

.ods-showcase-wave {
    display: flex;
    justify-content: center;
    margin: -4px auto 20px;
    opacity: 0.4;
}

.ods-showcase-wave svg {
    width: min(520px, 42vw);
    height: auto;
}

.ods-showcase-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 16px;
}

.ods-card--panel {
    width: 100%;
    position: relative;
    aspect-ratio: 1 / 1;
    display: flex;
    flex-direction: column;
    align-items: stretch;
    justify-content: flex-start;
    appearance: none;
    -webkit-appearance: none;
    padding: 18px 16px 14px;
    border: 1px solid rgba(0, 0, 0, 0.18);
    border-radius: 0;
    background: var(--ods-color, #15803d);
    box-shadow: 4px 4px 0 rgba(0, 46, 61, 0.45);
    overflow: hidden;
    cursor: pointer;
    text-align: left;
    font: inherit;
}

.ods-card--panel::before {
    content: "";
    position: absolute;
    inset: 0;
    background:
        linear-gradient(180deg, rgba(0, 0, 0, 0.18) 0%, rgba(0, 0, 0, 0.30) 100%),
        var(--ods-color, #15803d);
    pointer-events: none;
}

.ods-card--panel::after {
    content: "";
    position: absolute;
    inset: 12px;
    background-image: var(--ods-card-image);
    background-repeat: no-repeat;
    background-position: center center;
    background-size: 92%;
    opacity: var(--ods-card-image-opacity, 0.34);
    pointer-events: none;
}

.ods-card--panel:hover {
    transform: translateY(-4px);
    box-shadow: 8px 10px 0 rgba(0, 46, 61, 0.32);
}

.ods-card-number {
    position: relative;
    z-index: 1;
    display: block;
    color: #fff;
    font-size: clamp(2.4rem, 2.4vw, 4rem);
    line-height: 1;
    font-weight: 900;
    letter-spacing: -0.04em;
}

.ods-card-title {
    position: relative;
    z-index: 1;
    display: block;
    margin-top: 8px;
    max-width: 76%;
    color: #fff;
    font-size: clamp(0.84rem, 0.6vw, 1.1rem);
    font-weight: 800;
    line-height: 1.1;
    letter-spacing: -0.02em;
    text-transform: uppercase;
    white-space: pre-line;
    text-align: left;
}

.ods-card-icon {
    margin-top: auto;
    position: relative;
    min-height: 46%;
    z-index: 1;
}

.ods-card-icon svg {
    width: 52%;
    height: auto;
}

.ods-brand-block {
    display: flex;
    align-items: flex-end;
    justify-content: center;
    min-height: 150px;
    padding: 12px 8px;
}

.ods-brand-mark {
    max-width: min(88%, 420px);
    max-height: 190px;
    object-fit: contain;
}

.ods-brand-fallback {
    color: rgba(255, 255, 255, 0.9);
    font-size: clamp(2rem, 3vw, 3.6rem);
    font-weight: 900;
    letter-spacing: 0.08em;
}

.ods-modal-panel {
    border-radius: 28px;
    overflow: hidden;
    box-shadow: 0 28px 70px rgba(0, 0, 0, 0.28);
}

.ods-modal-header {
    position: relative;
    padding: 30px 88px 34px 32px;
}

.ods-modal-number {
    display: inline-block;
    margin-bottom: 10px;
    font-size: 0.95rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: rgba(255, 255, 255, 0.86);
}

.ods-modal-title {
    margin: 0 0 14px;
    font-size: clamp(2rem, 2vw, 2.9rem);
    line-height: 1.02;
    font-weight: 900;
    letter-spacing: -0.03em;
    color: #fff;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.14);
}

.ods-modal-summary {
    margin: 0;
    max-width: 90%;
    font-size: clamp(1rem, 1.15vw, 1.18rem);
    line-height: 1.6;
    color: rgba(255, 255, 255, 0.92);
}

.ods-modal-close {
    position: absolute;
    top: 20px;
    right: 20px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 44px;
    height: 44px;
    border: 0;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.16);
    color: #fff;
    box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.24);
    backdrop-filter: blur(6px);
    transition: background 180ms ease, transform 180ms ease;
}

.ods-modal-close:hover {
    background: rgba(255, 255, 255, 0.24);
    transform: scale(1.04);
}

.ods-modal-close:focus-visible {
    outline: 2px solid rgba(255, 255, 255, 0.8);
    outline-offset: 2px;
}

@media (max-width: 640px) {
    .ods-modal-header {
        padding: 24px 72px 26px 22px;
    }

    .ods-modal-close {
        top: 14px;
        right: 14px;
        width: 40px;
        height: 40px;
    }
}

@media (min-width: 768px) {
    .ods-showcase {
        padding: 40px 28px 32px;
    }

    .ods-showcase-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 20px;
    }

    .ods-card--panel {
        padding: 20px 18px 16px;
    }
}

@media (min-width: 1024px) {
    .ods-showcase {
        padding: 20px 20px 28px;
    }

    .ods-showcase-grid {
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 22px;
    }

    .ods-showcase-wave {
        margin-bottom: 14px;
    }

    .ods-card-title {
        max-width: 78%;
    }

    .ods-brand-block {
        grid-column: 3 / span 3;
        min-height: 220px;
        justify-content: flex-end;
        padding-right: 56px;
    }
}

/* Depoimentos */
.testimonials-swiper { padding-bottom: 50px !important; }
.testimonial-card {
    background: #fff;
    border-radius: 24px;
    padding: 32px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    border: 1px solid #f1f5f9;
    height: 100%;
}
.testimonial-quote {
    color: #15803d;
    opacity: 0.15;
    position: absolute;
    top: 20px;
    right: 32px;
}

/* FAQ Accordion */
.faq-item {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #f1f5f9;
    margin-bottom: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
}
.faq-item:hover { border-color: #dcfce7; box-shadow: 0 4px 20px rgba(22,163,74,0.05); }
.faq-button {
    width: 100%;
    padding: 20px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    text-align: left;
    font-weight: 700;
    color: #1f2937;
    transition: color 0.2s;
}
.faq-button.active { color: #15803d; }
.faq-icon {
    width: 20px;
    height: 20px;
    transition: transform 0.3s ease;
}
.faq-button.active .faq-icon { transform: rotate(180deg); }
.faq-content {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease-out, padding 0.3s ease;
    padding: 0 24px;
}
.faq-content.active { max-height: 500px; padding-bottom: 24px; }
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
        <a href="{{ route('about.index') }}" class="inline-block bg-white text-green-800 font-bold px-8 py-3 rounded-full hover:bg-green-50 shadow-lg">Conheca o ISSM</a>
    </div>
</section>
@endif

<section class="bg-green-800 text-white py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            @foreach($homeStats as $stat)
            <div>
                <p class="text-3xl font-black">{{ $stat['value'] }}{{ $stat['suffix'] }}</p>
                <p class="text-green-300 text-sm">{{ $stat['label'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

@include("public.cms._sections")

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

@if($testimonials->count() > 0)
<section class="py-24 bg-gray-50 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-green-600 font-semibold text-sm uppercase tracking-wider">Impacto Real</span>
            <h2 class="text-3xl lg:text-4xl font-black text-gray-900 mt-2">O que dizem <span class="text-green-700">Sobre Nós</span></h2>
        </div>

        <div class="testimonials-swiper swiper" id="testimonials-swiper">
            <div class="swiper-wrapper">
                @foreach($testimonials as $testimonial)
                <div class="swiper-slide h-auto">
                    <div class="testimonial-card relative h-full flex flex-col">
                        <svg class="testimonial-quote w-12 h-12" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21L14.017 18C14.017 16.895 14.912 16 16.017 16L19.017 16C19.569 16 20.017 15.552 20.017 15L20.017 11C20.017 10.448 19.569 10 19.017 10L15.017 10C13.912 10 13.017 9.105 13.017 8L13.017 5C13.017 3.895 13.912 3 15.017 3L18.017 3C19.122 3 20.017 3.895 20.017 5L20.017 6L22.017 6L22.017 5C22.017 2.239 19.778 0 17.017 0L15.017 0C12.256 0 10.017 2.239 10.017 5L10.017 8C10.017 10.761 12.256 13 15.017 13L18.017 13C18.017 13.552 17.569 14 17.017 14L16.017 14C13.808 14 12.017 15.791 12.017 18L12.017 21L14.017 21ZM4.017 21L4.017 18C4.017 16.895 4.912 16 6.017 16L9.017 16C9.569 16 10.017 15.552 10.017 15L10.017 11C10.017 10.448 9.569 10 9.017 10L5.017 10C3.912 10 3.017 9.105 3.017 8L3.017 5C3.017 3.895 3.912 3 5.017 3L8.017 3C9.122 3 10.017 3.895 10.017 5L10.017 6L12.017 6L12.017 5C12.017 2.239 9.778 0 7.017 0L5.017 0C2.256 0 0.017 2.239 0.017 5L0.017 8C0.017 10.761 2.256 13 5.017 13L8.017 13C8.017 13.552 7.569 14 7.017 14L6.017 14C3.808 14 2.017 15.791 2.017 18L2.017 21L4.017 21Z"/></svg>
                        <p class="text-gray-600 italic leading-relaxed mb-8 flex-grow">"{{ $testimonial->content }}"</p>
                        <div class="flex items-center gap-4">
                            @if($testimonial->photo)
                            <img src="{{ asset('media/'.$testimonial->photo) }}" class="w-14 h-14 rounded-full object-cover shadow-md">
                            @else
                            <div class="w-14 h-14 rounded-full bg-green-100 flex items-center justify-center text-green-700">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            @endif
                            <div>
                                <h4 class="font-black text-gray-900 leading-tight">{{ $testimonial->name }}</h4>
                                <p class="text-green-600 text-sm font-bold">{{ $testimonial->role }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="swiper-pagination testimonials-dots"></div>
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
                <h2 class="text-3xl lg:text-4xl font-black text-gray-900 mt-2">Últimas <span class="text-green-700">Notícias</span></h2>
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

@if($faqs->count() > 0)
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-green-600 font-semibold text-sm uppercase tracking-wider">Dúvidas Comuns</span>
            <h2 class="text-3xl lg:text-4xl font-black text-gray-900 mt-2">Perguntas <span class="text-green-700">Frequentes</span></h2>
        </div>
        
        <div class="space-y-4">
            @foreach($faqs as $faq)
            <div class="faq-item">
                <button class="faq-button" onclick="this.classList.toggle('active'); this.nextElementSibling.classList.toggle('active')">
                    <span>{{ $faq->question }}</span>
                    <svg class="faq-icon text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div class="faq-content">
                    <p class="text-gray-600 leading-relaxed">{{ $faq->answer }}</p>
                </div>
            </div>
            @endforeach
        </div>
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

// Swiper Depoimentos
(function() {
    new Swiper('#testimonials-swiper', {
        slidesPerView: 1,
        spaceBetween: 24,
        loop: true,
        autoplay: { delay: 5000, disableOnInteraction: false },
        pagination: { el: '.testimonials-dots', clickable: true },
        breakpoints: {
            640: { slidesPerView: 2 },
            1024: { slidesPerView: 3 }
        }
    });
})();

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

// ODS modal
(function () {
    const modal = document.getElementById('odsModal');
    const modalClose = document.getElementById('odsModalClose');
    const header    = document.getElementById('odsModalHeader');
    const number    = document.getElementById('odsModalNumber');
    const title     = document.getElementById('odsModalTitle');
    const desc      = document.getElementById('odsModalDescription');

    function closeModal() {
        if (!modal) return;
        modal.classList.add('hidden');
        modal.setAttribute('aria-hidden', 'true');
    }

    function openModal(card) {
        if (!modal || !card) return;

        const modalColor = card.dataset.modalColor;
        const modalTitle = card.dataset.modalTitle;
        const modalNumber = card.dataset.modalNumber;
        const modalDescription = card.dataset.modalDescription;

        if (header) {
            header.style.backgroundColor = modalColor || '#0f766e';
        }
        if (number) number.textContent = `ODS ${modalNumber || ''}`;
        if (title) title.textContent = modalTitle || '';
        if (desc) desc.textContent = modalDescription || '';

        modal.classList.remove('hidden');
        modal.setAttribute('aria-hidden', 'false');
        if (modalClose) modalClose.focus();
    }

    document.querySelectorAll('.ods-card').forEach(card => {
        card.addEventListener('click', () => openModal(card));
        card.addEventListener('keydown', (event) => {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                openModal(card);
            }
        });
    });

    if (modalClose) {
        modalClose.addEventListener('click', closeModal);
    }

    if (modal) {
        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                closeModal();
            }
        });
    }

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeModal();
        }
    });
})();
</script>
@endpush
@endif

@endsection

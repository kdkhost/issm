@extends("layouts.app")
@section("title", "Sobre o ISSM - Instituto Socioambiental Serra do Mendanha")

@push("styles")
<style>
.page-stat {
    display:inline-flex; align-items:center; gap:6px;
    background:rgba(255,255,255,.1); padding:6px 14px; border-radius:24px;
    font-size:13px; color:#fff; font-weight:500;
}
.page-stat svg { width:16px; height:16px; opacity:.8; }
</style>
@endpush

@php
$cmsPage = cms_page('about');
@endphp

@section("content")

@if($cmsPage && $cmsPage->use_custom_html)
    {!! $cmsPage->custom_html !!}
@else

@php
$g1 = cms('about', 'hero', 'gradient_start', '#166534');
$g2 = cms('about', 'hero', 'gradient_mid', '#15803d');
$g3 = cms('about', 'hero', 'gradient_end', '#059669');
$bcColor = cms('about', 'hero', 'breadcrumb_color', '#86efac');
$titleHighlight = cms('about', 'hero', 'title_highlight', 'Serra do Mendanha');
$titleColor = cms('about', 'hero', 'title_highlight_color', '#86efac');
$subColor = cms('about', 'hero', 'subtitle_color', '#bbf7d0');
@endphp

{{-- Hero Banner Premium --}}
<div style="background:linear-gradient(135deg,{{ $g1 }} 0%,{{ $g2 }} 50%,{{ $g3 }} 100%);padding:56px 0 40px;">
    <div style="max-width:1280px;margin:0 auto;padding:0 16px;">
        <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:{{ $bcColor }};margin-bottom:16px;">
            <a href="{{ route('home') }}" style="color:{{ $bcColor }};text-decoration:none;transition:color .2s;">Início</a>
            <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span style="color:#fff;">{{ cms('about', 'hero', 'breadcrumb', 'Sobre o ISSM') }}</span>
        </div>
        @php
            $fullTitle = cms('about', 'hero', 'title', 'Instituto Socioambiental');
        @endphp
        <h1 style="font-size:clamp(2rem,5vw,3rem);font-weight:900;color:#fff;line-height:1.1;margin-bottom:8px;">
            @if($titleHighlight && str_contains($fullTitle, $titleHighlight))
                {!! str_replace($titleHighlight, '<span style="color:'.$titleColor.'">'.$titleHighlight.'</span>', e($fullTitle)) !!}
            @else
                {{ $fullTitle }}
            @endif
        </h1>
        <p style="font-size:16px;color:{{ $subColor }};max-width:600px;margin-bottom:20px;">
            {{ cms('about', 'hero', 'subtitle', 'Conheça nossa história, missão e o compromisso com a preservação da Serra do Mendanha.') }}
        </p>
        <div style="display:flex;flex-wrap:wrap;gap:10px;">
            <div class="page-stat">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                {{ $teamMembers->count() }} {{ cms('about', 'hero', 'stat_label', 'Colaboradores ativos') }}
            </div>
        </div>
    </div>
</div>

<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div>
                <span class="text-green-600 font-semibold text-sm uppercase tracking-wider">{{ cms('about', 'identity', 'eyebrow', 'Nossa Identidade') }}</span>
                <h2 class="text-3xl lg:text-4xl font-black text-gray-900 mt-2 mb-6">{{ cms('about', 'identity', 'title', 'Preservação e Sustentabilidade') }}</h2>
                <div class="prose prose-green max-w-none text-gray-600 leading-relaxed mb-10">
                    {!! nl2br(e($settings['about_text'])) !!}
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-green-50 rounded-2xl p-6 border-l-4 border-green-600">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center text-green-700 mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <h4 class="font-bold text-green-800 mb-3">{{ cms('about', 'identity', 'mission_label', 'Missão') }}</h4>
                        <p class="text-gray-600 text-sm leading-relaxed">{{ $settings["mission"] }}</p>
                    </div>
                    <div class="bg-blue-50 rounded-2xl p-6 border-l-4 border-blue-600">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center text-blue-700 mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </div>
                        <h4 class="font-bold text-blue-800 mb-3">{{ cms('about', 'identity', 'vision_label', 'Visão') }}</h4>
                        <p class="text-gray-600 text-sm leading-relaxed">{{ $settings["vision"] }}</p>
                    </div>
                    <div class="bg-yellow-50 rounded-2xl p-6 border-l-4 border-yellow-600">
                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center text-yellow-700 mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <h4 class="font-bold text-yellow-800 mb-3">{{ cms('about', 'identity', 'values_label', 'Valores') }}</h4>
                        <p class="text-gray-600 text-sm leading-relaxed">{{ $settings["values"] }}</p>
                    </div>
                </div>
        </div>
    </div>
</section>

{{-- Equipe --}}
@if($teamMembers->count() > 0)
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-green-600 font-semibold text-sm uppercase tracking-wider">{{ cms('about', 'team', 'eyebrow', 'Capital Humano') }}</span>
            <h2 class="text-3xl lg:text-4xl font-black text-gray-900 mt-2">{{ cms('about', 'team', 'title', 'Nossa Equipe') }}</h2>
            <p class="text-gray-500 mt-4 max-w-2xl mx-auto">{{ cms('about', 'team', 'subtitle', 'Conheça as pessoas dedicadas que fazem o ISSM acontecer todos os dias.') }}</p>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-8">
            @foreach($teamMembers as $member)
            <div class="group text-center">
                <div class="relative w-32 h-32 mx-auto mb-4">
                    <div class="absolute inset-0 bg-green-200 rounded-full scale-0 group-hover:scale-110 transition-transform duration-300 opacity-50"></div>
                    <div class="relative w-32 h-32 mx-auto rounded-full overflow-hidden shadow-md border-4 border-white transition-transform duration-300 group-hover:-translate-y-2">
                        @if($member->photo)
                        <img src="{{ asset("media/".$member->photo) }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center">
                            <span class="text-green-700 font-black text-3xl">{{ substr($member->name, 0, 1) }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                <h4 class="font-bold text-gray-900 text-base mb-1">{{ $member->name }}</h4>
                <p class="text-green-600 text-xs font-semibold uppercase tracking-wide">{{ $member->role }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@endif

@endsection

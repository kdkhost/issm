@extends("layouts.app")
@section("title", "Projetos - ISSM")

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
$cmsPage = cms_page('projects');
$g1 = cms('projects', 'hero', 'gradient_start', '#166534');
$g2 = cms('projects', 'hero', 'gradient_mid', '#15803d');
$g3 = cms('projects', 'hero', 'gradient_end', '#059669');
$bcColor = cms('projects', 'hero', 'breadcrumb_color', '#86efac');
$titleHighlight = cms('projects', 'hero', 'title_highlight', 'Projetos');
$titleColor = cms('projects', 'hero', 'title_highlight_color', '#86efac');
$subColor = cms('projects', 'hero', 'subtitle_color', '#bbf7d0');
@endphp

@section("content")

@if($cmsPage && $cmsPage->use_custom_html)
    {!! $cmsPage->custom_html !!}
@else

{{-- Hero Banner Premium --}}
<div style="background:linear-gradient(135deg,{{ $g1 }} 0%,{{ $g2 }} 50%,{{ $g3 }} 100%);padding:56px 0 40px;">
    <div style="max-width:1280px;margin:0 auto;padding:0 16px;">
        <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:{{ $bcColor }};margin-bottom:16px;">
            <a href="{{ route('home') }}" style="color:{{ $bcColor }};text-decoration:none;transition:color .2s;">Início</a>
            <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span style="color:#fff;">{{ cms('projects', 'hero', 'breadcrumb', 'Projetos') }}</span>
        </div>
        <h1 style="font-size:clamp(2rem,5vw,3rem);font-weight:900;color:#fff;line-height:1.1;margin-bottom:8px;">
            {{ cms('projects', 'hero', 'title', 'Nossos') }}
            @if($titleHighlight)
            <span style="color:{{ $titleColor }};">{{ $titleHighlight }}</span>
            @endif
        </h1>
        <p style="font-size:16px;color:{{ $subColor }};max-width:600px;margin-bottom:20px;">
            {{ cms('projects', 'hero', 'subtitle', 'Iniciativas dedicadas à preservação, educação e sustentabilidade, alinhadas com os ODS 2030.') }}
        </p>
        <div style="display:flex;flex-wrap:wrap;gap:10px;">
            <div class="page-stat">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                {{ $projects->total() }} {{ cms('projects', 'hero', 'stat_label', 'Iniciativas ativas') }}
            </div>
        </div>
    </div>
</div>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @forelse($projects as $project)
        <article class="bg-white rounded-2xl shadow-md overflow-hidden card-hover border border-gray-100">
            @if($project->image)<img src="{{ asset("media/".$project->image) }}" alt="{{ $project->title }}" class="w-full h-48 object-cover">@else<div class="w-full h-48 bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center"><svg class="w-12 h-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>@endif
            <div class="p-6">
                @if($project->category)<span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded-full">{{ $project->category }}</span>@endif
                <h2 class="text-xl font-bold text-gray-900 mt-3 mb-2">{{ $project->title }}</h2>
                <p class="text-gray-600 text-sm leading-relaxed mb-4">{{ $project->excerpt ?? Str::limit(strip_tags($project->content), 120) }}</p>
                @if($project->ods_goals)
                <div class="flex flex-wrap gap-1 mb-4">
                    @foreach(array_slice($project->ods_goals, 0, 5) as $odsNum)
                    <span class="ods-{{ $odsNum }} text-white text-xs font-bold w-6 h-6 rounded flex items-center justify-center">{{ $odsNum }}</span>
                    @endforeach
                </div>
                @endif
                <a href="{{ route("projects.show", $project->slug) }}" class="text-green-700 hover:text-green-900 font-medium text-sm">{{ cms('projects', 'list', 'card_cta', 'Saiba mais') }}</a>
            </div>
        </article>
        @empty
        <div class="col-span-3 text-center py-16 text-gray-400">{{ cms('projects', 'list', 'empty_message', 'Nenhum projeto publicado ainda.') }}</div>
        @endforelse
    </div>
    <div class="mt-8">{{ $projects->links() }}</div>
</div>

@endif

@endsection

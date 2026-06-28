@extends("layouts.app")
@section("title", "Galeria - ISSM")

@push("styles")
<style>
.gal-filters{display:flex;flex-wrap:wrap;gap:8px;align-items:center;justify-content:center;padding:12px 0}
.gal-chip{padding:7px 18px;border-radius:24px;font-size:13px;font-weight:600;border:1.5px solid #d1d5db;background:#fff;color:#4b5563;cursor:pointer;text-decoration:none;transition:all .2s;display:inline-flex;align-items:center;gap:6px}
.gal-chip:hover{border-color:#15803d;color:#15803d;background:#f0fdf4}
.gal-chip.--active{background:#15803d;color:#fff;border-color:#15803d;box-shadow:0 2px 8px rgba(21,128,61,.25)}
.gal-chip-count{display:inline-flex;align-items:center;justify-content:center;min-width:20px;height:20px;border-radius:10px;font-size:10px;font-weight:700;padding:0 5px}
.gal-chip.--active .gal-chip-count{background:rgba(255,255,255,.25);color:#fff}
.gal-chip:not(.--active) .gal-chip-count{background:#e5e7eb;color:#6b7280}
.gal-album{background:#fff;border:1px solid #e5e7eb;border-radius:16px;padding:18px;margin-bottom:24px;box-shadow:0 1px 4px rgba(0,0,0,.04)}
.gal-album-head{display:flex;flex-direction:column;gap:12px;margin-bottom:16px}
@media(min-width:768px){.gal-album-head{flex-direction:row;align-items:flex-start;justify-content:space-between}}
.gal-album-title{font-size:1.1rem;font-weight:800;color:#111827;margin:0}
.gal-album-meta{font-size:.85rem;color:#6b7280;margin-top:4px}
.gal-projects{display:flex;flex-wrap:wrap;gap:6px}
.gal-project-link{display:inline-flex;align-items:center;gap:5px;padding:5px 10px;border-radius:999px;background:#eff6ff;color:#1d4ed8;font-size:12px;font-weight:700;text-decoration:none}
.gal-project-link:hover{background:#dbeafe;color:#1e40af}
.gal-results-note{font-size:.86rem;color:#6b7280;margin:0 0 18px}
.gal-folder-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:18px}
@media(max-width:1024px){.gal-folder-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:640px){.gal-folder-grid{grid-template-columns:1fr;gap:14px}}
.gal-folder{display:block;background:#fff;border:1px solid #e5e7eb;border-radius:16px;overflow:hidden;text-decoration:none;color:inherit;box-shadow:0 1px 4px rgba(0,0,0,.04);transition:transform .18s ease,box-shadow .18s ease,border-color .18s ease}
.gal-folder:hover{transform:translateY(-2px);box-shadow:0 12px 28px rgba(15,23,42,.12);border-color:#bbf7d0;color:inherit}
.gal-folder-cover{position:relative;height:190px;background:#e5e7eb;overflow:hidden}
.gal-folder-cover img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .35s ease}
.gal-folder:hover .gal-folder-cover img{transform:scale(1.04)}
.gal-folder-empty{height:100%;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#dcfce7,#f0fdf4);color:#15803d}
.gal-folder-empty svg{width:58px;height:58px}
.gal-folder-label{position:absolute;left:12px;top:12px;display:inline-flex;align-items:center;gap:7px;background:rgba(17,24,39,.78);color:#fff;border-radius:999px;padding:6px 11px;font-size:12px;font-weight:800}
.gal-folder-count{position:absolute;right:12px;bottom:12px;background:#15803d;color:#fff;border-radius:999px;padding:6px 11px;font-size:12px;font-weight:800;box-shadow:0 5px 16px rgba(21,128,61,.24)}
.gal-folder-body{padding:16px}
.gal-folder-title{margin:0;color:#111827;font-size:1rem;font-weight:900;line-height:1.25}
.gal-folder-meta{color:#6b7280;font-size:.82rem;margin-top:6px}
.gal-folder-projects{display:flex;flex-wrap:wrap;gap:5px;margin-top:12px}
.gal-folder-project{display:inline-flex;border-radius:999px;background:#eff6ff;color:#1d4ed8;padding:4px 8px;font-size:11px;font-weight:800}
.gal-folder-action{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-top:14px;padding-top:14px;border-top:1px solid #e5e7eb;color:#15803d;font-size:13px;font-weight:900}
.gal-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px}
@media(max-width:1024px){.gal-grid{grid-template-columns:repeat(3,1fr)}}
@media(max-width:640px){.gal-grid{grid-template-columns:repeat(2,1fr);gap:8px}}
.gal-card{position:relative;overflow:hidden;border-radius:12px;background:#f3f4f6;aspect-ratio:4/3;cursor:zoom-in;box-shadow:0 1px 4px rgba(0,0,0,.06);content-visibility:auto;contain-intrinsic-size:220px 165px}
.gal-card[data-lazy-card]{opacity:0;transform:translateY(18px);transition:opacity .42s ease,transform .42s ease;transition-delay:var(--reveal-delay,0ms);will-change:opacity,transform}
.gal-card[data-lazy-card].is-visible{opacity:1;transform:translateY(0)}
.gal-card img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .4s ease}
.gal-card img[data-gallery-lazy-image]{opacity:0;filter:blur(8px);transform:scale(1.02);transition:opacity .35s ease,filter .35s ease,transform .35s ease}
.gal-card img[data-gallery-lazy-image].is-loaded{opacity:1;filter:blur(0);transform:scale(1)}
.gal-card:hover img{transform:scale(1.08)}
.gal-card .gal-overlay{position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,.7) 0%,transparent 55%);opacity:0;transition:opacity .3s;display:flex;flex-direction:column;justify-content:flex-end;padding:14px 12px}
.gal-card:hover .gal-overlay{opacity:1}
.gal-overlay-title{color:#fff;font-size:.82rem;font-weight:700;line-height:1.3;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical}
.gal-overlay-album{color:rgba(255,255,255,.75);font-size:.72rem;margin-top:2px}
.gal-overlay-badge{position:absolute;top:10px;right:10px;padding:3px 10px;border-radius:20px;font-size:10px;font-weight:700;color:#fff;text-transform:uppercase;letter-spacing:.5px;background:rgba(21,128,61,.85)}
.gal-auto-loader{min-height:54px;margin-top:22px;display:flex;align-items:center;justify-content:center;color:#6b7280;font-size:13px;font-weight:700}
.gal-auto-loader::before{content:'';width:18px;height:18px;border-radius:999px;border:2px solid #d1d5db;border-top-color:#15803d;margin-right:9px;animation:galSpin .8s linear infinite}
.gal-auto-loader.is-idle{opacity:0;pointer-events:none}
.gal-auto-loader.is-complete{display:none}
@@keyframes galSpin{to{transform:rotate(360deg)}}
#lightbox{display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.93);align-items:center;justify-content:center}
#lightbox.open{display:flex}
#lightbox img#lb-img{max-width:90vw;max-height:85vh;border-radius:10px;object-fit:contain;box-shadow:0 12px 60px rgba(0,0,0,.7);animation:lbIn .2s ease}
@@keyframes lbIn{from{opacity:0;transform:scale(.93)}to{opacity:1;transform:scale(1)}}
.lb-btn{position:absolute;background:rgba(255,255,255,.1);border:none;color:#fff;cursor:pointer;transition:background .15s;display:flex;align-items:center;justify-content:center}
.lb-btn:hover{background:rgba(255,255,255,.2)}
#lb-close{top:16px;right:20px;font-size:32px;width:40px;height:40px;border-radius:50%}
#lb-prev,#lb-next{top:50%;transform:translateY(-50%);width:52px;height:52px;border-radius:50%;font-size:26px}
#lb-prev{left:16px}#lb-next{right:16px}
#lb-footer{position:absolute;bottom:0;left:0;right:0;padding:14px 20px;display:flex;align-items:center;justify-content:space-between;gap:16px}
#lb-caption{color:#d1d5db;font-size:.88rem}
#lb-counter{color:rgba(255,255,255,.5);font-size:.8rem;font-variant-numeric:tabular-nums}
.lb-meta{display:flex;flex-direction:column;gap:4px;min-width:0}
.lb-actions{display:flex;align-items:center;gap:8px;flex-wrap:wrap;justify-content:flex-end}
.lb-action{display:inline-flex;align-items:center;gap:7px;border:1px solid rgba(255,255,255,.18);background:rgba(255,255,255,.1);color:#fff;border-radius:999px;padding:9px 13px;font-size:12px;font-weight:800;text-decoration:none;cursor:pointer;transition:background .15s,border-color .15s}
.lb-action:hover{background:rgba(255,255,255,.18);border-color:rgba(255,255,255,.32);color:#fff;text-decoration:none}
.lb-action svg{width:15px;height:15px}
.gal-stat{display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.1);padding:6px 14px;border-radius:24px;font-size:13px;color:#fff;font-weight:500}
.gal-stat svg{width:16px;height:16px;opacity:.8}
.gal-empty{display:flex;flex-direction:column;align-items:center;justify-content:center;padding:80px 20px;text-align:center}
.gal-empty-icon{width:80px;height:80px;border-radius:50%;background:#e5e7eb;display:flex;align-items:center;justify-content:center;margin-bottom:20px}
.gal-empty-icon svg{width:40px;height:40px;color:#9ca3af}
.gal-empty h3{font-size:18px;font-weight:700;color:#9ca3af;margin-bottom:4px}
.gal-empty p{font-size:14px;color:#9ca3af}
@media(prefers-reduced-motion:reduce){
    .gal-card[data-lazy-card]{opacity:1;transform:none;transition:none}
    .gal-card img[data-gallery-lazy-image]{transition:none;filter:none;transform:none}
}
@media(max-width:640px){
    #lb-footer{align-items:flex-start;flex-direction:column;padding:12px 14px}
    .lb-actions{justify-content:flex-start}
}
</style>
@endpush

@php
$cmsPage = cms_page('gallery');
$g1 = cms('gallery', 'hero', 'gradient_start', '#166534');
$g2 = cms('gallery', 'hero', 'gradient_mid', '#15803d');
$g3 = cms('gallery', 'hero', 'gradient_end', '#059669');
$bcColor = cms('gallery', 'hero', 'breadcrumb_color', '#86efac');
$titleHighlight = cms('gallery', 'hero', 'title_highlight', '');
$titleColor = cms('gallery', 'hero', 'title_highlight_color', '#86efac');
$titleUseGradient = cms('gallery', 'hero', 'title_use_gradient', '') === '1';
$titleGradStart = cms('gallery', 'hero', 'title_gradient_start', '#86efac');
$titleGradEnd = cms('gallery', 'hero', 'title_gradient_end', '#34d399');
$subColor = cms('gallery', 'hero', 'subtitle_color', '#bbf7d0');
$fullTitle = cms('gallery', 'hero', 'title', 'Galeria Completa');
@endphp

@section("content")
@if($cmsPage && $cmsPage->use_custom_html)
    {!! $cmsPage->custom_html !!}
@else
<div style="background:linear-gradient(135deg,{{ $g1 }} 0%,{{ $g2 }} 50%,{{ $g3 }} 100%);padding:56px 0 40px;">
    <div style="max-width:1280px;margin:0 auto;padding:0 16px;">
        <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:{{ $bcColor }};margin-bottom:16px;">
            <a href="{{ route('home') }}" style="color:{{ $bcColor }};text-decoration:none;transition:color .2s;">Início</a>
            <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span style="color:#fff;">{{ cms('gallery', 'hero', 'breadcrumb', 'Galeria') }}</span>
        </div>
        @php
            $titlePart = cms('gallery', 'hero', 'title', 'Galeria');
            $highlightPart = trim($titleHighlight);
        @endphp
        <h1 style="font-size:clamp(2rem,5vw,3rem);font-weight:900;line-height:1.1;margin-bottom:8px;{{ $titleUseGradient ? '-webkit-background-clip:text;-webkit-text-fill-color:transparent;background:linear-gradient(90deg,'.$titleGradStart.','.$titleGradEnd.');background-clip:text;' : 'color:#fff;' }}">
            @if($highlightPart)
                {{ $titlePart }} <span style="color:{{ $titleColor }};">{{ $highlightPart }}</span>
            @else
                {{ $fullTitle }}
            @endif
        </h1>
        <p style="font-size:16px;color:{{ $subColor }};max-width:640px;margin-bottom:20px;">
            {{ cms('gallery', 'hero', 'subtitle', 'Registros fotográficos dos nossos projetos, eventos e ações socioambientais') }}
        </p>
        <div style="display:flex;flex-wrap:wrap;gap:10px;">
            <div class="gal-stat">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ $totalGallery }} foto{{ $totalGallery != 1 ? 's' : '' }}
            </div>
            <div class="gal-stat">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2"/></svg>
                {{ $totalAlbums }} álbum{{ $totalAlbums != 1 ? 's' : '' }}
            </div>
            <div class="gal-stat">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7m-8-2a2 2 0 002 2h2a2 2 0 002-2"/></svg>
                {{ $totalProjects }} projeto{{ $totalProjects != 1 ? 's' : '' }} vinculado{{ $totalProjects != 1 ? 's' : '' }}
            </div>
        </div>
    </div>
</div>

<div style="background:#fff;border-bottom:1px solid #e5e7eb;box-shadow:0 1px 3px rgba(0,0,0,.04);">
    <div style="max-width:1280px;margin:0 auto;padding:0 16px;">
        <div class="gal-filters">
            <a href="{{ route('gallery.index') }}" class="gal-chip {{ ! $selectedAlbum ? '--active' : '' }}">
                Todos
                <span class="gal-chip-count">{{ $totalGallery }}</span>
            </a>
            @foreach($allAlbums as $albumOption)
                <a href="{{ route('gallery.index', ['album' => $albumOption->slug]) }}" class="gal-chip {{ $selectedAlbum && $selectedAlbum->id === $albumOption->id ? '--active' : '' }}">
                    {{ $albumOption->title }}
                    <span class="gal-chip-count">{{ $albumOption->active_photos_count }}</span>
                </a>
            @endforeach
        </div>
    </div>
</div>

<section style="padding:40px 0 60px;background:#f9fafb;min-height:60vh;">
    <div style="max-width:1280px;margin:0 auto;padding:0 16px;">
        @if($albums->count() > 0)
            @if($selectedAlbum && $photos)
                <p class="gal-results-note">
                    Mostrando {{ number_format($photos->firstItem() ?? 0, 0, ",", ".") }} a {{ number_format($photos->lastItem() ?? 0, 0, ",", ".") }} de {{ number_format($photos->total(), 0, ",", ".") }} fotos do álbum selecionado.
                </p>

                <div id="gallery-grid">
                    @foreach($albums as $album)
                        <article class="gal-album">
                            <div class="gal-album-head">
                                <div>
                                    <h2 class="gal-album-title">{{ $album->title }}</h2>
                                    <p class="gal-album-meta">
                                        @if($album->event_date)
                                            {{ $album->event_date->format('d/m/Y') }}
                                        @else
                                            Data do evento não informada
                                        @endif
                                        @if($album->event_location)
                                            • {{ $album->event_location }}
                                        @endif
                                        • {{ $album->active_photos_count }} foto{{ $album->active_photos_count != 1 ? 's' : '' }}
                                    </p>
                                </div>

                                @if($album->projects->count())
                                    <div class="gal-projects">
                                        @foreach($album->projects as $project)
                                            <a href="{{ route('projects.show', $project->slug) }}" class="gal-project-link">
                                                <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                {{ $project->title }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <div class="gal-grid">
                                @foreach($photos as $photo)
                                    <div class="gal-card"
                                         data-lazy-card
                                         data-src="{{ asset('media/' . $photo->image) }}"
                                         data-album-id="{{ $album->id }}"
                                         data-photo-id="{{ $photo->id }}"
                                         data-watermarked-url="{{ route('gallery.photos.watermarked', $photo) }}"
                                         data-download-url="{{ route('gallery.photos.watermarked', ['photo' => $photo, 'download' => 1]) }}"
                                         data-file-name="{{ \Illuminate\Support\Str::slug($photo->title ?: 'foto-galeria') }}.jpg"
                                         data-caption="{{ $photo->title }} - {{ $album->title }}">
                                        <img
                                            src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw=="
                                            data-src="{{ asset('media/' . $photo->image) }}"
                                            data-gallery-lazy-image
                                            alt="{{ $photo->title }}"
                                            loading="lazy"
                                            decoding="async"
                                            width="{{ $photo->width ?: 800 }}"
                                            height="{{ $photo->height ?: 600 }}">
                                        <div class="gal-overlay">
                                            <span class="gal-overlay-badge">Evento</span>
                                            <div>
                                                <p class="gal-overlay-title">{{ $photo->title }}</p>
                                                <span class="gal-overlay-album">{{ $album->title }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </article>
                    @endforeach
                </div>

                @if($photos->hasMorePages())
                    <div class="gal-auto-loader is-idle"
                         data-gallery-autoload
                         data-autoload-type="photos"
                         data-next-url="{{ $photos->nextPageUrl() }}"
                         aria-live="polite">
                        Carregando mais fotos...
                    </div>
                @endif
            @else
                <p class="gal-results-note">
                    Pastas de álbuns organizadas por evento. Abra uma pasta para ver as fotos paginadas daquele álbum.
                </p>

                <div class="gal-folder-grid">
                    @foreach($albums as $album)
                        @php
                            $cover = $album->cover_image ?: $album->cover_photo_image;
                        @endphp
                        <a href="{{ route('gallery.index', ['album' => $album->slug]) }}" class="gal-folder" data-gallery-album-link data-album-id="{{ $album->id }}" data-label="{{ $album->title }}">
                            <div class="gal-folder-cover">
                                @if($cover)
                                    <img src="{{ asset('media/' . $cover) }}" alt="{{ $album->title }}" loading="lazy" decoding="async">
                                @else
                                    <div class="gal-folder-empty">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M3 7a2 2 0 012-2h5l2 2h7a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/></svg>
                                    </div>
                                @endif
                                <span class="gal-folder-label">
                                    <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7a2 2 0 012-2h5l2 2h7a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/></svg>
                                    Pasta
                                </span>
                                <span class="gal-folder-count">{{ $album->active_photos_count }} foto{{ $album->active_photos_count != 1 ? 's' : '' }}</span>
                            </div>
                            <div class="gal-folder-body">
                                <h2 class="gal-folder-title">{{ $album->title }}</h2>
                                <p class="gal-folder-meta">
                                    @if($album->event_date)
                                        {{ $album->event_date->format('d/m/Y') }}
                                    @else
                                        Data do evento não informada
                                    @endif
                                    @if($album->event_location)
                                        • {{ $album->event_location }}
                                    @endif
                                </p>
                                @if($album->projects->count())
                                    <div class="gal-folder-projects">
                                        @foreach($album->projects->take(3) as $project)
                                            <span class="gal-folder-project">{{ $project->title }}</span>
                                        @endforeach
                                        @if($album->projects->count() > 3)
                                            <span class="gal-folder-project">+{{ $album->projects->count() - 3 }}</span>
                                        @endif
                                    </div>
                                @endif
                                <div class="gal-folder-action">
                                    <span>Abrir pasta do álbum</span>
                                    <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                @if(method_exists($albums, "hasMorePages") && $albums->hasMorePages())
                    <div class="gal-auto-loader is-idle"
                         data-gallery-autoload
                         data-autoload-type="folders"
                         data-next-url="{{ $albums->nextPageUrl() }}"
                         aria-live="polite">
                        Carregando mais álbuns...
                    </div>
                @endif
            @endif
        @else
            <div class="gal-empty">
                <div class="gal-empty-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <h3>{{ cms('gallery', 'empty', 'title', 'Nenhuma foto encontrada') }}</h3>
                <p>{{ cms('gallery', 'empty', 'message', 'A galeria está vazia no momento.') }}</p>
            </div>
        @endif
    </div>
</section>

<div id="lightbox" role="dialog" aria-label="Visualizador de imagem">
    <button id="lb-close" class="lb-btn" aria-label="Fechar">&times;</button>
    <button id="lb-prev" class="lb-btn" aria-label="Anterior">&#8249;</button>
    <button id="lb-next" class="lb-btn" aria-label="Próxima">&#8250;</button>
    <img id="lb-img" src="" alt="">
    <div id="lb-footer">
        <div class="lb-meta">
            <p id="lb-caption"></p>
            <span id="lb-counter"></span>
        </div>
        <div class="lb-actions">
            <button type="button" id="lb-share" class="lb-action">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12s-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 103.368-1.342 3 3 0 00-3.368 1.342zm0 9.316a3 3 0 103.368 1.342 3 3 0 00-3.368-1.342z"/></svg>
                Compartilhar
            </button>
            <a id="lb-download" class="lb-action" href="#" download>
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4"/></svg>
                Baixar
            </a>
        </div>
    </div>
</div>

@push("scripts")
<script>
(function(){
    var galleryGrid = document.getElementById('gallery-grid');
    var folderGrid = document.querySelector('.gal-folder-grid');
    var loader = document.querySelector('[data-gallery-autoload]');
    var items = [];
    var lb = document.getElementById('lightbox');
    var img = document.getElementById('lb-img');
    var cap = document.getElementById('lb-caption');
    var ctr = document.getElementById('lb-counter');
    var shareButton = document.getElementById('lb-share');
    var downloadButton = document.getElementById('lb-download');
    var cur = 0;
    var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var lazyObserver = null;
    var autoObserver = null;
    var loadingNext = false;
    var trackUrl = @json(route('gallery.track'));
    var csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    function refreshItems() {
        items = Array.from(document.querySelectorAll('#gallery-grid .gal-card[data-src]'));
    }

    function trackGalleryEvent(type, data) {
        var payload = new FormData();
        payload.append('event_type', type);
        payload.append('_token', csrfToken);

        if (data && data.albumId) payload.append('album_id', data.albumId);
        if (data && data.photoId) payload.append('photo_id', data.photoId);
        if (data && data.label) payload.append('label', data.label);

        if (navigator.sendBeacon) {
            navigator.sendBeacon(trackUrl, payload);
            return;
        }

        fetch(trackUrl, {
            method: 'POST',
            body: payload,
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            credentials: 'same-origin',
            keepalive: true
        }).catch(function() {});
    }

    function loadCardImage(card) {
        var image = card.querySelector('[data-gallery-lazy-image]');
        if (!image || image.dataset.loaded === '1') return;

        var source = image.dataset.src;
        if (!source) return;

        image.addEventListener('load', function() {
            image.classList.add('is-loaded');
        }, { once: true });

        image.src = source;
        image.dataset.loaded = '1';

        if (image.complete) {
            image.classList.add('is-loaded');
        }
    }

    function revealCard(card) {
        loadCardImage(card);
        card.classList.add('is-visible');
    }

    function resetReveal(card) {
        if (!reduceMotion) {
            card.classList.remove('is-visible');
        }
    }

    function ensureLazyObserver() {
        if (lazyObserver || !('IntersectionObserver' in window)) return;
        lazyObserver = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        revealCard(entry.target);
                    } else {
                        resetReveal(entry.target);
                    }
                });
            }, {
                root: null,
                rootMargin: '140px 0px 80px 0px',
                threshold: 0.12
            });
    }

    function observeLazyCard(card, index) {
        if (!card || card.dataset.lazyObserved === '1') return;
        card.dataset.lazyObserved = '1';
        card.style.setProperty('--reveal-delay', ((index % 8) * 45) + 'ms');

        ensureLazyObserver();
        if (lazyObserver) {
            lazyObserver.observe(card);
        } else {
            revealCard(card);
        }
    }

    function observeLazyCards(container) {
        Array.from((container || document).querySelectorAll('#gallery-grid .gal-card[data-lazy-card], .gal-card[data-lazy-card]'))
            .forEach(function(card, index) {
                observeLazyCard(card, items.length + index);
            });
    }

    function loadNextBatch() {
        if (!loader || loadingNext || !loader.dataset.nextUrl) return;

        loadingNext = true;
        loader.classList.remove('is-idle');

        fetch(loader.dataset.nextUrl, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin'
        })
        .then(function(response) {
            if (!response.ok) throw new Error('Não foi possível carregar mais itens.');
            return response.text();
        })
        .then(function(html) {
            var doc = new DOMParser().parseFromString(html, 'text/html');
            var type = loader.dataset.autoloadType;

            if (type === 'photos') {
                var targetGrid = document.querySelector('#gallery-grid .gal-grid');
                var newCards = Array.from(doc.querySelectorAll('#gallery-grid .gal-card[data-src]'));
                newCards.forEach(function(card) {
                    targetGrid && targetGrid.appendChild(card);
                });
                refreshItems();
                newCards.forEach(function(card, index) {
                    observeLazyCard(card, items.length + index);
                });
            } else if (type === 'folders') {
                var newFolders = Array.from(doc.querySelectorAll('.gal-folder-grid .gal-folder'));
                newFolders.forEach(function(folder) {
                    folderGrid && folderGrid.appendChild(folder);
                });
            }

            var nextLoader = doc.querySelector('[data-gallery-autoload]');
            if (nextLoader && nextLoader.dataset.nextUrl) {
                loader.dataset.nextUrl = nextLoader.dataset.nextUrl;
                loader.classList.add('is-idle');
            } else {
                loader.dataset.nextUrl = '';
                loader.classList.add('is-complete');
                if (autoObserver) autoObserver.disconnect();
            }
        })
        .catch(function() {
            loader.classList.add('is-idle');
        })
        .finally(function() {
            loadingNext = false;
        });
    }

    refreshItems();
    observeLazyCards(document);

    document.querySelectorAll('[data-gallery-album-link]').forEach(function(link) {
        link.addEventListener('click', function() {
            trackGalleryEvent('album_click', { albumId: link.dataset.albumId, label: link.dataset.label });
        });
    });

    if (loader && loader.dataset.nextUrl) {
        if ('IntersectionObserver' in window) {
            autoObserver = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        loadNextBatch();
                    }
                });
            }, {
                root: null,
                rootMargin: '700px 0px',
                threshold: 0
            });
            autoObserver.observe(loader);
        } else {
            loader.classList.remove('is-idle');
            var fallbackButton = document.createElement('button');
            fallbackButton.type = 'button';
            fallbackButton.className = 'gal-chip';
            fallbackButton.textContent = 'Carregar mais';
            fallbackButton.addEventListener('click', loadNextBatch);
            loader.innerHTML = '';
            loader.appendChild(fallbackButton);
        }
    }

    if (!items.length || !lb || !img || !galleryGrid) return;

    function open(idx) {
        cur = idx;
        var d = items[idx].dataset;
        img.src = d.watermarkedUrl || d.src;
        cap.textContent = d.caption || '';
        ctr.textContent = (idx + 1) + ' / ' + items.length;
        updateLightboxActions(d);
        trackGalleryEvent('photo_view', { albumId: d.albumId, photoId: d.photoId, label: d.caption });
        lb.classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function close() {
        lb.classList.remove('open');
        img.src = '';
        document.body.style.overflow = '';
    }

    function nav(dir) {
        cur = (cur + dir + items.length) % items.length;
        var d = items[cur].dataset;
        img.style.animation = 'none';
        requestAnimationFrame(function(){
            img.style.animation = '';
            img.src = d.watermarkedUrl || d.src;
            cap.textContent = d.caption || '';
            ctr.textContent = (cur + 1) + ' / ' + items.length;
            updateLightboxActions(d);
            trackGalleryEvent('photo_view', { albumId: d.albumId, photoId: d.photoId, label: d.caption });
        });
    }

    function updateLightboxActions(data) {
        if (!downloadButton) return;

        downloadButton.href = data.downloadUrl || data.watermarkedUrl || data.src || '#';
        downloadButton.setAttribute('download', '');
    }

    function currentShareData() {
        var data = items[cur] ? items[cur].dataset : {};

        return {
            title: data.caption || 'Foto da galeria',
            url: data.watermarkedUrl || data.src || window.location.href,
            fileName: data.fileName || 'foto-galeria.jpg'
        };
    }

    function resetShareButton() {
        shareButton.innerHTML = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12s-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 103.368-1.342 3 3 0 00-3.368 1.342zm0 9.316a3 3 0 103.368 1.342 3 3 0 00-3.368-1.342z"/></svg>Compartilhar';
    }

    function copyShareLink(url) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(url).then(function() {
                shareButton.textContent = 'Link copiado';
                setTimeout(resetShareButton, 1800);
            });
        }
    }

    galleryGrid.addEventListener('click', function(event) {
        var card = event.target.closest('.gal-card[data-src]');
        if (!card) return;
        refreshItems();
        var index = items.indexOf(card);
        if (index >= 0) {
            trackGalleryEvent('photo_click', { albumId: card.dataset.albumId, photoId: card.dataset.photoId, label: card.dataset.caption });
            open(index);
        }
    });
    document.getElementById('lb-close').addEventListener('click', close);
    document.getElementById('lb-prev').addEventListener('click', function(){ nav(-1); });
    document.getElementById('lb-next').addEventListener('click', function(){ nav(1); });
    if (shareButton) {
        shareButton.addEventListener('click', function() {
            var data = currentShareData();
            var current = items[cur] ? items[cur].dataset : {};
            trackGalleryEvent('photo_share', { albumId: current.albumId, photoId: current.photoId, label: current.caption });

            if (navigator.share && window.fetch && window.File && navigator.canShare) {
                fetch(data.url, { credentials: 'same-origin' })
                    .then(function(response) {
                        if (!response.ok) throw new Error('Imagem indisponivel.');
                        return response.blob();
                    })
                    .then(function(blob) {
                        var file = new File([blob], data.fileName, { type: blob.type || 'image/jpeg' });

                        if (navigator.canShare({ files: [file] })) {
                            return navigator.share({ title: data.title, files: [file] });
                        }

                        return navigator.share({ title: data.title, url: data.url });
                    })
                    .catch(function() {
                        navigator.share({ title: data.title, url: data.url }).catch(function(){});
                    });
                return;
            }

            if (navigator.share) {
                navigator.share({ title: data.title, url: data.url }).catch(function(){});
                return;
            }

            copyShareLink(data.url);
        });
    }
    if (downloadButton) {
        downloadButton.addEventListener('click', function() {
            var current = items[cur] ? items[cur].dataset : {};
            trackGalleryEvent('download_click', { albumId: current.albumId, photoId: current.photoId, label: current.caption });
        });
    }
    lb.addEventListener('click', function(e){ if(e.target === lb) close(); });
    document.addEventListener('keydown', function(e){
        if(!lb.classList.contains('open')) return;
        if(e.key === 'Escape') close();
        if(e.key === 'ArrowLeft') nav(-1);
        if(e.key === 'ArrowRight') nav(1);
    });
})();
</script>
@endpush
@endif
@endsection

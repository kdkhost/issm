@extends("layouts.app")
@section("title", $page->meta_title ?: $page->title)
@section("meta_description", $page->meta_description ?? "")
@if($page->meta_keywords ?? false)
@section("meta_keywords", $page->meta_keywords)
@endif
@if($page->og_title ?? false)
@section("og_title", $page->og_title)
@endif
@if($page->og_description ?? false)
@section("og_description", $page->og_description)
@endif
@if($page->og_image ?? false)
@section("og_image", asset("media/" . $page->og_image))
@endif
@section("content")

{{-- CMS Sections (fully manageable from admin) --}}
@if($sections && $sections->count() > 0)
    @foreach($sections as $section)
        @php $blocks = $section->blocks; @endphp
        @if($section->is_active && $blocks->isNotEmpty())
            @foreach($blocks as $block)
                @if($block->is_active)
                    @php $t = $block->type ?? 'text'; @endphp
                    @if(view()->exists("public.cms.blocks.{$t}"))
                        @include("public.cms.blocks.{$t}", ['block' => $block, 'section' => $section, 'blocks' => $blocks])
                    @endif
                @endif
            @endforeach
        @endif
    @endforeach
@elseif($page && $page->content)
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="prose prose-green max-w-none">
                {!! $page->content !!}
            </div>
        </div>
    </section>
@endif

{{-- Dynamic DB-driven sections (per page) --}}
@if(isset($teamMembers) && $teamMembers->count() > 0)
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-green-600 font-semibold text-sm uppercase tracking-wider">Capital Humano</span>
            <h2 class="text-3xl lg:text-4xl font-black text-gray-900 mt-2">Nossa <span class="text-green-700">Equipe</span></h2>
            <p class="text-gray-500 mt-4 max-w-2xl mx-auto">Conheça as pessoas dedicadas que fazem o ISSM acontecer todos os dias.</p>
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

@if(isset($odsList) && $odsList->count() > 0)
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-12 text-center max-w-3xl mx-auto">
            <h2 class="text-3xl font-black text-gray-900 mb-4">A Agenda 2030</h2>
            <p class="text-gray-600 leading-relaxed">Adotada por todos os Estados-Membros das Nações Unidas, a Agenda 2030 fornece um plano compartilhado para a paz e a prosperidade das pessoas e do planeta, agora e no futuro.</p>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-4">
            @foreach($odsList as $ods)
            <div class="text-center p-3 rounded-xl" style="background:{{ $ods->color }}20;border:2px solid {{ $ods->color }}30;">
                <span class="text-2xl font-black" style="color:{{ $ods->color }}">{{ $ods->number }}</span>
                <p class="text-xs font-bold text-gray-700 mt-1 leading-tight">{{ $ods->title }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@if(isset($allItems) && $allItems->count() > 0)
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <span class="text-green-600 font-semibold text-sm uppercase tracking-wider">Galeria</span>
                <h2 class="text-3xl font-black text-gray-900 mt-2">Registros e <span class="text-green-700">Atividades</span></h2>
            </div>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
            @foreach($allItems as $item)
            <div class="relative overflow-hidden rounded-xl bg-gray-100 aspect-[4/3]">
                <img src="{{ asset("media/".$item->image) }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/70 to-transparent p-3">
                    <p class="text-white text-sm font-semibold">{{ $item->title }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@if(isset($banners) && $banners->count() > 0)
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
@endif

@if(isset($featuredProjects) && $featuredProjects->count() > 0)
<section class="py-20 bg-white">
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
            <article class="bg-white rounded-2xl shadow-md overflow-hidden border border-gray-100">
                @if($project->image)<img src="{{ asset("media/".$project->image) }}" alt="{{ $project->title }}" class="w-full h-48 object-cover">@else<div class="w-full h-48 bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center"><svg class="w-16 h-16 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>@endif
                <div class="p-6">
                    @if($project->category)<span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded-full">{{ $project->category }}</span>@endif
                    <h3 class="text-xl font-bold text-gray-900 mt-3 mb-2">{{ $project->title }}</h3>
                    <p class="text-gray-600 text-sm leading-relaxed mb-4">{{ $project->excerpt ?? Str::limit(strip_tags($project->content), 120) }}</p>
                    <a href="{{ route("projects.show", $project->slug) }}" class="text-green-700 font-semibold text-sm hover:text-green-800 flex items-center gap-1">Ver detalhes <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></a>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>
@endif

@if(isset($latestNews) && $latestNews->count() > 0)
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-end mb-12">
            <div>
                <span class="text-green-600 font-semibold text-sm uppercase tracking-wider">Blog</span>
                <h2 class="text-3xl lg:text-4xl font-black text-gray-900 mt-2">Últimas <span class="text-green-700">Notícias</span></h2>
            </div>
            <a href="{{ route("news.index") }}" class="text-green-700 hover:text-green-900 font-medium flex items-center gap-1">Ver todas <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($latestNews as $news)
            <article class="bg-white rounded-2xl shadow-md overflow-hidden border border-gray-100">
                @if($news->image)<img src="{{ asset("media/".$news->image) }}" alt="{{ $news->title }}" class="w-full h-48 object-cover">@endif
                <div class="p-6">
                    <span class="text-xs text-gray-500">{{ $news->created_at->format('d/m/Y') }}</span>
                    <h3 class="text-lg font-bold text-gray-900 mt-2 mb-2">{{ $news->title }}</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">{{ $news->excerpt ?? Str::limit(strip_tags($news->content), 120) }}</p>
                    <a href="{{ route("news.show", $news->slug) }}" class="text-green-700 font-semibold text-sm hover:text-green-800 mt-4 inline-flex items-center gap-1">Ler mais <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></a>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>
@endif

@if(isset($testimonials) && $testimonials->count() > 0)
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-green-600 font-semibold text-sm uppercase tracking-wider">Depoimentos</span>
            <h2 class="text-3xl lg:text-4xl font-black text-gray-900 mt-2">O que dizem <span class="text-green-700">sobre nós</span></h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($testimonials as $testimonial)
            <div class="bg-gray-50 rounded-2xl p-8 relative">
                <svg class="absolute top-6 right-8 w-12 h-12 text-green-200" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                <p class="text-gray-600 leading-relaxed mb-6 italic">{{ $testimonial->content }}</p>
                <div class="flex items-center gap-4">
                    @if($testimonial->photo)<img src="{{ asset("media/".$testimonial->photo) }}" alt="" class="w-12 h-12 rounded-full object-cover">@endif
                    <div>
                        <p class="font-bold text-gray-900">{{ $testimonial->name }}</p>
                        <p class="text-sm text-gray-500">{{ $testimonial->role ?? '' }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@if(isset($faqs) && $faqs->count() > 0)
<section class="py-20 bg-gray-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-green-600 font-semibold text-sm uppercase tracking-wider">FAQ</span>
            <h2 class="text-3xl lg:text-4xl font-black text-gray-900 mt-2">Perguntas <span class="text-green-700">Frequentes</span></h2>
        </div>
        <div class="space-y-4">
            @foreach($faqs as $faq)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <button type="button" class="faq-toggle w-full flex items-center justify-between p-5 text-left" aria-expanded="false">
                    <span class="font-semibold text-gray-800 pr-4">{{ $faq->question }}</span>
                    <svg class="w-5 h-5 text-gray-400 flex-shrink-0 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div class="faq-answer px-5 pb-5 hidden">
                    <p class="text-gray-600 leading-relaxed">{{ $faq->answer }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@if(isset($partners) && $partners->count() > 0)
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-black text-gray-900">Nossos <span class="text-green-700">Parceiros</span></h2>
        </div>
        <div class="grid grid-cols-3 md:grid-cols-5 gap-8 items-center">
            @foreach($partners as $partner)
            <div class="flex items-center justify-center">
                @if($partner->url)<a href="{{ $partner->url }}" target="_blank" rel="noopener">@endif
                @if($partner->logo)<img src="{{ asset("media/".$partner->logo) }}" alt="{{ $partner->name }}" class="max-h-16 grayscale hover:grayscale-0 transition-all">@else<span class="text-gray-400 font-bold text-sm">{{ $partner->name }}</span>@endif
                @if($partner->url)</a>@endif
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection

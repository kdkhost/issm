@extends("layouts.app")
@section("title", ($item->meta_title ?? $item->title) . " - ISSM")
@section("meta_description", $item->meta_description ?? strip_tags($item->excerpt ?? $item->title))
@section("meta_keywords", $item->meta_keywords ?? "")
@section("og_title", $item->og_title ?? ($item->meta_title ?? $item->title))
@section("og_description", $item->og_description ?? ($item->meta_description ?? strip_tags($item->excerpt ?? $item->title)))
@section("og_image", $item->og_image ? asset("media/" . $item->og_image) : ($item->image ? asset("media/" . $item->image) : ""))
@section("content")

{{-- Hero Banner Premium --}}
<div style="background:linear-gradient(135deg,#166534 0%,#15803d 50%,#059669 100%);padding:56px 0 40px;">
    <div style="max-width:1280px;margin:0 auto;padding:0 16px;">
        <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:#86efac;margin-bottom:16px;">
            <a href="{{ route('home') }}" style="color:#86efac;text-decoration:none;transition:color .2s;">Início</a>
            <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('news.index') }}" style="color:#86efac;text-decoration:none;transition:color .2s;">Notícias</a>
            <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span style="color:#fff;">{{ Str::limit($item->title, 30) }}</span>
        </div>
        <h1 style="font-size:clamp(2rem,5vw,3.5rem);font-weight:900;color:#fff;line-height:1.1;margin-bottom:16px;">
            @if($item->title_highlight)
                {{ $item->title }} <span style="color:{{ $item->title_highlight_color ?: '#86efac' }};">{{ $item->title_highlight }}</span>
            @else
                {{ $item->title }}
            @endif
        </h1>
        <div style="display:flex;flex-wrap:wrap;gap:12px;align-items:center;">
            @if($item->category)
            <span style="background:rgba(255,255,255,0.1);backdrop-filter:blur(4px);color:#fff;font-size:12px;font-weight:700;padding:6px 14px;border-radius:100px;border:1px solid rgba(255,255,255,0.2);">
                {{ $item->category }}
            </span>
            @endif
            @if($item->published_at)
            <span style="color:#bbf7d0;font-size:13px;font-weight:500;">
                Publicado em {{ $item->published_at->translatedFormat('d \d\e F \d\e Y') }}
            </span>
            @endif
        </div>
    </div>
</div>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <article class="bg-white rounded-[32px] overflow-hidden shadow-2xl shadow-gray-200/50 border border-gray-100">
        @if($item->image)
        <div class="w-full overflow-hidden" style="background:#f8fafc;">
            <img src="{{ asset('media/'.$item->image) }}" alt="{{ $item->title }}"
                 class="w-full h-auto block"
                 style="max-height:520px;object-fit:contain;margin:0 auto;display:block;">
        </div>
        @endif
        @endif
        
        <div class="p-8 lg:p-16">
            <div class="max-w-3xl mx-auto">
                <div class="prose prose-lg prose-green max-w-none text-gray-700 leading-relaxed font-medium">
                    {!! $item->content !!}
                </div>

                <div class="mt-16 pt-10 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-4">
                        <span class="text-sm font-bold text-gray-400">Compartilhar:</span>
                        {{-- Social Share Buttons (Placeholders) --}}
                        <div class="flex gap-2">
                            <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-green-50 hover:text-green-600 transition-all cursor-pointer border border-gray-100">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </div>
                            <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-green-50 hover:text-green-600 transition-all cursor-pointer border border-gray-100">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.84 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                            </div>
                        </div>
                    </div>
                    
                    <a href="{{ route('news.index') }}" class="inline-flex items-center gap-2 text-green-700 hover:text-green-900 font-bold group">
                        <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Ver todas as notícias
                    </a>
                </div>
            </div>
        </div>
    </article>
    <div class="mt-12 pt-8 border-t border-gray-200">
        <a href="{{ route("news.index") }}" class="text-green-700 hover:text-green-900 font-medium flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Voltar para Noticias
        </a>
    </div>
</div>
@endsection

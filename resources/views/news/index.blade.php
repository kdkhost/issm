@extends("layouts.app")
@section("title", "Noticias - ISSM")

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

@section("content")

{{-- Hero Banner Premium --}}
<div style="background:linear-gradient(135deg,#166534 0%,#15803d 50%,#059669 100%);padding:56px 0 40px;">
    <div style="max-width:1280px;margin:0 auto;padding:0 16px;">
        <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:#86efac;margin-bottom:16px;">
            <a href="{{ route('home') }}" style="color:#86efac;text-decoration:none;transition:color .2s;">Início</a>
            <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span style="color:#fff;">Notícias</span>
        </div>
        <h1 style="font-size:clamp(2rem,5vw,3rem);font-weight:900;color:#fff;line-height:1.1;margin-bottom:8px;">
            Blog & <span style="color:#86efac;">Notícias</span>
        </h1>
        <p style="font-size:16px;color:#bbf7d0;max-width:600px;margin-bottom:20px;">
            Fique por dentro das novidades, eventos e conquistas do Instituto Socioambiental Serra do Mendanha.
        </p>
        <div style="display:flex;flex-wrap:wrap;gap:10px;">
            <div class="page-stat">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                {{ $news->total() }} Artigos publicados
            </div>
        </div>
    </div>
</div>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @forelse($news as $item)
        <article class="bg-white rounded-2xl shadow-md overflow-hidden card-hover border border-gray-100">
            @if($item->image)<img src="{{ asset("media/".$item->image) }}" alt="{{ $item->title }}" class="w-full h-48 object-cover">@else<div class="w-full h-48 bg-gradient-to-br from-blue-50 to-green-50 flex items-center justify-center"><svg class="w-12 h-12 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg></div>@endif
            <div class="p-6">
                @if($item->category)<span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">{{ $item->category }}</span>@endif
                <h2 class="text-xl font-bold text-gray-900 mt-3 mb-2">{{ $item->title }}</h2>
                <p class="text-gray-600 text-sm leading-relaxed mb-4">{{ $item->excerpt ?? Str::limit(strip_tags($item->content), 120) }}</p>
                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-400">{{ $item->published_at ? $item->published_at->format("d/m/Y") : "" }}</span>
                    <a href="{{ route("news.show", $item->slug) }}" class="text-green-700 hover:text-green-900 font-medium text-sm">Ler mais</a>
                </div>
            </div>
        </article>
        @empty
        <div class="col-span-3 text-center py-16 text-gray-400">Nenhuma noticia publicada ainda.</div>
        @endforelse
    </div>
    <div class="mt-8">{{ $news->links() }}</div>
</div>
@endsection

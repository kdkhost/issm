@extends("layouts.app")
@section("title", "Noticias - ISSM")
@section("content")
<div class="bg-green-800 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-black text-white">Noticias</h1>
        <p class="text-green-200 mt-2">Fique por dentro das novidades do ISSM</p>
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

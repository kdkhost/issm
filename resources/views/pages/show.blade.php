@extends("layouts.app")
@section("title", ($page->meta_title ?? $page->title) . " - ISSM")
@section("content")
<div class="bg-green-800 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-black text-white">{{ $page->title }}</h1>
    </div>
</div>
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="prose prose-green max-w-none text-gray-700 leading-relaxed">{!! nl2br(e($page->content)) !!}</div>
</div>
@endsection

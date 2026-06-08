@extends("layouts.app")
@section("title", ($page->meta_title ?? $page->title) . " - ISSM")

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
            <a href="{{ route(""home"") }}" style="color:#86efac;text-decoration:none;transition:color .2s;">Início</a>
            <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span style="color:#fff;">{{ $page->title }}</span>
        </div>
        <h1 style="font-size:clamp(2rem,5vw,3rem);font-weight:900;color:#fff;line-height:1.1;margin-bottom:8px;">
            {{ $page->title }}
        </h1>
        @if($page->excerpt)
        <p style="font-size:16px;color:#bbf7d0;max-width:600px;margin-bottom:20px;">
            {{ $page->excerpt }}
        </p>
        @endif
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="prose prose-green max-w-none text-gray-700 leading-relaxed">{!! nl2br(e($page->content)) !!}</div>
</div>
@endsection

{{-- @autor marcelo-brad rj --}}
{{-- @contato Tel: 21 981325441 | Email: contato@kdkhost.com.br | Telegram: @MARCELO_BRAD | Instagram: @marcelobradrj | WhatsApp: 21981325441 --}}
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
@if($page && $sections && $sections->count() > 0)
    @foreach($sections as $section)
        @if($section->is_active)
            @php
                $blockView = "public.cms.blocks." . $section->type;
            @endphp
            @if(view()->exists($blockView))
                @include($blockView, ["section" => $section, "blocks" => $section->blocks()->where("is_active", true)->orderBy("order")->get(), "page" => $page])
            @else
                <section class="py-12 bg-white">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="text-center text-gray-400">
                            <p>Bloco do tipo <strong>{{ $section->type }}</strong> não encontrado.</p>
                        </div>
                    </div>
                </section>
            @endif
        @endif
    @endforeach
@else
    @if($page && $page->content)
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="prose prose-green max-w-none">
                {!! $page->content !!}
            </div>
        </div>
    </section>
    @else
    <section class="py-20 bg-gray-50">
        <div class="max-w-3xl mx-auto px-4 text-center">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">{{ $page->title }}</h1>
            <div class="prose prose-lg mx-auto text-gray-600">
                {!! $page->content ?? "<p>Conteúdo em breve.</p>" !!}
            </div>
        </div>
    </section>
    @endif
@endif
@endsection

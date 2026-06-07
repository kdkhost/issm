{{-- @autor marcelo-brad rj --}}
{{-- @contato Tel: 21 981325441 | Email: contato@kdkhost.com.br | Telegram: @MARCELO_BRAD | Instagram: @marcelobradrj | WhatsApp: 21981325441 --}}
@php
    $settings = $section->settings ?? [];
    $title = $settings["title"] ?? ($section->blocks->first()?->title ?? $section->name);
    $content = $settings["content"] ?? ($section->blocks->first()?->content ?? "");
    $bgColor = $settings["background_color"] ?? "bg-white";
@endphp
<section class="{{ $bgColor }} py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            @if($title)
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-800 mb-6">{{ $title }}</h2>
            @endif
            @if($content)
            <div class="prose prose-green prose-lg max-w-none text-gray-700">
                {!! $content !!}
            </div>
            @else
            @foreach($blocks as $block)
            @if($block->content)
            <div class="prose prose-green prose-lg max-w-none text-gray-700">
                {!! $block->content !!}
            </div>
            @endif
            @endforeach
            @endif
        </div>
    </div>
</section>

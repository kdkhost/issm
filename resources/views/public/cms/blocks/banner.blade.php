{{-- @autor marcelo-brad rj --}}
{{-- @contato Tel: 21 981325441 | Email: contato@kdkhost.com.br | Telegram: @MARCELO_BRAD | Instagram: @marcelobradrj | WhatsApp: 21981325441 --}}
@php
    $settings = $section->settings ?? [];
    $bgColor = $settings["background_color"] ?? "bg-green-800";
    $textColor = $settings["text_color"] ?? "text-white";
    $title = $settings["title"] ?? ($section->blocks->first()?->title ?? $section->name);
    $content = $settings["content"] ?? ($section->blocks->first()?->content ?? "");
    $ctaText = $settings["cta_text"] ?? "Saiba Mais";
    $ctaUrl = $settings["cta_url"] ?? "#";
@endphp
<section class="{{ $bgColor }} py-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        @if($title)
        <h2 class="text-3xl sm:text-4xl font-bold {{ $textColor }} mb-4">{{ $title }}</h2>
        @endif
        @if($content)
        <p class="text-lg {{ str_replace("text-", "text-", $textColor) }}-200/90 max-w-3xl mx-auto mb-6">{{ $content }}</p>
        @endif
        @if($ctaUrl && $ctaText)
        <a href="{{ $ctaUrl }}" class="inline-block bg-white text-green-800 font-bold px-8 py-3 rounded-full text-lg hover:bg-gray-100 transition-colors shadow-md">
            {{ $ctaText }}
        </a>
        @endif
    </div>
</section>

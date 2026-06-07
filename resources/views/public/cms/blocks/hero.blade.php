{{-- @autor marcelo-brad rj --}}
{{-- @contato Tel: 21 981325441 | Email: contato@kdkhost.com.br | Telegram: @MARCELO_BRAD | Instagram: @marcelobradrj | WhatsApp: 21981325441 --}}
@php
    $bgImage = "";
    $title = $section->name ?? "";
    $subtitle = "";
    $ctaText = "Saiba Mais";
    $ctaUrl = "#";
    $overlay = "rgba(0,0,0,0.5)";
    $textColor = "text-white";
    $align = "center";
    foreach ($blocks ?? [] as $block) {
        if ($block->type == "image" && !$bgImage) $bgImage = $block->content;
        if ($block->type == "text") {
            if (!$title) $title = $block->title;
            if (!$subtitle) $subtitle = $block->content;
        }
    }
    $settings = $section->settings ?? [];
    $bgImage = $settings["background_image"] ?? $bgImage;
    $title = $settings["title"] ?? $title;
    $subtitle = $settings["subtitle"] ?? $subtitle;
    $ctaText = $settings["cta_text"] ?? $ctaText;
    $ctaUrl = $settings["cta_url"] ?? $ctaUrl;
    $overlayColor = $settings["overlay_color"] ?? $overlay;
    $textAlign = $settings["text_align"] ?? $align;
    $height = $settings["height"] ?? "min-h-[60vh]";
@endphp
<section class="relative {{ $height }} flex items-center overflow-hidden" style="background-size:cover;background-position:center;background-image:{{ $bgImage ? "url('".asset("media/".$bgImage)."')" : "" }};">
    <div class="absolute inset-0" style="background:{{ $overlayColor }};"></div>
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full text-{{ $textAlign }}">
        @if($title)
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold {{ $textColor }} mb-6 leading-tight">{{ $title }}</h1>
        @endif
        @if($subtitle)
        <p class="text-xl sm:text-2xl {{ str_replace("text-", "text-", $textColor) }}-200/90 max-w-3xl {{ $textAlign == "center" ? "mx-auto" : "" }} mb-8">{{ $subtitle }}</p>
        @endif
        @if($ctaUrl && $ctaText)
        <a href="{{ $ctaUrl }}" class="inline-block bg-green-700 text-white font-bold px-8 py-4 rounded-full text-lg hover:bg-green-800 transition-colors shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all">
            {{ $ctaText }}
        </a>
        @endif
    </div>
</section>

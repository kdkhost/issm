{{-- @autor marcelo-brad rj --}}
{{-- @contato Tel: 21 981325441 | Email: contato@kdkhost.com.br | Telegram: @MARCELO_BRAD | Instagram: @marcelobradrj | WhatsApp: 21981325441 --}}
@php
    $settings = $section->settings ?? [];
    $title = $settings["title"] ?? $section->name;
    $description = $settings["description"] ?? "";
    $buttonText = $settings["button_text"] ?? "Entre em Contato";
    $buttonUrl = $settings["button_url"] ?? route("contact.index");
    $bgColor = $settings["background_color"] ?? "bg-green-800";
    $textColor = $settings["text_color"] ?? "text-white";
    $buttonStyle = $settings["button_style"] ?? "light";
    $buttonClass = $buttonStyle === "light" ? "bg-white text-green-800 hover:bg-gray-100" : "bg-green-700 text-white hover:bg-green-800 border-2 border-white/30 hover:border-white/50";
    $icon = $settings["icon"] ?? null;
@endphp
<section class="{{ $bgColor }} py-16 sm:py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        @if($icon)
        <div class="w-16 h-16 mx-auto mb-6 rounded-full bg-white/20 flex items-center justify-center">
            {!! $icon !!}
        </div>
        @endif
        @if($title)
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold {{ $textColor }} mb-4">{{ $title }}</h2>
        @endif
        @if($description)
        <p class="text-lg sm:text-xl {{ str_replace("text-", "text-", $textColor) }}-200/90 max-w-2xl mx-auto mb-8">{{ $description }}</p>
        @endif
        @if($buttonUrl && $buttonText)
        <a href="{{ $buttonUrl }}" class="inline-flex items-center gap-2 {{ $buttonClass }} font-bold px-8 py-4 rounded-full text-lg transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
            {{ $buttonText }}
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
        @endif
    </div>
</section>

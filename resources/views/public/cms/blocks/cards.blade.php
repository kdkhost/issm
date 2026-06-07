{{-- @autor marcelo-brad rj --}}
{{-- @contato Tel: 21 981325441 | Email: contato@kdkhost.com.br | Telegram: @MARCELO_BRAD | Instagram: @marcelobradrj | WhatsApp: 21981325441 --}}
@php
    $settings = $section->settings ?? [];
    $title = $settings["title"] ?? $section->name;
    $description = $settings["description"] ?? "";
    $columns = $settings["columns"] ?? "3";
    $bgColor = $settings["background_color"] ?? "bg-gray-50";
    $cardBg = $settings["card_background"] ?? "bg-white";
    $cards = $settings["cards"] ?? [];
    $gridClass = match((int)$columns) {
        1 => "grid-cols-1",
        2 => "grid-cols-1 sm:grid-cols-2",
        3 => "grid-cols-1 sm:grid-cols-2 lg:grid-cols-3",
        4 => "grid-cols-1 sm:grid-cols-2 lg:grid-cols-4",
        default => "grid-cols-1 sm:grid-cols-2 lg:grid-cols-3",
    };
@endphp
<section class="{{ $bgColor }} py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($title)
        <div class="text-center mb-12">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-800">{{ $title }}</h2>
            @if($description)
            <p class="text-lg text-gray-600 mt-4 max-w-3xl mx-auto">{{ $description }}</p>
            @endif
        </div>
        @endif
        <div class="grid {{ $gridClass }} gap-6 lg:gap-8">
            @forelse($cards as $card)
            <div class="{{ $cardBg }} rounded-xl shadow-sm hover:shadow-md transition-shadow overflow-hidden border border-gray-100">
                @if(isset($card["image"]))
                <img src="{{ asset("media/" . $card["image"]) }}" alt="{{ $card["title"] ?? "" }}" class="w-full h-48 object-cover">
                @endif
                <div class="p-6">
                    @if(isset($card["icon"]))
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                        {!! $card["icon"] !!}
                    </div>
                    @endif
                    @if(isset($card["title"]))
                    <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $card["title"] }}</h3>
                    @endif
                    @if(isset($card["description"]))
                    <p class="text-gray-600 leading-relaxed">{{ $card["description"] }}</p>
                    @endif
                    @if(isset($card["cta_text"]) && isset($card["cta_url"]))
                    <a href="{{ $card["cta_url"] }}" class="inline-flex items-center text-green-700 font-medium mt-4 hover:text-green-800">
                        {{ $card["cta_text"] }}
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    @endif
                </div>
            </div>
            @empty
            @foreach($blocks as $block)
            <div class="{{ $cardBg }} rounded-xl shadow-sm hover:shadow-md transition-shadow overflow-hidden border border-gray-100 p-6">
                @if($block->title)
                <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $block->title }}</h3>
                @endif
                @if($block->content)
                <p class="text-gray-600">{{ strip_tags($block->content) }}</p>
                @endif
            </div>
            @endforeach
            @endforelse
        </div>
    </div>
</section>

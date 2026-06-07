{{-- @autor marcelo-brad rj --}}
{{-- @contato Tel: 21 981325441 | Email: contato@kdkhost.com.br | Telegram: @MARCELO_BRAD | Instagram: @marcelobradrj | WhatsApp: 21981325441 --}}
@php
    $settings = $section->settings ?? [];
    $title = $settings["title"] ?? $section->name;
    $description = $settings["description"] ?? "";
    $columns = $settings["columns"] ?? "3";
    $bgColor = $settings["background_color"] ?? "bg-white";
    $lightbox = $settings["enable_lightbox"] ?? true;
    $images = $settings["images"] ?? [];
    $gridClass = match((int)$columns) {
        2 => "grid-cols-1 sm:grid-cols-2",
        3 => "grid-cols-1 sm:grid-cols-2 md:grid-cols-3",
        4 => "grid-cols-1 sm:grid-cols-2 md:grid-cols-4",
        5 => "grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5",
        default => "grid-cols-1 sm:grid-cols-2 md:grid-cols-3",
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
        <div class="grid {{ $gridClass }} gap-4">
            @forelse($images as $image)
            <div class="group relative overflow-hidden rounded-lg shadow-sm hover:shadow-md transition-shadow">
                <img src="{{ asset("media/" . $image["file"]) }}" alt="{{ $image["alt"] ?? "" }}" class="w-full h-48 sm:h-56 object-cover transition-transform duration-300 group-hover:scale-105">
                @if(isset($image["caption"]))
                <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/70 to-transparent p-3 opacity-0 group-hover:opacity-100 transition-opacity">
                    <p class="text-white text-sm">{{ $image["caption"] }}</p>
                </div>
                @endif
                @if($lightbox)
                <a href="{{ asset("media/" . $image["file"]) }}" class="absolute inset-0" data-lightbox="gallery-{{ $section->id }}" data-title="{{ $image["alt"] ?? "" }}" aria-label="Ampliar imagem"></a>
                @endif
            </div>
            @empty
            @foreach($blocks ?? [] as $block)
            @if($block->content && file_exists(public_path("media/" . $block->content)))
            <div class="group relative overflow-hidden rounded-lg shadow-sm hover:shadow-md">
                <img src="{{ asset("media/" . $block->content) }}" alt="{{ $block->title ?? "" }}" class="w-full h-48 sm:h-56 object-cover transition-transform duration-300 group-hover:scale-105">
                @if($lightbox)
                <a href="{{ asset("media/" . $block->content) }}" class="absolute inset-0" data-lightbox="gallery-{{ $section->id }}" data-title="{{ $block->title ?? "" }}"></a>
                @endif
            </div>
            @endif
            @endforeach
            @endforelse
        </div>
    </div>
</section>

@if($lightbox)
@push("scripts")
<script src="https://cdn.jsdelivr.net/npm/simplelightbox@2.14.1/dist/simple-lightbox.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simplelightbox@2.14.1/dist/simple-lightbox.min.css">
<script>
document.addEventListener("DOMContentLoaded", function() {
    var galleries = document.querySelectorAll("[data-lightbox]");
    if (galleries.length && typeof SimpleLightbox !== "undefined") {
        new SimpleLightbox("[data-lightbox]", { captionDelay: 300, captionsData: "title" });
    }
});
</script>
@endpush
@endif

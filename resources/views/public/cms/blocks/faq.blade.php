{{-- @autor marcelo-brad rj --}}
{{-- @contato Tel: 21 981325441 | Email: contato@kdkhost.com.br | Telegram: @MARCELO_BRAD | Instagram: @marcelobradrj | WhatsApp: 21981325441 --}}
@php
    $settings = $section->settings ?? [];
    $title = $settings["title"] ?? $section->name;
    $description = $settings["description"] ?? "";
    $bgColor = $settings["background_color"] ?? "bg-gray-50";
    $faqItems = $settings["items"] ?? [];
    $openFirst = $settings["open_first"] ?? true;
@endphp
<section class="{{ $bgColor }} py-16">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($title)
        <div class="text-center mb-12">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-800">{{ $title }}</h2>
            @if($description)
            <p class="text-lg text-gray-600 mt-4">{{ $description }}</p>
            @endif
        </div>
        @endif
        <div class="space-y-3">
            @forelse($faqItems as $index => $item)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <button type="button" class="faq-toggle w-full flex items-center justify-between p-4 sm:p-5 text-left focus:outline-none" aria-expanded="{{ $index === 0 && $openFirst ? "true" : "false" }}">
                    <span class="font-semibold text-gray-800 pr-4">{{ $item["question"] }}</span>
                    <svg class="w-5 h-5 text-gray-400 flex-shrink-0 transition-transform {{ $index === 0 && $openFirst ? "rotate-180" : "" }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="faq-answer px-4 sm:px-5 pb-4 sm:pb-5 {{ $index === 0 && $openFirst ? "" : "hidden" }}">
                    <div class="text-gray-600 leading-relaxed prose prose-sm max-w-none">
                        {!! $item["answer"] !!}
                    </div>
                </div>
            </div>
            @empty
            @foreach($blocks as $block)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <button type="button" class="faq-toggle w-full flex items-center justify-between p-4 sm:p-5 text-left focus:outline-none" aria-expanded="false">
                    <span class="font-semibold text-gray-800 pr-4">{{ $block->title ?? "Pergunta" }}</span>
                    <svg class="w-5 h-5 text-gray-400 flex-shrink-0 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="faq-answer px-4 sm:px-5 pb-4 sm:pb-5 hidden">
                    <div class="text-gray-600 leading-relaxed">{{ strip_tags($block->content) }}</div>
                </div>
            </div>
            @endforeach
            @endforelse
        </div>
    </div>
</section>

@push("scripts")
<script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".faq-toggle").forEach(function(btn) {
        btn.addEventListener("click", function() {
            var answer = this.nextElementSibling;
            var expanded = this.getAttribute("aria-expanded") === "true";
            answer.classList.toggle("hidden");
            this.setAttribute("aria-expanded", !expanded);
            this.querySelector("svg").classList.toggle("rotate-180");
        });
    });
});
</script>
@endpush

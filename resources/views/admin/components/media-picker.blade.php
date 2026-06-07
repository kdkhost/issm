{{-- @autor marcelo-brad rj --}}
{{-- @contato Tel: 21 981325441 | Email: contato@kdkhost.com.br | Telegram: @MARCELO_BRAD | Instagram: @marcelobradrj | WhatsApp: 21981325441 --}}
@php
    $inputId = $inputId ?? "media_input";
    $previewId = $previewId ?? "media_preview";
    $buttonText = $buttonText ?? "Selecionar Mídia";
    $selectedId = $selectedId ?? null;
@endphp
<div {{ $attributes->except(["inputId", "previewId", "buttonText", "selectedId"]) }}>
    <div id="{{ $previewId }}" class="mb-2">
        @if($selectedId)
        @php $selectedMedia = \App\Models\Media::find($selectedId); @endphp
        @if($selectedMedia && str_starts_with($selectedMedia->mime_type ?? $selectedMedia->type, "image/"))
        <img src="{{ asset("media/" . $selectedMedia->filename) }}" class="h-24 w-auto object-cover rounded border">
        @endif
        @endif
    </div>
    <input type="hidden" name="{{ $inputId }}" id="{{ $inputId }}" value="{{ $selectedId }}">
    <button type="button" class="open-media-picker bg-gray-100 text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-200 text-sm font-medium flex items-center gap-2" data-input="{{ $inputId }}" data-preview="{{ $previewId }}">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        {{ $buttonText }}
    </button>
</div>

<div id="media-picker-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40" style="backdrop-filter:blur(2px);">
    <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full mx-4 max-h-[85vh] flex flex-col">
        <div class="flex items-center justify-between p-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-800">Selecionar Mídia</h3>
            <button type="button" class="close-media-picker text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto p-4">
            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-3" id="media-picker-grid">
                @php $pickerMedia = \App\Models\Media::where("type", "image")->latest()->take(30)->get(); @endphp
                @forelse($pickerMedia as $m)
                <div class="media-picker-item cursor-pointer border-2 border-transparent rounded-lg overflow-hidden hover:border-green-500 transition-colors" data-id="{{ $m->id }}" data-url="{{ asset("media/" . $m->filename) }}">
                    <img src="{{ asset("media/" . $m->filename) }}" alt="{{ $m->name }}" class="w-full h-20 object-cover">
                    <p class="text-xs text-gray-600 truncate p-1">{{ $m->name ?? $m->filename }}</p>
                </div>
                @empty
                <div class="col-span-full text-center py-10 text-gray-400">Nenhuma imagem disponível.</div>
                @endforelse
            </div>
        </div>
        <div class="p-4 border-t border-gray-100 flex justify-end">
            <button type="button" class="close-media-picker text-gray-600 hover:text-gray-800 font-medium">Fechar</button>
        </div>
    </div>
</div>

@push("scripts")
<script>
$(function() {
    var activeInput = null;
    var activePreview = null;

    $(document).on("click", ".open-media-picker", function() {
        activeInput = $(this).data("input");
        activePreview = $(this).data("preview");
        $("#media-picker-modal").removeClass("hidden");
    });

    $(document).on("click", ".close-media-picker, #media-picker-modal", function(e) {
        if (e.target === this || $(e.target).hasClass("close-media-picker")) {
            $("#media-picker-modal").addClass("hidden");
        }
    });

    $(document).on("click", ".media-picker-item", function() {
        var id = $(this).data("id");
        var url = $(this).data("url");
        if (activeInput) {
            $("#" + activeInput).val(id);
            if (activePreview) {
                $("#" + activePreview).html('<img src="' + url + '" class="h-24 w-auto object-cover rounded border">');
            }
        }
        $("#media-picker-modal").addClass("hidden");
    });
});
</script>
@endpush

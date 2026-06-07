{{-- @autor marcelo-brad rj --}}
{{-- @contato Tel: 21 981325441 | Email: contato@kdkhost.com.br | Telegram: @MARCELO_BRAD | Instagram: @marcelobradrj | WhatsApp: 21981325441 --}}
@php
    $editUrl = $editUrl ?? null;
    $deleteUrl = $deleteUrl ?? null;
    $viewUrl = $viewUrl ?? null;
    $toggleUrl = $toggleUrl ?? null;
    $isActive = $isActive ?? null;
    $deleteMessage = $deleteMessage ?? "Excluir este registro permanentemente?";
    $extraButtons = $extraButtons ?? [];
@endphp
<div class="flex items-center gap-1 {{ $attributes->get("class") }}">
    @if($viewUrl)
    <a href="{{ $viewUrl }}" class="text-green-600 hover:text-green-800 text-sm font-medium px-1" data-tooltip="Visualizar" {{ str_contains($viewUrl, "http") && !str_contains($viewUrl, url("/")) ? 'target="_blank"' : "" }}>
        <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
        Ver
    </a>
    @endif
    @if($editUrl)
    <a href="{{ $editUrl }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium px-1" data-tooltip="Editar">
        <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        Editar
    </a>
    @endif
    @if($toggleUrl !== null)
    <form method="POST" action="{{ $toggleUrl }}" class="inline">
        @csrf @method("PATCH")
        <button type="submit" class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors {{ $isActive ? "bg-green-600" : "bg-gray-300" }}" data-tooltip="{{ $isActive ? "Desativar" : "Ativar" }}">
            <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white transition-transform {{ $isActive ? "translate-x-[18px]" : "translate-x-1" }}"></span>
        </button>
    </form>
    @endif
    @foreach($extraButtons as $btn)
    @if(isset($btn["url"]))
    <a href="{{ $btn["url"] }}" class="text-sm font-medium px-1 {{ $btn["class"] ?? "text-gray-600 hover:text-gray-800" }}" {{ isset($btn["tooltip"]) ? 'data-tooltip="'.$btn["tooltip"].'"' : "" }} {{ ($btn["target"] ?? "") === "_blank" ? 'target="_blank"' : "" }}>
        @if(isset($btn["icon"]))<svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $btn["icon"] !!}</svg>@endif
        {{ $btn["label"] ?? "" }}
    </a>
    @endif
    @endforeach
    @if($deleteUrl)
    <form method="POST" action="{{ $deleteUrl }}" class="inline">
        @csrf @method("DELETE")
        <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium px-1" data-confirm="{{ $deleteMessage }}" data-tooltip="Excluir">
            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            Excluir
        </button>
    </form>
    @endif
</div>

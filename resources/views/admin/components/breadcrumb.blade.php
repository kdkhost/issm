{{-- @autor marcelo-brad rj --}}
{{-- @contato Tel: 21 981325441 | Email: contato@kdkhost.com.br | Telegram: @MARCELO_BRAD | Instagram: @marcelobradrj | WhatsApp: 21981325441 --}}
{{--
    Usage:
    @component("admin.components.breadcrumb", ["items" => [
        ["label" => "Dashboard", "url" => route("admin.dashboard")],
        ["label" => "Páginas", "url" => route("admin.cms.pages.index")],
        ["label" => "Nova Página", "url" => null],
    ]]) @endcomponent
--}}
@php
    $items = $items ?? [];
    $homeUrl = $homeUrl ?? route("admin.dashboard");
    $homeLabel = $homeLabel ?? "Dashboard";
@endphp
<nav class="flex items-center gap-1 text-xs text-gray-400" aria-label="Breadcrumb">
    <a href="{{ $homeUrl }}" class="hover:text-green-700 transition-colors">{{ $homeLabel }}</a>
    @foreach($items as $item)
    <svg class="w-3 h-3 flex-shrink-0 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
    @if($item["url"] && !$loop->last)
    <a href="{{ $item["url"] }}" class="hover:text-green-700 transition-colors">{{ $item["label"] }}</a>
    @else
    <span class="{{ $loop->last ? "text-gray-600 font-medium" : "" }}">{{ $item["label"] }}</span>
    @endif
    @endforeach
</nav>

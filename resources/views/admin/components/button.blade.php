{{-- @autor marcelo-brad rj --}}
{{-- @contato Tel: 21 981325441 | Email: contato@kdkhost.com.br | Telegram: @MARCELO_BRAD | Instagram: @marcelobradrj | WhatsApp: 21981325441 --}}
@php
    $variant = $variant ?? "primary";
    $size = $size ?? "md";
    $loading = $loading ?? false;
    $disabled = $disabled ?? false;
    $icon = $icon ?? null;
    $type = $type ?? "button";

    $variantClasses = [
        "primary"   => "bg-green-700 text-white hover:bg-green-800 focus:ring-green-500",
        "secondary" => "bg-gray-600 text-white hover:bg-gray-700 focus:ring-gray-500",
        "danger"    => "bg-red-600 text-white hover:bg-red-700 focus:ring-red-500",
        "warning"   => "bg-yellow-500 text-white hover:bg-yellow-600 focus:ring-yellow-500",
        "info"      => "bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500",
        "ghost"     => "text-gray-600 hover:text-gray-800 hover:bg-gray-100 focus:ring-gray-300",
    ];
    $sizeClasses = [
        "sm"  => "px-3 py-1.5 text-xs",
        "md"  => "px-4 py-2 text-sm",
        "lg"  => "px-6 py-3 text-base",
        "xl"  => "px-8 py-4 text-lg",
    ];
    $base = "inline-flex items-center justify-center gap-2 font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed";
    $class = $base . " " . ($variantClasses[$variant] ?? $variantClasses["primary"]) . " " . ($sizeClasses[$size] ?? $sizeClasses["md"]) . " " . ($attributes->get("class") ?? "");
@endphp
<button type="{{ $type }}" {{ $attributes->merge(["class" => $class])->except(["variant", "size", "loading", "icon"]) }} {{ $disabled || $loading ? "disabled" : "" }}>
    @if($loading)
    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
    </svg>
    @endif
    @if($icon && !$loading)
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $icon !!}</svg>
    @endif
    {{ $slot }}
</button>

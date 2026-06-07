{{-- @autor marcelo-brad rj --}}
{{-- @contato Tel: 21 981325441 | Email: contato@kdkhost.com.br | Telegram: @MARCELO_BRAD | Instagram: @marcelobradrj | WhatsApp: 21981325441 --}}
@php
    $name = $name ?? $attributes->get("name") ?? "";
    $label = $label ?? null;
    $help = $help ?? null;
    $error = $error ?? ($errors->has($name) ? $errors->first($name) : null);
    $required = $required ?? false;
    $type = $type ?? "text";
    $id = $id ?? $name;
@endphp
<div class="{{ $attributes->get("wrapper-class") ?? "" }}">
    @if($label)
    <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 mb-1">
        {{ $label }}
        @if($required)<span class="text-red-500">*</span>@endif
    </label>
    @endif
    <input type="{{ $type }}"
           name="{{ $name }}"
           id="{{ $id }}"
           {{ $required ? "required" : "" }}
           {{ $attributes->except(["wrapper-class", "label", "help", "error", "required", "name", "type", "id"]) }}
           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 {{ $error ? "border-red-500" : "" }} {{ $attributes->get("class") }}">
    @if($help)
    <p class="text-xs text-gray-400 mt-1">{{ $help }}</p>
    @endif
    @if($error)
    <p class="text-red-500 text-xs mt-1">{{ $error }}</p>
    @endif
</div>

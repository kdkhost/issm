{{-- @autor marcelo-brad rj --}}
{{-- @contato Tel: 21 981325441 | Email: contato@kdkhost.com.br | Telegram: @MARCELO_BRAD | Instagram: @marcelobradrj | WhatsApp: 21981325441 --}}
@php
    $name = $name ?? $attributes->get("name") ?? "";
    $label = $label ?? null;
    $error = $error ?? ($errors->has($name) ? $errors->first($name) : null);
    $required = $required ?? false;
    $id = $id ?? $name;
    $help = $help ?? null;
    $rows = $rows ?? 4;
@endphp
<div class="{{ $attributes->get("wrapper-class") ?? "" }}">
    @if($label)
    <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 mb-1">
        {{ $label }}
        @if($required)<span class="text-red-500">*</span>@endif
    </label>
    @endif
    <textarea name="{{ $name }}"
              id="{{ $id }}"
              rows="{{ $rows }}"
              {{ $required ? "required" : "" }}
              {{ $attributes->except(["wrapper-class", "label", "error", "required", "name", "id", "help", "rows"]) }}
              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 {{ $error ? "border-red-500" : "" }} {{ $attributes->get("class") }}">{{ $slot }}{{ old($name, $attributes->get("value")) }}</textarea>
    @if($help)
    <p class="text-xs text-gray-400 mt-1">{{ $help }}</p>
    @endif
    @if($error)
    <p class="text-red-500 text-xs mt-1">{{ $error }}</p>
    @endif
</div>

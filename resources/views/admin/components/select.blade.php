{{-- @autor marcelo-brad rj --}}
{{-- @contato Tel: 21 981325441 | Email: contato@kdkhost.com.br | Telegram: @MARCELO_BRAD | Instagram: @marcelobradrj | WhatsApp: 21981325441 --}}
@php
    $name = $name ?? $attributes->get("name") ?? "";
    $label = $label ?? null;
    $error = $error ?? ($errors->has($name) ? $errors->first($name) : null);
    $required = $required ?? false;
    $id = $id ?? $name;
    $options = $options ?? [];
    $placeholder = $placeholder ?? "Selecione...";
    $selected = $selected ?? old($name, $attributes->get("value") ?? "");
@endphp
<div class="{{ $attributes->get("wrapper-class") ?? "" }}">
    @if($label)
    <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 mb-1">
        {{ $label }}
        @if($required)<span class="text-red-500">*</span>@endif
    </label>
    @endif
    <select name="{{ $name }}"
            id="{{ $id }}"
            {{ $required ? "required" : "" }}
            {{ $attributes->except(["wrapper-class", "label", "error", "required", "name", "id", "options", "placeholder", "selected"]) }}
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 {{ $error ? "border-red-500" : "" }} {{ $attributes->get("class") }}">
        @if($placeholder)
        <option value="">{{ $placeholder }}</option>
        @endif
        @foreach($options as $value => $text)
        <option value="{{ $value }}" {{ (string)$selected === (string)$value ? "selected" : "" }}>{{ $text }}</option>
        @endforeach
    </select>
    @if($error)
    <p class="text-red-500 text-xs mt-1">{{ $error }}</p>
    @endif
</div>

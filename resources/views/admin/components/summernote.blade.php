{{-- @autor marcelo-brad rj --}}
{{-- @contato Tel: 21 981325441 | Email: contato@kdkhost.com.br | Telegram: @MARCELO_BRAD | Instagram: @marcelobradrj | WhatsApp: 21981325441 --}}
@php
    $name = $name ?? $attributes->get("name") ?? "";
    $label = $label ?? null;
    $error = $error ?? ($errors->has($name) ? $errors->first($name) : null);
    $required = $required ?? false;
    $id = $id ?? $name;
    $height = $height ?? 300;
    $toolbar = $toolbar ?? "default";
    $value = $value ?? old($name, $attributes->get("value") ?? "");
@endphp
<div class="{{ $attributes->get("wrapper-class") ?? "" }}">
    @if($label)
    <label class="block text-sm font-medium text-gray-700 mb-1">
        {{ $label }}
        @if($required)<span class="text-red-500">*</span>@endif
    </label>
    @endif
    <textarea name="{{ $name }}"
              id="{{ $id }}"
              {{ $required ? "required" : "" }}
              class="wysiwyg {{ $attributes->get("class") }}"
              data-height="{{ $height }}"
              {{ $attributes->except(["wrapper-class", "label", "error", "required", "name", "id", "height", "toolbar", "value"]) }}>{{ $value }}</textarea>
    @if($error)
    <p class="text-red-500 text-xs mt-1">{{ $error }}</p>
    @endif
</div>

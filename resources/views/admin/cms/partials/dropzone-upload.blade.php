<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label ?? 'Arquivo' }}</label>
    <input type="file"
        name="{{ $fieldName ?? 'file' }}"
        {{ ($required ?? false) ? 'required' : '' }}
        data-auto-upload="{{ $uploadRoute ?? route('admin.cms.media.upload') }}"
        data-url-name="{{ $urlField ?? $fieldName ?? 'file' }}_path"
        data-hint="{{ $hint ?? 'PNG, JPG, PDF até 10MB' }}"
        data-existing-url="{{ $existingUrl ?? '' }}"
        accept="{{ $accept ?? '*' }}">
    <input type="hidden" name="{{ $urlField ?? $fieldName ?? 'file' }}_path" value="{{ $existingPath ?? '' }}">
    @error($fieldName ?? 'file')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
</div>
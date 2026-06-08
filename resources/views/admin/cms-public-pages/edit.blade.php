@extends("layouts.admin")
@section("title", "Editar " . $page->admin_label)
@section("page-title", "Editar: " . $page->admin_label)

@section("content")
<div class="mb-6 flex justify-between items-center">
    <div>
        <a href="{{ route('admin.cms-public-pages.index') }}" class="text-sm text-green-700 hover:text-green-900">&larr; Voltar</a>
        <p class="text-xs text-gray-500 mt-1">View: {{ $page->view_path }} | URL: {{ $page->route_uri }}</p>
    </div>
    @if($page->publicUrl())
    <a href="{{ $page->publicUrl() }}" target="_blank" class="text-sm text-blue-600 hover:text-blue-800 flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
        Visualizar página
    </a>
    @endif
</div>

{{-- Abas --}}
<div class="flex gap-1 mb-6 border-b border-gray-200">
    <a href="{{ route('admin.cms-public-pages.edit', $page) }}" class="px-4 py-2 text-sm font-medium text-green-700 border-b-2 border-green-700 bg-green-50 rounded-t-lg">
        Campos/Seções
    </a>
    <a href="{{ route('admin.cms-public-pages.edit-full-html', $page) }}" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-300">
        HTML Completo
    </a>
    @if($page->seo_enabled)
    <a href="{{ route('admin.cms-public-pages.seo', $page) }}" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-300">
        SEO
    </a>
    @endif
</div>

@if($page->use_custom_html)
<div class="mb-4 bg-amber-50 border border-amber-200 rounded-lg px-4 py-3 flex items-start gap-3">
    <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
    <div>
        <p class="text-sm font-medium text-amber-800">Modo HTML Completo ativo</p>
        <p class="text-xs text-amber-700 mt-1">Esta página está usando HTML personalizado. Os campos abaixo não serão exibidos no site enquanto o modo HTML Completo estiver ativo.</p>
    </div>
</div>
@endif

<form method="POST" action="{{ route('admin.cms-public-pages.update', $page) }}">
    @csrf
    @method('PUT')

    @foreach($page->sections as $section)
    <div class="bg-white rounded-xl shadow-sm mb-6 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-bold text-gray-800">{{ $section->section_label }}</h3>
            <p class="text-xs text-gray-500">Seção: {{ $section->section_key }}</p>
        </div>
        <div class="p-6 space-y-5">
            @foreach($fieldsBySection->get($section->section_key, collect()) as $field)
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">
                    {{ $field->field_label }}
                    @if($field->isPending())
                    <span class="text-orange-500 text-xs font-normal">(usando padrão)</span>
                    @endif
                </label>
                @if($field->field_type === 'html')
                <textarea name="fields[{{ $field->id }}]" class="wysiwyg w-full border border-gray-300 rounded-lg" data-height="200">{{ $field->field_value ?? $field->default_value }}</textarea>
                @elseif($field->field_type === 'textarea')
                <textarea name="fields[{{ $field->id }}]" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">{{ $field->field_value ?? $field->default_value }}</textarea>
                @else
                <input type="{{ $field->field_type === 'url' ? 'url' : 'text' }}" name="fields[{{ $field->id }}]" value="{{ $field->field_value ?? $field->default_value }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                @endif
                @if($field->help_text)
                <p class="text-xs text-gray-500 mt-1">{{ $field->help_text }}</p>
                @endif
                @if($field->default_value)
                <p class="text-xs text-gray-400 mt-1">Padrão: {{ Str::limit($field->default_value, 80) }}</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endforeach

    <div class="flex justify-end gap-3">
        <a href="{{ route('admin.cms-public-pages.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancelar</a>
        <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 font-medium">Salvar Conteúdo</button>
    </div>
</form>
@endsection

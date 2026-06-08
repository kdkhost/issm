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

    @php
    $heroFields = $page->fields->where('section_key', 'hero')->keyBy('field_key');
    $heroVal = fn($key, $default = '') => $heroFields->get($key)?->field_value ?? $default;
    @endphp

    {{-- Configuracoes do Banner --}}
    <div class="form-card mb-6">
        <h3 class="form-card-title">Configuracoes do Banner</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="form-group">
                <label class="form-label">Titulo do Banner</label>
                <input type="text" name="hero[title]" value="{{ $heroVal('title') }}" class="form-input" placeholder="Ex: Nossos Projetos">
            </div>
            <div class="form-group">
                <label class="form-label">Palavra em Destaque <small class="text-gray-400 font-normal">(dentro do titulo)</small></label>
                <input type="text" name="hero[title_highlight]" value="{{ $heroVal('title_highlight') }}" class="form-input" placeholder="Ex: Projetos">
            </div>
            <div class="form-group">
                <label class="form-label">Cor da Palavra Destacada</label>
                <div class="flex items-center gap-2">
                    <input type="color" name="hero[title_highlight_color]" value="{{ $heroVal('title_highlight_color', '#86efac') }}" class="w-10 h-10 rounded cursor-pointer border-0 p-0">
                    <input type="text" name="hero[title_highlight_color]" value="{{ $heroVal('title_highlight_color', '#86efac') }}" class="form-input flex-1" placeholder="#86efac">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Cor do Breadcrumb</label>
                <div class="flex items-center gap-2">
                    <input type="color" name="hero[breadcrumb_color]" value="{{ $heroVal('breadcrumb_color', '#86efac') }}" class="w-10 h-10 rounded cursor-pointer border-0 p-0">
                    <input type="text" name="hero[breadcrumb_color]" value="{{ $heroVal('breadcrumb_color', '#86efac') }}" class="form-input flex-1" placeholder="#86efac">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Cor do Subtitulo</label>
                <div class="flex items-center gap-2">
                    <input type="color" name="hero[subtitle_color]" value="{{ $heroVal('subtitle_color', '#bbf7d0') }}" class="w-10 h-10 rounded cursor-pointer border-0 p-0">
                    <input type="text" name="hero[subtitle_color]" value="{{ $heroVal('subtitle_color', '#bbf7d0') }}" class="form-input flex-1" placeholder="#bbf7d0">
                </div>
            </div>
            <div class="form-group md:col-span-2 border-t border-gray-100 pt-4 mt-2">
                <label class="form-label">Gradiente do Fundo</label>
                <div class="flex items-center gap-3 flex-wrap">
                    <div class="flex items-center gap-2">
                        <input type="color" name="hero[gradient_start]" value="{{ $heroVal('gradient_start', '#166534') }}" class="w-10 h-10 rounded cursor-pointer border-0 p-0">
                        <input type="text" name="hero[gradient_start]" value="{{ $heroVal('gradient_start', '#166534') }}" class="form-input w-28" placeholder="#166534">
                    </div>
                    <span class="text-gray-400">→</span>
                    <div class="flex items-center gap-2">
                        <input type="color" name="hero[gradient_mid]" value="{{ $heroVal('gradient_mid', '#15803d') }}" class="w-10 h-10 rounded cursor-pointer border-0 p-0">
                        <input type="text" name="hero[gradient_mid]" value="{{ $heroVal('gradient_mid', '#15803d') }}" class="form-input w-28" placeholder="#15803d">
                    </div>
                    <span class="text-gray-400">→</span>
                    <div class="flex items-center gap-2">
                        <input type="color" name="hero[gradient_end]" value="{{ $heroVal('gradient_end', '#059669') }}" class="w-10 h-10 rounded cursor-pointer border-0 p-0">
                        <input type="text" name="hero[gradient_end]" value="{{ $heroVal('gradient_end', '#059669') }}" class="form-input w-28" placeholder="#059669">
                    </div>
                </div>
            </div>
            <div class="form-group md:col-span-2 border-t border-gray-100 pt-4">
                <div class="flex items-center gap-3 mb-3">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="hero[title_use_gradient]" value="1" class="sr-only peer" {{ $heroVal('title_use_gradient') === '1' ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                        <span class="ml-3 text-sm font-medium text-gray-700">Gradiente no texto do titulo</span>
                    </label>
                </div>
                <div class="flex items-center gap-3 flex-wrap">
                    <div class="flex items-center gap-2">
                        <input type="color" name="hero[title_gradient_start]" value="{{ $heroVal('title_gradient_start', '#86efac') }}" class="w-10 h-10 rounded cursor-pointer border-0 p-0">
                        <input type="text" name="hero[title_gradient_start]" value="{{ $heroVal('title_gradient_start', '#86efac') }}" class="form-input w-28" placeholder="#86efac">
                    </div>
                    <span class="text-gray-400">→</span>
                    <div class="flex items-center gap-2">
                        <input type="color" name="hero[title_gradient_end]" value="{{ $heroVal('title_gradient_end', '#34d399') }}" class="w-10 h-10 rounded cursor-pointer border-0 p-0">
                        <input type="text" name="hero[title_gradient_end]" value="{{ $heroVal('title_gradient_end', '#34d399') }}" class="form-input w-28" placeholder="#34d399">
                    </div>
                </div>
            </div>
        </div>
    </div>

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
@push('scripts')
<script>
// Sincroniza inputs color <-> text
document.querySelectorAll('input[type="color"]').forEach(colorInput => {
    const name = colorInput.name;
    const textInput = document.querySelector('input[type="text"][name="' + name + '"');
    if (textInput) {
        colorInput.addEventListener('input', () => textInput.value = colorInput.value);
        textInput.addEventListener('input', () => colorInput.value = textInput.value);
    }
});
</script>
@endpush

@endsection

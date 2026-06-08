@extends("layouts.admin")
@section("title", "HTML Completo: " . $page->admin_label)
@section("page-title", "HTML Completo: " . $page->admin_label)

@section("content")
<div class="mb-6 flex justify-between items-center">
    <div>
        <a href="{{ route('admin.cms-public-pages.index') }}" class="text-sm text-green-700 hover:text-green-900">&larr; Voltar</a>
        <p class="text-xs text-gray-500 mt-1">View: {{ $page->view_path }} | URL: {{ $page->route_uri }}</p>
    </div>
    <div class="flex gap-2">
        @if($page->publicUrl())
        <a href="{{ $page->publicUrl() }}" target="_blank" class="text-sm text-blue-600 hover:text-blue-800 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            Visualizar página
        </a>
        @endif
    </div>
</div>

{{-- Abas --}}
<div class="flex gap-1 mb-6 border-b border-gray-200">
    <a href="{{ route('admin.cms-public-pages.edit', $page) }}" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-300">
        Campos/Seções
    </a>
    <a href="{{ route('admin.cms-public-pages.edit-full-html', $page) }}" class="px-4 py-2 text-sm font-medium text-green-700 border-b-2 border-green-700 bg-green-50 rounded-t-lg">
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
        <p class="text-xs text-amber-700 mt-1">A página está usando o HTML personalizado abaixo em vez dos campos individuais. Desative para voltar ao modo normal.</p>
    </div>
</div>
@endif

<form method="POST" action="{{ route('admin.cms-public-pages.update-full-html', $page) }}">
    @csrf
    @method('PUT')

    <div class="bg-white rounded-xl shadow-sm mb-6 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <div>
                <h3 class="font-bold text-gray-800">Editor HTML Completo</h3>
                <p class="text-xs text-gray-500">Edite todo o conteúdo HTML da página aqui. Use CSS inline ou classes do Tailwind.</p>
            </div>
            <div class="flex items-center gap-2">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="use_custom_html" value="1" class="sr-only peer" {{ $page->use_custom_html ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                    <span class="ml-3 text-sm font-medium text-gray-700">Usar HTML personalizado</span>
                </label>
            </div>
        </div>
        <div class="p-6">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Conteúdo HTML da Página</label>
            <textarea name="custom_html" id="custom_html_editor" rows="20" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono" style="min-height:400px; font-family: 'JetBrains Mono', 'Fira Code', Consolas, monospace; font-size: 13px; line-height: 1.6;">{{ old('custom_html', $page->custom_html) }}</textarea>
            <p class="text-xs text-gray-500 mt-2">Dica: inclua apenas o conteúdo da página (sem &lt;html&gt; ou &lt;body&gt;). O layout principal (header, footer) será mantido.</p>
        </div>
    </div>

    <div class="flex justify-end gap-3">
        <a href="{{ route('admin.cms-public-pages.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancelar</a>
        <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 font-medium">Salvar HTML Completo</button>
    </div>
</form>

@push('scripts')
<script>
// Auto-resize textarea
const textarea = document.getElementById('custom_html_editor');
if (textarea) {
    textarea.addEventListener('keydown', function(e) {
        if (e.key === 'Tab') {
            e.preventDefault();
            const start = this.selectionStart;
            const end = this.selectionEnd;
            this.value = this.value.substring(0, start) + '    ' + this.value.substring(end);
            this.selectionStart = this.selectionEnd = start + 4;
        }
    });
}
</script>
@endpush
@endsection

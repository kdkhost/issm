@extends("layouts.admin")
@section("title", "SEO — " . $page->admin_label)
@section("page-title", "SEO: " . $page->admin_label)

@section("content")
<div class="mb-6">
    <a href="{{ route('admin.cms-original-pages.index') }}" class="text-sm text-green-700 hover:text-green-900">&larr; Voltar</a>
</div>

<form method="POST" action="{{ route('admin.cms-original-pages.update-seo', $page) }}" class="max-w-3xl">
    @csrf
    @method('PUT')

    <div class="bg-white rounded-xl shadow-sm p-6 space-y-5">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Meta Title</label>
            <input type="text" name="meta_title" value="{{ old('meta_title', $page->seo?->meta_title) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Meta Description</label>
            <textarea name="meta_description" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">{{ old('meta_description', $page->seo?->meta_description) }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Meta Keywords</label>
            <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $page->seo?->meta_keywords) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>
        <hr class="border-gray-200">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">OG Title</label>
            <input type="text" name="og_title" value="{{ old('og_title', $page->seo?->og_title) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">OG Description</label>
            <textarea name="og_description" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">{{ old('og_description', $page->seo?->og_description) }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">OG Image (caminho media/)</label>
            <input type="text" name="og_image" value="{{ old('og_image', $page->seo?->og_image) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>
        <hr class="border-gray-200">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">URL Canônica</label>
            <input type="url" name="canonical_url" value="{{ old('canonical_url', $page->seo?->canonical_url ?? $page->publicUrl()) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Robots Meta</label>
            <input type="text" name="robots_meta" value="{{ old('robots_meta', $page->seo?->robots_meta ?? 'index, follow') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>
    </div>

    <div class="flex justify-end gap-3 mt-6">
        <a href="{{ route('admin.cms-original-pages.edit', $page) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Editar Conteúdo</a>
        <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 font-medium">Salvar SEO</button>
    </div>
</form>
@endsection

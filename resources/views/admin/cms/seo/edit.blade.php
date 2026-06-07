{{-- @autor marcelo-brad rj --}}
{{-- @contato Tel: 21 981325441 | Email: contato@kdkhost.com.br | Telegram: @MARCELO_BRAD | Instagram: @marcelobradrj | WhatsApp: 21981325441 --}}
@extends("layouts.admin")
@section("title", "SEO - {{ $page->title }}")
@section("page-title", "SEO: {{ $page->title }}")
@section("content")
<div class="max-w-4xl">
    <form method="POST" action="{{ route("admin.cms.seo.update", $page) }}">
        @csrf @method("PUT")
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Meta Tags Básicas
                </h3>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                    <input type="text" name="meta_title" value="{{ old("meta_title", $page->meta_title ?? "") }}" maxlength="60" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Título para SEO (máx. 60 caracteres)">
                    <p class="text-xs text-gray-400 mt-1">Recomendado: máximo 60 caracteres</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                    <textarea name="meta_description" rows="3" maxlength="160" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Descrição para SEO (máx. 160 caracteres)">{{ old("meta_description", $page->meta_description ?? "") }}</textarea>
                    <p class="text-xs text-gray-400 mt-1">Recomendado: máximo 160 caracteres</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Meta Keywords</label>
                    <input type="text" name="meta_keywords" value="{{ old("meta_keywords", $page->meta_keywords ?? "") }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="palavra-chave1, palavra-chave2, palavra-chave3">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Meta Robots</label>
                    <select name="meta_robots" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="index, follow" {{ old("meta_robots", $page->meta_robots ?? "index, follow") == "index, follow" ? "selected" : "" }}>Indexar e seguir links</option>
                        <option value="noindex, follow" {{ old("meta_robots", $page->meta_robots ?? "") == "noindex, follow" ? "selected" : "" }}>Não indexar, seguir links</option>
                        <option value="index, nofollow" {{ old("meta_robots", $page->meta_robots ?? "") == "index, nofollow" ? "selected" : "" }}>Indexar, não seguir links</option>
                        <option value="noindex, nofollow" {{ old("meta_robots", $page->meta_robots ?? "") == "noindex, nofollow" ? "selected" : "" }}>Não indexar, não seguir</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Canonical URL</label>
                    <input type="url" name="canonical_url" value="{{ old("canonical_url", $page->canonical_url ?? "") }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="https://www.exemplo.com/pagina">
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    Open Graph (Facebook / WhatsApp / LinkedIn)
                </h3>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">OG Title</label>
                    <input type="text" name="og_title" value="{{ old("og_title", $page->og_title ?? "") }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">OG Description</label>
                    <textarea name="og_description" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">{{ old("og_description", $page->og_description ?? "") }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">OG Image</label>
                    <div class="flex gap-3 items-start">
                        @if($page->og_image ?? false)
                        <img src="{{ asset("media/" . $page->og_image) }}" class="h-16 w-16 object-cover rounded">
                        @endif
                        <input type="file" name="og_image" accept="image/*" class="flex-1">
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Recomendado: 1200x630px</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">OG Type</label>
                    <select name="og_type" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="website" {{ old("og_type", $page->og_type ?? "website") == "website" ? "selected" : "" }}>Website</option>
                        <option value="article" {{ old("og_type", $page->og_type ?? "") == "article" ? "selected" : "" }}>Article</option>
                        <option value="profile" {{ old("og_type", $page->og_type ?? "") == "profile" ? "selected" : "" }}>Profile</option>
                    </select>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                    Twitter Card
                </h3>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Twitter Title</label>
                    <input type="text" name="twitter_title" value="{{ old("twitter_title", $page->twitter_title ?? "") }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Twitter Description</label>
                    <textarea name="twitter_description" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">{{ old("twitter_description", $page->twitter_description ?? "") }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Twitter Image</label>
                    <input type="file" name="twitter_image" accept="image/*" class="w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Twitter Card Type</label>
                    <select name="twitter_card" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="summary_large_image" {{ old("twitter_card", $page->twitter_card ?? "summary_large_image") == "summary_large_image" ? "selected" : "" }}>Summary Large Image</option>
                        <option value="summary" {{ old("twitter_card", $page->twitter_card ?? "") == "summary" ? "selected" : "" }}>Summary</option>
                    </select>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Sitemap & Indexação
                </h3>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prioridade no Sitemap</label>
                    <select name="sitemap_priority" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                        @foreach(["0.0","0.1","0.2","0.3","0.4","0.5","0.6","0.7","0.8","0.9","1.0"] as $p)
                        <option value="{{ $p }}" {{ old("sitemap_priority", $page->sitemap_priority ?? "0.5") == $p ? "selected" : "" }}>{{ $p }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Frequência de Atualização</label>
                    <select name="sitemap_frequency" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                        @foreach(["always","hourly","daily","weekly","monthly","yearly","never"] as $f)
                        <option value="{{ $f }}" {{ old("sitemap_frequency", $page->sitemap_frequency ?? "monthly") == $f ? "selected" : "" }}>{{ ucfirst($f) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="include_in_sitemap" value="1" id="include_in_sitemap" {{ old("include_in_sitemap", $page->include_in_sitemap ?? true) ? "checked" : "" }} class="w-4 h-4 text-green-600 rounded">
                    <label for="include_in_sitemap" class="text-sm font-medium text-gray-700">Incluir no Sitemap</label>
                </div>
            </div>

            <div class="flex justify-between pt-2">
                <a href="{{ route("admin.cms.pages.edit", $page) }}" class="text-gray-600 hover:text-gray-800 font-medium">← Voltar para a página</a>
                <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 font-medium">Salvar Configurações SEO</button>
            </div>
        </div>
    </form>
</div>
@endsection

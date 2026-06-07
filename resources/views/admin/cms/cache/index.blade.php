{{-- @autor marcelo-brad rj --}}
{{-- @contato Tel: 21 981325441 | Email: contato@kdkhost.com.br | Telegram: @MARCELO_BRAD | Instagram: @marcelobradrj | WhatsApp: 21981325441 --}}
@extends("layouts.admin")
@section("title", "Gerenciar Cache")
@section("page-title", "Gerenciamento de Cache")
@push("scripts")
<script>
$(function() {
    $(".clear-cache-btn").on("click", function(e) {
        e.preventDefault();
        var btn = $(this);
        var type = btn.data("type");
        var label = btn.data("label");
        Swal.fire({
            title: "Limpar " + label + "?",
            text: "O cache de " + label.toLowerCase() + " será limpo.",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#16a34a",
            cancelButtonColor: "#6b7280",
            confirmButtonText: "Sim, limpar",
            cancelButtonText: "Cancelar",
            reverseButtons: true,
            borderRadius: "16px",
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route("admin.cms.cache.clear") }}",
                    method: "POST",
                    data: {
                        _token: document.querySelector('meta[name="csrf-token"]').content,
                        type: type
                    },
                    success: function() {
                        showToast(label + " limpo com sucesso!", "success");
                    },
                    error: function() {
                        showToast("Erro ao limpar " + label.toLowerCase() + ".", "error");
                    }
                });
            }
        });
    });
});
</script>
@endpush
@section("content")
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
            </div>
            <div>
                <h3 class="font-bold text-gray-800">Cache de Visualizações</h3>
                <p class="text-sm text-gray-500">Páginas CMS cacheadas</p>
            </div>
        </div>
        <p class="text-sm text-gray-600 mb-4">Limpa o cache de todas as páginas públicas do CMS.</p>
        <button type="button" class="clear-cache-btn w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm font-medium flex items-center justify-center gap-2" data-type="views" data-label="Cache de Views">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            Limpar Cache de Views
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h3 class="font-bold text-gray-800">Cache de Rotas</h3>
                <p class="text-sm text-gray-500">Rotas cacheadas</p>
            </div>
        </div>
        <p class="text-sm text-gray-600 mb-4">Limpa o cache de rotas do Laravel.</p>
        <button type="button" class="clear-cache-btn w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 text-sm font-medium flex items-center justify-center gap-2" data-type="routes" data-label="Cache de Rotas">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            Limpar Cache de Rotas
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/></svg>
            </div>
            <div>
                <h3 class="font-bold text-gray-800">Cache de Config</h3>
                <p class="text-sm text-gray-500">Configurações cacheadas</p>
            </div>
        </div>
        <p class="text-sm text-gray-600 mb-4">Limpa o cache de configurações do Laravel.</p>
        <button type="button" class="clear-cache-btn w-full bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 text-sm font-medium flex items-center justify-center gap-2" data-type="config" data-label="Cache de Config">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            Limpar Cache de Config
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
            <div>
                <h3 class="font-bold text-gray-800">Cache de Eventos</h3>
                <p class="text-sm text-gray-500">Eventos cacheados</p>
            </div>
        </div>
        <p class="text-sm text-gray-600 mb-4">Limpa o cache de eventos.</p>
        <button type="button" class="clear-cache-btn w-full bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 text-sm font-medium flex items-center justify-center gap-2" data-type="events" data-label="Cache de Eventos">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            Limpar Cache de Eventos
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            </div>
            <div>
                <h3 class="font-bold text-gray-800">Otimização</h3>
                <p class="text-sm text-gray-500">Limpar tudo</p>
            </div>
        </div>
        <p class="text-sm text-gray-600 mb-4">Limpa e otimiza todo o cache do Laravel.</p>
        <button type="button" class="clear-cache-btn w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 text-sm font-medium flex items-center justify-center gap-2" data-type="all" data-label="Todo Cache">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            Limpar Todo Cache
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 bg-teal-100 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
            </div>
            <div>
                <h3 class="font-bold text-gray-800">Compilados</h3>
                <p class="text-sm text-gray-500">Blade compilados</p>
            </div>
        </div>
        <p class="text-sm text-gray-600 mb-4">Limpa o cache de templates Blade compilados.</p>
        <button type="button" class="clear-cache-btn w-full bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 text-sm font-medium flex items-center justify-center gap-2" data-type="compiled" data-label="Cache Compilado">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            Limpar Views Compiladas
        </button>
    </div>
</div>

<div class="mt-8 bg-white rounded-xl shadow-sm p-6">
    <h3 class="font-bold text-gray-800 mb-2">Informações do Cache</h3>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
        <div class="p-3 bg-gray-50 rounded-lg">
            <dt class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Cache Driver</dt>
            <dd class="text-gray-800 mt-0.5">{{ config("cache.default") }}</dd>
        </div>
        <div class="p-3 bg-gray-50 rounded-lg">
            <dt class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Session Driver</dt>
            <dd class="text-gray-800 mt-0.5">{{ config("session.driver") }}</dd>
        </div>
        <div class="p-3 bg-gray-50 rounded-lg">
            <dt class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Ambiente</dt>
            <dd class="text-gray-800 mt-0.5">{{ ucfirst(app()->environment()) }}</dd>
        </div>
    </div>
</div>
@endsection

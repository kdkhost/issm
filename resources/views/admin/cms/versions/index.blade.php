{{-- @autor marcelo-brad rj --}}
{{-- @contato Tel: 21 981325441 | Email: contato@kdkhost.com.br | Telegram: @MARCELO_BRAD | Instagram: @marcelobradrj | WhatsApp: 21981325441 --}}
@extends("layouts.admin")
@section("title", "Versões")
@section("page-title", "Histórico de Versões")
@push("scripts")
<script>
$(function() {
    $(".show-diff").on("click", function() {
        var versionId = $(this).data("id");
        var v1 = $(this).data("v1");
        var modal = $("#diff-modal");
        modal.find("#diff-content").html('<div class="text-center text-gray-400 py-4">Carregando...</div>');
        modal.removeClass("hidden");
        $.get("{{ route("admin.cms.versions.diff") }}?version_id_1=" + v1 + "&version_id_2=" + versionId, function(data) {
            modal.find("#diff-content").html(data);
        });
    });
    $("#diff-modal .close-diff, #diff-modal").on("click", function(e) {
        if (e.target === this || $(e.target).hasClass("close-diff")) {
            $("#diff-modal").addClass("hidden");
        }
    });
});
</script>
@endpush
@section("content")
<div class="flex items-center justify-between mb-6">
    <a href="{{ $page ? route("admin.cms.pages.edit", $page) : route("admin.cms.pages.index") }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Voltar para página
    </a>
    @php $versionCount = $versions->total() ?? $versions->count(); @endphp
    <span class="text-sm text-gray-500">{{ $versionCount }} versão(ns) registrada(s)</span>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Data</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Usuário</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Resumo</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden md:table-cell">Módulo</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($versions as $version)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 text-gray-600 text-sm font-mono">v{{ $version->version_number ?? $loop->iteration }}</td>
                    <td class="px-4 py-3 text-gray-600 text-sm whitespace-nowrap">{{ $version->created_at->format("d/m/Y H:i") }}</td>
                    <td class="px-4 py-3 text-gray-900 text-sm font-medium">{{ $version->user?->name ?? "Sistema" }}</td>
                    <td class="px-4 py-3 text-gray-600 text-sm">{{ Str::limit($version->summary ?? "Sem resumo", 60) }}</td>
                    <td class="px-4 py-3 hidden md:table-cell"><span class="badge-gray">{{ ucfirst($version->module ?? "page") }}</span></td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="flex items-center gap-1">
                            <button type="button" class="show-diff text-blue-600 hover:text-blue-800 text-sm font-medium px-1" data-id="{{ $version->id }}">Diff</button>
                            <form method="POST" action="{{ route("admin.cms.versions.restore", $version) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-800 text-sm font-medium px-1" data-tooltip="Restaurar esta versão">Restaurar</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-10 text-center text-gray-400">Nenhuma versão encontrada.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-gray-100">{{ $versions->links() }}</div>
</div>

<div id="diff-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40" style="backdrop-filter:blur(2px);">
    <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full mx-4 max-h-[85vh] overflow-y-auto">
        <div class="flex items-center justify-between p-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-800">Diff da Versão</h3>
            <button type="button" class="close-diff text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div id="diff-content" class="p-4"></div>
    </div>
</div>
@endsection

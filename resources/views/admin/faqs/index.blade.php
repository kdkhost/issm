@extends("layouts.admin")
@section("title", "FAQ")
@section("page-title", "Gerenciar FAQ")
@section("content")
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-gray-800">Perguntas Frequentes</h2>
    <a href="{{ route("admin.faq.create") }}" class="bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800 flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>Nova Pergunta</a>
</div>
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pergunta</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden sm:table-cell">Ordem</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($faqs as $item)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3 text-sm">
                    <p class="font-medium text-gray-900">{{ $item->question }}</p>
                    <p class="text-xs text-gray-500 mt-0.5 truncate">{{ strip_tags($item->answer) }}</p>
                </td>
                <td class="px-4 py-3 text-gray-600 text-sm hidden sm:table-cell">{{ $item->order }}</td>
                <td class="px-4 py-3">
                    <button onclick="toggleActive({{ $item->id }}, this)" class="px-2 py-1 rounded-full text-xs font-medium transition-all {{ $item->active ? "bg-green-100 text-green-700" : "bg-gray-100 text-gray-500" }}">
                        {{ $item->active ? "Ativo" : "Inativo" }}
                    </button>
                </td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <div class="flex items-center gap-1">
                        <a href="{{ route("admin.faq.edit", $item) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium px-1">Editar</a>
                        <form method="POST" action="{{ route("admin.faq.destroy", $item) }}">@csrf @method("DELETE")<button type="submit" data-confirm="Excluir esta pergunta?" class="text-red-600 hover:text-red-800 text-sm font-medium px-1">Excluir</button></form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-6 py-10 text-center text-gray-400">Nenhuma pergunta cadastrada.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($faqs->hasPages())
    <div class="p-4 border-t border-gray-100">{{ $faqs->links() }}</div>
    @endif
</div>
@endsection

@push("scripts")
<script>
function toggleActive(id, btn) {
    fetch(`{{ url("admin/faq") }}/${id}/toggle`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json"
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.active) {
            btn.className = "px-2 py-1 rounded-full text-xs font-medium transition-all bg-green-100 text-green-700";
            btn.textContent = "Ativo";
            if (typeof showToast !== "undefined") showToast("Pergunta ativada!", "success");
        } else {
            btn.className = "px-2 py-1 rounded-full text-xs font-medium transition-all bg-gray-100 text-gray-500";
            btn.textContent = "Inativo";
            if (typeof showToast !== "undefined") showToast("Pergunta desativada!", "info");
        }
    });
}
</script>
@endpush

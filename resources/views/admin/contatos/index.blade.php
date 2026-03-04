@extends("layouts.admin")
@section("title", "Mensagens")
@section("page-title", "Mensagens de Contato")
@section("content")
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nome</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden sm:table-cell">E-mail</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden md:table-cell">Assunto</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden lg:table-cell">Data</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($contacts as $contact)
            <tr class="hover:bg-gray-50 transition-colors {{ $contact->status === "new" ? "font-semibold" : "" }}">
                <td class="px-4 py-3 text-gray-900 text-sm">{{ $contact->name }}</td>
                <td class="px-4 py-3 text-gray-600 text-sm hidden sm:table-cell">{{ $contact->email }}</td>
                <td class="px-4 py-3 text-gray-600 text-sm hidden md:table-cell" style="max-width:180px;">{{ Str::limit($contact->subject, 35) }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $contact->status === "new" ? "bg-red-100 text-red-700" : ($contact->status === "replied" ? "bg-green-100 text-green-700" : "bg-gray-100 text-gray-600") }}">
                        {{ $contact->status === "new" ? "Nova" : ($contact->status === "replied" ? "Respondida" : "Lida") }}
                    </span>
                </td>
                <td class="px-4 py-3 text-gray-500 text-sm whitespace-nowrap hidden lg:table-cell">{{ optional($contact->created_at)->format("d/m/Y H:i") ?? "-" }}</td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <div class="flex items-center gap-1">
                        <button type="button" data-dt-toggle class="dt-toggle p-1 rounded text-gray-400 hover:text-green-700 hover:bg-green-50 transition-colors" title="Ver detalhes ocultos">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <a href="{{ route("admin.contatos.show", $contact) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium px-1">Ver</a>
                        <form method="POST" action="{{ route("admin.contatos.destroy", $contact) }}" onsubmit="return confirm('Excluir esta mensagem?')">
                            @csrf @method("DELETE")
                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium px-1">Excluir</button>
                        </form>
                    </div>
                </td>
            </tr>
            <tr class="dt-detail hidden">
                <td colspan="6" class="px-4 py-3 bg-green-50 border-b border-green-100">
                    <dl class="grid grid-cols-1 sm:grid-cols-3 gap-x-6 gap-y-2 text-sm">
                        <div><dt class="text-xs text-gray-500 font-semibold uppercase tracking-wider">E-mail</dt><dd class="text-gray-800 mt-0.5">{{ $contact->email }}</dd></div>
                        <div><dt class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Assunto</dt><dd class="text-gray-800 mt-0.5">{{ $contact->subject }}</dd></div>
                        <div><dt class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Data</dt><dd class="text-gray-800 mt-0.5">{{ optional($contact->created_at)->format("d/m/Y H:i") ?? "-" }}</dd></div>
                    </dl>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-6 py-10 text-center text-gray-400">Nenhuma mensagem recebida.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4 border-t border-gray-100">{{ $contacts->links() }}</div>
</div>
@endsection

{{-- @autor marcelo-brad rj --}}
{{-- @contato Tel: 21 981325441 | Email: contato@kdkhost.com.br | Telegram: @MARCELO_BRAD | Instagram: @marcelobradrj | WhatsApp: 21981325441 --}}
@extends("layouts.admin")
@section("title", "Auditoria")
@section("page-title", "Log de Auditoria")
@section("content")
<div class="bg-white rounded-xl shadow-sm p-4 mb-6">
    <form method="GET" action="{{ route("admin.cms.audit.index") }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Ação</label>
            <select name="action" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="">Todas</option>
                <option value="created" {{ request("action") == "created" ? "selected" : "" }}>Criação</option>
                <option value="updated" {{ request("action") == "updated" ? "selected" : "" }}>Atualização</option>
                <option value="deleted" {{ request("action") == "deleted" ? "selected" : "" }}>Exclusão</option>
                <option value="restored" {{ request("action") == "restored" ? "selected" : "" }}>Restauração</option>
                <option value="toggled" {{ request("action") == "toggled" ? "selected" : "" }}>Alternado</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Módulo</label>
            <select name="module" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="">Todos</option>
                <option value="page" {{ request("module") == "page" ? "selected" : "" }}>Página</option>
                <option value="section" {{ request("module") == "section" ? "selected" : "" }}>Seção</option>
                <option value="block" {{ request("module") == "block" ? "selected" : "" }}>Bloco</option>
                <option value="media" {{ request("module") == "media" ? "selected" : "" }}>Mídia</option>
                <option value="menu" {{ request("module") == "menu" ? "selected" : "" }}>Menu</option>
                <option value="seo" {{ request("module") == "seo" ? "selected" : "" }}>SEO</option>
                <option value="user" {{ request("module") == "user" ? "selected" : "" }}>Usuário</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Usuário</label>
            <select name="user_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="">Todos</option>
                @foreach($users ?? [] as $user)
                <option value="{{ $user->id }}" {{ request("user_id") == $user->id ? "selected" : "" }}>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Data Início</label>
            <input type="date" name="date_from" value="{{ request("date_from") }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Data Fim</label>
            <input type="date" name="date_to" value="{{ request("date_to") }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
        </div>
        <div class="sm:col-span-2 lg:col-span-5 flex items-end gap-2">
            <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 text-sm font-medium">Filtrar</button>
            @if(request()->anyFilled(["action","module","user_id","date_from","date_to"]))
            <a href="{{ route("admin.cms.audit.index") }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium">Limpar filtros</a>
            @endif
        </div>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Data/Hora</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Usuário</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ação</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Módulo</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden md:table-cell">Descrição</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden lg:table-cell">IP</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($audits as $audit)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 text-gray-600 text-sm whitespace-nowrap">{{ $audit->created_at->format("d/m/Y H:i:s") }}</td>
                    <td class="px-4 py-3 text-gray-900 text-sm font-medium">{{ $audit->user?->name ?? "Sistema" }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium
                            {{ $audit->action == "created" ? "bg-green-100 text-green-700" : "" }}
                            {{ $audit->action == "updated" ? "bg-blue-100 text-blue-700" : "" }}
                            {{ $audit->action == "deleted" ? "bg-red-100 text-red-700" : "" }}
                            {{ $audit->action == "restored" ? "bg-purple-100 text-purple-700" : "" }}">
                            {{ ucfirst($audit->action) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-600 text-sm"><span class="badge-gray">{{ ucfirst($audit->module) }}</span></td>
                    <td class="px-4 py-3 text-gray-600 text-sm hidden md:table-cell">{{ Str::limit($audit->description, 80) }}</td>
                    <td class="px-4 py-3 text-gray-500 text-xs font-mono hidden lg:table-cell">{{ $audit->ip_address ?? "-" }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-10 text-center text-gray-400">Nenhum registro de auditoria encontrado.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-gray-100">{{ $audits->appends(request()->query())->links() }}</div>
</div>
@endsection

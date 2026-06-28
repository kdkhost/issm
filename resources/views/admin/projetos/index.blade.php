@extends("layouts.admin")
@section("title", "Projetos")
@section("page-title", "Projetos")
@section("content")
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-800">Projetos</h2>
        <p class="text-sm text-gray-500 mt-1">Gerencie os projetos e acompanhe os apoios recebidos por cada iniciativa.</p>
    </div>
    <a href="{{ route("admin.projetos.create") }}" class="bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800 flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Novo Projeto
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Titulo</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden sm:table-cell">Categoria</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Destaque</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Apoios</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Acoes</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($projects as $project)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 font-medium text-gray-900 text-sm">{{ Str::limit($project->title, 50) }}</td>
                    <td class="px-4 py-3 text-gray-600 text-sm hidden sm:table-cell">{{ $project->category ?? "-" }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $project->status === "active" ? "bg-green-100 text-green-700" : ($project->status === "completed" ? "bg-blue-100 text-blue-700" : "bg-amber-100 text-amber-700") }}">
                            {{ $project->status === "active" ? "Ativo" : ($project->status === "completed" ? "Concluido" : "Planejado") }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center text-sm">
                        <span title="{{ $project->featured ? "Projeto em destaque" : "Nao em destaque" }}">{{ $project->featured ? "*" : "-" }}</span>
                    </td>
                    <td class="px-4 py-3 text-center text-sm">
                        <a href="{{ route("admin.project-supports.index", ["project" => $project->id]) }}" class="inline-flex items-center justify-center gap-1 px-2 py-1 rounded-full {{ $project->new_support_requests_count ? "bg-red-100 text-red-700" : "bg-green-50 text-green-700" }} font-bold text-xs">
                            {{ $project->support_requests_count }}
                            @if($project->new_support_requests_count)
                                <span>({{ $project->new_support_requests_count }} novo{{ $project->new_support_requests_count > 1 ? "s" : "" }})</span>
                            @endif
                        </a>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="flex items-center gap-1">
                            <button type="button" data-dt-toggle class="dt-toggle p-1 rounded text-gray-400 hover:text-green-700 hover:bg-green-50 transition-colors" title="Ver detalhes ocultos">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <a href="{{ route("admin.projetos.edit", $project) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium px-1">Editar</a>
                            <a href="{{ route("admin.project-supports.index", ["project" => $project->id]) }}" class="text-green-700 hover:text-green-900 text-sm font-medium px-1">Apoios</a>
                            <form method="POST" action="{{ route("admin.projetos.destroy", $project) }}">
                                @csrf
                                @method("DELETE")
                                <button type="submit" data-confirm="Excluir este projeto?" class="text-red-600 hover:text-red-800 text-sm font-medium px-1">Excluir</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <tr class="dt-detail hidden">
                    <td colspan="6" class="px-4 py-3 bg-green-50 border-b border-green-100">
                        <dl class="grid grid-cols-2 sm:grid-cols-5 gap-x-6 gap-y-2 text-sm">
                            <div><dt class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Categoria</dt><dd class="text-gray-800 mt-0.5">{{ $project->category ?? "-" }}</dd></div>
                            <div><dt class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Status</dt><dd class="text-gray-800 mt-0.5">{{ $project->status === "active" ? "Ativo" : ($project->status === "completed" ? "Concluido" : "Planejado") }}</dd></div>
                            <div><dt class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Destaque</dt><dd class="text-gray-800 mt-0.5">{{ $project->featured ? "Sim" : "Nao" }}</dd></div>
                            <div><dt class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Criado em</dt><dd class="text-gray-800 mt-0.5">{{ optional($project->created_at)->format("d/m/Y") ?? "-" }}</dd></div>
                            <div><dt class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Apoios recebidos</dt><dd class="text-gray-800 mt-0.5">{{ $project->support_requests_count }} registro(s)</dd></div>
                        </dl>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-6 py-10 text-center text-gray-400">Nenhum projeto cadastrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4 border-t border-gray-100">{{ $projects->links() }}</div>
</div>
@endsection

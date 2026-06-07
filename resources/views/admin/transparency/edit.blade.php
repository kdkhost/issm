@extends("layouts.admin")
@section("title", "Editar Documento - Portal da Transparência")
@section("page-title", "Editar Documento")
@section("content")
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <form method="POST" action="{{ route("admin.transparencia.update", $transparency) }}" enctype="multipart/form-data" class="p-6">
            @csrf
            @method("PUT")
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Título do Documento</label>
                    <input type="text" name="title" value="{{ old("title", $transparency->title) }}" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Categoria</label>
                        <select name="category" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                            <option value="Financeiro" {{ old("category", $transparency->category) == "Financeiro" ? "selected" : "" }}>Financeiro</option>
                            <option value="Administrativo" {{ old("category", $transparency->category) == "Administrativo" ? "selected" : "" }}>Administrativo</option>
                            <option value="Atas" {{ old("category", $transparency->category) == "Atas" ? "selected" : "" }}>Atas</option>
                            <option value="Relatórios" {{ old("category", $transparency->category) == "Relatórios" ? "selected" : "" }}>Relatórios</option>
                            <option value="Estatuto" {{ old("category", $transparency->category) == "Estatuto" ? "selected" : "" }}>Estatuto</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ano Referência</label>
                        <input type="number" name="year" value="{{ old("year", $transparency->year) }}" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data de Publicação</label>
                    <input type="date" name="published_at" value="{{ old("published_at", $transparency->published_at->format('Y-m-d')) }}" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Substituir Arquivo (Opcional)</label>
                    <input type="file" name="file"
                        data-auto-upload="{{ route("admin.cms.media.upload") }}"
                        data-url-name="file_path"
                        data-hint="PDF, DOC, XLS, até 10MB"
                        data-existing-url="{{ $transparency->file_path ? asset('storage/' . $transparency->file_path) : '' }}"
                        accept=".pdf,.doc,.docx,.xls,.xlsx,.odt,.ods,.txt,.csv">
                    <input type="hidden" name="file_path" value="{{ $transparency->file_path }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descrição (Opcional)</label>
                    <textarea name="description" rows="3" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">{{ old("description", $transparency->description) }}</textarea>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="active" value="1" {{ old("active", $transparency->active) ? "checked" : "" }} class="rounded text-green-600 focus:ring-green-500">
                    <label class="text-sm font-medium text-gray-700">Documento Ativo</label>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route("admin.transparencia.index") }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg">Cancelar</a>
                <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 font-bold shadow-lg">Atualizar Documento</button>
            </div>
        </form>
    </div>
</div>
@endsection

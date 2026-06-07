@extends("layouts.admin")
@section("title", "Novo Documento - Portal da Transparência")
@section("page-title", "Novo Documento")
@section("content")
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <form method="POST" action="{{ route("admin.transparencia.store") }}" enctype="multipart/form-data" class="p-6">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Título do Documento</label>
                    <input type="text" name="title" value="{{ old("title") }}" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                    @error("title")<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Categoria</label>
                        <select name="category" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                            <option value="">Selecione...</option>
                            <option value="Financeiro" {{ old("category") == "Financeiro" ? "selected" : "" }}>Financeiro</option>
                            <option value="Administrativo" {{ old("category") == "Administrativo" ? "selected" : "" }}>Administrativo</option>
                            <option value="Atas" {{ old("category") == "Atas" ? "selected" : "" }}>Atas</option>
                            <option value="Relatórios" {{ old("category") == "Relatórios" ? "selected" : "" }}>Relatórios</option>
                            <option value="Estatuto" {{ old("category") == "Estatuto" ? "selected" : "" }}>Estatuto</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ano Referência</label>
                        <input type="number" name="year" value="{{ old("year", date("Y")) }}" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data de Publicação</label>
                    <input type="date" name="published_at" value="{{ old("published_at", date("Y-m-d")) }}" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Arquivo (PDF, DOC, etc)</label>
                    <input type="file" name="file" required class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                    <p class="text-xs text-gray-400 mt-1">Limite: {{ \App\Models\Setting::uploadLimitMb('document', 10) }}MB</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descrição (Opcional)</label>
                    <textarea name="description" rows="3" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">{{ old("description") }}</textarea>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="active" value="1" {{ old("active", 1) ? "checked" : "" }} class="rounded text-green-600 focus:ring-green-500">
                    <label class="text-sm font-medium text-gray-700">Documento Ativo</label>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route("admin.transparencia.index") }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg">Cancelar</a>
                <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 font-bold shadow-lg">Salvar Documento</button>
            </div>
        </form>
    </div>
</div>
@endsection

@extends("layouts.admin")
@section("title", "Editar Parceiro")
@section("page-title", "Editar Parceiro")
@section("content")
<div class="max-w-lg">
    <form method="POST" action="{{ route("admin.parceiros.update", $partner) }}" enctype="multipart/form-data">
        @csrf @method("PUT")
        <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Nome *</label><input type="text" name="name" value="{{ old("name", $partner->name) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">URL do Site</label><input type="url" name="url" value="{{ old("url", $partner->url) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label><select name="type" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"><option value="partner" {{ $partner->type === "partner" ? "selected" : "" }}>Parceiro</option><option value="sponsor" {{ $partner->type === "sponsor" ? "selected" : "" }}>Patrocinador</option><option value="supporter" {{ $partner->type === "supporter" ? "selected" : "" }}>Apoiador</option></select></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Logo</label>@if($partner->logo)<img src="{{ asset("media/".$partner->logo) }}" class="h-12 object-contain mb-2">@endif<input type="file" name="logo" accept="image/*" class="w-full text-sm text-gray-600 border border-gray-300 rounded-lg px-3 py-2"></div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Ordem</label><input type="number" name="order" value="{{ old("order", $partner->order) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
                <div class="flex items-center gap-3 mt-6"><input type="checkbox" name="active" value="1" id="active" {{ $partner->active ? "checked" : "" }} class="w-4 h-4 text-green-600 rounded"><label for="active" class="text-sm font-medium text-gray-700">Ativo</label></div>
            </div>
            <div class="flex justify-between pt-4 border-t border-gray-100">
                <a href="{{ route("admin.parceiros.index") }}" class="text-gray-600 hover:text-gray-800 font-medium">Cancelar</a>
                <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 font-medium">Atualizar</button>
            </div>
        </div>
    </form>
</div>
@endsection

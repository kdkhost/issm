@extends("layouts.admin")
@section("title", "Editar Imagem")
@section("page-title", "Editar Imagem da Galeria")
@section("content")
<div class="max-w-lg">
    <form method="POST" action="{{ route("admin.galeria.update", $gallery) }}" enctype="multipart/form-data">
        @csrf @method("PUT")
        <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Titulo *</label><input type="text" name="title" value="{{ old("title", $gallery->title) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Descricao</label><textarea name="description" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">{{ old("description", $gallery->description) }}</textarea></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Album</label><input type="text" name="album" value="{{ old("album", $gallery->album) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Imagem</label>@if($gallery->image)<img src="{{ asset("media/".$gallery->image) }}" class="w-full h-32 object-cover rounded mb-2">@endif<input type="file" name="image" accept="image/*" class="w-full text-sm text-gray-600 border border-gray-300 rounded-lg px-3 py-2"></div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Ordem</label><input type="number" name="order" value="{{ old("order", $gallery->order) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
                <div class="flex items-center gap-3 mt-6"><input type="checkbox" name="active" value="1" id="active" {{ $gallery->active ? "checked" : "" }} class="w-4 h-4 text-green-600 rounded"><label for="active" class="text-sm font-medium text-gray-700">Ativo</label></div>
            </div>
            <div class="flex justify-between pt-4 border-t border-gray-100">
                <a href="{{ route("admin.galeria.index") }}" class="text-gray-600 hover:text-gray-800 font-medium">Cancelar</a>
                <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 font-medium">Atualizar</button>
            </div>
        </div>
    </form>
</div>
@endsection

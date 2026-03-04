@extends("layouts.admin")
@section("title", "Editar ODS")
@section("page-title", "Editar ODS " . $od->number)
@section("content")
<div class="max-w-lg">
    <form method="POST" action="{{ route("admin.ods.update", $od) }}">
        @csrf @method("PUT")
        <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
            <div class="p-4 rounded-xl text-white text-center mb-4" style="background-color: {{ $od->color }}">
                <p class="text-4xl font-black">{{ $od->number }}</p>
                <p class="font-semibold">{{ $od->title }}</p>
            </div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Titulo</label><input type="text" name="title" value="{{ old("title", $od->title) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Descricao</label><textarea name="description" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">{{ old("description", $od->description) }}</textarea></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Cor</label><input type="color" name="color" value="{{ old("color", $od->color) }}" class="w-full h-10 border border-gray-300 rounded-lg px-1 py-1"></div>
            <div class="flex items-center gap-3"><input type="checkbox" name="active" value="1" id="active" {{ $od->active ? "checked" : "" }} class="w-4 h-4 text-green-600 rounded"><label for="active" class="text-sm font-medium text-gray-700">Ativo</label></div>
            <div class="flex justify-between pt-4 border-t border-gray-100">
                <a href="{{ route("admin.ods.index") }}" class="text-gray-600 hover:text-gray-800 font-medium">Cancelar</a>
                <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 font-medium">Atualizar</button>
            </div>
        </div>
    </form>
</div>
@endsection

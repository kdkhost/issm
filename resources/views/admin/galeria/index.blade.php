@extends("layouts.admin")
@section("title", "Galeria")
@section("page-title", "Galeria de Imagens")
@section("content")
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-gray-800">Galeria</h2>
    <a href="{{ route("admin.galeria.create") }}" class="bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800 flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>Adicionar Imagem</a>
</div>
<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
    @forelse($items as $item)
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <img src="{{ asset("media/".$item->image) }}" alt="{{ $item->title }}" class="w-full h-32 object-cover">
        <div class="p-3">
            <p class="text-xs font-medium text-gray-900 truncate">{{ $item->title }}</p>
            @if($item->album)<p class="text-xs text-gray-500">{{ $item->album }}</p>@endif
            <div class="flex items-center gap-2 mt-2">
                <a href="{{ route("admin.galeria.edit", $item) }}" class="text-blue-600 hover:text-blue-800 text-xs">Editar</a>
                <form method="POST" action="{{ route("admin.galeria.destroy", $item) }}" onsubmit="return confirm('Excluir?')">@csrf @method("DELETE")<button type="submit" class="text-red-600 hover:text-red-800 text-xs">Excluir</button></form>
            </div>
        </div>
    </div>
    @empty<div class="col-span-5 text-center py-12 text-gray-400">Nenhuma imagem na galeria.</div>@endforelse
</div>
<div class="mt-4">{{ $items->links() }}</div>
@endsection

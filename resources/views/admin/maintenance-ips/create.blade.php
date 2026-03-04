@extends("layouts.admin")
@section("title", "Adicionar IP")
@section("page-title", "Adicionar IP Liberado")
@section("content")
<div class="max-w-lg">
    <form method="POST" action="{{ route("admin.ips-manutencao.store") }}">
        @csrf
        <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Endereco IP *</label><input type="text" name="ip_address" value="{{ old("ip_address") }}" required placeholder="Ex: 192.168.1.1" class="w-full border border-gray-300 rounded-lg px-3 py-2 font-mono focus:outline-none focus:ring-2 focus:ring-green-500">@error("ip_address")<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Descricao</label><input type="text" name="description" value="{{ old("description") }}" placeholder="Ex: Escritorio, Casa, Servidor" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
            <div class="flex items-center gap-3"><input type="checkbox" name="active" value="1" id="active" checked class="w-4 h-4 text-green-600 rounded"><label for="active" class="text-sm font-medium text-gray-700">IP Ativo</label></div>
            <div class="flex justify-between pt-4 border-t border-gray-100">
                <a href="{{ route("admin.ips-manutencao.index") }}" class="text-gray-600 hover:text-gray-800 font-medium">Cancelar</a>
                <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 font-medium">Adicionar</button>
            </div>
        </div>
    </form>
</div>
@endsection

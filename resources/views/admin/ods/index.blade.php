@extends("layouts.admin")
@section("title", "ODS 2030")
@section("page-title", "Objetivos de Desenvolvimento Sustentavel")
@section("content")
<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
    @foreach($odsList as $od)
    <div class="rounded-xl overflow-hidden shadow-sm">
        <div class="p-4 text-white" style="background-color: {{ $od->color }}">
            <p class="text-3xl font-black">{{ $od->number }}</p>
            <p class="text-sm font-medium leading-tight mt-1">{{ $od->title }}</p>
        </div>
        <div class="bg-white p-3 flex items-center justify-between">
            <span class="text-xs {{ $od->active ? "text-green-600" : "text-gray-400" }}">{{ $od->active ? "Ativo" : "Inativo" }}</span>
            <a href="{{ route("admin.ods.edit", $od) }}" class="text-blue-600 hover:text-blue-800 text-xs">Editar</a>
        </div>
    </div>
    @endforeach
</div>
@endsection

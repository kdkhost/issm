@extends('layouts.admin')
@section('title', 'Editar Depoimento')
@section('page-title', 'Editar Depoimento')

@section('content')
<div class="max-w-4xl">
    <div class="mb-6">
        <a href="{{ route('admin.depoimentos.index') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-green-700 font-medium transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Voltar para a lista
        </a>
    </div>

    <form action="{{ route('admin.depoimentos.update', $testimonial) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf @method('PUT')
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nome do Autor *</label>
                    <input type="text" name="name" required value="{{ old('name', $testimonial->name) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-green-500 focus:bg-white transition-all">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Cargo / Papel (Ex: Beneficiário)</label>
                    <input type="text" name="role" value="{{ old('role', $testimonial->role) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-green-500 focus:bg-white transition-all">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">Conteúdo do Depoimento *</label>
                <textarea name="content" rows="5" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-green-500 focus:bg-white transition-all resize-none">{{ old('content', $testimonial->content) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Foto do Autor</label>
                    @if($testimonial->photo)
                    <div class="mb-2">
                        <img src="{{ asset('media/' . $testimonial->photo) }}" class="h-20 w-20 rounded-full object-cover border border-gray-200">
                    </div>
                    @endif
                    <input type="file" name="photo" class="w-full">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Ordem de Exibição</label>
                    <input type="number" name="order" value="{{ old('order', $testimonial->order) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-green-500 focus:bg-white transition-all">
                </div>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="active" id="active" value="1" {{ old('active', $testimonial->active) ? 'checked' : '' }} class="w-5 h-5 rounded border-gray-300 text-green-600 focus:ring-green-500">
                <label for="active" class="text-sm font-bold text-gray-700">Depoimento Ativo</label>
            </div>
        </div>

        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('admin.depoimentos.index') }}" class="px-6 py-3 rounded-xl font-bold text-gray-500 hover:bg-gray-100 transition-all">Cancelar</a>
            <button type="submit" class="bg-green-700 hover:bg-green-800 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-green-900/20 transition-all">
                Salvar Alterações
            </button>
        </div>
    </form>
</div>
@endsection

@extends("layouts.admin")
@section("title", "Novo Álbum")
@section("page-title", "Novo Álbum da Galeria")

@section("content")
<div class="max-w-5xl">
    <form method="POST" action="{{ route("admin.galeria.store") }}" enctype="multipart/form-data" id="galleryAlbumForm">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-5">
                <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
                    <h3 class="font-semibold text-gray-800">Dados do álbum</h3>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Título *</label>
                        <input type="text" name="title" value="{{ old("title") }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                        @error("title")<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                        <textarea name="description" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">{{ old("description") }}</textarea>
                        @error("description")<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
                    <h3 class="font-semibold text-gray-800">Evento</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Data do evento</label>
                            <input type="date" name="event_date" value="{{ old("event_date") }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            @error("event_date")<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Local do evento</label>
                            <input type="text" name="event_location" value="{{ old("event_location") }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            @error("event_location")<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
                    <h3 class="font-semibold text-gray-800">Projetos vinculados</h3>
                    @include("admin.galeria._project-checkboxes", [
                        "projects" => $projects,
                        "selectedProjectIds" => old("project_ids", []),
                    ])
                </div>
            </div>

            <div class="space-y-5">
                <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
                    <h3 class="font-semibold text-gray-800">Publicação</h3>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ordem</label>
                        <input type="number" name="sort_order" value="{{ old("sort_order", 0) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                        @error("sort_order")<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="active" value="1" id="active" {{ old("active", "1") ? "checked" : "" }} class="w-4 h-4 text-green-600 rounded">
                        <label for="active" class="text-sm font-medium text-gray-700">Ativo</label>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
                    <h3 class="font-semibold text-gray-800">Dimensão ideal</h3>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Largura</label>
                            <input type="number" name="ideal_image_width" value="{{ old("ideal_image_width", 1600) }}" min="320" max="8000" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            @error("ideal_image_width")<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Altura</label>
                            <input type="number" name="ideal_image_height" value="{{ old("ideal_image_height", 1200) }}" min="240" max="8000" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            @error("ideal_image_height")<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
                    <h3 class="font-semibold text-gray-800">Imagem</h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Imagem de capa</label>
                        <input type="file" name="cover_image" accept="image/*" class="w-full text-sm text-gray-600">
                        @error("cover_image")<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fotos iniciais</label>
                        <input type="file" name="images[]" accept="image/*" multiple class="w-full text-sm text-gray-600">
                        @error("images.*")<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="flex justify-between">
                    <a href="{{ route("admin.galeria.index") }}" class="text-gray-600 hover:text-gray-800 font-medium">Cancelar</a>
                    <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 font-medium">Salvar</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

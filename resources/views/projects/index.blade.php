@extends("layouts.app")
@section("title", "Projetos - ISSM")
@section("content")
<div class="bg-green-800 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-black text-white">Nossos Projetos</h1>
        <p class="text-green-200 mt-2">Iniciativas alinhadas com os ODS 2030</p>
    </div>
</div>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @forelse($projects as $project)
        <article class="bg-white rounded-2xl shadow-md overflow-hidden card-hover border border-gray-100">
            @if($project->image)<img src="{{ asset("media/".$project->image) }}" alt="{{ $project->title }}" class="w-full h-48 object-cover">@else<div class="w-full h-48 bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center"><svg class="w-12 h-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>@endif
            <div class="p-6">
                @if($project->category)<span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded-full">{{ $project->category }}</span>@endif
                <h2 class="text-xl font-bold text-gray-900 mt-3 mb-2">{{ $project->title }}</h2>
                <p class="text-gray-600 text-sm leading-relaxed mb-4">{{ $project->excerpt ?? Str::limit(strip_tags($project->content), 120) }}</p>
                @if($project->ods_goals)
                <div class="flex flex-wrap gap-1 mb-4">
                    @foreach(array_slice($project->ods_goals, 0, 5) as $odsNum)
                    <span class="ods-{{ $odsNum }} text-white text-xs font-bold w-6 h-6 rounded flex items-center justify-center">{{ $odsNum }}</span>
                    @endforeach
                </div>
                @endif
                <a href="{{ route("projects.show", $project->slug) }}" class="text-green-700 hover:text-green-900 font-medium text-sm">Saiba mais</a>
            </div>
        </article>
        @empty
        <div class="col-span-3 text-center py-16 text-gray-400">Nenhum projeto publicado ainda.</div>
        @endforelse
    </div>
    <div class="mt-8">{{ $projects->links() }}</div>
</div>
@endsection

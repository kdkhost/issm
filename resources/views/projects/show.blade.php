@extends("layouts.app")
@section("title", $project->title . " - ISSM")
@section("content")
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-8">
        <a href="{{ route("home") }}" class="hover:text-green-700">Inicio</a>
        <span>/</span>
        <a href="{{ route("projects.index") }}" class="hover:text-green-700">Projetos</a>
        <span>/</span>
        <span class="text-gray-900">{{ Str::limit($project->title, 40) }}</span>
    </nav>
    <article>
        <div class="flex flex-wrap items-center gap-3 mb-4">
            @if($project->category)<span class="text-xs font-semibold text-green-600 bg-green-50 px-3 py-1 rounded-full">{{ $project->category }}</span>@endif
            <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $project->status === "active" ? "bg-green-100 text-green-700" : ($project->status === "completed" ? "bg-blue-100 text-blue-700" : "bg-yellow-100 text-yellow-700") }}">{{ $project->status === "active" ? "Em andamento" : ($project->status === "completed" ? "Concluido" : "Planejado") }}</span>
        </div>
        <h1 class="text-3xl lg:text-4xl font-black text-gray-900 mb-4">{{ $project->title }}</h1>
        @if($project->ods_goals)
        <div class="flex flex-wrap gap-2 mb-6">
            <span class="text-sm font-medium text-gray-600">ODS relacionados:</span>
            @foreach($project->ods_goals as $odsNum)
            <span class="ods-{{ $odsNum }} text-white text-xs font-bold w-7 h-7 rounded flex items-center justify-center">{{ $odsNum }}</span>
            @endforeach
        </div>
        @endif
        @if($project->image)<img src="{{ asset("media/".$project->image) }}" alt="{{ $project->title }}" class="w-full rounded-2xl mb-8 shadow-md">@endif
        <div class="prose prose-green max-w-none text-gray-700 leading-relaxed mb-8">{!! $project->content !!}</div>
        @if($project->location || $project->start_date)
        <div class="bg-gray-50 rounded-xl p-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
            @if($project->location)<div><p class="text-xs text-gray-500 mb-1">Localizacao</p><p class="font-medium text-gray-900">{{ $project->location }}</p></div>@endif
            @if($project->start_date)<div><p class="text-xs text-gray-500 mb-1">Inicio</p><p class="font-medium text-gray-900">{{ $project->start_date->format("d/m/Y") }}</p></div>@endif
            @if($project->end_date)<div><p class="text-xs text-gray-500 mb-1">Previsao de termino</p><p class="font-medium text-gray-900">{{ $project->end_date->format("d/m/Y") }}</p></div>@endif
        </div>
        @endif
    </article>
    <div class="mt-12 pt-8 border-t border-gray-200">
        <a href="{{ route("projects.index") }}" class="text-green-700 hover:text-green-900 font-medium flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Voltar para Projetos
        </a>
    </div>
</div>
@endsection

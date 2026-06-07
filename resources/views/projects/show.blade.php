@extends("layouts.app")
@section("title", $project->title . " - ISSM")
@section("content")
@section("content")

{{-- Hero Banner Premium --}}
<div style="background:linear-gradient(135deg,#166534 0%,#15803d 50%,#059669 100%);padding:56px 0 40px;">
    <div style="max-width:1280px;margin:0 auto;padding:0 16px;">
        <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:#86efac;margin-bottom:16px;">
            <a href="{{ route('home') }}" style="color:#86efac;text-decoration:none;transition:color .2s;">Início</a>
            <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('projects.index') }}" style="color:#86efac;text-decoration:none;transition:color .2s;">Projetos</a>
            <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span style="color:#fff;">{{ Str::limit($project->title, 30) }}</span>
        </div>
        <h1 style="font-size:clamp(2rem,5vw,3.5rem);font-weight:900;color:#fff;line-height:1.1;margin-bottom:16px;">
            {{ $project->title }}
        </h1>
        <div style="display:flex;flex-wrap:wrap;gap:12px;align-items:center;">
            @if($project->category)
            <span style="background:rgba(255,255,255,0.1);backdrop-filter:blur(4px);color:#fff;font-size:12px;font-weight:700;padding:6px 14px;border-radius:100px;border:1px solid rgba(255,255,255,0.2);">
                {{ $project->category }}
            </span>
            @endif
            <span style="background:{{ $project->status === 'active' ? '#22c55e' : ($project->status === 'completed' ? '#3b82f6' : '#eab308') }};color:#fff;font-size:12px;font-weight:700;padding:6px 14px;border-radius:100px;box-shadow:0 4px 12px rgba(0,0,0,0.1);">
                {{ $project->status === 'active' ? 'Em andamento' : ($project->status === 'completed' ? 'Concluído' : 'Planejado') }}
            </span>
        </div>
    </div>
</div>

@include("public.cms._sections")

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <article class="bg-white rounded-[32px] overflow-hidden shadow-2xl shadow-gray-200/50 border border-gray-100">
        @if($project->image)
        <div class="relative h-[400px] lg:h-[500px]">
            <img src="{{ asset('media/'.$project->image) }}" alt="{{ $project->title }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
        </div>
        @endif
        
        <div class="p-8 lg:p-16">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-12">
                {{-- Conteúdo Principal --}}
                <div class="lg:col-span-3">
                    @if($project->ods_goals)
                    <div class="flex flex-wrap gap-2 mb-8">
                        @foreach($project->ods_goals as $odsNum)
                        <span class="ods-{{ $odsNum }} text-white text-[11px] font-black w-8 h-8 rounded-lg flex items-center justify-center shadow-sm">{{ $odsNum }}</span>
                        @endforeach
                    </div>
                    @endif
                    
                    <div class="prose prose-lg prose-green max-w-none text-gray-700 leading-relaxed font-medium">
                        {!! $project->content !!}
                    </div>
                </div>

                {{-- Sidebar de Informações --}}
                <div class="space-y-6">
                    <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                        <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">Detalhes</h4>
                        <div class="space-y-4">
                            @if($project->location)
                            <div>
                                <p class="text-[10px] font-bold text-green-600 uppercase">Localização</p>
                                <p class="text-sm font-bold text-gray-900">{{ $project->location }}</p>
                            </div>
                            @endif
                            @if($project->start_date)
                            <div>
                                <p class="text-[10px] font-bold text-green-600 uppercase">Início</p>
                                <p class="text-sm font-bold text-gray-900">{{ $project->start_date->format('d/m/Y') }}</p>
                            </div>
                            @endif
                            @if($project->end_date)
                            <div>
                                <p class="text-[10px] font-bold text-green-600 uppercase">Previsão</p>
                                <p class="text-sm font-bold text-gray-900">{{ $project->end_date->format('d/m/Y') }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <a href="{{ route('contact.index') }}" class="block text-center bg-green-700 hover:bg-green-800 text-white font-bold py-4 rounded-2xl transition-all shadow-lg shadow-green-900/10">
                        Apoiar Projeto
                    </a>
                </div>
            </div>
        </div>
    </article>
    <div class="mt-12 pt-8 border-t border-gray-200">
        <a href="{{ route("projects.index") }}" class="text-green-700 hover:text-green-900 font-medium flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Voltar para Projetos
        </a>
    </div>
</div>
@endsection

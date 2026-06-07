@extends("layouts.app")
@section("title", "Portal da Transparência - ISSM")

@push("styles")
<style>
.page-stat {
    display:inline-flex; align-items:center; gap:6px;
    background:rgba(255,255,255,.1); padding:6px 14px; border-radius:24px;
    font-size:13px; color:#fff; font-weight:500;
}
.page-stat svg { width:16px; height:16px; opacity:.8; }
</style>
@endpush

@section("content")

{{-- Hero Banner Premium --}}
<div style="background:linear-gradient(135deg,#166534 0%,#15803d 50%,#059669 100%);padding:56px 0 40px;">
    <div style="max-width:1280px;margin:0 auto;padding:0 16px;">
        <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:#86efac;margin-bottom:16px;">
            <a href="{{ route('home') }}" style="color:#86efac;text-decoration:none;transition:color .2s;">Início</a>
            <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span style="color:#fff;">Transparência</span>
        </div>
        <h1 style="font-size:clamp(2rem,5vw,3rem);font-weight:900;color:#fff;line-height:1.1;margin-bottom:8px;">
            Portal da <span style="color:#86efac;">Transparência</span>
        </h1>
        <p style="font-size:16px;color:#bbf7d0;max-width:600px;margin-bottom:20px;">
            Compromisso com a integridade, ética e a prestação de contas clara das nossas atividades e recursos.
        </p>
        <div style="display:flex;flex-wrap:wrap;gap:10px;">
            <div class="page-stat">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Documentação Oficial
            </div>
        </div>
    </div>
</div>

<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($documents->isEmpty())
        <div class="max-w-3xl mx-auto text-center py-24 bg-gray-50 rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden relative group">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-green-400 to-emerald-500"></div>
            <div class="relative z-10">
                <div class="w-24 h-24 bg-white rounded-3xl shadow-md flex items-center justify-center mx-auto mb-8 transform group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 mb-3">Nenhum documento encontrado</h3>
                <p class="text-gray-500 font-medium max-w-sm mx-auto leading-relaxed">
                    No momento não há documentos ou relatórios disponíveis para visualização. Por favor, volte em breve.
                </p>
            </div>
        </div>
        @else
        <div class="space-y-20">
            @foreach($documents as $year => $categories)
            <div class="relative">
                <div class="flex items-center gap-6 mb-12">
                    <span class="text-6xl font-black text-green-100 leading-none select-none">{{ $year }}</span>
                    <div class="flex-1 h-px bg-gradient-to-r from-green-100 to-transparent"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                    @foreach($categories as $category => $docs)
                    <div class="flex flex-col h-full">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-xl bg-green-700 flex items-center justify-center text-white shadow-lg shadow-green-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                            </div>
                            <h3 class="text-xl font-black text-gray-900 tracking-tight">{{ $category }}</h3>
                        </div>

                        <div class="space-y-4 flex-1">
                            @foreach($docs as $doc)
                            <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank"
                               class="group block p-4 bg-gray-50 rounded-2xl border border-gray-100 hover:bg-white hover:shadow-xl hover:shadow-green-900/5 hover:border-green-100 transition-all duration-300">
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-white border border-gray-100 text-green-700 flex items-center justify-center flex-shrink-0 group-hover:bg-green-700 group-hover:text-white group-hover:border-green-700 transition-all duration-300 shadow-sm">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-extrabold text-gray-900 line-clamp-2 leading-tight mb-1 group-hover:text-green-800 transition-colors">{{ $doc->title }}</p>
                                        <div class="flex items-center gap-2">
                                            <span class="text-[10px] bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-bold uppercase tracking-wider">PDF</span>
                                            <span class="text-[10px] text-gray-400 font-bold">{{ $doc->published_at->format('d/m/Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>

{{-- Seção CTA --}}
<section class="py-24 relative overflow-hidden">
    <div class="absolute inset-0 bg-green-50/50"></div>
    <div class="max-w-4xl mx-auto px-4 relative z-10 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-full shadow-lg mb-8">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
        </div>
        <h2 class="text-3xl lg:text-4xl font-black text-gray-900 mb-6 tracking-tight">Dúvidas ou Informações?</h2>
        <p class="text-lg text-gray-600 mb-10 leading-relaxed font-medium">
            Caso não encontre o documento que procura ou precise de informações adicionais sobre nossas contas e relatórios, entre em contato através dos nossos canais oficiais.
        </p>
        <a href="{{ route('home') }}#contato" class="inline-flex items-center justify-center px-10 py-4 bg-green-700 text-white font-black rounded-2xl hover:bg-green-800 hover:shadow-2xl hover:shadow-green-900/20 transform hover:-translate-y-1 transition-all duration-300">
            Fale com nossa equipe
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
    </div>
</section>
@endsection

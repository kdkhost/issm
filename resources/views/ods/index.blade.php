@extends("layouts.app")
@section("title", $cmsPage?->meta_title ?? $cmsPage?->title ?? "ODS 2030 - Compromisso Sustentável - ISSM")

@push("styles")
<style>
.page-stat {
    display:inline-flex; align-items:center; gap:6px;
    background:rgba(255,255,255,.1); padding:6px 14px; border-radius:24px;
    font-size:13px; color:#fff; font-weight:500;
}
.page-stat svg { width:16px; height:16px; opacity:.8; }

.ods-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 20px;
}

.ods-card {
    position: relative;
    aspect-ratio: 1 / 1;
    display: flex;
    flex-direction: column;
    padding: 20px;
    background: var(--ods-color);
    border-radius: 1.5rem;
    color: #fff;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
    border: none;
    text-align: left;
}

.ods-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.15);
}

.ods-card::before {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,0.2) 100%);
}

.ods-card-num {
    font-size: 2.5rem;
    font-weight: 900;
    line-height: 1;
    margin-bottom: 8px;
    position: relative;
    z-index: 2;
}

.ods-card-title {
    font-size: 0.9rem;
    font-weight: 800;
    line-height: 1.2;
    text-transform: uppercase;
    position: relative;
    z-index: 2;
}

.ods-card-bg-icon {
    position: absolute;
    right: -10%;
    bottom: -10%;
    width: 70%;
    height: 70%;
    background-image: var(--ods-icon);
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
    opacity: 0.25;
    transition: transform 0.5s ease;
}

.ods-card:hover .ods-card-bg-icon {
    transform: scale(1.1) rotate(-5deg);
}

/* Modal Padrão */
.ods-modal-panel {
    background: #fff;
    border-radius: 2rem;
    overflow: hidden;
    max-width: 600px;
    width: 100%;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

.ods-modal-header {
    padding: 40px;
    color: #fff;
    position: relative;
}

.ods-modal-body {
    padding: 40px;
}

.ods-modal-close {
    position: absolute;
    top: 20px;
    right: 20px;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    border: none;
    color: #fff;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
}

.ods-modal-close:hover { background: rgba(255,255,255,0.3); }
</style>
@endpush

@section("content")

{{-- Hero Banner Premium --}}
<div style="background:linear-gradient(135deg,#166534 0%,#15803d 50%,#059669 100%);padding:56px 0 40px;">
    <div style="max-width:1280px;margin:0 auto;padding:0 16px;">
        <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:#86efac;margin-bottom:16px;">
            <a href="{{ route('home') }}" style="color:#86efac;text-decoration:none;transition:color .2s;">Início</a>
            <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span style="color:#fff;">{{ $cms['hero_ods_title'] ?? 'ODS 2030' }}</span>
        </div>
        <h1 style="font-size:clamp(2rem,5vw,3rem);font-weight:900;color:#fff;line-height:1.1;margin-bottom:8px;">
            Objetivos de <span style="color:#86efac;">Desenvolvimento</span>
        </h1>
        <p style="font-size:16px;color:#bbf7d0;max-width:600px;margin-bottom:20px;">
            {{ $cms['hero_ods_subtitle'] ?? 'Nossas ações estão alinhadas à Agenda 2030 da ONU para construir um futuro mais justo e sustentável.' }}
        </p>
        <div style="display:flex;flex-wrap:wrap;gap:10px;">
            <div class="page-stat">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ $odsList->count() }} Objetivos integrados
            </div>
        </div>
    </div>
</div>

<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-12 text-center max-w-3xl mx-auto">
            <h2 class="text-3xl font-black text-gray-900 mb-4">{{ $cms['o_que_sao_ods_title'] ?? 'A Agenda 2030' }}</h2>
            <p class="text-gray-600 leading-relaxed">
                {{ $cms['o_que_sao_ods_content'] ?? 'Adotada por todos os Estados-Membros das Nações Unidas, a Agenda 2030 fornece um plano compartilhado para a paz e a prosperidade das pessoas e do planeta, agora e no futuro. No ISSM, cada projeto é pensado para impactar positivamente um ou mais destes objetivos.' }}
            </p>
        </div>

        <div class="ods-grid">
            @foreach($odsList as $ods)
                @php
                    $officialPngPath = public_path('media/ods/' . $ods->number . '.png');
                    $officialSvgPath = public_path('media/ods/' . $ods->number . '.svg');
                    $officialImageUrl = file_exists($officialPngPath)
                        ? asset('media/ods/' . $ods->number . '.png')
                        : (file_exists($officialSvgPath) ? asset('media/ods/' . $ods->number . '.svg') : null);
                    $iconUrl = $ods->icon_url ?: $officialImageUrl;
                    $bgIcon = $iconUrl ? "url('{$iconUrl}')" : 'none';
                @endphp
                <button class="ods-card" 
                        style="--ods-color: {{ $ods->color }}; --ods-icon: {!! $bgIcon !!};"
                        onclick="openOdsModal('{{ $ods->number }}', '{{ addslashes($ods->title) }}', '{{ addslashes($ods->description) }}', '{{ $ods->color }}')">
                    <span class="ods-card-num">{{ $ods->number }}</span>
                    <span class="ods-card-title">{{ $ods->title }}</span>
                    <div class="ods-card-bg-icon"></div>
                </button>
            @endforeach
        </div>
    </div>
</section>

{{-- Modal ODS --}}
<div id="odsModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm">
    <div class="ods-modal-panel">
        <div id="modalHeader" class="ods-modal-header">
            <button onclick="closeOdsModal()" class="ods-modal-close">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <p id="modalNumber" class="text-xs font-bold uppercase tracking-widest mb-2 opacity-80"></p>
            <h3 id="modalTitle" class="text-3xl font-black leading-tight"></h3>
        </div>
        <div class="ods-modal-body">
            <h4 class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-4">Sobre este objetivo</h4>
            <p id="modalDescription" class="text-gray-700 leading-relaxed text-lg font-medium"></p>
            <div class="mt-10 pt-6 border-t border-gray-100 flex justify-end">
                <button onclick="closeOdsModal()" class="bg-gray-900 text-white px-8 py-2 rounded-full font-bold text-sm hover:bg-gray-800 transition-colors">Fechar Detalhes</button>
            </div>
        </div>
    </div>
</div>

<script>
function openOdsModal(num, title, desc, color) {
    document.getElementById('modalNumber').innerText = 'Objetivo ' + num;
    document.getElementById('modalTitle').innerText = title;
    document.getElementById('modalDescription').innerText = desc;
    document.getElementById('modalHeader').style.backgroundColor = color;
    document.getElementById('odsModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeOdsModal() {
    document.getElementById('odsModal').classList.add('hidden');
    document.body.style.overflow = '';
}

// Fechar ao clicar fora
document.getElementById('odsModal').addEventListener('click', function(e) {
    if (e.target === this) closeOdsModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeOdsModal();
});
</script>

@endsection

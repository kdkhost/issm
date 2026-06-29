@extends("layouts.app")
@section("title", ($project->meta_title ?? $project->title) . " - ISSM")
@section("meta_description", $project->meta_description ?? strip_tags($project->excerpt ?? $project->title))
@section("meta_keywords", $project->meta_keywords ?? "")
@section("og_title", $project->og_title ?? ($project->meta_title ?? $project->title))
@section("og_description", $project->og_description ?? ($project->meta_description ?? strip_tags($project->excerpt ?? $project->title)))
@section("og_image", $project->og_image ? asset("media/" . $project->og_image) : ($project->image ? asset("media/" . $project->image) : ""))

@push("styles")
<style>
    .support-modal{position:fixed;inset:0;z-index:9998;display:none;align-items:center;justify-content:center;padding:18px;background:rgba(2,6,23,.72);backdrop-filter:blur(8px)}
    .support-modal.is-open{display:flex}
    .support-dialog{width:min(960px,100%);max-height:92vh;overflow:auto;background:#fff;border-radius:24px;box-shadow:0 30px 90px rgba(0,0,0,.28)}
    .support-head{display:flex;align-items:flex-start;justify-content:space-between;gap:16px;padding:22px 24px;border-bottom:1px solid #e5e7eb}
    .support-title{font-size:20px;font-weight:900;color:#111827;margin:0}
    .support-subtitle{font-size:13px;color:#6b7280;margin-top:4px}
    .support-close{width:38px;height:38px;border-radius:999px;background:#f3f4f6;color:#111827;font-size:24px;line-height:1;border:0;cursor:pointer}
    .support-body{display:grid;grid-template-columns:minmax(0,1fr) minmax(0,1.25fr);gap:20px;padding:24px}
    .support-options{display:grid;gap:10px}
    .support-option{display:block;border:1px solid #e5e7eb;border-radius:16px;padding:14px;cursor:pointer;transition:border-color .18s,background .18s,box-shadow .18s}
    .support-option:hover,.support-option:has(input:checked){border-color:#16a34a;background:#f0fdf4;box-shadow:0 8px 22px rgba(22,163,74,.1)}
    .support-option input{margin-right:8px}
    .support-option strong{font-size:14px;color:#111827}
    .support-option span{display:block;font-size:12px;color:#6b7280;margin-top:4px;line-height:1.45}
    .support-form-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}
    .support-field-full{grid-column:1/-1}
    .support-label{display:block;font-size:11px;font-weight:900;color:#166534;text-transform:uppercase;letter-spacing:.04em;margin-bottom:5px}
    .support-input{width:100%;border:1px solid #d1d5db;border-radius:12px;padding:11px 12px;font-size:14px;color:#111827;background:#fff}
    .support-input:focus{outline:0;border-color:#16a34a;box-shadow:0 0 0 3px rgba(22,163,74,.12)}
    .support-help{font-size:12px;color:#6b7280;line-height:1.5;margin-top:8px}
    .support-actions{display:flex;align-items:center;justify-content:flex-end;gap:10px;margin-top:14px}
    @media(max-width:820px){.support-body{grid-template-columns:1fr}.support-form-grid{grid-template-columns:1fr}}
</style>
@endpush

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

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <article class="bg-white rounded-[32px] overflow-hidden shadow-2xl shadow-gray-200/50 border border-gray-100">
        @if($project->image)
        <div class="w-full overflow-hidden" style="background:#f8fafc;">
            <img src="{{ asset('media/'.$project->image) }}" alt="{{ $project->title }}"
                 class="w-full h-auto block"
                 style="max-height:520px;object-fit:contain;display:block;margin:0 auto;">
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

                    @if($supportTypes->count())
                        <button type="button" id="open-support-modal" class="block w-full text-center bg-green-700 hover:bg-green-800 text-white font-bold py-4 rounded-2xl transition-all shadow-lg shadow-green-900/10">
                            Apoiar Projeto
                        </button>
                    @else
                        <a href="{{ route('contact.index') }}" class="block text-center bg-green-700 hover:bg-green-800 text-white font-bold py-4 rounded-2xl transition-all shadow-lg shadow-green-900/10">
                            Apoiar Projeto
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </article>

    @if($supportTypes->count())
        <div id="support-modal" class="support-modal" role="dialog" aria-modal="true" aria-labelledby="support-modal-title">
            <div class="support-dialog">
                <div class="support-head">
                    <div>
                        <h2 id="support-modal-title" class="support-title">Apoiar {{ $project->title }}</h2>
                        <p class="support-subtitle">Escolha a melhor forma de apoiar este projeto e envie seus dados para nossa equipe.</p>
                    </div>
                    <button type="button" class="support-close" data-support-close aria-label="Fechar">&times;</button>
                </div>
                <form id="support-form" method="POST" action="{{ route("projects.support", $project) }}" class="support-body">
                    @csrf
                    <div>
                        <label class="support-label">Forma de apoio</label>
                        <div class="support-options">
                            @foreach($supportTypes as $type)
                                <label class="support-option">
                                    <input type="radio" name="project_support_type_id" value="{{ $type->id }}" data-category="{{ $type->category }}" data-requires-amount="{{ $type->requires_amount ? 1 : 0 }}" data-requires-quantity="{{ $type->requires_quantity ? 1 : 0 }}" data-requires-address="{{ $type->requires_address ? 1 : 0 }}" data-requires-document="{{ $type->requires_document ? 1 : 0 }}" @checked($loop->first)>
                                    <strong>{{ $type->name }}</strong>
                                    @if($type->description)<span>{{ $type->description }}</span>@endif
                                    @if($type->instructions)<span>{{ $type->instructions }}</span>@endif
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <div class="support-form-grid">
                            <div>
                                <label class="support-label">Perfil</label>
                                <select name="supporter_type" class="support-input" required>
                                    <option value="pessoa_fisica">Pessoa fisica</option>
                                    <option value="pessoa_juridica">Empresa</option>
                                    <option value="instituicao">Instituicao</option>
                                    <option value="governo">Governo / orgao publico</option>
                                </select>
                            </div>
                            <div>
                                <label class="support-label">Nome completo</label>
                                <input name="name" class="support-input" required>
                            </div>
                            <div>
                                <label class="support-label">Telefone / WhatsApp</label>
                                <input name="phone" class="support-input" required inputmode="tel" placeholder="(00) 00000-0000">
                            </div>
                            <div>
                                <label class="support-label">E-mail</label>
                                <input type="email" name="email" class="support-input">
                            </div>
                            <div data-support-document>
                                <label class="support-label">CPF/CNPJ/Identificacao</label>
                                <input name="document" class="support-input" placeholder="CPF, CNPJ ou identificacao">
                            </div>
                            <div>
                                <label class="support-label">Organizacao</label>
                                <input name="organization" class="support-input" placeholder="Empresa, escola, coletivo...">
                            </div>
                            <div data-support-government>
                                <label class="support-label">Orgao governamental</label>
                                <input name="government_agency" class="support-input" placeholder="Secretaria, setor ou orgao">
                            </div>
                            <div data-support-amount>
                                <label class="support-label">Valor</label>
                                <input name="amount" class="support-input" inputmode="decimal" placeholder="R$ 0,00">
                            </div>
                            <div data-support-payment>
                                <label class="support-label">Forma de pagamento</label>
                                @if($activeDonationGateway && count($donationPaymentMethods))
                                    <select name="payment_method" class="support-input">
                                        @foreach($donationPaymentMethods as $method)
                                            <option value="{{ $method }}">{{ $donationMethodLabels[$method] ?? strtoupper($method) }}</option>
                                        @endforeach
                                    </select>
                                    <p class="support-help">Gateway ativo: {{ strtoupper($activeDonationGateway) }}.</p>
                                @else
                                    <input class="support-input" value="Gateway de doacoes desativado no painel" disabled>
                                @endif
                            </div>
                            <div data-support-quantity>
                                <label class="support-label">Quantidade</label>
                                <input name="quantity" class="support-input" inputmode="decimal">
                            </div>
                            <div data-support-quantity>
                                <label class="support-label">Unidade</label>
                                <input name="unit" class="support-input" placeholder="kg, un, horas...">
                            </div>
                            <div class="support-field-full" data-support-address>
                                <label class="support-label">Endereco para entrega/retirada</label>
                                <input name="address" class="support-input">
                            </div>
                            <div class="support-field-full">
                                <label class="support-label">Descreva o apoio</label>
                                <textarea name="item_description" class="support-input" rows="3" placeholder="Ex: valor, itens, servico, agenda, condicoes de entrega..."></textarea>
                            </div>
                            <div>
                                <label class="support-label">Preferencia de contato</label>
                                <select name="preferred_contact" class="support-input">
                                    <option value="whatsapp">WhatsApp</option>
                                    <option value="telefone">Telefone</option>
                                    <option value="email">E-mail</option>
                                </select>
                            </div>
                            <div class="support-field-full">
                                <label class="support-label">Mensagem</label>
                                <textarea name="message" class="support-input" rows="3"></textarea>
                            </div>
                        </div>
                        <p class="support-help">Os dados enviados serao registrados no painel administrativo vinculados ao projeto {{ $project->title }}.</p>
                        <div class="support-actions">
                            <button type="button" data-support-close class="px-4 py-2 rounded-lg font-bold text-gray-600">Cancelar</button>
                            <button type="submit" class="bg-green-700 hover:bg-green-800 text-white px-5 py-2 rounded-lg font-bold">Enviar apoio</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
    <div class="mt-12 pt-8 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-3">
            <span class="text-sm font-bold text-gray-400">Compartilhar:</span>
            @php
                $shareUrl   = urlencode(url()->current());
                $shareTitle = urlencode($project->title ?? '');
            @endphp
            <a href="https://wa.me/?text={{ $shareTitle }}%20{{ $shareUrl }}" target="_blank" rel="noopener" title="WhatsApp"
               class="w-10 h-10 rounded-full flex items-center justify-center border border-gray-100 bg-gray-50 text-gray-500 hover:bg-green-50 hover:text-green-600 hover:border-green-200 transition-all">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            </a>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" rel="noopener" title="Facebook"
               class="w-10 h-10 rounded-full flex items-center justify-center border border-gray-100 bg-gray-50 text-gray-500 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 transition-all">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            </a>
            <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareTitle }}" target="_blank" rel="noopener" title="X (Twitter)"
               class="w-10 h-10 rounded-full flex items-center justify-center border border-gray-100 bg-gray-50 text-gray-500 hover:bg-gray-100 hover:text-gray-900 hover:border-gray-300 transition-all">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.748l7.73-8.835L1.254 2.25H8.08l4.253 5.622 5.91-5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
            </a>
            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}" target="_blank" rel="noopener" title="LinkedIn"
               class="w-10 h-10 rounded-full flex items-center justify-center border border-gray-100 bg-gray-50 text-gray-500 hover:bg-blue-50 hover:text-blue-700 hover:border-blue-200 transition-all">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
            </a>
            <a href="https://t.me/share/url?url={{ $shareUrl }}&text={{ $shareTitle }}" target="_blank" rel="noopener" title="Telegram"
               class="w-10 h-10 rounded-full flex items-center justify-center border border-gray-100 bg-gray-50 text-gray-500 hover:bg-sky-50 hover:text-sky-500 hover:border-sky-200 transition-all">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
            </a>
            <a href="mailto:?subject={{ $shareTitle }}&body={{ $shareTitle }}%20-%20{{ $shareUrl }}" title="Enviar por e-mail"
               class="w-10 h-10 rounded-full flex items-center justify-center border border-gray-100 bg-gray-50 text-gray-500 hover:bg-orange-50 hover:text-orange-500 hover:border-orange-200 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </a>
            <button type="button" onclick="navigator.clipboard.writeText(window.location.href).then(function(){this.title='Copiado!'}.bind(this))" title="Copiar link"
               class="w-10 h-10 rounded-full flex items-center justify-center border border-gray-100 bg-gray-50 text-gray-500 hover:bg-green-50 hover:text-green-600 hover:border-green-200 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
            </button>
        </div>
        <a href="{{ route("projects.index") }}" class="text-green-700 hover:text-green-900 font-medium flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Voltar para Projetos
        </a>
    </div>
</div>

@if($supportTypes->count())
@push("scripts")
<script>
(function() {
    var modal = document.getElementById('support-modal');
    var openButton = document.getElementById('open-support-modal');
    var form = document.getElementById('support-form');
    if (!modal || !openButton || !form) return;

    function setOpen(open) {
        modal.classList.toggle('is-open', open);
        document.body.style.overflow = open ? 'hidden' : '';
    }

    function onlyDigits(value) {
        return (value || '').replace(/\D/g, '');
    }

    function maskPhone(input) {
        var digits = onlyDigits(input.value).slice(0, 11);
        if (digits.length > 10) {
            input.value = digits.replace(/(\d{2})(\d{5})(\d{0,4})/, '($1) $2-$3');
            return;
        }
        input.value = digits.replace(/(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
    }

    function maskMoney(input) {
        var digits = onlyDigits(input.value);
        var value = (parseInt(digits || '0', 10) / 100).toFixed(2);
        input.value = 'R$ ' + value.replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function selectedType() {
        return form.querySelector('input[name="project_support_type_id"]:checked');
    }

    function toggleGroup(selector, visible, required) {
        form.querySelectorAll(selector).forEach(function(group) {
            group.style.display = visible ? '' : 'none';
            group.querySelectorAll('input, textarea, select').forEach(function(field) {
                field.required = !!required && visible;
                if (!visible) field.value = '';
            });
        });
    }

    function updateFields() {
        var type = selectedType();
        if (!type) return;
        var category = type.dataset.category;
        var requiresAmount = type.dataset.requiresAmount === '1';
        var requiresQuantity = type.dataset.requiresQuantity === '1';
        var requiresAddress = type.dataset.requiresAddress === '1';
        var requiresDocument = type.dataset.requiresDocument === '1' || category === 'governamental';
        var isGovernment = category === 'governamental';

        toggleGroup('[data-support-amount]', requiresAmount || category === 'monetario', requiresAmount);
        toggleGroup('[data-support-payment]', category === 'monetario', category === 'monetario');
        toggleGroup('[data-support-quantity]', requiresQuantity || category === 'insumos', requiresQuantity);
        toggleGroup('[data-support-address]', requiresAddress, requiresAddress);
        toggleGroup('[data-support-document]', requiresDocument, requiresDocument);
        toggleGroup('[data-support-government]', isGovernment, isGovernment);
    }

    openButton.addEventListener('click', function() { setOpen(true); });
    modal.querySelectorAll('[data-support-close]').forEach(function(button) {
        button.addEventListener('click', function() { setOpen(false); });
    });
    modal.addEventListener('click', function(event) {
        if (event.target === modal) setOpen(false);
    });
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') setOpen(false);
    });
    form.querySelectorAll('input[name="project_support_type_id"]').forEach(function(input) {
        input.addEventListener('change', updateFields);
    });

    var phone = form.querySelector('input[name="phone"]');
    var amount = form.querySelector('input[name="amount"]');
    if (phone) phone.addEventListener('input', function() { maskPhone(phone); });
    if (amount) amount.addEventListener('input', function() { maskMoney(amount); });

    form.addEventListener('submit', function(event) {
        event.preventDefault();
        var submit = form.querySelector('button[type="submit"]');
        submit.disabled = true;
        submit.textContent = 'Enviando...';

        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            credentials: 'same-origin'
        })
        .then(function(response) {
            return response.json().then(function(data) {
                if (!response.ok) throw data;
                return data;
            });
        })
        .then(function(data) {
            form.reset();
            updateFields();
            setOpen(false);
            var payment = data.payment && data.payment.public ? data.payment.public : null;
            if (payment && payment.type === 'pix') {
                var pixHtml = '';
                if (payment.qr_code_base64) {
                    pixHtml += '<img src="data:image/png;base64,' + payment.qr_code_base64 + '" style="width:180px;height:180px;margin:0 auto 12px;">';
                }
                if (payment.qr_code) {
                    pixHtml += '<textarea readonly style="width:100%;height:96px;border:1px solid #d1d5db;border-radius:10px;padding:10px;font-size:12px;">' + payment.qr_code + '</textarea>';
                }
                if (payment.ticket_url) {
                    pixHtml += '<a href="' + payment.ticket_url + '" target="_blank" style="display:inline-block;margin-top:10px;color:#15803d;font-weight:700;">Abrir pagamento</a>';
                }
                if (window.Swal) {
                    Swal.fire({ icon: 'success', title: 'PIX gerado', html: pixHtml || data.message, confirmButtonColor: '#15803d' });
                } else {
                    alert(payment.qr_code || data.message);
                }
                return;
            }
            if (payment && payment.type === 'redirect' && payment.url) {
                window.location.href = payment.url;
                return;
            }
            if (payment && payment.message) {
                if (window.Swal) {
                    Swal.fire({ icon: 'info', title: 'Apoio registrado', text: payment.message, confirmButtonColor: '#15803d' });
                } else {
                    alert(payment.message);
                }
                return;
            }
            if (window.Swal) {
                Swal.fire({ icon: 'success', title: 'Apoio registrado', text: data.message || 'Recebemos seu apoio.', confirmButtonColor: '#15803d' });
            } else {
                alert(data.message || 'Apoio registrado com sucesso.');
            }
        })
        .catch(function(error) {
            var message = error.message || 'Nao foi possivel registrar o apoio.';
            if (error.errors) {
                message = Object.values(error.errors).flat().join('\n');
            }
            if (window.Swal) {
                Swal.fire({ icon: 'error', title: 'Verifique os dados', text: message, confirmButtonColor: '#15803d' });
            } else {
                alert(message);
            }
        })
        .finally(function() {
            submit.disabled = false;
            submit.textContent = 'Enviar apoio';
        });
    });

    updateFields();
})();
</script>
@endpush
@endif
@endsection

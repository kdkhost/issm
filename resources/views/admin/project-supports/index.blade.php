@extends("layouts.admin")
@section("title", "Apoios aos Projetos")
@section("page-title", "Apoios aos Projetos")

@section("content")
@php
    $fmt = fn ($v) => number_format((float) $v, 2, ",", ".");
    $statusLabels   = ["new" => "Novo", "read" => "Lido", "contacted" => "Contatado", "completed" => "Concluido", "cancelled" => "Cancelado"];
    $categoryLabels = ["monetario" => "Doacao monetaria", "insumos" => "Insumos", "servicos" => "Servicos", "voluntariado" => "Voluntariado", "governamental" => "Governamental", "outro" => "Outro"];
    $gatewayLabels  = ["mercadopago" => "Mercado Pago", "cora" => "Cora", "pagbank" => "PagBank", "asaas" => "Asaas", "efi" => "Efi Pro", "stripe" => "Stripe", "paypal" => "PayPal"];
    $activeGateway  = $gatewaySettings["donation_gateway_active"] ?? "";
    $firstActive    = $activeGateway ?: 'mercadopago';
    $gatewayKeys    = ['mercadopago', 'stripe', 'paypal', 'cora', 'pagbank', 'asaas', 'efi'];
    $gatewayInstructions = [
        "mercadopago" => ["Acesse <strong>Suas integracoes</strong> no painel do Mercado Pago e copie o Access Token de producao.", "Para cartao: adicione tambem a Public Key (exibida na mesma tela).", "Cadastre o Webhook apontando para a URL dinamica abaixo."],
        "stripe"      => ["Copie a <strong>Publishable Key</strong> e a <strong>Secret Key</strong> do Dashboard Stripe.", "Ative os metodos de pagamento desejados (cards) em <em>Settings > Payment methods</em>.", "Cadastre o endpoint de webhook para o evento <code>checkout.session.completed</code>."],
        "paypal"      => ["Crie um app REST em <strong>PayPal Developer</strong> e copie Client ID e Secret.", "Selecione o escopo <em>Accept payments</em> na criacao do app.", "Use modo Sandbox para testes antes de ativar producao."],
        "cora"        => ["Ative a API PIX/cobrancas no portal Cora Business e copie o token de acesso.", "A URL base da API e configurada automaticamente pelo sistema.", "Cadastre o webhook apontando para a URL dinamica abaixo."],
        "pagbank"     => ["Crie uma aplicacao em <strong>PagBank Developers</strong> e copie o token Bearer.", "Para cartao: adicione a Public Key (usada para criptografia no frontend).", "Cadastre notificacoes para o evento <em>CHARGE_UPDATED</em>. A URL da API e automatica."],
        "asaas"       => ["Gere a API Key em <strong>Minha Conta &rsaquo; Integracoes</strong>.", "A URL base da API de producao e homologacao e configurada automaticamente.", "Cadastre o webhook para o evento <em>PAYMENT_RECEIVED</em>."],
        "efi"         => ["Informe o <strong>Client ID</strong> e a <strong>Client Secret / API Key</strong> da conta Efi.", "Preencha a <strong>Chave PIX</strong> cadastrada na sua conta Efi (CPF, CNPJ, e-mail ou aleatoria).", "A URL base da API PIX e configurada automaticamente pelo sistema."],
    ];
@endphp

{{-- ── Page Header ── igual ao padrão de todas as páginas admin ──────────── --}}
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-800">Apoios aos Projetos</h2>
        <p class="text-sm text-gray-500 mt-1">Gerencie tipos de apoio, gateway de doacoes e registros recebidos.</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="#gateway" class="bg-white border border-gray-200 text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-50 text-sm font-medium flex items-center gap-1.5 transition-colors">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            Gateway
        </a>
        <a href="#tipos" class="bg-white border border-gray-200 text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-50 text-sm font-medium flex items-center gap-1.5 transition-colors">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
            Tipos
        </a>
        <a href="#registros" class="bg-green-700 text-white px-3 py-2 rounded-lg hover:bg-green-800 text-sm font-medium flex items-center gap-1.5 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Registros
        </a>
    </div>
</div>

{{-- ── KPI Cards ───────────────────────────────────────────────────────────── --}}
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-4 border-t-4 border-blue-500">
        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">Total</p>
        <p class="text-2xl font-bold text-gray-900">{{ $stats["total"] }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4 border-t-4 border-red-500">
        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">Novos</p>
        <p class="text-2xl font-bold text-red-600">{{ $stats["new"] }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4 border-t-4 border-yellow-500">
        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">Contatados</p>
        <p class="text-2xl font-bold text-yellow-600">{{ $stats["contacted"] }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4 border-t-4 border-green-500">
        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">Concluidos</p>
        <p class="text-2xl font-bold text-green-700">{{ $stats["completed"] }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4 border-t-4 border-emerald-600">
        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">Valor Sinalizado</p>
        <p class="text-2xl font-bold text-emerald-700">R$ {{ $fmt($stats["amount"]) }}</p>
    </div>
</div>

{{-- ── Gateway ─────────────────────────────────────────────────────────────── --}}
<div id="gateway" class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
        <h3 class="text-base font-bold text-gray-800">Gateway de doacoes</h3>
        @if($activeGateway)
            <span class="badge-green">Ativo: {{ $gatewayLabels[$activeGateway] ?? strtoupper($activeGateway) }}</span>
        @else
            <span class="badge-gray">Desativado</span>
        @endif
    </div>
    <div class="p-6">
        <p class="text-sm text-gray-500 mb-6">Somente um gateway fica ativo por vez. A URL de webhook abaixo deve ser cadastrada no portal do gateway escolhido.</p>

        <form method="POST" action="{{ route("admin.project-supports.gateway.update") }}">
            @csrf
            @method("PUT")

            {{-- Linha principal --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="form-group mb-0">
                    <label class="form-label">Gateway ativo</label>
                    <select name="donation_gateway_active">
                        <option value="">Desativado</option>
                        @foreach($gateways as $gw)
                            <option value="{{ $gw }}" @selected($activeGateway === $gw)>{{ $gatewayLabels[$gw] ?? strtoupper($gw) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-0">
                    <label class="form-label">E-mail padrao do pagador</label>
                    <input type="email" name="donation_default_payer_email" value="{{ $gatewaySettings["donation_default_payer_email"] ?? "" }}" placeholder="doacao@issm.org.br">
                </div>
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-xs text-green-800 flex flex-col gap-1 justify-center">
                    <p><strong>Webhook:</strong></p>
                    <code class="bg-green-100 rounded px-1 py-0.5 break-all">{{ url("/pagamentos/{gateway}/webhook") }}</code>
                    <p class="mt-1"><strong>Retorno:</strong></p>
                    <code class="bg-green-100 rounded px-1 py-0.5 break-all">{{ url("/pagamentos/{gateway}/retorno") }}</code>
                    <p class="text-[10px] text-green-600 mt-1">Substitua <strong>{gateway}</strong> pelo identificador do gateway ativo.</p>
                </div>
            </div>

            {{-- Abas por gateway --}}
            <div class="flex flex-wrap gap-1 border-b border-gray-200 mb-0" id="gtabs">
                @foreach($gatewayKeys as $g)
                    <button type="button" class="gtab px-3 py-2 text-sm font-semibold rounded-t-lg border border-b-0 -mb-px transition-colors {{ $g === $firstActive ? 'bg-white text-green-700 border-gray-200' : 'bg-gray-50 text-gray-500 border-transparent hover:text-gray-700' }}" data-tab="{{ $g }}">
                        {{ $gatewayLabels[$g] }}
                    </button>
                @endforeach
            </div>

            {{-- Conteúdo de cada aba --}}
            @foreach($gatewayKeys as $g)
                @php $mode = $gatewaySettings["donation_{$g}_mode"] ?? 'production'; @endphp
                <div class="gtab-content border border-gray-200 border-t-0 rounded-b-xl rounded-tr-xl p-5 {{ $g === $firstActive ? '' : 'hidden' }}" data-tab="{{ $g }}">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        {{-- Coluna esquerda: credenciais --}}
                        <div class="md:col-span-2 space-y-4">
                            <div class="flex items-center gap-3 pb-3 border-b border-gray-100">
                                <span class="font-bold text-gray-800 text-sm">{{ $gatewayLabels[$g] }}</span>
                                <div class="ml-auto w-36">
                                    <select name="donation_{{ $g }}_mode" onchange="toggleGatewayFields('{{ $g }}', this.value)">
                                        <option value="production" @selected($mode === 'production')>Producao</option>
                                        <option value="sandbox"    @selected($mode === 'sandbox')>Sandbox</option>
                                    </select>
                                </div>
                            </div>

                            @if($g === 'mercadopago')
                                <div data-gateway="mercadopago" data-mode="production" @if($mode !== 'production') style="display:none" @endif>
                                    <label class="form-label">Access Token (Producao)</label>
                                    <input type="text" name="donation_mercadopago_access_token" value="{{ $gatewaySettings['donation_mercadopago_access_token'] ?? '' }}" placeholder="APP_USR-...">
                                    <label class="form-label mt-3">Public Key (Producao) <span class="normal-case font-normal text-gray-400">— para pagamento com cartao</span></label>
                                    <input type="text" name="donation_mercadopago_public_key" value="{{ $gatewaySettings['donation_mercadopago_public_key'] ?? '' }}" placeholder="APP_USR-...">
                                </div>
                                <div data-gateway="mercadopago" data-mode="sandbox" @if($mode !== 'sandbox') style="display:none" @endif>
                                    <label class="form-label">Access Token (Sandbox)</label>
                                    <input type="text" name="donation_mercadopago_access_token_sandbox" value="{{ $gatewaySettings['donation_mercadopago_access_token_sandbox'] ?? '' }}" placeholder="TEST-...">
                                    <label class="form-label mt-3">Public Key (Sandbox)</label>
                                    <input type="text" name="donation_mercadopago_public_key_sandbox" value="{{ $gatewaySettings['donation_mercadopago_public_key_sandbox'] ?? '' }}" placeholder="TEST-...">
                                </div>

                            @elseif($g === 'stripe')
                                <div data-gateway="stripe" data-mode="production" @if($mode !== 'production') style="display:none" @endif>
                                    <label class="form-label">Publishable Key (Producao)</label>
                                    <input type="text" name="donation_stripe_publishable_key" value="{{ $gatewaySettings['donation_stripe_publishable_key'] ?? '' }}" placeholder="pk_live_...">
                                    <label class="form-label mt-3">Secret Key (Producao)</label>
                                    <input type="text" name="donation_stripe_secret_key" value="{{ $gatewaySettings['donation_stripe_secret_key'] ?? '' }}" placeholder="sk_live_...">
                                </div>
                                <div data-gateway="stripe" data-mode="sandbox" @if($mode !== 'sandbox') style="display:none" @endif>
                                    <label class="form-label">Publishable Key (Sandbox)</label>
                                    <input type="text" name="donation_stripe_publishable_key_sandbox" value="{{ $gatewaySettings['donation_stripe_publishable_key_sandbox'] ?? '' }}" placeholder="pk_test_...">
                                    <label class="form-label mt-3">Secret Key (Sandbox)</label>
                                    <input type="text" name="donation_stripe_secret_key_sandbox" value="{{ $gatewaySettings['donation_stripe_secret_key_sandbox'] ?? '' }}" placeholder="sk_test_...">
                                </div>

                            @elseif($g === 'paypal')
                                <div data-gateway="paypal" data-mode="production" @if($mode !== 'production') style="display:none" @endif>
                                    <label class="form-label">Client ID (Producao)</label>
                                    <input type="text" name="donation_paypal_client_id" value="{{ $gatewaySettings['donation_paypal_client_id'] ?? '' }}">
                                    <label class="form-label mt-3">Secret (Producao)</label>
                                    <input type="text" name="donation_paypal_secret" value="{{ $gatewaySettings['donation_paypal_secret'] ?? '' }}">
                                </div>
                                <div data-gateway="paypal" data-mode="sandbox" @if($mode !== 'sandbox') style="display:none" @endif>
                                    <label class="form-label">Client ID (Sandbox)</label>
                                    <input type="text" name="donation_paypal_client_id_sandbox" value="{{ $gatewaySettings['donation_paypal_client_id_sandbox'] ?? '' }}">
                                    <label class="form-label mt-3">Secret (Sandbox)</label>
                                    <input type="text" name="donation_paypal_secret_sandbox" value="{{ $gatewaySettings['donation_paypal_secret_sandbox'] ?? '' }}">
                                </div>

                            @elseif($g === 'pagbank')
                                <div data-gateway="pagbank" data-mode="production" @if($mode !== 'production') style="display:none" @endif>
                                    <label class="form-label">API Key / Token Bearer (Producao)</label>
                                    <input type="text" name="donation_pagbank_api_key" placeholder="Token Bearer" value="{{ $gatewaySettings['donation_pagbank_api_key'] ?? '' }}">
                                </div>
                                <div data-gateway="pagbank" data-mode="sandbox" @if($mode !== 'sandbox') style="display:none" @endif>
                                    <label class="form-label">API Key / Token Bearer (Sandbox)</label>
                                    <input type="text" name="donation_pagbank_api_key_sandbox" placeholder="Token Bearer" value="{{ $gatewaySettings['donation_pagbank_api_key_sandbox'] ?? '' }}">
                                </div>
                                <div class="mt-3">
                                    <label class="form-label">Public Key <span class="normal-case font-normal text-gray-400">— para criptografia de cartao</span></label>
                                    <input type="text" name="donation_pagbank_public_key" value="{{ $gatewaySettings['donation_pagbank_public_key'] ?? '' }}" placeholder="Public Key PagBank">
                                </div>

                            @elseif($g === 'efi')
                                <div data-gateway="efi" data-mode="production" @if($mode !== 'production') style="display:none" @endif>
                                    <label class="form-label">Client ID (Producao)</label>
                                    <input type="text" name="donation_efi_client_id" placeholder="Client_Id_..." value="{{ $gatewaySettings['donation_efi_client_id'] ?? '' }}">
                                    <label class="form-label mt-3">Client Secret / API Key (Producao)</label>
                                    <input type="text" name="donation_efi_api_key" placeholder="Client_Secret_..." value="{{ $gatewaySettings['donation_efi_api_key'] ?? '' }}">
                                </div>
                                <div data-gateway="efi" data-mode="sandbox" @if($mode !== 'sandbox') style="display:none" @endif>
                                    <label class="form-label">Client ID (Sandbox)</label>
                                    <input type="text" name="donation_efi_client_id_sandbox" placeholder="Client_Id_..." value="{{ $gatewaySettings['donation_efi_client_id_sandbox'] ?? '' }}">
                                    <label class="form-label mt-3">Client Secret / API Key (Sandbox)</label>
                                    <input type="text" name="donation_efi_api_key_sandbox" placeholder="Client_Secret_..." value="{{ $gatewaySettings['donation_efi_api_key_sandbox'] ?? '' }}">
                                </div>
                                <div class="mt-3">
                                    <label class="form-label">Chave PIX cadastrada na conta Efi</label>
                                    <input type="text" name="donation_efi_pix_key" value="{{ $gatewaySettings['donation_efi_pix_key'] ?? '' }}" placeholder="CPF, CNPJ, e-mail ou chave aleatoria">
                                </div>

                            @else
                                {{-- Cora: apenas API Key, URL é fixa no driver --}}
                                <div data-gateway="{{ $g }}" data-mode="production" @if($mode !== 'production') style="display:none" @endif>
                                    <label class="form-label">API Key / Token (Producao)</label>
                                    <input type="text" name="donation_{{ $g }}_api_key" placeholder="API Key ou Token" value="{{ $gatewaySettings["donation_{$g}_api_key"] ?? '' }}">
                                </div>
                                <div data-gateway="{{ $g }}" data-mode="sandbox" @if($mode !== 'sandbox') style="display:none" @endif>
                                    <label class="form-label">API Key / Token (Sandbox)</label>
                                    <input type="text" name="donation_{{ $g }}_api_key_sandbox" placeholder="API Key ou Token" value="{{ $gatewaySettings["donation_{$g}_api_key_sandbox"] ?? '' }}">
                                </div>
                            @endif
                        </div>

                        {{-- Coluna direita: instruções --}}
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Como configurar</p>
                            <ol class="list-decimal pl-4 space-y-2 text-sm text-gray-600">
                                @foreach($gatewayInstructions[$g] as $inst)
                                    <li>{!! $inst !!}</li>
                                @endforeach
                            </ol>
                        </div>

                    </div>
                </div>
            @endforeach

            <div class="mt-4 flex justify-end">
                <button type="submit" class="bg-green-700 text-white px-6 py-2.5 rounded-lg hover:bg-green-800 font-semibold transition-colors">
                    Salvar configuracoes do gateway
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ── Tipos de Apoio ──────────────────────────────────────────────────────── --}}
<div id="tipos" class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
        <h3 class="text-base font-bold text-gray-800">Tipos de apoio</h3>
    </div>
    <div class="p-6">

        {{-- Formulário novo tipo --}}
        <form method="POST" action="{{ route('admin.project-supports.types.store') }}" class="bg-gray-50 rounded-xl p-4 mb-4 border border-gray-100">
            @csrf
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Novo tipo</p>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div class="form-group mb-0 md:col-span-2">
                    <label class="form-label">Nome</label>
                    <input type="text" name="name" required placeholder="Ex: Doacao de mudas">
                </div>
                <div class="form-group mb-0">
                    <label class="form-label">Categoria</label>
                    <select name="category" required>
                        @foreach($categoryLabels as $k => $l)<option value="{{ $k }}">{{ $l }}</option>@endforeach
                    </select>
                </div>
                <div class="form-group mb-0">
                    <label class="form-label">Ordem</label>
                    <input type="number" name="sort_order" value="0">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-green-700 text-white font-semibold rounded-lg py-2.5 hover:bg-green-800 transition-colors">
                        Adicionar
                    </button>
                </div>
            </div>
        </form>

        {{-- Listagem --}}
        @forelse($supportTypes as $type)
            <form method="POST" action="{{ route('admin.project-supports.types.update', $type) }}" class="bg-white border border-gray-200 rounded-xl p-4 mb-3 hover:border-gray-300 transition-colors">
                @csrf
                @method("PUT")
                <div class="grid grid-cols-1 md:grid-cols-6 gap-4 items-end">
                    <div class="form-group mb-0 md:col-span-2">
                        <label class="form-label">Nome</label>
                        <input type="text" name="name" value="{{ $type->name }}" required>
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-label">Categoria</label>
                        <select name="category" required>
                            @foreach($categoryLabels as $k => $l)
                                <option value="{{ $k }}" @selected($type->category === $k)>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-label">Ordem</label>
                        <input type="number" name="sort_order" value="{{ $type->sort_order }}">
                    </div>
                    <div class="flex items-center">
                        <label class="flex items-center gap-2 text-sm font-medium text-gray-700 cursor-pointer">
                            <input type="checkbox" name="active" value="1" @checked($type->active) class="w-4 h-4 accent-green-700">
                            Ativo
                        </label>
                    </div>
                    <div class="flex items-center gap-2 justify-end">
                        <span class="text-xs text-gray-400 whitespace-nowrap">{{ $type->requests_count }} reg(s)</span>
                        <button type="submit" class="bg-gray-800 text-white font-semibold rounded-lg px-4 py-2 text-sm hover:bg-gray-900 transition-colors">Salvar</button>
                        @if($type->requests_count === 0)
                            <button form="del-type-{{ $type->id }}" type="submit" data-confirm="Excluir este tipo de apoio?" class="text-red-600 font-semibold text-sm px-2 py-2 hover:bg-red-50 rounded-lg transition-colors">Excluir</button>
                        @endif
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-3 pt-3 border-t border-gray-100">
                    <div class="form-group mb-0">
                        <label class="form-label">Descricao</label>
                        <input type="text" name="description" value="{{ $type->description }}">
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-label">Instrucoes para o apoiador</label>
                        <input type="text" name="instructions" value="{{ $type->instructions }}">
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-label">Valores sugeridos <span class="normal-case font-normal text-gray-400">(separados por virgula)</span></label>
                        <input type="text" name="suggested_amounts" value="{{ collect($type->suggested_amounts ?? [])->implode(', ') }}" placeholder="50, 100, 250">
                    </div>
                </div>
            </form>
            @if($type->requests_count === 0)
                <form id="del-type-{{ $type->id }}" method="POST" action="{{ route('admin.project-supports.types.destroy', $type) }}" class="hidden">
                    @csrf @method("DELETE")
                </form>
            @endif
        @empty
            <div class="py-8 text-center text-gray-400 text-sm">Nenhum tipo de apoio configurado ainda.</div>
        @endforelse
    </div>
</div>

{{-- ── Registros ───────────────────────────────────────────────────────────── --}}
<div id="registros" class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="flex flex-wrap items-center justify-between gap-3 px-6 py-4 border-b border-gray-100">
        <h3 class="text-base font-bold text-gray-800">Apoios recebidos</h3>
        <form method="GET" class="flex flex-wrap items-center gap-2">
            <select name="project">
                <option value="">Todos os projetos</option>
                @foreach($projects as $p)
                    <option value="{{ $p->id }}" @selected((string) request('project') === (string) $p->id)>{{ $p->title }}</option>
                @endforeach
            </select>
            <select name="status">
                <option value="">Todos os status</option>
                @foreach($statusLabels as $k => $l)
                    <option value="{{ $k }}" @selected(request('status') === $k)>{{ $l }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-gray-800 text-white font-semibold rounded-lg px-4 py-2 text-sm hover:bg-gray-900 transition-colors">Filtrar</button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nome</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden md:table-cell">Projeto</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden sm:table-cell">Tipo</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden lg:table-cell">Pagamento</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden md:table-cell">Data</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Acoes</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($supportRequests as $support)
                    <tr class="hover:bg-gray-50 transition-colors" id="apoio-{{ $support->id }}">
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $support->name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600 hidden md:table-cell">{{ optional($support->project)->title ?: '-' }}</td>
                        <td class="px-4 py-3 hidden sm:table-cell">
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">{{ optional($support->supportType)->name ?: '-' }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $support->status === 'new' ? 'bg-red-100 text-red-700' : ($support->status === 'completed' ? 'bg-green-100 text-green-700' : ($support->status === 'cancelled' ? 'bg-gray-100 text-gray-500' : 'bg-amber-100 text-amber-700')) }}">
                                {{ $statusLabels[$support->status] ?? $support->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600 hidden lg:table-cell">
                            @if($support->payment_gateway)
                                <span class="px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700">{{ strtoupper($support->payment_gateway) }}</span>
                                <span class="text-xs text-gray-400 ml-1">{{ $support->payment_status ?: '' }}</span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500 hidden md:table-cell whitespace-nowrap">{{ optional($support->created_at)->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center gap-1">
                                <button type="button" data-dt-toggle class="dt-toggle p-1 rounded text-gray-400 hover:text-green-700 hover:bg-green-50 transition-colors" title="Ver detalhes">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    {{-- Linha de detalhes — padrão dt-detail igual às demais páginas --}}
                    <tr class="dt-detail hidden">
                        <td colspan="7" class="px-4 py-4 bg-green-50 border-b border-green-100">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <dl class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
                                        <div><dt class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Contato</dt><dd class="text-gray-800 mt-0.5">{{ $support->phone }}{{ $support->email ? ' · ' . $support->email : '' }}</dd></div>
                                        <div><dt class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Perfil</dt><dd class="text-gray-800 mt-0.5">{{ str_replace('_', ' ', $support->supporter_type) }}</dd></div>
                                        @if($support->amount)
                                        <div><dt class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Valor</dt><dd class="text-green-700 font-bold mt-0.5">R$ {{ $fmt($support->amount) }}</dd></div>
                                        @endif
                                        @if($support->payment_gateway)
                                        <div><dt class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Gateway</dt><dd class="text-gray-800 mt-0.5">{{ strtoupper($support->payment_gateway) }} / {{ strtoupper((string) $support->payment_method) }}</dd></div>
                                        @endif
                                        @if($support->payment_external_id)
                                        <div class="col-span-2"><dt class="text-xs text-gray-500 font-semibold uppercase tracking-wider">ID externo</dt><dd class="text-gray-800 mt-0.5 break-all text-xs font-mono">{{ $support->payment_external_id }}</dd></div>
                                        @endif
                                        @if($support->payment_reference)
                                        <div class="col-span-2"><dt class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Referencia</dt><dd class="text-gray-800 mt-0.5 text-xs font-mono">{{ $support->payment_reference }}</dd></div>
                                        @endif
                                        @if($support->item_description)
                                        <div class="col-span-2"><dt class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Descricao do apoio</dt><dd class="text-gray-800 mt-0.5">{{ $support->item_description }}</dd></div>
                                        @endif
                                        @if($support->message)
                                        <div class="col-span-2"><dt class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Mensagem</dt><dd class="text-gray-800 mt-0.5">{{ $support->message }}</dd></div>
                                        @endif
                                    </dl>
                                </div>
                                <div>
                                    <form method="POST" action="{{ route('admin.project-supports.requests.update', $support) }}">
                                        @csrf
                                        @method("PUT")
                                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Atualizar registro</p>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
                                            <div class="form-group mb-0">
                                                <label class="form-label">Status</label>
                                                <select name="status">
                                                    @foreach($statusLabels as $k => $l)
                                                        <option value="{{ $k }}" @selected($support->status === $k)>{{ $l }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group mb-0">
                                                <label class="form-label">Obs. interna</label>
                                                <input type="text" name="admin_note" value="{{ data_get($support->metadata, 'admin_note') }}" placeholder="Nota interna...">
                                            </div>
                                        </div>
                                        <div class="flex justify-end">
                                            <button type="submit" class="bg-green-700 text-white font-semibold rounded-lg px-5 py-2 text-sm hover:bg-green-800 transition-colors">Salvar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-6 py-10 text-center text-gray-400">Nenhum apoio registrado ainda.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-gray-100">{{ $supportRequests->links() }}</div>
</div>


<script>
function toggleGatewayFields(gateway, mode) {
    document.querySelectorAll('[data-gateway="'+gateway+'"]').forEach(function(el) {
        el.style.display = (el.dataset.mode === mode) ? '' : 'none';
    });
}
document.addEventListener('DOMContentLoaded', function() {
    // Abas gateway
    document.querySelectorAll('.gtab').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var tab = this.dataset.tab;
            document.querySelectorAll('.gtab').forEach(function(b) {
                b.classList.remove('bg-white', 'text-green-700', 'border-gray-200');
                b.classList.add('bg-gray-50', 'text-gray-500', 'border-transparent');
            });
            this.classList.remove('bg-gray-50', 'text-gray-500', 'border-transparent');
            this.classList.add('bg-white', 'text-green-700', 'border-gray-200');
            document.querySelectorAll('.gtab-content').forEach(function(c) {
                c.classList.toggle('hidden', c.dataset.tab !== tab);
            });
        });
    });
    // Inicializar visibilidade dos campos por modo
    ['mercadopago','stripe','paypal','cora','pagbank','asaas','efi'].forEach(function(g) {
        var sel = document.querySelector('select[name="donation_'+g+'_mode"]');
        if (sel) toggleGatewayFields(g, sel.value);
    });
});
</script>
@endsection

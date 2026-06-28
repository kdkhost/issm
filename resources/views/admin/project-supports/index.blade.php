@extends("layouts.admin")
@section("title", "Apoios aos Projetos")
@section("page-title", "Apoios aos Projetos")

@section("content")
@php
    $fmt = fn ($value) => number_format((float) $value, 2, ",", ".");
    $statusLabels = ["new" => "Novo", "read" => "Lido", "contacted" => "Contatado", "completed" => "Concluido", "cancelled" => "Cancelado"];
    $categoryLabels = ["monetario" => "Doacao monetaria", "insumos" => "Insumos", "servicos" => "Servicos", "voluntariado" => "Voluntariado", "governamental" => "Governamental", "outro" => "Outro"];
    $gatewayLabels = ["mercadopago" => "Mercado Pago", "cora" => "Cora", "pagbank" => "PagBank", "asaas" => "Asaas", "efi" => "Efi Pro", "stripe" => "Stripe", "paypal" => "PayPal"];
    $gatewayInstructions = [
        "mercadopago" => ["Crie uma aplicacao em Suas integracoes.", "Cole o Access Token de producao.", "Configure Webhooks para pagamentos usando a URL dinamica abaixo."],
        "cora" => ["Ative API Pix/cobrancas no portal Cora.", "Informe URL base e token/API Key.", "Cadastre o webhook do gateway apontando para a URL dinamica."],
        "pagbank" => ["Crie uma aplicacao no PagBank Developers.", "Copie o token de producao e configure a URL base.", "Cadastre notificacoes para cobrancas/pagamentos."],
        "asaas" => ["Gere a API Key em Integracoes.", "Use a URL base de producao ou sandbox conforme sua conta.", "Cadastre o webhook para eventos de pagamento recebido."],
        "efi" => ["Configure a aplicacao Pix/API no painel Efi.", "Use credenciais homologadas e URL base do ambiente correto.", "Para uso completo, confirme certificado/credenciais exigidas pela conta."],
        "stripe" => ["Copie Publishable Key e Secret Key.", "Ative metodos de pagamento no Dashboard Stripe.", "Cadastre endpoint de webhook para PaymentIntent succeeded."],
        "paypal" => ["Crie app REST no PayPal Developer.", "Informe Client ID e Secret.", "Use sandbox apenas para testes antes da producao."],
    ];
    $activeGateway = $gatewaySettings["donation_gateway_active"] ?? "";
    $gatewayKeys = ['mercadopago', 'stripe', 'paypal', 'cora', 'pagbank', 'asaas', 'efi'];
    $gatewayUrls = [
        'cora' => ['production' => 'https://api.cora.com.br', 'sandbox' => 'https://api.sandbox.cora.com.br'],
        'pagbank' => ['production' => 'https://api.pagbank.com.br', 'sandbox' => 'https://api.sandbox.pagbank.com.br'],
        'asaas' => ['production' => 'https://api.asaas.com', 'sandbox' => 'https://homologacao.asaas.com.br'],
        'efi' => ['production' => 'https://api.efi.com.br', 'sandbox' => 'https://api-hom.efi.com.br'],
    ];
    $firstActive = $activeGateway ?: 'mercadopago';
@endphp

{{-- KPI Cards — grid horizontal como Dashboard --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
    {{-- Total --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border-t-4 border-blue-500">
        <div class="p-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total</span>
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                    <span class="text-blue-600 font-bold text-sm">T</span>
                </div>
            </div>
            <strong class="block text-2xl font-bold text-gray-900">{{ $stats["total"] }}</strong>
        </div>
    </div>

    {{-- Novos --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border-t-4 border-green-500">
        <div class="p-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Novos</span>
                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                    <span class="text-green-600 font-bold text-sm">N</span>
                </div>
            </div>
            <strong class="block text-2xl font-bold text-green-600">{{ $stats["new"] }}</strong>
        </div>
    </div>

    {{-- Contatados --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border-t-4 border-yellow-500">
        <div class="p-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Contatados</span>
                <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center">
                    <span class="text-yellow-600 font-bold text-sm">C</span>
                </div>
            </div>
            <strong class="block text-2xl font-bold text-yellow-600">{{ $stats["contacted"] }}</strong>
        </div>
    </div>

    {{-- Concluidos --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border-t-4 border-emerald-500">
        <div class="p-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Concluidos</span>
                <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
                    <span class="text-emerald-600 font-bold text-sm">OK</span>
                </div>
            </div>
            <strong class="block text-2xl font-bold text-emerald-600">{{ $stats["completed"] }}</strong>
        </div>
    </div>

    {{-- Valor --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border-t-4 border-red-500">
        <div class="p-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Valor Sinalizado</span>
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                    <span class="text-red-600 font-bold text-sm">R$</span>
                </div>
            </div>
            <strong class="block text-2xl font-bold text-red-600">R$ {{ $fmt($stats["amount"]) }}</strong>
        </div>
    </div>
</div>

{{-- Header descritivo --}}
<div class="bg-white rounded-xl shadow-sm p-5 mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h3 class="font-bold text-gray-800 text-base">Central de apoios e doacoes</h3>
            <p class="text-sm text-gray-500 mt-0.5">Gerencie formas de apoio, gateway de doacoes monetarias, registros recebidos e instrucoes para cada portal de pagamento.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="#gateway" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                Gateway
            </a>
            <a href="#tipos" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                Tipos de apoio
            </a>
            <a href="#registros" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Registros
            </a>
        </div>
    </div>
</div>

{{-- Gateway --}}
<div id="gateway" class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
    <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-100">
        <h3 class="font-bold text-gray-800 text-sm">Gateway de doacoes</h3>
        @if($activeGateway)
            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-[11px] font-semibold bg-green-100 text-green-700">Ativo: {{ $gatewayLabels[$activeGateway] ?? strtoupper($activeGateway) }}</span>
        @else
            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-[11px] font-semibold bg-gray-100 text-gray-600">Gateway desativado</span>
        @endif
    </div>
    <div class="p-5">
        <p class="text-sm text-gray-500 mb-4">Somente um gateway fica ativo por vez. As URLs abaixo devem ser cadastradas no portal escolhido.</p>

        <form method="POST" action="{{ route("admin.project-supports.gateway.update") }}">
            @csrf
            @method("PUT")

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="form-label">Gateway ativo</label>
                    <select name="donation_gateway_active">
                        <option value="">Desativado</option>
                        @foreach($gateways as $gw)
                            <option value="{{ $gw }}" @selected($activeGateway === $gw)>{{ $gatewayLabels[$gw] ?? strtoupper($gw) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">E-mail padrao do pagador</label>
                    <input type="email" name="donation_default_payer_email" value="{{ $gatewaySettings["donation_default_payer_email"] ?? "" }}" placeholder="doacao@issm.org.br">
                </div>
                <div class="bg-green-50 border border-green-200 rounded-lg p-3 text-[12px] text-green-800">
                    <p><strong>Webhook:</strong> <code class="bg-green-100 rounded px-1">{{ url("/pagamentos/{gateway}/webhook") }}</code></p>
                    <p class="mt-1"><strong>Retorno:</strong> <code class="bg-green-100 rounded px-1">{{ url("/pagamentos/{gateway}/retorno") }}</code></p>
                </div>
            </div>

            <div class="flex flex-wrap gap-1 border-b border-gray-200 mb-4" id="gtabs">
                @foreach($gatewayKeys as $g)
                    <button type="button" class="gtab px-3 py-2 text-sm font-semibold rounded-t-lg border border-b-0 -mb-px transition-colors
                        {{ $g === $firstActive ? 'bg-white text-green-700 border-gray-200' : 'bg-gray-50 text-gray-500 border-transparent hover:text-gray-700' }}"
                        data-tab="{{ $g }}">{{ $gatewayLabels[$g] }}</button>
                @endforeach
            </div>

            @foreach($gatewayKeys as $g)
                <div class="gtab-content {{ $g === $firstActive ? '' : 'hidden' }}" data-tab="{{ $g }}">
                    @php $mode = $gatewaySettings["donation_{$g}_mode"] ?? 'production'; @endphp
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3 pb-2 border-b border-gray-100">
                            <span class="font-bold text-gray-800 text-sm">{{ $gatewayLabels[$g] }}</span>
                            <select name="donation_{{ $g }}_mode" class="rounded-lg border border-gray-300 bg-white px-2 py-1 text-sm" onchange="toggleGatewayFields('{{ $g }}', this.value)">
                                <option value="production" @selected($mode === 'production')>Producao</option>
                                <option value="sandbox" @selected($mode === 'sandbox')>Sandbox</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            @if($g === 'mercadopago')
                                <div data-gateway="mercadopago" data-mode="production" {{ $mode !== 'production' ? 'style=display:none' : '' }}>
                                    <label class="form-label">Access Token (Producao)</label>
                                    <input name="donation_mercadopago_access_token" value="{{ $gatewaySettings["donation_mercadopago_access_token"] ?? "" }}" placeholder="APP_USR-...">
                                </div>
                                <div data-gateway="mercadopago" data-mode="sandbox" {{ $mode !== 'sandbox' ? 'style=display:none' : '' }}>
                                    <label class="form-label">Access Token (Sandbox)</label>
                                    <input name="donation_mercadopago_access_token_sandbox" value="{{ $gatewaySettings["donation_mercadopago_access_token_sandbox"] ?? "" }}" placeholder="APP_USR-...">
                                </div>
                            @elseif($g === 'stripe')
                                <div data-gateway="stripe" data-mode="production" {{ $mode !== 'production' ? 'style=display:none' : '' }}>
                                    <label class="form-label">Publishable Key (Producao)</label>
                                    <input name="donation_stripe_publishable_key" value="{{ $gatewaySettings["donation_stripe_publishable_key"] ?? "" }}" placeholder="pk_live_...">
                                    <label class="form-label mt-2">Secret Key (Producao)</label>
                                    <input name="donation_stripe_secret_key" value="{{ $gatewaySettings["donation_stripe_secret_key"] ?? "" }}" placeholder="sk_live_...">
                                </div>
                                <div data-gateway="stripe" data-mode="sandbox" {{ $mode !== 'sandbox' ? 'style=display:none' : '' }}>
                                    <label class="form-label">Publishable Key (Sandbox)</label>
                                    <input name="donation_stripe_publishable_key_sandbox" value="{{ $gatewaySettings["donation_stripe_publishable_key_sandbox"] ?? "" }}" placeholder="pk_test_...">
                                    <label class="form-label mt-2">Secret Key (Sandbox)</label>
                                    <input name="donation_stripe_secret_key_sandbox" value="{{ $gatewaySettings["donation_stripe_secret_key_sandbox"] ?? "" }}" placeholder="sk_test_...">
                                </div>
                            @elseif($g === 'paypal')
                                <div data-gateway="paypal" data-mode="production" {{ $mode !== 'production' ? 'style=display:none' : '' }}>
                                    <label class="form-label">Client ID (Producao)</label>
                                    <input name="donation_paypal_client_id" value="{{ $gatewaySettings["donation_paypal_client_id"] ?? "" }}">
                                    <label class="form-label mt-2">Secret (Producao)</label>
                                    <input name="donation_paypal_secret" value="{{ $gatewaySettings["donation_paypal_secret"] ?? "" }}">
                                </div>
                                <div data-gateway="paypal" data-mode="sandbox" {{ $mode !== 'sandbox' ? 'style=display:none' : '' }}>
                                    <label class="form-label">Client ID (Sandbox)</label>
                                    <input name="donation_paypal_client_id_sandbox" value="{{ $gatewaySettings["donation_paypal_client_id_sandbox"] ?? "" }}">
                                    <label class="form-label mt-2">Secret (Sandbox)</label>
                                    <input name="donation_paypal_secret_sandbox" value="{{ $gatewaySettings["donation_paypal_secret_sandbox"] ?? "" }}">
                                </div>
                            @else
                                @php $u = $gatewayUrls[$g] ?? []; @endphp
                                <div data-gateway="{{ $g }}" data-mode="production" {{ $mode !== 'production' ? 'style=display:none' : '' }}>
                                    <label class="form-label">URL Base (Producao)</label>
                                    <input name="donation_{{ $g }}_base_url" placeholder="{{ $u['production'] ?? '' }}" value="{{ $gatewaySettings["donation_{$g}_base_url"] ?? $u['production'] ?? '' }}">
                                    <label class="form-label mt-2">API Key (Producao)</label>
                                    <input name="donation_{{ $g }}_api_key" placeholder="API Key/Token" value="{{ $gatewaySettings["donation_{$g}_api_key"] ?? "" }}">
                                </div>
                                <div data-gateway="{{ $g }}" data-mode="sandbox" {{ $mode !== 'sandbox' ? 'style=display:none' : '' }}>
                                    <label class="form-label">URL Base (Sandbox)</label>
                                    <input name="donation_{{ $g }}_base_url_sandbox" placeholder="{{ $u['sandbox'] ?? '' }}" value="{{ $gatewaySettings["donation_{$g}_base_url_sandbox"] ?? $u['sandbox'] ?? '' }}">
                                    <label class="form-label mt-2">API Key (Sandbox)</label>
                                    <input name="donation_{{ $g }}_api_key_sandbox" placeholder="API Key/Token" value="{{ $gatewaySettings["donation_{$g}_api_key_sandbox"] ?? "" }}">
                                </div>
                            @endif
                            <div class="pt-2 mt-2 border-t border-gray-100">
                                <ul class="m-0 pl-4 text-[12px] text-gray-500 leading-relaxed">
                                    @foreach($gatewayInstructions[$g] as $inst)<li>{{ $inst }}</li>@endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <button class="w-full bg-green-700 text-white font-semibold rounded-xl py-2.5 hover:bg-green-800 transition-colors mt-4">Salvar configuracoes do gateway</button>
        </form>
    </div>
</div>

{{-- Tipos de apoio --}}
<div id="tipos" class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
    <div class="px-5 py-3.5 border-b border-gray-100">
        <h3 class="font-bold text-gray-800 text-sm">Tipos de apoio</h3>
    </div>
    <div class="p-5">
        <form method="POST" action="{{ route("admin.project-supports.types.store") }}" class="grid grid-cols-1 md:grid-cols-5 gap-3 mb-4">
            @csrf
            <div>
                <label class="form-label">Nome</label>
                <input name="name" required placeholder="Ex: Doacao de mudas">
            </div>
            <div>
                <label class="form-label">Categoria</label>
                <select name="category" required>
                    @foreach($categoryLabels as $k => $l)<option value="{{ $k }}">{{ $l }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Descricao</label>
                <input name="description" placeholder="Descricao resumida...">
            </div>
            <div>
                <label class="form-label">Ordem</label>
                <input type="number" name="sort_order" value="0">
            </div>
            <div class="flex items-end">
                <button class="w-full bg-green-700 text-white font-semibold rounded-lg py-2 text-sm hover:bg-green-800 transition-colors">Adicionar</button>
            </div>
        </form>

        @forelse($supportTypes as $type)
            <form method="POST" action="{{ route("admin.project-supports.types.update", $type) }}" class="border border-gray-200 rounded-lg p-3 mb-2">
                @csrf
                @method("PUT")
                <div class="grid grid-cols-1 md:grid-cols-6 gap-3 items-end">
                    <div class="md:col-span-2">
                        <label class="form-label">Nome</label>
                        <input name="name" value="{{ $type->name }}" required>
                    </div>
                    <div>
                        <label class="form-label">Categoria</label>
                        <select name="category" required>
                            @foreach($categoryLabels as $k => $l)<option value="{{ $k }}" @selected($type->category === $k)>{{ $l }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Ordem</label>
                        <input type="number" name="sort_order" value="{{ $type->sort_order }}">
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="flex items-center gap-1.5 text-[13px] font-medium text-gray-700">
                            <input type="checkbox" name="active" value="1" @checked($type->active) class="w-4 h-4 accent-green-700"> Ativo
                        </label>
                    </div>
                    <div class="flex items-center gap-2 justify-end">
                        <span class="text-[11px] text-gray-500">{{ $type->requests_count }} regs</span>
                        <button class="bg-gray-800 text-white font-semibold rounded-lg px-3 py-1.5 text-[12px] hover:bg-gray-900 transition-colors">Salvar</button>
                        @if($type->requests_count === 0)
                            <button form="del-{{ $type->id }}" type="submit" data-confirm="Excluir este tipo?" class="text-red-600 font-semibold text-[12px] px-2 hover:bg-red-50 rounded">Excluir</button>
                        @endif
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-2 pt-2 border-t border-gray-100">
                    <div>
                        <label class="form-label">Descricao</label>
                        <input name="description" value="{{ $type->description }}">
                    </div>
                    <div>
                        <label class="form-label">Instrucoes</label>
                        <input name="instructions" value="{{ $type->instructions }}">
                    </div>
                    <div>
                        <label class="form-label">Valores sugeridos</label>
                        <input name="suggested_amounts" value="{{ collect($type->suggested_amounts ?? [])->implode(', ') }}" placeholder="50, 100, 250">
                    </div>
                </div>
            </form>
            @if($type->requests_count === 0)
                <form id="del-{{ $type->id }}" method="POST" action="{{ route("admin.project-supports.types.destroy", $type) }}" class="hidden">@csrf @method("DELETE")</form>
            @endif
        @empty
            <div class="py-6 text-center text-gray-400 text-sm">Nenhum tipo configurado.</div>
        @endforelse
    </div>
</div>

{{-- Registros --}}
<div id="registros" class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-100">
        <h3 class="font-bold text-gray-800 text-sm">Apoios recebidos</h3>
        <form method="GET" class="flex gap-2">
            <select name="project" class="text-sm py-1 px-2">
                <option value="">Todos os projetos</option>
                @foreach($projects as $p)<option value="{{ $p->id }}" @selected((string) request("project") === (string) $p->id)>{{ $p->title }}</option>@endforeach
            </select>
            <select name="status" class="text-sm py-1 px-2">
                <option value="">Todos</option>
                @foreach($statusLabels as $k => $l)<option value="{{ $k }}" @selected(request("status") === $k)>{{ $l }}</option>@endforeach
            </select>
            <button class="bg-gray-800 text-white font-semibold rounded-lg px-3 py-1 text-[12px] hover:bg-gray-900 transition-colors">Filtrar</button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-2.5 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Nome</th>
                    <th class="px-4 py-2 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Projeto</th>
                    <th class="px-4 py-2 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Tipo</th>
                    <th class="px-4 py-2 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-2 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Pagamento</th>
                    <th class="px-4 py-2 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Data</th>
                    <th class="px-4 py-2 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Acoes</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($supportRequests as $support)
                    <tr class="hover:bg-gray-50 transition-colors" id="apoio-{{ $support->id }}">
                        <td class="px-4 py-2.5 text-sm font-medium text-gray-900">{{ $support->name }}</td>
                        <td class="px-4 py-2.5 text-sm text-gray-600">{{ optional($support->project)->title ?: "-" }}</td>
                        <td class="px-4 py-2.5"><span class="inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-semibold bg-green-100 text-green-700">{{ optional($support->supportType)->name ?: "-" }}</span></td>
                        <td class="px-4 py-2.5">
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-semibold
                                {{ $support->status === 'new' ? 'bg-red-100 text-red-700' : ($support->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600') }}">
                                {{ $statusLabels[$support->status] ?? $support->status }}
                            </span>
                        </td>
                        <td class="px-4 py-2.5 text-sm text-gray-600">{{ $support->payment_status ?: "-" }}</td>
                        <td class="px-4 py-2.5 text-sm text-gray-500">{{ optional($support->created_at)->format("d/m/Y H:i") }}</td>
                        <td class="px-4 py-2.5 whitespace-nowrap">
                            <button type="button" onclick="document.getElementById('detail-{{ $support->id }}').classList.toggle('hidden')" class="text-green-600 hover:text-green-800 text-[12px] font-semibold px-1">Detalhes</button>
                        </td>
                    </tr>
                    <tr id="detail-{{ $support->id }}" class="hidden">
                        <td colspan="7" class="px-4 py-3 bg-green-50 border-b border-green-100">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <dl class="space-y-1 text-[12px]">
                                        <div class="flex gap-2"><dt class="font-semibold text-gray-500 w-24">Contato:</dt><dd class="text-gray-800">{{ $support->phone }} {{ $support->email ? '- ' . $support->email : '' }}</dd></div>
                                        <div class="flex gap-2"><dt class="font-semibold text-gray-500 w-24">Perfil:</dt><dd class="text-gray-800">{{ str_replace("_", " ", $support->supporter_type) }}</dd></div>
                                        @if($support->amount)<div class="flex gap-2"><dt class="font-semibold text-gray-500 w-24">Valor:</dt><dd class="font-bold text-green-700">R$ {{ $fmt($support->amount) }}</dd></div>@endif
                                        @if($support->payment_gateway)<div class="flex gap-2"><dt class="font-semibold text-gray-500 w-24">Gateway:</dt><dd class="text-gray-800">{{ strtoupper($support->payment_gateway) }} / {{ strtoupper((string) $support->payment_method) }}</dd></div>@endif
                                        @if($support->payment_external_id)<div class="flex gap-2"><dt class="font-semibold text-gray-500 w-24">ID externo:</dt><dd class="text-gray-800 break-all">{{ $support->payment_external_id }}</dd></div>@endif
                                        @if($support->payment_reference)<div class="flex gap-2"><dt class="font-semibold text-gray-500 w-24">Ref:</dt><dd class="text-gray-800">{{ $support->payment_reference }}</dd></div>@endif
                                        <div class="flex gap-2"><dt class="font-semibold text-gray-500 w-24">IP:</dt><dd class="text-gray-500">{{ $support->ip_address ?: "-" }}</dd></div>
                                    </dl>
                                </div>
                                <div>
                                    @if($support->message)<p class="text-[12px] text-gray-600 mb-2"><strong>Mensagem:</strong> {{ $support->message }}</p>@endif
                                    @if($support->item_description)<p class="text-[12px] text-gray-600 mb-2"><strong>Apoio:</strong> {{ $support->item_description }}</p>@endif
                                    <form method="POST" action="{{ route("admin.project-supports.requests.update", $support) }}" class="mt-2 pt-2 border-t border-gray-200">
                                        @csrf
                                        @method("PUT")
                                        <div class="flex gap-2 items-end">
                                            <div class="flex-1">
                                                <label class="form-label">Status</label>
                                                <select name="status">
                                                    @foreach($statusLabels as $k => $l)<option value="{{ $k }}" @selected($support->status === $k)>{{ $l }}</option>@endforeach
                                                </select>
                                            </div>
                                            <div class="flex-1">
                                                <label class="form-label">Obs interna</label>
                                                <input name="admin_note" value="{{ data_get($support->metadata, 'admin_note') }}" placeholder="Observacao...">
                                            </div>
                                            <button class="bg-green-700 text-white font-semibold rounded-lg px-4 py-2 text-[12px] hover:bg-green-800 transition-colors whitespace-nowrap">Atualizar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-6 py-10 text-center text-gray-400 text-sm">Nenhum apoio registrado ainda.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-gray-100">{{ $supportRequests->links() }}</div>
</div>

<script>
function toggleGatewayFields(gateway, mode) {
    document.querySelectorAll('[data-gateway="'+gateway+'"][data-mode="production"]').forEach(function(el){ el.style.display = mode==='production' ? 'block' : 'none'; });
    document.querySelectorAll('[data-gateway="'+gateway+'"][data-mode="sandbox"]').forEach(function(el){ el.style.display = mode==='sandbox' ? 'block' : 'none'; });
}
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.gtab').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var tab = this.dataset.tab;
            document.querySelectorAll('.gtab').forEach(function(b) {
                b.classList.remove('bg-white','text-green-700','border-gray-200');
                b.classList.add('bg-gray-50','text-gray-500','border-transparent');
            });
            this.classList.remove('bg-gray-50','text-gray-500','border-transparent');
            this.classList.add('bg-white','text-green-700','border-gray-200');
            document.querySelectorAll('.gtab-content').forEach(function(c) { c.classList.toggle('hidden', c.dataset.tab !== tab); });
        });
    });
    ['mercadopago','stripe','paypal','cora','pagbank','asaas','efi'].forEach(function(g) {
        var sel = document.querySelector('select[name="donation_'+g+'_mode"]');
        if (sel) toggleGatewayFields(g, sel.value);
    });
});
</script>
@endsection

@extends("layouts.admin")
@section("title", "Apoios aos Projetos")
@section("page-title", "Apoios aos Projetos")

@push("styles")
<style>
    .support-shell { display:grid;gap:20px; }
    .support-card { background:#fff;border:1px solid #e2e8f0;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,0.08),0 1px 2px rgba(0,0,0,0.06);overflow:hidden; }
    .support-card-head { display:flex;align-items:flex-start;justify-content:space-between;gap:14px;padding:20px;border-bottom:1px solid #f1f5f9;background:#fafbfc; }
    .support-card-title { margin:0;color:#1e293b;font-size:15px;font-weight:700;line-height:1.3; }
    .support-card-subtitle { margin:4px 0 0;color:#64748b;font-size:13px;line-height:1.4; }
    .support-card-body { padding:20px; }
    .support-kpi { display:flex;align-items:center;justify-content:space-between;gap:12px;padding:16px;border-radius:10px;background:#fff;border:1px solid #e2e8f0;box-shadow:0 1px 2px rgba(0,0,0,0.05);min-height:88px; }
    .support-kpi span { display:block;color:#64748b;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.04em; }
    .support-kpi strong { display:block;margin-top:4px;color:#0f172a;font-size:24px;font-weight:700;line-height:1; }
    .support-kpi-icon { width:40px;height:40px;border-radius:8px;display:flex;align-items:center;justify-content:center;background:#f0fdf4;color:#15803d;font-weight:600; }
    .support-field { width:100%;border:1px solid #d1d5db;border-radius:8px;padding:10px 12px;font-size:13px;color:#1f2937;background:#fff;transition:border-color .15s,box-shadow .15s; }
    .support-field:focus { outline:0;border-color:#16a34a;box-shadow:0 0 0 3px rgba(22,163,74,.1); }
    .support-label { display:block;font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:0.02em;color:#475569;margin-bottom:6px; }
    .support-check { display:flex;align-items:center;gap:8px;min-height:32px;font-size:13px;font-weight:500;color:#374151; }
    .support-check input { width:16px;height:16px;accent-color:#16a34a; }
    .support-btn { display:inline-flex;align-items:center;justify-content:center;gap:8px;border-radius:8px;padding:10px 16px;font-size:13px;font-weight:600;transition:background .15s,color .15s,border-color .15s; }
    .support-btn-primary { background:#16a34a;color:#fff; }
    .support-btn-primary:hover { background:#15803d;color:#fff;text-decoration:none; }
    .support-btn-dark { background:#1f2937;color:#fff; }
    .support-btn-dark:hover { background:#111827;color:#fff;text-decoration:none; }
    .support-badge { display:inline-flex;align-items:center;border-radius:9999px;padding:4px 10px;font-size:11px;font-weight:600;line-height:1; }
    .support-badge-green { background:#dcfce7;color:#166534; }
    .support-badge-gray { background:#f1f5f9;color:#475569; }
    .support-badge-blue { background:#dbeafe;color:#1d4ed8; }
    .support-badge-red { background:#fee2e2;color:#dc2626; }
    .support-row { border-top:1px solid #f1f5f9;scroll-margin-top:110px; }
    .support-row:hover { background:#fafbfc; }
    .support-helpbox { border:1px solid #bbf7d0;background:#f0fdf4;border-radius:10px;padding:14px;color:#166534;font-size:13px;line-height:1.5; }
    .support-helpbox code { background:rgba(22,101,52,.08);border-radius:4px;padding:2px 6px;font-weight:600;font-size:12px; }
    .gateway-grid { display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px; }
    .gateway-note { border:1px solid #e2e8f0;border-radius:10px;padding:14px;background:#fff; }
    .gateway-note strong { display:block;color:#1e293b;font-size:13px;font-weight:600;margin-bottom:6px; }
    .gateway-note ul { margin:0;padding-left:18px;color:#64748b;font-size:13px;line-height:1.5; }
    .type-list { display:grid;gap:14px; }
    .type-card { border:1px solid #e2e8f0;border-radius:10px;padding:16px;background:#fff; }
    .request-grid { display:grid;grid-template-columns:minmax(0,1fr) 240px;gap:20px;align-items:start; }
    .request-meta { display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px 20px;margin-top:14px;font-size:13px; }
    .request-meta dt { color:#64748b;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.04em; }
    .request-meta dd { margin:2px 0 0;color:#1f2937;font-weight:600;word-break:break-word; }
    .section-tabs { display:flex;gap:8px;flex-wrap:wrap; }
    .section-tabs a { border:1px solid #e2e8f0;background:#fff;color:#475569;border-radius:9999px;padding:8px 14px;font-size:12px;font-weight:600;text-decoration:none; }
    .section-tabs a:hover { border-color:#16a34a;color:#16a34a;background:#f0fdf4; }
    @media(max-width:1100px){.gateway-grid,.request-grid{grid-template-columns:1fr}.request-meta{grid-template-columns:1fr}.support-card-head{flex-direction:column}.section-tabs{width:100%}}
    [data-theme="dark"] .support-card,[data-theme="dark"] .support-kpi,[data-theme="dark"] .gateway-note,[data-theme="dark"] .type-card { background:#1e293b;border-color:#334155;box-shadow:none; }
    [data-theme="dark"] .support-card-head { background:#0f172a;border-color:#334155; }
    [data-theme="dark"] .support-card-title,[data-theme="dark"] .support-kpi strong,[data-theme="dark"] .gateway-note strong { color:#f1f5f9; }
    [data-theme="dark"] .support-card-subtitle,[data-theme="dark"] .support-kpi span,[data-theme="dark"] .gateway-note ul,[data-theme="dark"] .support-label { color:#94a3b8; }
    [data-theme="dark"] .support-field { background:#0f172a;border-color:#475569;color:#f1f5f9; }
    [data-theme="dark"] .support-check,[data-theme="dark"] .request-meta dd { color:#cbd5e1; }
    [data-theme="dark"] .support-row { border-color:#334155; }
    [data-theme="dark"] .support-row:hover { background:#1e293b; }
    [data-theme="dark"] .support-helpbox { background:rgba(22,101,52,.15);border-color:rgba(74,222,128,.3);color:#86efac; }
    [data-theme="dark"] .section-tabs a { background:#1e293b;border-color:#334155;color:#cbd5e1; }
    [data-theme="dark"] .section-tabs a:hover { border-color:#16a34a;color:#86efac;background:rgba(22,163,74,.1); }
</style>
@endpush

@section("content")
@php
    $fmt = fn ($value) => number_format((float) $value, 2, ",", ".");
    $statusLabels = [
        "new" => "Novo",
        "read" => "Lido",
        "contacted" => "Contatado",
        "completed" => "Concluido",
        "cancelled" => "Cancelado",
    ];
    $categoryLabels = [
        "monetario" => "Doacao monetaria",
        "insumos" => "Insumos",
        "servicos" => "Servicos",
        "voluntariado" => "Voluntariado",
        "governamental" => "Governamental",
        "outro" => "Outro",
    ];
    $gatewayLabels = [
        "mercadopago" => "Mercado Pago",
        "cora" => "Cora",
        "pagbank" => "PagBank",
        "asaas" => "Asaas",
        "efi" => "Efi Pro",
        "stripe" => "Stripe",
        "paypal" => "PayPal",
    ];
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
@endphp

<div class="support-shell">
    <div class="support-card">
        <div class="support-card-head">
            <div>
                <h2 class="support-card-title">Central de apoios e doacoes</h2>
                <p class="support-card-subtitle">Gerencie formas de apoio, gateway de doacoes monetarias, registros recebidos e instrucoes para cada portal de pagamento.</p>
            </div>
            <div class="section-tabs">
                <a href="#gateway">Gateway</a>
                <a href="#tipos">Tipos de apoio</a>
                <a href="#registros">Registros</a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="support-kpi"><div><span>Total</span><strong>{{ $stats["total"] }}</strong></div><div class="support-kpi-icon">T</div></div>
        <div class="support-kpi"><div><span>Novos</span><strong class="text-red-600">{{ $stats["new"] }}</strong></div><div class="support-kpi-icon">N</div></div>
        <div class="support-kpi"><div><span>Contatados</span><strong class="text-blue-600">{{ $stats["contacted"] }}</strong></div><div class="support-kpi-icon">C</div></div>
        <div class="support-kpi"><div><span>Concluidos</span><strong class="text-green-700">{{ $stats["completed"] }}</strong></div><div class="support-kpi-icon">OK</div></div>
        <div class="support-kpi"><div><span>Valor sinalizado</span><strong class="text-green-700">R$ {{ $fmt($stats["amount"]) }}</strong></div><div class="support-kpi-icon">R$</div></div>
    </div>

    <div id="gateway" class="support-card">
        <div class="support-card-head">
            <div>
                <h2 class="support-card-title">Gateway de doacoes</h2>
                <p class="support-card-subtitle">Somente um gateway fica ativo por vez. As URLs abaixo devem ser cadastradas no portal escolhido.</p>
            </div>
            <span class="support-badge {{ $activeGateway ? 'support-badge-green' : 'support-badge-gray' }}">
                {{ $activeGateway ? 'Ativo: ' . ($gatewayLabels[$activeGateway] ?? strtoupper($activeGateway)) : 'Gateway desativado' }}
            </span>
        </div>
        <div class="support-card-body">
            <form method="POST" action="{{ route("admin.project-supports.gateway.update") }}" class="grid grid-cols-1 xl:grid-cols-3 gap-5">
                @csrf
                @method("PUT")
                <div class="xl:col-span-1 space-y-4">
                    <div>
                        <label class="support-label">Gateway ativo</label>
                        <select name="donation_gateway_active" class="support-field">
                            <option value="">Desativado</option>
                            @foreach($gateways as $gateway)
                                <option value="{{ $gateway }}" @selected($activeGateway === $gateway)>{{ $gatewayLabels[$gateway] ?? strtoupper($gateway) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="support-label">E-mail padrao do pagador</label>
                        <input type="email" name="donation_default_payer_email" class="support-field" value="{{ $gatewaySettings["donation_default_payer_email"] ?? "" }}" placeholder="doacao@issm.org.br">
                    </div>
                    <div class="support-helpbox">
                        <p><strong>Webhook:</strong> <code>{{ url("/pagamentos/{gateway}/webhook") }}</code></p>
                        <p><strong>Retorno:</strong> <code>{{ url("/pagamentos/{gateway}/retorno") }}</code></p>
                        <p>Troque <code>{gateway}</code> por mercadopago, cora, pagbank, asaas, efi, stripe ou paypal.</p>
                    </div>
                </div>

                <div class="xl:col-span-2 space-y-5">
                    <!-- Mercado Pago -->
                    <div class="type-card">
                        <div class="flex items-center justify-between mb-3">
                            <label class="support-label mb-0">Mercado Pago</label>
                            <select name="donation_mercadopago_mode" class="support-field" style="width: auto; padding: 6px 10px;" onchange="toggleGatewayFields('mercadopago', this.value)">
                                <option value="production" @selected(($gatewaySettings["donation_mercadopago_mode"] ?? "production") === "production")>Produção</option>
                                <option value="sandbox" @selected(($gatewaySettings["donation_mercadopago_mode"] ?? "production") === "sandbox")>Sandbox</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <div data-gateway="mercadopago" data-mode="production">
                                <label class="support-label text-xs">Access Token (Produção)</label>
                                <input name="donation_mercadopago_access_token" class="support-field" value="{{ $gatewaySettings["donation_mercadopago_access_token"] ?? "" }}" placeholder="APP_USR-...">
                            </div>
                            <div data-gateway="mercadopago" data-mode="sandbox" style="display: none;">
                                <label class="support-label text-xs">Access Token (Sandbox)</label>
                                <input name="donation_mercadopago_access_token_sandbox" class="support-field" value="{{ $gatewaySettings["donation_mercadopago_access_token_sandbox"] ?? "" }}" placeholder="APP_USR-...">
                            </div>
                        </div>
                    </div>

                    <!-- Stripe -->
                    <div class="type-card">
                        <div class="flex items-center justify-between mb-3">
                            <label class="support-label mb-0">Stripe</label>
                            <select name="donation_stripe_mode" class="support-field" style="width: auto; padding: 6px 10px;" onchange="toggleGatewayFields('stripe', this.value)">
                                <option value="production" @selected(($gatewaySettings["donation_stripe_mode"] ?? "production") === "production")>Produção</option>
                                <option value="sandbox" @selected(($gatewaySettings["donation_stripe_mode"] ?? "production") === "sandbox")>Sandbox</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <div data-gateway="stripe" data-mode="production">
                                <label class="support-label text-xs">Publishable Key (Produção)</label>
                                <input name="donation_stripe_publishable_key" class="support-field" value="{{ $gatewaySettings["donation_stripe_publishable_key"] ?? "" }}" placeholder="pk_live_...">
                                <label class="support-label text-xs mt-2">Secret Key (Produção)</label>
                                <input name="donation_stripe_secret_key" class="support-field" value="{{ $gatewaySettings["donation_stripe_secret_key"] ?? "" }}" placeholder="sk_live_...">
                            </div>
                            <div data-gateway="stripe" data-mode="sandbox" style="display: none;">
                                <label class="support-label text-xs">Publishable Key (Sandbox)</label>
                                <input name="donation_stripe_publishable_key_sandbox" class="support-field" value="{{ $gatewaySettings["donation_stripe_publishable_key_sandbox"] ?? "" }}" placeholder="pk_test_...">
                                <label class="support-label text-xs mt-2">Secret Key (Sandbox)</label>
                                <input name="donation_stripe_secret_key_sandbox" class="support-field" value="{{ $gatewaySettings["donation_stripe_secret_key_sandbox"] ?? "" }}" placeholder="sk_test_...">
                            </div>
                        </div>
                    </div>

                    <!-- PayPal -->
                    <div class="type-card">
                        <div class="flex items-center justify-between mb-3">
                            <label class="support-label mb-0">PayPal</label>
                            <select name="donation_paypal_mode" class="support-field" style="width: auto; padding: 6px 10px;" onchange="toggleGatewayFields('paypal', this.value)">
                                <option value="production" @selected(($gatewaySettings["donation_paypal_mode"] ?? "production") === "production")>Produção</option>
                                <option value="sandbox" @selected(($gatewaySettings["donation_paypal_mode"] ?? "production") === "sandbox")>Sandbox</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <div data-gateway="paypal" data-mode="production">
                                <label class="support-label text-xs">Client ID (Produção)</label>
                                <input name="donation_paypal_client_id" class="support-field" value="{{ $gatewaySettings["donation_paypal_client_id"] ?? "" }}">
                                <label class="support-label text-xs mt-2">Secret (Produção)</label>
                                <input name="donation_paypal_secret" class="support-field" value="{{ $gatewaySettings["donation_paypal_secret"] ?? "" }}">
                            </div>
                            <div data-gateway="paypal" data-mode="sandbox" style="display: none;">
                                <label class="support-label text-xs">Client ID (Sandbox)</label>
                                <input name="donation_paypal_client_id_sandbox" class="support-field" value="{{ $gatewaySettings["donation_paypal_client_id_sandbox"] ?? "" }}">
                                <label class="support-label text-xs mt-2">Secret (Sandbox)</label>
                                <input name="donation_paypal_secret_sandbox" class="support-field" value="{{ $gatewaySettings["donation_paypal_secret_sandbox"] ?? "" }}">
                            </div>
                        </div>
                    </div>

                    <!-- Cora, PagBank, Asaas, Efi -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        @foreach(["cora" => "Cora", "pagbank" => "PagBank", "asaas" => "Asaas", "efi" => "Efi Pro"] as $key => $label)
                            <div class="type-card">
                                <div class="flex items-center justify-between mb-3">
                                    <label class="support-label mb-0">{{ $label }}</label>
                                    <select name="donation_{{ $key }}_mode" class="support-field" style="width: auto; padding: 6px 10px;" onchange="toggleGatewayFields('{{ $key }}', this.value)">
                                        <option value="production" @selected(($gatewaySettings["donation_{$key}_mode"] ?? "production") === "production")>Produção</option>
                                        <option value="sandbox" @selected(($gatewaySettings["donation_{$key}_mode"] ?? "production") === "sandbox")>Sandbox</option>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <div data-gateway="{{ $key }}" data-mode="production">
                                        <label class="support-label text-xs">URL Base (Produção)</label>
                                        <input name="donation_{{ $key }}_base_url" class="support-field" placeholder="URL base da API" value="{{ $gatewaySettings["donation_{$key}_base_url"] ?? "" }}">
                                        <label class="support-label text-xs mt-2">API Key (Produção)</label>
                                        <input name="donation_{{ $key }}_api_key" class="support-field" placeholder="API Key/Token" value="{{ $gatewaySettings["donation_{$key}_api_key"] ?? "" }}">
                                    </div>
                                    <div data-gateway="{{ $key }}" data-mode="sandbox" style="display: none;">
                                        <label class="support-label text-xs">URL Base (Sandbox)</label>
                                        <input name="donation_{{ $key }}_base_url_sandbox" class="support-field" placeholder="URL base da API" value="{{ $gatewaySettings["donation_{$key}_base_url_sandbox"] ?? "" }}">
                                        <label class="support-label text-xs mt-2">API Key (Sandbox)</label>
                                        <input name="donation_{{ $key }}_api_key_sandbox" class="support-field" placeholder="API Key/Token" value="{{ $gatewaySettings["donation_{$key}_api_key_sandbox"] ?? "" }}">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button class="support-btn support-btn-primary w-full text-base py-3 mt-4">Salvar configuracoes do gateway</button>
                </div>
            </form>

            <div class="mt-5">
                <h3 class="text-sm font-black text-gray-900 dark:text-white mb-3">Instrucoes por portal de pagamento</h3>
                <div class="gateway-grid">
                    @foreach($gatewayInstructions as $key => $instructions)
                        <div class="gateway-note">
                            <strong>{{ $gatewayLabels[$key] ?? strtoupper($key) }}</strong>
                            <ul>
                                @foreach($instructions as $instruction)
                                    <li>{{ $instruction }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div id="tipos" class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="support-card">
            <div class="support-card-head">
                <div>
                    <h2 class="support-card-title">Novo tipo de apoio</h2>
                    <p class="support-card-subtitle">Defina o que aparece no botao Apoiar Projeto.</p>
                </div>
            </div>
            <div class="support-card-body">
                <form method="POST" action="{{ route("admin.project-supports.types.store") }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="support-label">Nome</label>
                        <input name="name" class="support-field" required placeholder="Ex: Doacao de mudas">
                    </div>
                    <div>
                        <label class="support-label">Categoria</label>
                        <select name="category" class="support-field" required>
                            @foreach($categoryLabels as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="support-label">Descricao</label>
                        <textarea name="description" class="support-field" rows="2"></textarea>
                    </div>
                    <div>
                        <label class="support-label">Instrucoes para o apoiador</label>
                        <textarea name="instructions" class="support-field" rows="3"></textarea>
                    </div>
                    <div>
                        <label class="support-label">Valores sugeridos</label>
                        <input name="suggested_amounts" class="support-field" placeholder="50, 100, 250">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="support-check"><input type="checkbox" name="requires_amount" value="1"> Valor</label>
                        <label class="support-check"><input type="checkbox" name="requires_quantity" value="1"> Quantidade</label>
                        <label class="support-check"><input type="checkbox" name="requires_address" value="1"> Endereco</label>
                        <label class="support-check"><input type="checkbox" name="requires_document" value="1"> Documento</label>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="support-label">Ordem</label>
                            <input type="number" name="sort_order" class="support-field" value="0">
                        </div>
                        <label class="support-check mt-6"><input type="checkbox" name="active" value="1" checked> Ativo</label>
                    </div>
                    <button class="support-btn support-btn-primary w-full">Adicionar tipo</button>
                </form>
            </div>
        </div>

        <div class="xl:col-span-2 support-card">
            <div class="support-card-head">
                <div>
                    <h2 class="support-card-title">Tipos configurados</h2>
                    <p class="support-card-subtitle">Edite exigencias, instrucoes e ordem de exibicao no site.</p>
                </div>
            </div>
            <div class="support-card-body type-list">
                @forelse($supportTypes as $type)
                    <form method="POST" action="{{ route("admin.project-supports.types.update", $type) }}" class="type-card">
                        @csrf
                        @method("PUT")
                        <div class="grid grid-cols-1 lg:grid-cols-5 gap-3">
                            <div class="lg:col-span-2">
                                <label class="support-label">Nome</label>
                                <input name="name" class="support-field" value="{{ $type->name }}" required>
                            </div>
                            <div>
                                <label class="support-label">Categoria</label>
                                <select name="category" class="support-field" required>
                                    @foreach($categoryLabels as $key => $label)
                                        <option value="{{ $key }}" @selected($type->category === $key)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="support-label">Ordem</label>
                                <input type="number" name="sort_order" class="support-field" value="{{ $type->sort_order }}">
                            </div>
                            <div class="flex items-end">
                                <label class="support-check"><input type="checkbox" name="active" value="1" @checked($type->active)> Ativo</label>
                            </div>
                            <div class="lg:col-span-2">
                                <label class="support-label">Descricao</label>
                                <textarea name="description" class="support-field" rows="2">{{ $type->description }}</textarea>
                            </div>
                            <div class="lg:col-span-2">
                                <label class="support-label">Instrucoes</label>
                                <textarea name="instructions" class="support-field" rows="2">{{ $type->instructions }}</textarea>
                            </div>
                            <div>
                                <label class="support-label">Valores</label>
                                <input name="suggested_amounts" class="support-field" value="{{ collect($type->suggested_amounts ?? [])->implode(', ') }}" placeholder="50, 100, 250">
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center justify-between gap-3 mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                            <div class="flex flex-wrap gap-3">
                                <label class="support-check"><input type="checkbox" name="requires_amount" value="1" @checked($type->requires_amount)> Exigir valor</label>
                                <label class="support-check"><input type="checkbox" name="requires_quantity" value="1" @checked($type->requires_quantity)> Exigir quantidade</label>
                                <label class="support-check"><input type="checkbox" name="requires_address" value="1" @checked($type->requires_address)> Exigir endereco</label>
                                <label class="support-check"><input type="checkbox" name="requires_document" value="1" @checked($type->requires_document)> Exigir documento</label>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-xs text-gray-500">{{ $type->requests_count }} registro(s)</span>
                                <button class="support-btn support-btn-dark">Salvar</button>
                                @if($type->requests_count === 0)
                                    <button form="delete-type-{{ $type->id }}" type="submit" data-confirm="Excluir este tipo de apoio?" class="support-btn text-red-600 border border-red-200">Excluir</button>
                                @endif
                            </div>
                        </div>
                    </form>
                    @if($type->requests_count === 0)
                        <form id="delete-type-{{ $type->id }}" method="POST" action="{{ route("admin.project-supports.types.destroy", $type) }}" class="hidden">@csrf @method("DELETE")</form>
                    @endif
                @empty
                    <div class="p-8 text-center text-gray-400">Nenhum tipo configurado.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div id="registros" class="support-card">
        <div class="support-card-head">
            <div>
                <h2 class="support-card-title">Apoios recebidos</h2>
                <p class="support-card-subtitle">Todos os registros ficam vinculados ao projeto apoiado e aparecem no sininho administrativo.</p>
            </div>
            <form method="GET" class="flex flex-col sm:flex-row gap-2 w-full xl:w-auto">
                <select name="project" class="support-field">
                    <option value="">Todos os projetos</option>
                    @foreach($projects as $projectOption)
                        <option value="{{ $projectOption->id }}" @selected((string) request("project") === (string) $projectOption->id)>
                            {{ $projectOption->title }} ({{ $projectOption->support_requests_count }})
                        </option>
                    @endforeach
                </select>
                <select name="status" class="support-field">
                    <option value="">Todos</option>
                    @foreach($statusLabels as $key => $label)
                        <option value="{{ $key }}" @selected(request("status") === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                <button class="support-btn support-btn-dark">Filtrar</button>
            </form>
        </div>

        @forelse($supportRequests as $support)
            <div id="apoio-{{ $support->id }}" class="support-row p-5">
                <div class="request-grid">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="support-badge support-badge-green">{{ optional($support->supportType)->name ?: "Tipo removido" }}</span>
                            <span class="support-badge {{ $support->status === 'new' ? 'support-badge-red' : ($support->status === 'completed' ? 'support-badge-green' : 'support-badge-gray') }}">{{ $statusLabels[$support->status] ?? $support->status }}</span>
                            @if($support->payment_status)
                                <span class="support-badge support-badge-blue">Pagamento: {{ $support->payment_status }}</span>
                            @endif
                            <span class="text-xs text-gray-500">{{ optional($support->created_at)->format("d/m/Y H:i") }}</span>
                        </div>
                        <h3 class="font-black text-gray-900 dark:text-white text-lg">{{ $support->name }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300">{{ optional($support->project)->title ?: "Projeto removido" }}</p>

                        <dl class="request-meta">
                            <div><dt>Contato</dt><dd>{{ $support->phone }} @if($support->email) - {{ $support->email }} @endif</dd></div>
                            <div><dt>Perfil</dt><dd>{{ str_replace("_", " ", $support->supporter_type) }}</dd></div>
                            @if($support->organization || $support->government_agency)<div><dt>Organizacao/orgao</dt><dd>{{ $support->organization ?: $support->government_agency }}</dd></div>@endif
                            @if($support->amount)<div><dt>Valor</dt><dd class="text-green-700">R$ {{ $fmt($support->amount) }}</dd></div>@endif
                            @if($support->payment_gateway)<div><dt>Gateway</dt><dd>{{ strtoupper($support->payment_gateway) }} / {{ strtoupper((string) $support->payment_method) }}</dd></div>@endif
                            @if($support->payment_external_id)<div><dt>ID externo</dt><dd>{{ $support->payment_external_id }}</dd></div>@endif
                            @if($support->payment_reference)<div><dt>Referencia</dt><dd>{{ $support->payment_reference }}</dd></div>@endif
                            @if($support->quantity)<div><dt>Quantidade</dt><dd>{{ $fmt($support->quantity) }} {{ $support->unit }}</dd></div>@endif
                            @if($support->item_description)<div class="md:col-span-2"><dt>Apoio oferecido</dt><dd>{{ $support->item_description }}</dd></div>@endif
                            @if($support->message)<div class="md:col-span-2"><dt>Mensagem</dt><dd>{{ $support->message }}</dd></div>@endif
                            <div><dt>IP</dt><dd class="text-gray-500">{{ $support->ip_address ?: "-" }}</dd></div>
                        </dl>
                    </div>
                    <form method="POST" action="{{ route("admin.project-supports.requests.update", $support) }}" class="space-y-2">
                        @csrf
                        @method("PUT")
                        <label class="support-label">Status interno</label>
                        <select name="status" class="support-field">
                            @foreach($statusLabels as $key => $label)
                                <option value="{{ $key }}" @selected($support->status === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <label class="support-label">Observacao interna</label>
                        <textarea name="admin_note" class="support-field" rows="4" placeholder="Observacao interna">{{ data_get($support->metadata, "admin_note") }}</textarea>
                        <button class="support-btn support-btn-primary w-full">Atualizar registro</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="p-12 text-center text-gray-400">Nenhum apoio registrado ainda.</div>
        @endforelse

        <div class="p-4 border-t border-gray-100 dark:border-gray-700">{{ $supportRequests->links() }}</div>
    </div>
</div>

<script>
function toggleGatewayFields(gateway, mode) {
    const productionFields = document.querySelectorAll(`[data-gateway="${gateway}"][data-mode="production"]`);
    const sandboxFields = document.querySelectorAll(`[data-gateway="${gateway}"][data-mode="sandbox"]`);
    
    productionFields.forEach(field => {
        field.style.display = mode === 'production' ? 'block' : 'none';
    });
    
    sandboxFields.forEach(field => {
        field.style.display = mode === 'sandbox' ? 'block' : 'none';
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const gateways = ['mercadopago', 'stripe', 'paypal', 'cora', 'pagbank', 'asaas', 'efi'];
    gateways.forEach(gateway => {
        const select = document.querySelector(`select[name="donation_${gateway}_mode"]`);
        if (select) {
            toggleGatewayFields(gateway, select.value);
        }
    });
});
</script>
@endsection

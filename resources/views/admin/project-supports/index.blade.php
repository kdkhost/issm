@extends("layouts.admin")
@section("title", "Apoios aos Projetos")
@section("page-title", "Apoios aos Projetos")


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

<div class="mb-6">
    <div class="bg-white rounded-xl shadow-sm mb-6 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="font-bold text-gray-800">Central de apoios e doacoes</h2>
                    <p class="text-sm text-gray-500 mt-1">Gerencie formas de apoio, gateway de doacoes monetarias, registros recebidos e instrucoes para cada portal de pagamento.</p>
                </div>
                <div class="flex gap-2">
                    <a href="#gateway" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">Gateway</a>
                    <a href="#tipos" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">Tipos de apoio</a>
                    <a href="#registros" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">Registros</a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-5 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total</p>
                <p class="text-2xl font-black text-gray-900 mt-1">{{ $stats["total"] }}</p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Novos</p>
                <p class="text-2xl font-black text-red-600 mt-1">{{ $stats["new"] }}</p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Contatados</p>
                <p class="text-2xl font-black text-blue-600 mt-1">{{ $stats["contacted"] }}</p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Concluidos</p>
                <p class="text-2xl font-black text-green-700 mt-1">{{ $stats["completed"] }}</p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Valor sinalizado</p>
                <p class="text-2xl font-black text-green-700 mt-1">R$ {{ $fmt($stats["amount"]) }}</p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>
</div>

    <div id="gateway" class="bg-white rounded-xl shadow-sm mb-6 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="font-bold text-gray-800">Gateway de doacoes</h2>
                    <p class="text-sm text-gray-500 mt-1">Somente um gateway fica ativo por vez. As URLs abaixo devem ser cadastradas no portal escolhido.</p>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $activeGateway ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                    {{ $activeGateway ? 'Ativo: ' . ($gatewayLabels[$activeGateway] ?? strtoupper($activeGateway)) : 'Gateway desativado' }}
                </span>
            </div>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route("admin.project-supports.gateway.update") }}" class="grid grid-cols-1 xl:grid-cols-3 gap-5">
                @csrf
                @method("PUT")
                <div class="xl:col-span-1 space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Gateway ativo</label>
                        <select name="donation_gateway_active">
                            <option value="">Desativado</option>
                            @foreach($gateways as $gateway)
                                <option value="{{ $gateway }}" @selected($activeGateway === $gateway)>{{ $gatewayLabels[$gateway] ?? strtoupper($gateway) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">E-mail padrao do pagador</label>
                        <input type="email" name="donation_default_payer_email" value="{{ $gatewaySettings["donation_default_payer_email"] ?? "" }}" placeholder="doacao@issm.org.br">
                    </div>
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-sm text-green-800">
                        <p><strong class="font-semibold">Webhook:</strong> <code class="bg-green-100 px-1 rounded">{{ url("/pagamentos/{gateway}/webhook") }}</code></p>
                        <p class="mt-1"><strong class="font-semibold">Retorno:</strong> <code class="bg-green-100 px-1 rounded">{{ url("/pagamentos/{gateway}/retorno") }}</code></p>
                        <p class="mt-1 text-xs">Troque <code class="bg-green-100 px-1 rounded">{gateway}</code> por mercadopago, cora, pagbank, asaas, efi, stripe ou paypal.</p>
                    </div>
                </div>

                <div class="xl:col-span-2 space-y-5">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Mercado Pago - Access Token</label>
                            <input name="donation_mercadopago_access_token" value="{{ $gatewaySettings["donation_mercadopago_access_token"] ?? "" }}" placeholder="APP_USR-...">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Stripe - Publishable Key</label>
                            <input name="donation_stripe_publishable_key" value="{{ $gatewaySettings["donation_stripe_publishable_key"] ?? "" }}" placeholder="pk_live_...">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Stripe - Secret Key</label>
                            <input name="donation_stripe_secret_key" value="{{ $gatewaySettings["donation_stripe_secret_key"] ?? "" }}" placeholder="sk_live_...">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">PayPal - Client ID</label>
                            <input name="donation_paypal_client_id" value="{{ $gatewaySettings["donation_paypal_client_id"] ?? "" }}">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">PayPal - Secret</label>
                            <input name="donation_paypal_secret" value="{{ $gatewaySettings["donation_paypal_secret"] ?? "" }}">
                        </div>
                        <div class="flex items-center mt-6">
                            <input type="checkbox" name="donation_paypal_sandbox" value="1" id="paypal_sandbox" @checked(($gatewaySettings["donation_paypal_sandbox"] ?? "1") === "1") class="w-4 h-4 text-green-600 rounded">
                            <label for="paypal_sandbox" class="ml-2 text-sm font-medium text-gray-700">Usar sandbox do PayPal para testes</label>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        @foreach(["cora" => "Cora", "pagbank" => "PagBank", "asaas" => "Asaas", "efi" => "Efi Pro"] as $key => $label)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ $label }}</label>
                                <div class="space-y-2">
                                    <input name="donation_{{ $key }}_base_url" placeholder="URL base da API" value="{{ $gatewaySettings["donation_{$key}_base_url"] ?? "" }}">
                                    <input name="donation_{{ $key }}_api_key" placeholder="API Key/Token" value="{{ $gatewaySettings["donation_{$key}_api_key"] ?? "" }}">
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button class="bg-gray-900 text-white px-6 py-2 rounded-lg hover:bg-gray-800 font-medium text-sm w-full">Salvar configuracoes do gateway</button>
                </div>
            </form>

            <div class="mt-6 pt-6 border-t border-gray-100">
                <h3 class="text-sm font-bold text-gray-900 mb-3">Instrucoes por portal de pagamento</h3>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    @foreach($gatewayInstructions as $key => $instructions)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <strong class="block text-sm font-bold text-gray-900 mb-2">{{ $gatewayLabels[$key] ?? strtoupper($key) }}</strong>
                            <ul class="text-sm text-gray-600 space-y-1">
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
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="font-bold text-gray-800">Novo tipo de apoio</h2>
                <p class="text-sm text-gray-500 mt-1">Defina o que aparece no botao Apoiar Projeto.</p>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route("admin.project-supports.types.store") }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nome</label>
                        <input name="name" required placeholder="Ex: Doacao de mudas">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Categoria</label>
                        <select name="category" required>
                            @foreach($categoryLabels as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Descricao</label>
                        <textarea name="description" rows="2"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Instrucoes para o apoiador</label>
                        <textarea name="instructions" rows="3"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Valores sugeridos</label>
                        <input name="suggested_amounts" placeholder="50, 100, 250">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="requires_amount" value="1" class="w-4 h-4 text-green-600 rounded">
                            <label class="text-sm font-medium text-gray-700">Valor</label>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="requires_quantity" value="1" class="w-4 h-4 text-green-600 rounded">
                            <label class="text-sm font-medium text-gray-700">Quantidade</label>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="requires_address" value="1" class="w-4 h-4 text-green-600 rounded">
                            <label class="text-sm font-medium text-gray-700">Endereco</label>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="requires_document" value="1" class="w-4 h-4 text-green-600 rounded">
                            <label class="text-sm font-medium text-gray-700">Documento</label>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Ordem</label>
                            <input type="number" name="sort_order" value="0">
                        </div>
                        <div class="flex items-end">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" name="active" value="1" checked class="w-4 h-4 text-green-600 rounded">
                                <label class="text-sm font-medium text-gray-700">Ativo</label>
                            </div>
                        </div>
                    </div>
                    <button class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 font-medium text-sm w-full">Adicionar tipo</button>
                </form>
            </div>
        </div>

        <div class="xl:col-span-2 bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="font-bold text-gray-800">Tipos configurados</h2>
                <p class="text-sm text-gray-500 mt-1">Edite exigencias, instrucoes e ordem de exibicao no site.</p>
            </div>
            <div class="p-6 space-y-4">
                @forelse($supportTypes as $type)
                    <form method="POST" action="{{ route("admin.project-supports.types.update", $type) }}" class="border border-gray-200 rounded-lg p-4">
                        @csrf
                        @method("PUT")
                        <div class="grid grid-cols-1 lg:grid-cols-5 gap-3">
                            <div class="lg:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Nome</label>
                                <input name="name" value="{{ $type->name }}" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Categoria</label>
                                <select name="category" required>
                                    @foreach($categoryLabels as $key => $label)
                                        <option value="{{ $key }}" @selected($type->category === $key)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Ordem</label>
                                <input type="number" name="sort_order" value="{{ $type->sort_order }}">
                            </div>
                            <div class="flex items-end">
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" name="active" value="1" @checked($type->active) class="w-4 h-4 text-green-600 rounded">
                                    <label class="text-sm font-medium text-gray-700">Ativo</label>
                                </div>
                            </div>
                            <div class="lg:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Descricao</label>
                                <textarea name="description" rows="2">{{ $type->description }}</textarea>
                            </div>
                            <div class="lg:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Instrucoes</label>
                                <textarea name="instructions" rows="2">{{ $type->instructions }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Valores</label>
                                <input name="suggested_amounts" value="{{ collect($type->suggested_amounts ?? [])->implode(', ') }}" placeholder="50, 100, 250">
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center justify-between gap-3 mt-3 pt-3 border-t border-gray-100">
                            <div class="flex flex-wrap gap-3">
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" name="requires_amount" value="1" @checked($type->requires_amount) class="w-4 h-4 text-green-600 rounded">
                                    <label class="text-sm font-medium text-gray-700">Exigir valor</label>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" name="requires_quantity" value="1" @checked($type->requires_quantity) class="w-4 h-4 text-green-600 rounded">
                                    <label class="text-sm font-medium text-gray-700">Exigir quantidade</label>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" name="requires_address" value="1" @checked($type->requires_address) class="w-4 h-4 text-green-600 rounded">
                                    <label class="text-sm font-medium text-gray-700">Exigir endereco</label>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" name="requires_document" value="1" @checked($type->requires_document) class="w-4 h-4 text-green-600 rounded">
                                    <label class="text-sm font-medium text-gray-700">Exigir documento</label>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-xs text-gray-500">{{ $type->requests_count }} registro(s)</span>
                                <button class="bg-gray-900 text-white px-4 py-2 rounded-lg hover:bg-gray-800 font-medium text-sm">Salvar</button>
                                @if($type->requests_count === 0)
                                    <button form="delete-type-{{ $type->id }}" type="submit" data-confirm="Excluir este tipo de apoio?" class="px-4 py-2 border border-red-200 rounded-lg text-red-600 hover:bg-red-50 text-sm">Excluir</button>
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

    <div id="registros" class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="font-bold text-gray-800">Apoios recebidos</h2>
                    <p class="text-sm text-gray-500 mt-1">Todos os registros ficam vinculados ao projeto apoiado e aparecem no sininho administrativo.</p>
                </div>
                <form method="GET" class="flex flex-col sm:flex-row gap-2 w-full xl:w-auto">
                    <select name="project">
                        <option value="">Todos os projetos</option>
                        @foreach($projects as $projectOption)
                            <option value="{{ $projectOption->id }}" @selected((string) request("project") === (string) $projectOption->id)>
                                {{ $projectOption->title }} ({{ $projectOption->support_requests_count }})
                            </option>
                        @endforeach
                    </select>
                    <select name="status">
                        <option value="">Todos</option>
                        @foreach($statusLabels as $key => $label)
                            <option value="{{ $key }}" @selected(request("status") === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button class="bg-gray-900 text-white px-4 py-2 rounded-lg hover:bg-gray-800 font-medium text-sm">Filtrar</button>
                </form>
            </div>
        </div>

        <div class="p-6">
            @forelse($supportRequests as $support)
                <div id="apoio-{{ $support->id }}" class="border-t border-gray-100 first:border-t-0 p-5 scroll-margin-top-20 hover:bg-gray-50">
                    <div class="grid grid-cols-1 lg:grid-cols-[1fr_250px] gap-6">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">{{ optional($support->supportType)->name ?: "Tipo removido" }}</span>
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $support->status === 'new' ? 'bg-red-100 text-red-700' : ($support->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700') }}">{{ $statusLabels[$support->status] ?? $support->status }}</span>
                                @if($support->payment_status)
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">Pagamento: {{ $support->payment_status }}</span>
                                @endif
                                <span class="text-xs text-gray-500">{{ optional($support->created_at)->format("d/m/Y H:i") }}</span>
                            </div>
                            <h3 class="font-black text-gray-900 text-lg">{{ $support->name }}</h3>
                            <p class="text-sm text-gray-600">{{ optional($support->project)->title ?: "Projeto removido" }}</p>

                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3 mt-4 text-sm">
                                <div><dt class="text-xs font-bold text-gray-500 uppercase tracking-wider">Contato</dt><dd class="mt-1 font-semibold text-gray-900">{{ $support->phone }} @if($support->email) - {{ $support->email }} @endif</dd></div>
                                <div><dt class="text-xs font-bold text-gray-500 uppercase tracking-wider">Perfil</dt><dd class="mt-1 font-semibold text-gray-900">{{ str_replace("_", " ", $support->supporter_type) }}</dd></div>
                                @if($support->organization || $support->government_agency)<div><dt class="text-xs font-bold text-gray-500 uppercase tracking-wider">Organizacao/orgao</dt><dd class="mt-1 font-semibold text-gray-900">{{ $support->organization ?: $support->government_agency }}</dd></div>@endif
                                @if($support->amount)<div><dt class="text-xs font-bold text-gray-500 uppercase tracking-wider">Valor</dt><dd class="mt-1 font-semibold text-green-700">R$ {{ $fmt($support->amount) }}</dd></div>@endif
                                @if($support->payment_gateway)<div><dt class="text-xs font-bold text-gray-500 uppercase tracking-wider">Gateway</dt><dd class="mt-1 font-semibold text-gray-900">{{ strtoupper($support->payment_gateway) }} / {{ strtoupper((string) $support->payment_method) }}</dd></div>@endif
                                @if($support->payment_external_id)<div><dt class="text-xs font-bold text-gray-500 uppercase tracking-wider">ID externo</dt><dd class="mt-1 font-semibold text-gray-900">{{ $support->payment_external_id }}</dd></div>@endif
                                @if($support->payment_reference)<div><dt class="text-xs font-bold text-gray-500 uppercase tracking-wider">Referencia</dt><dd class="mt-1 font-semibold text-gray-900">{{ $support->payment_reference }}</dd></div>@endif
                                @if($support->quantity)<div><dt class="text-xs font-bold text-gray-500 uppercase tracking-wider">Quantidade</dt><dd class="mt-1 font-semibold text-gray-900">{{ $fmt($support->quantity) }} {{ $support->unit }}</dd></div>@endif
                                @if($support->item_description)<div class="md:col-span-2"><dt class="text-xs font-bold text-gray-500 uppercase tracking-wider">Apoio oferecido</dt><dd class="mt-1 font-semibold text-gray-900">{{ $support->item_description }}</dd></div>@endif
                                @if($support->message)<div class="md:col-span-2"><dt class="text-xs font-bold text-gray-500 uppercase tracking-wider">Mensagem</dt><dd class="mt-1 font-semibold text-gray-900">{{ $support->message }}</dd></div>@endif
                                <div><dt class="text-xs font-bold text-gray-500 uppercase tracking-wider">IP</dt><dd class="mt-1 text-gray-500">{{ $support->ip_address ?: "-" }}</dd></div>
                            </dl>
                        </div>
                        <form method="POST" action="{{ route("admin.project-supports.requests.update", $support) }}" class="space-y-4">
                            @csrf
                            @method("PUT")
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Status interno</label>
                                <select name="status">
                                    @foreach($statusLabels as $key => $label)
                                        <option value="{{ $key }}" @selected($support->status === $key)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Observacao interna</label>
                                <textarea name="admin_note" rows="4" placeholder="Observacao interna">{{ data_get($support->metadata, "admin_note") }}</textarea>
                            </div>
                            <button class="bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800 font-medium text-sm w-full">Atualizar registro</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center text-gray-400">Nenhum apoio registrado ainda.</div>
            @endforelse
        </div>

        <div class="p-4 border-t border-gray-100">{{ $supportRequests->links() }}</div>
    </div>
</div>
@endsection

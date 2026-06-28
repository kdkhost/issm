@extends("layouts.admin")
@section("title", "Apoios aos Projetos")
@section("page-title", "Apoios aos Projetos")

@push("styles")
<style>
    .support-card { background:#fff;border:1px solid #e5e7eb;border-radius:16px;box-shadow:0 1px 4px rgba(15,23,42,.05); }
    .support-field { width:100%;border:1px solid #d1d5db;border-radius:10px;padding:9px 11px;font-size:13px;color:#111827;background:#fff; }
    .support-label { display:block;font-size:11px;font-weight:900;text-transform:uppercase;letter-spacing:.04em;color:#6b7280;margin-bottom:6px; }
    .support-check { display:flex;align-items:center;gap:8px;font-size:12px;font-weight:700;color:#374151; }
    .support-badge { display:inline-flex;align-items:center;border-radius:999px;padding:4px 9px;font-size:11px;font-weight:800; }
    .support-row { border-top:1px solid #e5e7eb; }
    [data-theme="dark"] .support-card { background:#1f2937;border-color:#374151; }
    [data-theme="dark"] .support-field { background:#111827;border-color:#4b5563;color:#f9fafb; }
    [data-theme="dark"] .support-label,
    [data-theme="dark"] .support-check { color:#9ca3af; }
    [data-theme="dark"] .support-row { border-color:#374151; }
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
@endphp

<div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
    <div class="support-card p-4"><p class="text-xs text-gray-500 font-bold uppercase">Total</p><strong class="text-2xl text-gray-900 dark:text-white">{{ $stats["total"] }}</strong></div>
    <div class="support-card p-4"><p class="text-xs text-gray-500 font-bold uppercase">Novos</p><strong class="text-2xl text-red-600">{{ $stats["new"] }}</strong></div>
    <div class="support-card p-4"><p class="text-xs text-gray-500 font-bold uppercase">Contatados</p><strong class="text-2xl text-blue-600">{{ $stats["contacted"] }}</strong></div>
    <div class="support-card p-4"><p class="text-xs text-gray-500 font-bold uppercase">Concluidos</p><strong class="text-2xl text-green-700">{{ $stats["completed"] }}</strong></div>
    <div class="support-card p-4"><p class="text-xs text-gray-500 font-bold uppercase">Valor sinalizado</p><strong class="text-2xl text-green-700">R$ {{ $fmt($stats["amount"]) }}</strong></div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <div class="xl:col-span-1 space-y-6">
        <div class="support-card p-5">
            <h2 class="text-base font-black text-gray-900 dark:text-white mb-1">Gateway de doacoes</h2>
            <p class="text-sm text-gray-500 mb-4">Somente um gateway fica ativo por vez para doacoes monetarias.</p>
            <form method="POST" action="{{ route("admin.project-supports.gateway.update") }}" class="space-y-3">
                @csrf
                @method("PUT")
                <div>
                    <label class="support-label">Gateway ativo</label>
                    <select name="donation_gateway_active" class="support-field">
                        <option value="">Desativado</option>
                        @foreach($gateways as $gateway)
                            <option value="{{ $gateway }}" @selected(($gatewaySettings["donation_gateway_active"] ?? "") === $gateway)>{{ strtoupper($gateway) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="support-label">E-mail padrao do pagador</label>
                    <input type="email" name="donation_default_payer_email" class="support-field" value="{{ $gatewaySettings["donation_default_payer_email"] ?? "" }}">
                </div>
                <div class="rounded-xl bg-green-50 border border-green-100 p-3 text-xs text-green-900 space-y-1">
                    <p><strong>Webhook dinamico:</strong> {{ url("/pagamentos/{gateway}/webhook") }}</p>
                    <p><strong>Retorno dinamico:</strong> {{ url("/pagamentos/{gateway}/retorno") }}</p>
                    <p>Substitua <strong>{gateway}</strong> por mercadopago, cora, pagbank, asaas, efi, stripe ou paypal.</p>
                </div>
                <div>
                    <label class="support-label">Mercado Pago - Access Token</label>
                    <input name="donation_mercadopago_access_token" class="support-field" value="{{ $gatewaySettings["donation_mercadopago_access_token"] ?? "" }}">
                </div>
                <div class="grid grid-cols-1 gap-2">
                    <input name="donation_stripe_publishable_key" class="support-field" placeholder="Stripe Publishable Key" value="{{ $gatewaySettings["donation_stripe_publishable_key"] ?? "" }}">
                    <input name="donation_stripe_secret_key" class="support-field" placeholder="Stripe Secret Key" value="{{ $gatewaySettings["donation_stripe_secret_key"] ?? "" }}">
                    <input name="donation_paypal_client_id" class="support-field" placeholder="PayPal Client ID" value="{{ $gatewaySettings["donation_paypal_client_id"] ?? "" }}">
                    <input name="donation_paypal_secret" class="support-field" placeholder="PayPal Secret" value="{{ $gatewaySettings["donation_paypal_secret"] ?? "" }}">
                    <label class="support-check"><input type="checkbox" name="donation_paypal_sandbox" value="1" @checked(($gatewaySettings["donation_paypal_sandbox"] ?? "1") === "1")> PayPal sandbox</label>
                </div>
                @foreach(["cora" => "Cora", "pagbank" => "PagBank", "asaas" => "Asaas", "efi" => "Efí Pro"] as $key => $label)
                    <div class="grid grid-cols-1 gap-2 border-t border-gray-100 pt-3">
                        <label class="support-label">{{ $label }}</label>
                        <input name="donation_{{ $key }}_base_url" class="support-field" placeholder="URL base da API" value="{{ $gatewaySettings["donation_{$key}_base_url"] ?? "" }}">
                        <input name="donation_{{ $key }}_api_key" class="support-field" placeholder="API Key/Token" value="{{ $gatewaySettings["donation_{$key}_api_key"] ?? "" }}">
                    </div>
                @endforeach
                <button class="w-full bg-gray-900 hover:bg-black text-white rounded-lg px-4 py-2 font-bold">Salvar gateway</button>
            </form>
        </div>

        <div class="support-card p-5">
            <h2 class="text-base font-black text-gray-900 dark:text-white mb-1">Novo tipo de apoio</h2>
            <p class="text-sm text-gray-500 mb-4">Configure as formas exibidas no botao Apoiar Projeto.</p>
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
                    <label class="support-check"><input type="checkbox" name="requires_amount" value="1"> Exigir valor</label>
                    <label class="support-check"><input type="checkbox" name="requires_quantity" value="1"> Exigir quantidade</label>
                    <label class="support-check"><input type="checkbox" name="requires_address" value="1"> Exigir endereco</label>
                    <label class="support-check"><input type="checkbox" name="requires_document" value="1"> Exigir documento</label>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="support-label">Ordem</label>
                        <input type="number" name="sort_order" class="support-field" value="0">
                    </div>
                    <label class="support-check mt-7"><input type="checkbox" name="active" value="1" checked> Ativo</label>
                </div>
                <button class="w-full bg-green-700 hover:bg-green-800 text-white rounded-lg px-4 py-2 font-bold">Adicionar tipo</button>
            </form>
        </div>

        <div class="support-card p-5">
            <h2 class="text-base font-black text-gray-900 dark:text-white mb-4">Tipos configurados</h2>
            <div class="space-y-4">
                @forelse($supportTypes as $type)
                    <form method="POST" action="{{ route("admin.project-supports.types.update", $type) }}" class="border border-gray-200 dark:border-gray-700 rounded-xl p-3 space-y-3">
                        @csrf
                        @method("PUT")
                        <div class="grid grid-cols-1 gap-2">
                            <input name="name" class="support-field" value="{{ $type->name }}" required>
                            <select name="category" class="support-field" required>
                                @foreach($categoryLabels as $key => $label)
                                    <option value="{{ $key }}" @selected($type->category === $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <textarea name="description" class="support-field" rows="2">{{ $type->description }}</textarea>
                            <textarea name="instructions" class="support-field" rows="2">{{ $type->instructions }}</textarea>
                            <input name="suggested_amounts" class="support-field" value="{{ collect($type->suggested_amounts ?? [])->implode(', ') }}" placeholder="50, 100, 250">
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="support-check"><input type="checkbox" name="requires_amount" value="1" @checked($type->requires_amount)> Valor</label>
                            <label class="support-check"><input type="checkbox" name="requires_quantity" value="1" @checked($type->requires_quantity)> Quantidade</label>
                            <label class="support-check"><input type="checkbox" name="requires_address" value="1" @checked($type->requires_address)> Endereco</label>
                            <label class="support-check"><input type="checkbox" name="requires_document" value="1" @checked($type->requires_document)> Documento</label>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <input type="number" name="sort_order" class="support-field max-w-[90px]" value="{{ $type->sort_order }}">
                            <label class="support-check"><input type="checkbox" name="active" value="1" @checked($type->active)> Ativo</label>
                            <span class="text-xs text-gray-500">{{ $type->requests_count }} registro(s)</span>
                        </div>
                        <div class="flex items-center justify-between gap-2">
                            <button class="bg-blue-600 hover:bg-blue-700 text-white rounded-lg px-3 py-2 text-sm font-bold">Salvar</button>
                            @if($type->requests_count === 0)
                                <button form="delete-type-{{ $type->id }}" type="submit" data-confirm="Excluir este tipo de apoio?" class="text-red-600 text-sm font-bold">Excluir</button>
                            @endif
                        </div>
                    </form>
                    @if($type->requests_count === 0)
                        <form id="delete-type-{{ $type->id }}" method="POST" action="{{ route("admin.project-supports.types.destroy", $type) }}" class="hidden">@csrf @method("DELETE")</form>
                    @endif
                @empty
                    <p class="text-sm text-gray-500">Nenhum tipo configurado.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="xl:col-span-2 support-card overflow-hidden">
        <div class="p-5 border-b border-gray-100 dark:border-gray-700 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
                <h2 class="text-base font-black text-gray-900 dark:text-white">Apoios recebidos</h2>
                <p class="text-sm text-gray-500">Todos os registros ficam vinculados ao projeto apoiado.</p>
            </div>
            <form method="GET" class="flex flex-col sm:flex-row gap-2">
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
                <button class="bg-gray-900 text-white px-4 rounded-lg font-bold text-sm">Filtrar</button>
            </form>
        </div>

        @forelse($supportRequests as $support)
            <div id="apoio-{{ $support->id }}" class="support-row p-5">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="support-badge bg-green-100 text-green-800">{{ optional($support->supportType)->name ?: "Tipo removido" }}</span>
                            <span class="support-badge bg-gray-100 text-gray-700">{{ $statusLabels[$support->status] ?? $support->status }}</span>
                            <span class="text-xs text-gray-500">{{ optional($support->created_at)->format("d/m/Y H:i") }}</span>
                        </div>
                        <h3 class="font-black text-gray-900 dark:text-white">{{ $support->name }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300">{{ optional($support->project)->title ?: "Projeto removido" }}</p>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2 mt-3 text-sm">
                            <div><dt class="text-xs font-bold text-gray-500 uppercase">Contato</dt><dd class="text-gray-800 dark:text-gray-200">{{ $support->phone }} @if($support->email) • {{ $support->email }} @endif</dd></div>
                            <div><dt class="text-xs font-bold text-gray-500 uppercase">Perfil</dt><dd class="text-gray-800 dark:text-gray-200">{{ str_replace("_", " ", $support->supporter_type) }}</dd></div>
                            @if($support->organization || $support->government_agency)<div><dt class="text-xs font-bold text-gray-500 uppercase">Organizacao/orgao</dt><dd class="text-gray-800 dark:text-gray-200">{{ $support->organization ?: $support->government_agency }}</dd></div>@endif
                            @if($support->amount)<div><dt class="text-xs font-bold text-gray-500 uppercase">Valor</dt><dd class="text-green-700 font-bold">R$ {{ $fmt($support->amount) }}</dd></div>@endif
                            @if($support->payment_gateway)<div><dt class="text-xs font-bold text-gray-500 uppercase">Gateway</dt><dd class="text-gray-800 dark:text-gray-200">{{ strtoupper($support->payment_gateway) }} / {{ strtoupper((string) $support->payment_method) }}</dd></div>@endif
                            @if($support->payment_status)<div><dt class="text-xs font-bold text-gray-500 uppercase">Pagamento</dt><dd class="text-gray-800 dark:text-gray-200">{{ $support->payment_status }} @if($support->payment_external_id) • {{ $support->payment_external_id }} @endif</dd></div>@endif
                            @if($support->payment_reference)<div><dt class="text-xs font-bold text-gray-500 uppercase">Referencia</dt><dd class="text-gray-800 dark:text-gray-200">{{ $support->payment_reference }}</dd></div>@endif
                            @if($support->item_description)<div class="md:col-span-2"><dt class="text-xs font-bold text-gray-500 uppercase">Apoio oferecido</dt><dd class="text-gray-800 dark:text-gray-200">{{ $support->item_description }}</dd></div>@endif
                            @if($support->quantity)<div><dt class="text-xs font-bold text-gray-500 uppercase">Quantidade</dt><dd class="text-gray-800 dark:text-gray-200">{{ $fmt($support->quantity) }} {{ $support->unit }}</dd></div>@endif
                            @if($support->message)<div class="md:col-span-2"><dt class="text-xs font-bold text-gray-500 uppercase">Mensagem</dt><dd class="text-gray-800 dark:text-gray-200">{{ $support->message }}</dd></div>@endif
                            <div><dt class="text-xs font-bold text-gray-500 uppercase">IP</dt><dd class="text-gray-500">{{ $support->ip_address ?: "-" }}</dd></div>
                        </dl>
                    </div>
                    <form method="POST" action="{{ route("admin.project-supports.requests.update", $support) }}" class="w-full lg:w-56 space-y-2">
                        @csrf
                        @method("PUT")
                        <select name="status" class="support-field">
                            @foreach($statusLabels as $key => $label)
                                <option value="{{ $key }}" @selected($support->status === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <textarea name="admin_note" class="support-field" rows="3" placeholder="Observacao interna">{{ data_get($support->metadata, "admin_note") }}</textarea>
                        <button class="w-full bg-green-700 hover:bg-green-800 text-white rounded-lg px-3 py-2 text-sm font-bold">Atualizar</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="p-12 text-center text-gray-400">Nenhum apoio registrado ainda.</div>
        @endforelse

        <div class="p-4 border-t border-gray-100 dark:border-gray-700">{{ $supportRequests->links() }}</div>
    </div>
</div>
@endsection

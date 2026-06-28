<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectSupportRequest;
use App\Models\ProjectSupportType;
use App\Models\Setting;
use App\Services\Payments\ProjectDonationGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProjectSupportController extends Controller
{
    public function index(Request $request)
    {
        $supportTypes = ProjectSupportType::withCount('requests')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        $projects = Project::withCount('supportRequests')
            ->having('support_requests_count', '>', 0)
            ->orderBy('title')
            ->get(['id', 'title']);

        $supportRequests = ProjectSupportRequest::with(['project:id,title,slug', 'supportType:id,name,category'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', (string) $request->string('status')))
            ->when($request->filled('project'), fn ($query) => $query->where('project_id', $request->integer('project')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total' => ProjectSupportRequest::count(),
            'new' => ProjectSupportRequest::where('status', 'new')->count(),
            'contacted' => ProjectSupportRequest::where('status', 'contacted')->count(),
            'completed' => ProjectSupportRequest::where('status', 'completed')->count(),
            'amount' => ProjectSupportRequest::whereNotNull('amount')->sum('amount'),
        ];
        $gatewaySettings = collect([
            'donation_gateway_active',
            'donation_default_payer_email',
            'donation_mercadopago_mode',
            'donation_mercadopago_access_token',
            'donation_mercadopago_access_token_sandbox',
            'donation_stripe_mode',
            'donation_stripe_publishable_key',
            'donation_stripe_secret_key',
            'donation_stripe_publishable_key_sandbox',
            'donation_stripe_secret_key_sandbox',
            'donation_paypal_mode',
            'donation_paypal_client_id',
            'donation_paypal_secret',
            'donation_paypal_client_id_sandbox',
            'donation_paypal_secret_sandbox',
            'donation_cora_mode',
            'donation_cora_base_url',
            'donation_cora_api_key',
            'donation_cora_base_url_sandbox',
            'donation_cora_api_key_sandbox',
            'donation_pagbank_mode',
            'donation_pagbank_base_url',
            'donation_pagbank_api_key',
            'donation_pagbank_base_url_sandbox',
            'donation_pagbank_api_key_sandbox',
            'donation_asaas_mode',
            'donation_asaas_base_url',
            'donation_asaas_api_key',
            'donation_asaas_base_url_sandbox',
            'donation_asaas_api_key_sandbox',
            'donation_efi_mode',
            'donation_efi_base_url',
            'donation_efi_api_key',
            'donation_efi_base_url_sandbox',
            'donation_efi_api_key_sandbox',
        ])->mapWithKeys(fn ($key) => [$key => Setting::get($key, '')])->all();
        $gateways = ProjectDonationGateway::GATEWAYS;

        return view('admin.project-supports.index', compact('supportTypes', 'supportRequests', 'stats', 'projects', 'gatewaySettings', 'gateways'));
    }

    public function updateGateway(Request $request)
    {
        $validated = $request->validate([
            'donation_gateway_active' => ['nullable', 'string', Rule::in(array_merge([''], ProjectDonationGateway::GATEWAYS))],
            'donation_default_payer_email' => 'nullable|email|max:255',
            'donation_mercadopago_mode' => 'nullable|string|in:production,sandbox',
            'donation_mercadopago_access_token' => 'nullable|string|max:1000',
            'donation_mercadopago_access_token_sandbox' => 'nullable|string|max:1000',
            'donation_stripe_mode' => 'nullable|string|in:production,sandbox',
            'donation_stripe_publishable_key' => 'nullable|string|max:500',
            'donation_stripe_secret_key' => 'nullable|string|max:500',
            'donation_stripe_publishable_key_sandbox' => 'nullable|string|max:500',
            'donation_stripe_secret_key_sandbox' => 'nullable|string|max:500',
            'donation_paypal_mode' => 'nullable|string|in:production,sandbox',
            'donation_paypal_client_id' => 'nullable|string|max:500',
            'donation_paypal_secret' => 'nullable|string|max:500',
            'donation_paypal_client_id_sandbox' => 'nullable|string|max:500',
            'donation_paypal_secret_sandbox' => 'nullable|string|max:500',
            'donation_cora_mode' => 'nullable|string|in:production,sandbox',
            'donation_cora_base_url' => 'nullable|string|max:500',
            'donation_cora_api_key' => 'nullable|string|max:1000',
            'donation_cora_base_url_sandbox' => 'nullable|string|max:500',
            'donation_cora_api_key_sandbox' => 'nullable|string|max:1000',
            'donation_pagbank_mode' => 'nullable|string|in:production,sandbox',
            'donation_pagbank_base_url' => 'nullable|string|max:500',
            'donation_pagbank_api_key' => 'nullable|string|max:1000',
            'donation_pagbank_base_url_sandbox' => 'nullable|string|max:500',
            'donation_pagbank_api_key_sandbox' => 'nullable|string|max:1000',
            'donation_asaas_mode' => 'nullable|string|in:production,sandbox',
            'donation_asaas_base_url' => 'nullable|string|max:500',
            'donation_asaas_api_key' => 'nullable|string|max:1000',
            'donation_asaas_base_url_sandbox' => 'nullable|string|max:500',
            'donation_asaas_api_key_sandbox' => 'nullable|string|max:1000',
            'donation_efi_mode' => 'nullable|string|in:production,sandbox',
            'donation_efi_base_url' => 'nullable|string|max:500',
            'donation_efi_api_key' => 'nullable|string|max:1000',
            'donation_efi_base_url_sandbox' => 'nullable|string|max:500',
            'donation_efi_api_key_sandbox' => 'nullable|string|max:1000',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value ?? '');
        }

        return back()->with('success', 'Gateway de doacoes atualizado com sucesso.');
    }

    public function storeType(Request $request)
    {
        $validated = $this->typeRules($request);
        $validated['slug'] = $this->uniqueSlug($validated['name']);
        $validated['suggested_amounts'] = $this->parseSuggestedAmounts($validated['suggested_amounts'] ?? null);
        $validated = $this->booleanPayload($validated, $request);

        ProjectSupportType::create($validated);

        return back()->with('success', 'Tipo de apoio criado com sucesso.');
    }

    public function updateType(Request $request, ProjectSupportType $type)
    {
        $validated = $this->typeRules($request, $type);
        $validated['slug'] = $type->slug ?: $this->uniqueSlug($validated['name'], $type->id);
        $validated['suggested_amounts'] = $this->parseSuggestedAmounts($validated['suggested_amounts'] ?? null);
        $validated = $this->booleanPayload($validated, $request);

        $type->update($validated);

        return back()->with('success', 'Tipo de apoio atualizado com sucesso.');
    }

    public function destroyType(ProjectSupportType $type)
    {
        if ($type->requests()->exists()) {
            return back()->with('error', 'Este tipo possui apoios registrados e nao pode ser excluido. Desative-o se nao quiser exibir no site.');
        }

        $type->delete();

        return back()->with('success', 'Tipo de apoio excluido com sucesso.');
    }

    public function updateRequest(Request $request, ProjectSupportRequest $supportRequest)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:new,read,contacted,completed,cancelled',
            'admin_note' => 'nullable|string|max:3000',
        ]);

        if ($validated['status'] === 'contacted' && ! $supportRequest->contacted_at) {
            $validated['contacted_at'] = now();
        }

        $metadata = $supportRequest->metadata ?? [];
        $metadata['admin_note'] = $validated['admin_note'] ?? null;

        $supportRequest->update([
            'status' => $validated['status'],
            'contacted_at' => $validated['contacted_at'] ?? $supportRequest->contacted_at,
            'metadata' => $metadata,
        ]);

        return back()->with('success', 'Registro de apoio atualizado.');
    }

    private function typeRules(Request $request, ?ProjectSupportType $type = null): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'category' => ['required', 'string', Rule::in(['monetario', 'insumos', 'servicos', 'voluntariado', 'governamental', 'outro'])],
            'description' => 'nullable|string|max:2000',
            'instructions' => 'nullable|string|max:3000',
            'suggested_amounts' => 'nullable|string|max:255',
            'requires_amount' => 'nullable|boolean',
            'requires_quantity' => 'nullable|boolean',
            'requires_address' => 'nullable|boolean',
            'requires_document' => 'nullable|boolean',
            'active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);
    }

    private function booleanPayload(array $validated, Request $request): array
    {
        foreach (['requires_amount', 'requires_quantity', 'requires_address', 'requires_document', 'active'] as $field) {
            $validated[$field] = $request->boolean($field);
        }

        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        return $validated;
    }

    private function parseSuggestedAmounts(?string $amounts): ?array
    {
        if (! $amounts) {
            return null;
        }

        $values = collect(explode(',', $amounts))
            ->map(fn ($value) => trim($value))
            ->filter()
            ->map(fn ($value) => (float) str_replace(['.', ','], ['', '.'], preg_replace('/[^\d,.]/', '', $value)))
            ->filter(fn ($value) => $value > 0)
            ->values()
            ->all();

        return $values ?: null;
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'apoio';
        $slug = $base;
        $counter = 2;

        while (ProjectSupportType::where('slug', $slug)->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))->exists()) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}

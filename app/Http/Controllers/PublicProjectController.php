<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectSupportType;
use App\Models\Setting;
use App\Services\Payments\ProjectDonationGateway;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class PublicProjectController extends Controller
{
    public function index()
    {
        $featuredProjects = Project::active()->featured()->take(3)->get();
        $projects = Project::active()->paginate(9);
        $settings = ['site_name' => Setting::get('site_name', 'ISSM')];
        return view('projects.index', compact('featuredProjects', 'projects', 'settings'));
    }

    public function show(string $slug, ProjectDonationGateway $donationGateway)
    {
        $project = Project::active()->where('slug', $slug)->firstOrFail();
        $related = Project::active()->where('id', '!=', $project->id)->take(3)->get();
        $supportTypes = ProjectSupportType::active()->get();
        $activeDonationGateway = $donationGateway->activeGateway();
        $donationPaymentMethods = $donationGateway->supportedMethods($activeDonationGateway);
        $donationMethodLabels = ProjectDonationGateway::METHOD_LABELS;
        $settings = ['site_name' => Setting::get('site_name', 'ISSM')];
        return view('projects.show', compact('project', 'related', 'settings', 'supportTypes', 'activeDonationGateway', 'donationPaymentMethods', 'donationMethodLabels'));
    }

    public function support(Request $request, Project $project, ProjectDonationGateway $donationGateway)
    {
        abort_unless($project->active, 404);

        $validated = $request->validate([
            'project_support_type_id' => 'required|integer|exists:project_support_types,id',
            'supporter_type' => 'required|string|in:pessoa_fisica,pessoa_juridica,governo,instituicao',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:30',
            'document' => 'nullable|string|max:30',
            'organization' => 'nullable|string|max:255',
            'government_agency' => 'nullable|string|max:255',
            'amount' => 'nullable|string|max:30',
            'payment_method' => 'nullable|string|in:pix,credit_card,debit_card,paypal',
            'item_description' => 'nullable|string|max:2000',
            'quantity' => 'nullable|string|max:30',
            'unit' => 'nullable|string|max:40',
            'address' => 'nullable|string|max:255',
            'message' => 'nullable|string|max:3000',
            'preferred_contact' => 'nullable|string|in:telefone,whatsapp,email',
        ]);

        $supportType = ProjectSupportType::active()->whereKey($validated['project_support_type_id'])->firstOrFail();
        $this->validateRequiredSupportFields($supportType, $validated);

        $support = $project->supportRequests()->create([
            'project_support_type_id' => $supportType->id,
            'supporter_type' => $validated['supporter_type'],
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'],
            'document' => $validated['document'] ?? null,
            'organization' => $validated['organization'] ?? null,
            'government_agency' => $validated['government_agency'] ?? null,
            'amount' => $this->numberToDecimal($validated['amount'] ?? null),
            'currency' => 'BRL',
            'item_description' => $validated['item_description'] ?? null,
            'quantity' => $this->numberToDecimal($validated['quantity'] ?? null),
            'unit' => $validated['unit'] ?? null,
            'address' => $validated['address'] ?? null,
            'message' => $validated['message'] ?? null,
            'preferred_contact' => $validated['preferred_contact'] ?? null,
            'status' => 'new',
            'metadata' => [
                'support_type_name' => $supportType->name,
                'project_title' => $project->title,
                'referer' => $request->headers->get('referer'),
                'url' => $request->fullUrl(),
            ],
            'ip_address' => $request->ip(),
            'user_agent' => (string) $request->userAgent(),
        ]);

        $payment = null;
        $message = 'Apoio registrado com sucesso. Nossa equipe entrara em contato para dar continuidade.';

        if ($supportType->category === 'monetario') {
            try {
                $payment = $donationGateway->createPayment($support->fresh(['project']), $validated['payment_method'] ?? 'pix', $request);
            } catch (RuntimeException $exception) {
                throw ValidationException::withMessages([
                    'payment_method' => $exception->getMessage(),
                ]);
            }
            $message = 'Doacao registrada. Conclua o pagamento pelo gateway configurado.';
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => $message, 'payment' => $payment]);
        }

        return back()->with('success', $message);
    }

    private function validateRequiredSupportFields(ProjectSupportType $supportType, array $validated): void
    {
        $messages = [];

        if ($supportType->requires_amount && empty($validated['amount'])) {
            $messages['amount'] = 'Informe o valor da doacao.';
        }

        if ($supportType->requires_quantity && empty($validated['quantity'])) {
            $messages['quantity'] = 'Informe a quantidade aproximada.';
        }

        if ($supportType->requires_address && empty($validated['address'])) {
            $messages['address'] = 'Informe o endereco para entrega ou retirada.';
        }

        if ($supportType->requires_document && empty($validated['document'])) {
            $messages['document'] = 'Informe o CPF, CNPJ ou identificacao institucional.';
        }

        if ($supportType->category === 'governamental' && empty($validated['government_agency'])) {
            $messages['government_agency'] = 'Informe o orgao, secretaria ou entidade governamental.';
        }

        if ($messages) {
            throw ValidationException::withMessages($messages);
        }
    }

    private function numberToDecimal(?string $value): ?float
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        $normalized = str_replace(['.', ','], ['', '.'], preg_replace('/[^\d,.]/', '', $value));

        return is_numeric($normalized) ? (float) $normalized : null;
    }
}

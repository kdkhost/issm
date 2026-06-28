<?php

namespace App\Http\Controllers;

use App\Models\ProjectSupportRequest;
use App\Services\Payments\ProjectDonationGateway;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function webhook(string $gateway, Request $request, ProjectDonationGateway $donationGateway)
    {
        $donationGateway->handleWebhook($gateway, $request);

        return response()->json(['received' => true]);
    }

    public function return(string $gateway, Request $request)
    {
        $support = ProjectSupportRequest::where('payment_reference', $request->query('reference'))->first();
        $route = $support?->project
            ? route('projects.show', $support->project->slug)
            : route('projects.index');

        return redirect($route)
            ->with($request->boolean('cancelled') ? 'error' : 'success', $request->boolean('cancelled') ? 'Pagamento cancelado.' : 'Pagamento recebido para processamento. O status sera atualizado automaticamente.');
    }
}

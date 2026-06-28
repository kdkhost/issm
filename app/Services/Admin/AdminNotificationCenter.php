<?php

namespace App\Services\Admin;

use App\Models\Contact;
use App\Models\ProjectSupportRequest;

class AdminNotificationCenter
{
    public function unreadCount(): int
    {
        return Contact::new()->count()
            + ProjectSupportRequest::new()->count()
            + ProjectSupportRequest::where('payment_status', 'paid')->where('paid_at', '>=', now()->subDay())->count();
    }

    public function latest(int $limit = 8): array
    {
        $contacts = Contact::new()
            ->latest()
            ->take($limit)
            ->get(['id', 'name', 'email', 'subject', 'created_at'])
            ->map(fn (Contact $contact) => [
                'id' => 'contact-' . $contact->id,
                'type' => 'Contato',
                'name' => $contact->name,
                'subject' => $contact->subject,
                'detail' => $contact->email,
                'time' => optional($contact->created_at)->format('d/m/Y H:i'),
                'timestamp' => optional($contact->created_at)->timestamp ?? 0,
                'url' => route('admin.contatos.show', $contact),
            ]);

        $supports = ProjectSupportRequest::new()
            ->with(['project:id,title,slug', 'supportType:id,name,category'])
            ->latest()
            ->take($limit)
            ->get()
            ->map(fn (ProjectSupportRequest $support) => [
                'id' => 'support-' . $support->id,
                'type' => $support->payment_gateway ? 'Doacao' : 'Apoio',
                'name' => $support->name,
                'subject' => (optional($support->supportType)->name ?: 'Apoio recebido') . ' - ' . (optional($support->project)->title ?: 'Projeto'),
                'detail' => $support->email ?: $support->phone,
                'time' => optional($support->created_at)->format('d/m/Y H:i'),
                'timestamp' => optional($support->created_at)->timestamp ?? 0,
                'url' => route('admin.project-supports.index', ['project' => $support->project_id]) . '#apoio-' . $support->id,
            ]);

        $payments = ProjectSupportRequest::where('payment_status', 'paid')
            ->where('paid_at', '>=', now()->subDays(7))
            ->with(['project:id,title,slug'])
            ->latest('paid_at')
            ->take($limit)
            ->get()
            ->map(fn (ProjectSupportRequest $support) => [
                'id' => 'payment-' . $support->id,
                'type' => 'Pagamento',
                'name' => $support->name,
                'subject' => 'Doacao paga - ' . (optional($support->project)->title ?: 'Projeto'),
                'detail' => 'R$ ' . number_format((float) $support->amount, 2, ',', '.') . ' via ' . strtoupper((string) $support->payment_gateway),
                'time' => optional($support->paid_at ?: $support->updated_at)->format('d/m/Y H:i'),
                'timestamp' => optional($support->paid_at ?: $support->updated_at)->timestamp ?? 0,
                'url' => route('admin.project-supports.index', ['project' => $support->project_id]) . '#apoio-' . $support->id,
            ]);

        return $contacts
            ->merge($supports)
            ->merge($payments)
            ->sortByDesc('timestamp')
            ->take($limit)
            ->values()
            ->all();
    }
}

<?php

namespace App\Services\Admin;

use App\Mail\AdminActionNotificationMail;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AdminNotificationMailer
{
    public function send(string $type, string $title, string $summary, string $url, array $details = []): void
    {
        if (Setting::get('contact_notification_email_enabled', '1') !== '1') {
            return;
        }

        $to = trim((string) Setting::get('contact_notification_to', ''))
            ?: trim((string) Setting::get('contact_email', ''));

        if (! filter_var($to, FILTER_VALIDATE_EMAIL)) {
            return;
        }

        $bcc = $this->emailList(Setting::get('contact_notification_bcc', ''));

        try {
            $message = Mail::to($to);

            if ($bcc) {
                $message->bcc($bcc);
            }

            $message->send(new AdminActionNotificationMail($type, $title, $summary, $url, $details));
        } catch (\Throwable $exception) {
            Log::warning('Falha ao enviar notificacao administrativa por e-mail.', [
                'type' => $type,
                'title' => $title,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    private function emailList(?string $value): array
    {
        return collect(preg_split('/[\s,;]+/', (string) $value))
            ->map(fn ($email) => trim($email))
            ->filter(fn ($email) => filter_var($email, FILTER_VALIDATE_EMAIL))
            ->unique()
            ->values()
            ->all();
    }
}

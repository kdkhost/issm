<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminActionNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $type,
        public string $title,
        public string $summary,
        public string $url,
        public array $details = []
    ) {
    }

    public function build()
    {
        return $this
            ->subject($this->type . ' - ' . $this->title)
            ->view('emails.admin-action-notification');
    }
}

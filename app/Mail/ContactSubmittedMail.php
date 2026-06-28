<?php

namespace App\Mail;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Contact $contact)
    {
    }

    public function build()
    {
        return $this
            ->subject('Nova mensagem de contato - '.$this->contact->subject)
            ->replyTo($this->contact->email, $this->contact->name)
            ->view('emails.contact-submitted');
    }
}

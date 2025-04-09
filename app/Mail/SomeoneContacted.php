<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SomeoneContacted extends Mailable
{
    use Queueable, SerializesModels;

    public string $name;
    public string $email;
    public string $messageSubject;
    public string $contactMessage;

    /**
     * Create a new message instance.
     */
    public function __construct(string $name, string $email, string $messageSubject, string $contactMessage)
    {
        $this->name = $name;
        $this->email = $email;
        $this->messageSubject = $messageSubject;
        $this->contactMessage = $contactMessage;
    }


    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'FEICS - Solicitação de contato',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contact.contacted',
            // with: [
            //     'name' => $this->name,
            //     'email' => $this->email,
            //     'messageSubject' => $this->messageSubject,
            //     'message' => $this->message,
            // ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

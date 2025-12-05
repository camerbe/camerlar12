<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $contactData;
    protected $nom;
    protected $objet;
    protected $message;
    protected $email;

    /**
     * Create a new message instance.
     */
    public function __construct($contactData)
    {
        //
        $this->contactData=$contactData;
        $this->email=$this->contactData['email'];
        $this->objet=$this->contactData['subject'];
        $this->nom=$this->contactData['nom'];
        $this->message=$this->contactData['message'];


    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->contactData['subject'],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.contact',
            with:[
                'fullName'=>$this->nom,
                'email'=>$this->email,
                'msg'=>$this->message,

            ],
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

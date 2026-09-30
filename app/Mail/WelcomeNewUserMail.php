<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeNewUserMail extends Mailable
{
    use Queueable, SerializesModels;


    protected $userData;
    protected $email;
    protected $nom;
    protected $prenom;
    protected $password;
    /**
     * Create a new message instance.
     */
    public function __construct($user)
    {
        //
        $this->userData=$user;

        $this->email=$this->userData['email'];
        $this->nom=$this->userData['nom'];
        $this->nom=$this->userData['prenom'];
        $this->password=$this->userData['password'];
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: [
                $this->email,
            ],
            subject: 'E-mail de bienvenue au nouvel utilisateur',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.user-creation',with:[
                'nom'=>$this->nom,
                'prenom'=>$this->prenom,
                'password'=>'123456',
                'email'=>$this->email,
            ]
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

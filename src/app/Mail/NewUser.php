<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use App\Models\User;
use App\Models\UsersProjects;

class NewUser extends Mailable
{
    use Queueable;

    public $data;
    public $destinatario;

    /**
     * Create a new message instance.
     */
    public function __construct($id)
    {

        $data = User::select('name','email')
            ->where('id',$id)
            ->get();

        $this->data = $data[0];

        $this->destinatario = $this->data['email'];

    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: $this->destinatario,
            from: new Address( env('MAIL_FROM_ADDRESS', 'devtrac@devtrac.com'), env('MAIL_FROM_NAME', 'DevTrac')),
            subject: 'Usuário Cadastrado',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {

        return new Content(
            html: 'email-templates.new-user',
            with: [
                'data' => $this->data,
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

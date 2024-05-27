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
    
        $destinatario = [];

        $ret = User::Select('email')
            ->where('admin', 1)
            ->get();
      
        foreach($ret as $elem) {
            array_push($destinatario,$elem->email);
        }
        
        $this->destinatario = $destinatario;

    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: $this->destinatario,
            from: new Address( env('MAIL_FROM_ADDRESS', 'devtrac@devtrac.com'), env('MAIL_FROM_NAME', 'DevTrac')),
            subject: 'Novo Usuário Registrado',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {

        return new Content(
            html: 'email-templates.test-ticket',
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

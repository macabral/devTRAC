<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use App\Models\User;
use App\Models\UsersProjects;

class UserAssociated extends Mailable
{
    use Queueable;

    public $data;
    public $destinatario;

    /**
     * Create a new message instance.
     */
    public function __construct($id,$project)
    {

        $data = UsersProjects::select('users.name','users.email','projects.title','users_projects.gp','users_projects.relator','users_projects.dev','users_projects.tester')
            ->leftJoin('users','users.id','=','users_projects.users_id')
            ->leftJoin('projects','projects.id','=','users_projects.projects_id')
            ->where('users_id','=',$id)
            ->where('projects_id','=',$project)
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
            subject: 'Usuário Associado ao Projeto',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {

        return new Content(
            html: 'email-templates.user-associated',
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

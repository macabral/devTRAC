<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use App\Models\Tickets;
use App\Models\UsersProjects;

class TestTicket extends Mailable
{
    use Queueable;

    public $data;
    public $destinatario;

    /**
     * Create a new message instance.
     */
    public function __construct($id)
    {

        $destinatario = [];

        $data = Tickets::Select('tickets.id','tickets.title','projects.title as project','version','tickets.projects_id')
            ->leftJoin('projects','projects.id','=','tickets.projects_id')
            ->leftJoin('sprints','sprints.id','=','tickets.sprints_id')
            ->where('tickets.id', $id)->get();

        $this->data = $data[0];

        $project = $this->data['projects_id'];
      
        $ret = UsersProjects::select('a.email')
            ->leftJoin('users as a','a.id','=','users_id')
            ->where('projects_id','=',$project)
            ->where('tester','=','1')
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
            subject: 'Tíquete enviado para teste',
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

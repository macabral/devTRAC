<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use App\Models\Tickets;

class NewTicket extends Mailable
{
    use Queueable;

    public $data;

    /**
     * Create a new message instance.
     */
    public function __construct($id)
    {

        $data = Tickets::Select('tickets.title','projects.title as project','version','users.email')
            ->leftJoin('projects','projects.id','=','tickets.projects_id')
            ->leftJoin('releases','releases.id','=','tickets.releases_id')
            ->leftJoin('users','resp_id','=','users.id')
            ->where('tickets.id', $id)->get();

        $this->data = $data;

    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: $this->data['email'],
            from: new Address( env('MAIL_FROM_ADDRESS', 'devtrac@devtrac.com'), env('MAIL_FROM_NAME', 'DevTrac')),
            subject: 'Novo Tíquete',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {

        return new Content(
            html: 'email-templates.new-ticket',
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

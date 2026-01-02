<?php

namespace Modules\Employee\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Modules\Employee\Models\Employee;

class SendPasswordSetupMail extends Mailable
{
    use Queueable, SerializesModels;

    public Employee $employee;
    public string $setupUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Employee $employee, string $setupUrl)
    {
        $this->employee = $employee;
        $this->setupUrl = $setupUrl;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Thiết lập mật khẩu tài khoản - ' . config('app.name'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'employee::emails.password-setup',
            with: [
                'employee' => $this->employee,
                'setupUrl' => $this->setupUrl,
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

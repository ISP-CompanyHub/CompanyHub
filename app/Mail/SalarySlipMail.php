<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SalarySlipMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public User $user,
        public string $month,
        public string $pdfPath
    ) {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $monthName = \Carbon\Carbon::parse($this->month)->format('F Y');
        return new Envelope(
            subject: "Salary Slip for {$monthName} - " . config('app.name'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.salary-slip',
            with: [
                'user' => $this->user,
                'month' => $this->month,
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
        $monthName = \Carbon\Carbon::parse($this->month)->format('Y-m');
        return [
            Attachment::fromStorage($this->pdfPath)
                ->as("salary-slip-{$monthName}.pdf")
                ->withMime('application/pdf'),
        ];
    }
}

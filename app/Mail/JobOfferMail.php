<?php

namespace App\Mail;

use App\Models\JobOffer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JobOfferMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public JobOffer $jobOffer,
        public string $pdfPath
    ) {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Job Offer from '.config('app.name'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.job-offer',
            with: [
                'jobOffer' => $this->jobOffer,
                'candidate' => $this->jobOffer->candidate,
                'jobPosting' => $this->jobOffer->jobPosting,
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
        return [
            Attachment::fromStorage($this->pdfPath)
                ->as("job-offer-{$this->jobOffer->offer_number}.pdf")
                ->withMime('application/pdf'),
        ];
    }
}

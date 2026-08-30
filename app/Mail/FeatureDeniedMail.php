<?php

namespace App\Mail;

use App\Models\Feature;
use App\Models\Schools;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FeatureDeniedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Schools $school;

    public Feature $feature;

    public string $reason;

    public string $code;

    /**
     * Create a new message instance.
     */
    public function __construct(Schools $school, Feature $feature, string $reason, string $code)
    {
        $this->school = $school;
        $this->feature = $feature;
        $this->reason = $reason;
        $this->code = $code;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Feature Access Restricted — ' . $this->feature->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.feature-denied',
            with: [
                'school' => $this->school,
                'feature' => $this->feature,
                'reason' => $this->reason,
                'code' => $this->code,
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

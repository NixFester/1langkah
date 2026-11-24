<?php

namespace App\Mail;

use App\Models\SiapAttempt;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SiapResultMail extends Mailable
{
    use Queueable, SerializesModels;

    public SiapAttempt $attempt;

    public bool $isFullReport;

    /** @var array<int, array{focus: string, recommendation: string}> */
    public array $recommendations;

    /**
     * @param  array<int, array{focus: string, recommendation: string}>  $recommendations
     */
    public function __construct(SiapAttempt $attempt, bool $isFullReport = false, array $recommendations = [])
    {
        $this->attempt = $attempt;
        $this->isFullReport = $isFullReport;
        $this->recommendations = $recommendations;
    }

    public function envelope(): Envelope
    {
        $title = $this->attempt->assessment?->title ?? 'Asesmen SIAP';
        $prefix = $this->isFullReport ? '[Hasil Lengkap SIAP]' : '[Hasil Preview SIAP]';

        return new Envelope(
            subject: "{$prefix} {$title} - 1Langkah",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.siap-result',
        );
    }
}

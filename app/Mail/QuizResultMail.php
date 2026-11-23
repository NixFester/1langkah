<?php

namespace App\Mail;

use App\Models\Quiz;
use App\Models\TestAttempt;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuizResultMail extends Mailable
{
    use Queueable, SerializesModels;

    public TestAttempt $attempt;

    public ?Quiz $quiz;

    public bool $isFullReport;

    public function __construct(TestAttempt $attempt, ?Quiz $quiz = null, bool $isFullReport = false)
    {
        $this->attempt = $attempt;
        $this->quiz = $quiz;
        $this->isFullReport = $isFullReport;
    }

    public function envelope(): Envelope
    {
        $title = $this->quiz?->title ?? 'Tes Psikologi & Kepribadian';
        $prefix = $this->isFullReport ? '[Hasil Lengkap & Pembahasan]' : '[Hasil Preview]';

        return new Envelope(
            subject: "{$prefix} {$title} - 1Langkah",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.quiz-result',
        );
    }
}

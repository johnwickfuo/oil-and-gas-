<?php

namespace App\Mail;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentAdminNotificationMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(public Payment $payment)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Deposit paid · '.$this->payment->booking->reference,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.payments.admin',
            with: [
                'payment' => $this->payment,
                'booking' => $this->payment->booking,
            ],
        );
    }
}

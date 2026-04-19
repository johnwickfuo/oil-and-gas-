<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewBookingAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Booking $booking)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New booking: '.$this->booking->reference,
            replyTo: [$this->booking->email],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.bookings.admin',
            with: [
                'booking' => $this->booking,
                'service' => $this->booking->service,
            ],
        );
    }
}

<x-mail::message>
# Thank you, {{ $booking->name }}!

We've received your deposit of **₦{{ number_format((float) $payment->amount, 2) }}** for booking **{{ $booking->reference }}**.

- **Service:** {{ $booking->service?->title }}
- **Event date:** {{ optional($booking->event_date)->format('D, j F Y') }} @ {{ $booking->event_time }}
- **Gateway:** {{ ucfirst($payment->gateway) }}
- **Payment reference:** {{ $payment->reference }}

Our team will confirm the finer details shortly. If you need anything urgent, reply to this email.

<x-mail::button :url="url('/')">
Visit Blue Dine
</x-mail::button>

Thanks again,<br>
Chef Eureka & the Blue Dine team
</x-mail::message>

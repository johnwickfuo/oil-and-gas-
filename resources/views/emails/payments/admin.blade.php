<x-mail::message>
# Deposit paid

**{{ $booking->name }}** has paid their deposit.

- **Booking:** {{ $booking->reference }}
- **Service:** {{ $booking->service?->title }}
- **Event:** {{ optional($booking->event_date)->format('D, j F Y') }} @ {{ $booking->event_time }}
- **Amount:** ₦{{ number_format((float) $payment->amount, 2) }}
- **Gateway:** {{ ucfirst($payment->gateway) }}
- **Gateway reference:** {{ $payment->reference }}

<x-mail::button :url="url('/admin/bookings/'.$booking->id.'/edit')">
Open in admin
</x-mail::button>

— Blue Dine system
</x-mail::message>

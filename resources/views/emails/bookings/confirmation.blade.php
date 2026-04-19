@php
    /** @var \App\Models\Booking $booking */
    /** @var \App\Models\Service|null $service */
@endphp
<x-mail::message>
# We received your booking

Hi {{ $booking->name }},

Thanks for booking with **Blue Dine Cuisines**. We'll confirm availability and come back to you within one business day.

## Booking summary

- **Reference:** {{ $booking->reference }}
- **Service:** {{ $service?->title ?? 'Blue Dine service' }}
- **Date:** {{ $booking->event_date?->format('l, j F Y') }} at {{ substr((string) $booking->event_time, 0, 5) }}
- **Guests:** {{ $booking->guests }}
- **Location:** {{ $booking->location }}

## Estimated total

**₦{{ number_format((float) $booking->estimated_total) }}** — deposit due to confirm: **₦{{ number_format((float) $booking->deposit_amount) }}**.

<x-mail::button :url="url('/payment/'.$booking->reference)">
Pay deposit
</x-mail::button>

If anything in the summary looks off, just reply to this email and we'll fix it up.

Warmly,
Chef Eureka & the Blue Dine team
</x-mail::message>

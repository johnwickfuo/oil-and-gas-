@php
    /** @var \App\Models\Booking $booking */
    /** @var \App\Models\Service|null $service */
@endphp
<x-mail::message>
# New booking enquiry

A new booking has been submitted from the site.

## Details

- **Reference:** {{ $booking->reference }}
- **Client:** {{ $booking->name }} — {{ $booking->email }} — {{ $booking->phone }}
- **Service:** {{ $service?->title ?? '—' }}
- **Date:** {{ $booking->event_date?->format('l, j F Y') }} at {{ substr((string) $booking->event_time, 0, 5) }}
- **Guests:** {{ $booking->guests }}
- **Location:** {{ $booking->location }}
- **Estimated total:** ₦{{ number_format((float) $booking->estimated_total) }}
- **Deposit due:** ₦{{ number_format((float) $booking->deposit_amount) }}

@if (!empty($booking->addons))
## Add-ons
@foreach ($booking->addons as $key)
- {{ $key }}
@endforeach
@endif

@if ($booking->menu_preferences)
## Menu preferences
{{ $booking->menu_preferences }}
@endif

@if ($booking->dietary_notes)
## Dietary notes
{{ $booking->dietary_notes }}
@endif

@if ($booking->special_requests)
## Special requests
{{ $booking->special_requests }}
@endif

<x-mail::button :url="url('/admin/bookings/'.$booking->id.'/edit')">
Open in admin
</x-mail::button>

Blue Dine booking system
</x-mail::message>

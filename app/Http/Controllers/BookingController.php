<?php

namespace App\Http\Controllers;

use App\Events\BookingCreated;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Service;
use App\Support\BookingPricing;
use App\Support\Settings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BookingController extends Controller
{
    public function calculator(Request $request): Response
    {
        return Inertia::render('PricingCalculator', [
            'services' => $this->servicesPayload(),
            'config' => $this->pricingPayload(),
            'initial' => [
                'service_slug' => $request->query('service'),
            ],
        ]);
    }

    public function create(Request $request): Response
    {
        return Inertia::render('Booking', [
            'services' => $this->servicesPayload(),
            'config' => $this->pricingPayload(),
            'initial' => [
                'service_slug' => $request->query('service'),
                'guests' => (int) $request->query('guests', 0) ?: null,
                'location' => $request->query('location'),
                'addons' => array_values(array_filter((array) $request->query('addons', []))),
            ],
        ]);
    }

    public function store(StoreBookingRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $pricing = BookingPricing::compute([
            'service_slug' => $data['service_slug'],
            'guests' => (int) $data['guests'],
            'addons' => $data['addons'] ?? [],
            'location' => $data['location'],
        ]);

        $service = Service::query()->where('slug', $data['service_slug'])->first();

        $booking = Booking::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'service_id' => $service?->id,
            'event_date' => $data['event_date'],
            'event_time' => $data['event_time'],
            'guests' => $pricing['guests'],
            'location' => $pricing['location_label'] ?? $data['location'],
            'dietary_notes' => $data['dietary_notes'] ?? null,
            'special_requests' => $data['special_requests'] ?? null,
            'menu_preferences' => $data['menu_preferences'] ?? null,
            'addons' => $data['addons'] ?? [],
            'estimated_total' => $pricing['total'],
            'deposit_amount' => $pricing['deposit'],
            'status' => 'pending_payment',
            'payment_status' => 'unpaid',
        ]);

        BookingCreated::dispatch($booking);

        return redirect()->route('booking.confirmation', $booking->reference);
    }

    public function confirmation(string $reference): Response
    {
        $booking = Booking::with('service')->where('reference', $reference)->firstOrFail();

        return Inertia::render('BookingConfirmation', [
            'booking' => [
                'reference' => $booking->reference,
                'name' => $booking->name,
                'email' => $booking->email,
                'phone' => $booking->phone,
                'event_date' => $booking->event_date?->toDateString(),
                'event_time' => substr((string) $booking->event_time, 0, 5),
                'guests' => $booking->guests,
                'location' => $booking->location,
                'estimated_total' => (float) $booking->estimated_total,
                'deposit_amount' => (float) $booking->deposit_amount,
                'service' => $booking->service?->only(['title', 'slug']),
                'addons' => $booking->addons ?? [],
            ],
            'settings' => [
                'whatsapp_number' => Settings::get('whatsapp_number'),
            ],
        ]);
    }

    private function servicesPayload(): array
    {
        return Service::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id', 'title', 'slug', 'short_description', 'base_price', 'image'])
            ->map(function (Service $s) {
                $rate = config('pricing.service_base_rates.'.$s->slug);

                return [
                    'id' => $s->id,
                    'title' => $s->title,
                    'slug' => $s->slug,
                    'short_description' => $s->short_description,
                    'base_price' => (float) $s->base_price,
                    'image' => $s->image,
                    'base_per_guest' => $rate['base_per_guest'] ?? null,
                    'minimum_guests' => $rate['minimum_guests'] ?? 1,
                ];
            })
            ->all();
    }

    private function pricingPayload(): array
    {
        return [
            'deposit_percentage' => (int) config('pricing.deposit_percentage'),
            'addons' => collect(config('pricing.addons', []))
                ->map(fn ($a, $k) => [
                    'key' => $k,
                    'label' => $a['label'],
                    'description' => $a['description'] ?? '',
                    'price' => (int) $a['price'],
                ])
                ->values()
                ->all(),
            'locations' => collect(config('pricing.location_fees', []))
                ->map(fn ($l, $k) => [
                    'key' => $k,
                    'label' => $l['label'],
                    'logistics_fee' => (int) $l['logistics_fee'],
                ])
                ->values()
                ->all(),
        ];
    }
}

<?php

namespace App\Support;

use App\Models\Service;

class BookingPricing
{
    /**
     * Compute the line-item breakdown for a booking selection, server-side.
     *
     * @param  array{service_slug:string, guests:int, addons?:array<string>, location?:?string}  $input
     * @return array{
     *     base_per_guest: int,
     *     guests: int,
     *     base_subtotal: int,
     *     addon_lines: array<int, array{key:string,label:string,price:int}>,
     *     addons_subtotal: int,
     *     location_label: ?string,
     *     location_fee: int,
     *     total: int,
     *     deposit: int,
     * }
     */
    public static function compute(array $input): array
    {
        $config = config('pricing');
        $slug = $input['service_slug'] ?? null;
        $guests = max(1, (int) ($input['guests'] ?? 1));
        $selectedAddons = array_values(array_unique($input['addons'] ?? []));
        $location = $input['location'] ?? null;

        $service = $slug ? Service::query()->where('slug', $slug)->first() : null;

        $rate = $config['service_base_rates'][$slug] ?? null;
        $basePerGuest = $rate['base_per_guest']
            ?? ($service ? (int) round((float) $service->base_price) : 0);

        if ($rate) {
            $guests = max($rate['minimum_guests'] ?? 1, $guests);
        }

        $baseSubtotal = $basePerGuest * $guests;

        $addonLines = [];
        $addonsSubtotal = 0;
        foreach ($selectedAddons as $key) {
            if (isset($config['addons'][$key])) {
                $addon = $config['addons'][$key];
                $addonLines[] = [
                    'key' => $key,
                    'label' => $addon['label'],
                    'price' => (int) $addon['price'],
                ];
                $addonsSubtotal += (int) $addon['price'];
            }
        }

        $locationKey = $location && isset($config['location_fees'][$location]) ? $location : null;
        $locationFee = $locationKey ? (int) $config['location_fees'][$locationKey]['logistics_fee'] : 0;
        $locationLabel = $locationKey ? $config['location_fees'][$locationKey]['label'] : null;

        $total = $baseSubtotal + $addonsSubtotal + $locationFee;

        $depositPct = (int) $config['deposit_percentage'];
        $deposit = (int) round($total * ($depositPct / 100));

        return [
            'base_per_guest' => $basePerGuest,
            'guests' => $guests,
            'base_subtotal' => $baseSubtotal,
            'addon_lines' => $addonLines,
            'addons_subtotal' => $addonsSubtotal,
            'location_label' => $locationLabel,
            'location_fee' => $locationFee,
            'total' => $total,
            'deposit' => $deposit,
        ];
    }
}

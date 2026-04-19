<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Deposit Percentage
    |--------------------------------------------------------------------------
    |
    | The percentage of the estimated total that must be paid up-front to
    | confirm a booking. Applied server-side when creating a booking.
    */
    'deposit_percentage' => (int) env('DEPOSIT_PERCENTAGE', 30),

    /*
    |--------------------------------------------------------------------------
    | Service Base Rates
    |--------------------------------------------------------------------------
    |
    | Per-guest price used by the pricing calculator and server-side booking
    | total. Keyed by the service slug. The total is calculated as:
    |   guests * base_per_guest + addons + location_fee
    */
    'service_base_rates' => [
        'private-chef-dinner' => [
            'base_per_guest' => 25000,
            'minimum_guests' => 2,
        ],
        'weekly-meal-prep' => [
            'base_per_guest' => 17000,
            'minimum_guests' => 1,
        ],
        'small-chops-catering' => [
            'base_per_guest' => 2500,
            'minimum_guests' => 10,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Add-ons
    |--------------------------------------------------------------------------
    |
    | Flat-fee add-ons available on the pricing calculator and booking form.
    | Keys are stable identifiers used when validating and storing selections.
    */
    'addons' => [
        'dessert_course' => [
            'label' => 'Extra dessert course',
            'description' => 'An additional plated dessert course at service.',
            'price' => 5000,
        ],
        'dietary_customization' => [
            'label' => 'Dietary customization',
            'description' => 'Full menu rework for allergies or restrictions.',
            'price' => 3000,
        ],
        'service_staff' => [
            'label' => 'Additional service staff',
            'description' => 'One extra server for the duration of service.',
            'price' => 10000,
        ],
        'premium_drinks' => [
            'label' => 'Premium drinks pairing',
            'description' => 'Curated wine or cocktail pairing per course.',
            'price' => 15000,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Location Fees
    |--------------------------------------------------------------------------
    |
    | Logistics fee applied by service area. Keys are stable identifiers used
    | when validating; "label" is shown in the UI.
    */
    'location_fees' => [
        'gra' => ['label' => 'GRA', 'logistics_fee' => 0],
        'old_gra' => ['label' => 'Old GRA', 'logistics_fee' => 0],
        'trans_amadi' => ['label' => 'Trans Amadi', 'logistics_fee' => 2000],
        'eliozu' => ['label' => 'Eliozu', 'logistics_fee' => 3000],
        'rumuokoro' => ['label' => 'Rumuokoro', 'logistics_fee' => 3000],
        'woji' => ['label' => 'Woji', 'logistics_fee' => 2500],
        'outside_ph' => ['label' => 'Outside Port Harcourt', 'logistics_fee' => 5000],
    ],
];

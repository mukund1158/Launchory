<?php

return [
    'access_token' => env('POLAR_ACCESS_TOKEN'),
    'webhook_secret' => env('POLAR_WEBHOOK_SECRET'),
    'sandbox' => env('POLAR_SANDBOX', false),

    /*
    |--------------------------------------------------------------------------
    | Polar Organization ID (optional; OAT may imply it)
    |--------------------------------------------------------------------------
    */
    'organization_id' => env('POLAR_ORGANIZATION_ID'),

    /*
    |--------------------------------------------------------------------------
    | Presentment currency — must match your Polar org's default currency
    |--------------------------------------------------------------------------
    | Set in Polar: Organization settings > Default presentment currency.
    | Options: usd, eur, gbp, aud, cad, chf, jpy, sek, brl, inr
    */
    'currency' => strtolower(env('POLAR_CURRENCY', 'usd')),

    /*
    |--------------------------------------------------------------------------
    | Plan definitions — used when auto-creating products in Polar
    |--------------------------------------------------------------------------
    | price_cents, name, recurring_interval (null = one-time, 'month' = monthly).
    */
    'plan_definitions' => [
        'launch_featured' => [
            'name' => 'Featured Launch',
            'price_cents' => 1900,
            'recurring_interval' => null,
        ],
        'directory_standard' => [
            'name' => 'Directory Listing',
            'price_cents' => 900,
            'recurring_interval' => 'month',
        ],
        'directory_featured' => [
            'name' => 'Featured Directory',
            'price_cents' => 1900,
            'recurring_interval' => 'month',
        ],
        'bundle_standard' => [
            'name' => 'Launch + Directory',
            'price_cents' => 900,
            'recurring_interval' => 'month',
        ],
        'bundle_featured' => [
            'name' => 'Featured Bundle',
            'price_cents' => 3900,
            'recurring_interval' => 'month',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Polar Product IDs (optional — if set, used instead of auto-created)
    |--------------------------------------------------------------------------
    */
    'products' => [
        'launch_featured' => env('POLAR_PRODUCT_LAUNCH_FEATURED'),
        'directory_standard' => env('POLAR_PRODUCT_DIRECTORY_STANDARD'),
        'directory_featured' => env('POLAR_PRODUCT_DIRECTORY_FEATURED'),
        'bundle_standard' => env('POLAR_PRODUCT_BUNDLE_STANDARD'),
        'bundle_featured' => env('POLAR_PRODUCT_BUNDLE_FEATURED'),
    ],
];

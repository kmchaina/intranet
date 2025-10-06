<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Role-Based Theme Colors
    |--------------------------------------------------------------------------
    |
    | Define unique color schemes for each user role to provide visual
    | distinction and improve user experience across different dashboards.
    |
    */

    'roles' => [
        'staff' => [
            'name' => 'Innovation',
            'primary' => 'indigo',
            'accent' => 'purple',
            'primary_600' => '#4f46e5',
            'accent_600' => '#9333ea',
            'primary_from' => '#eef2ff',
        ],
        'station_admin' => [
            'name' => 'Precision',
            'primary' => 'cyan',
            'accent' => 'blue',
            'primary_600' => '#0891b2',
            'accent_600' => '#3b82f6',
            'primary_from' => '#ecfeff',
        ],
        'centre_admin' => [
            'name' => 'Insight',
            'primary' => 'violet',
            'accent' => 'fuchsia',
            'primary_600' => '#8b5cf6',
            'accent_600' => '#d946ef',
            'primary_from' => '#f5f3ff',
        ],
        'hq_admin' => [
            'name' => 'Governance',
            'primary' => 'slate',
            'accent' => 'gray',
            'primary_600' => '#475569',
            'accent_600' => '#374151',
            'primary_from' => '#f8fafc',
        ],
        'super_admin' => [
            'name' => 'Dominion',
            'primary' => 'amber',
            'accent' => 'yellow',
            'primary_600' => '#f59e0b',
            'accent_600' => '#eab308',
            'primary_from' => '#fffbeb',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Theme
    |--------------------------------------------------------------------------
    */

    'default' => 'staff',
];

<?php

use Illuminate\Support\Str;

return [
    'default' => env('GEODATA_PROVIDER', 'nominatim'),

    'providers' => [

        'nominatim' => [
            'provider' => env('GEODATA_PROVIDER', 'nominatim'), // Провайдер по умолчанию
            'base_url' => env('GEODATA_BASE_URL', 'https://nominatim.openstreetmap.org'),
            'user_agent' => env('GEODATA_USER_AGENT', 'BackgroundJobTestTask/1.0'),
            'default_params' => [
                'polygon_geojson' => 1,
                'format' => 'json',
            ],
        ],

    ],
];

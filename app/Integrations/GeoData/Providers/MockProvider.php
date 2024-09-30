<?php

namespace App\Integrations\GeoData\Providers;

use App\Integrations\GeoData\GeoDataProviderContract;

class MockProvider implements GeoDataProviderContract
{
    public function __construct(
        private readonly string $baseUrl,
        private readonly string $userAgent,
        private array $defaultParams,
    ) {
        //
    }
    public function fetchPolygons(string $country, ?string $state = null, array $params = []): array
    {
        return [
            [30.5, 50.4],
            [30.6, 50.5],
            [30.7, 50.6]
        ];
    }
}

<?php

namespace App\Integrations\GeoData;

use InvalidArgumentException;

use App\Integrations\GeoData\Providers\{
    MockProvider,
    NominatimProvider,
};

class GeoDataService
{
    protected GeoDataProviderContract $provider;

    public function __construct(GeoDataFactory $factory)
    {
        $this->provider = $factory->make(config('geo_data.default'));
    }

    public function fetchPolygons(
        string $country = 'Ukraine',
        ?string $state = null,
        array $params = []
    ): array {
        return $this->provider->fetchPolygons($country, $state, $params);
    }
}

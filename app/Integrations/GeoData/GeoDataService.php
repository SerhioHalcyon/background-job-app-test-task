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

    public function __construct()
    {
        $defaultProvider = config('geo_data.default');
        $this->loadProvider($defaultProvider);
    }

    private function loadProvider(string $providerKey): void
    {
        $providersConfig = config('geo_data.providers');

        if (!isset($providersConfig[$providerKey])) {
            throw new InvalidArgumentException("Provider {$providerKey} not configured.");
        }

        $providerConfig = $providersConfig[$providerKey];

        switch ($providerKey) {
            case 'nominatim':
                $this->provider = new NominatimProvider(
                    $providerConfig['base_url'],
                    $providerConfig['user_agent'],
                    $providerConfig['default_params']
                );
                break;

            case 'mock':
                $this->provider = new MockProvider(
                    $providerConfig['base_url'],
                    $providerConfig['user_agent'],
                    $providerConfig['default_params']
                );
                break;

            default:
                throw new InvalidArgumentException("Provider {$providerKey} not recognized.");
        }
    }

    public function switchProvider(string $providerKey): void
    {
        $this->loadProvider($providerKey);
    }

    public function fetchPolygons(
        string $country = 'Ukraine',
        ?string $state = null,
        array $params = []
    ): array {
        return $this->provider->fetchPolygons($country, $state, $params);
    }
}

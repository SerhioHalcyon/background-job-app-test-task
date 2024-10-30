<?php

namespace App\Integrations\GeoData;

use App\Integrations\GeoData\Providers\MockProvider;
use App\Integrations\GeoData\Providers\NominatimProvider;
use InvalidArgumentException;

class GeoDataFactory
{
    protected array $providers;

    public function __construct(array $providers)
    {
        $this->providers = $providers;
    }
    public function make(string $provider): GeoDataProviderContract
    {
        if (! isset($this->providers[$provider])) {
            throw new InvalidArgumentException("Provider {$provider} not configured.");
        }

        return app($this->providers[$provider]);
    }

    public function switchProvider(string $providerKey): void
    {
        $this->loadProvider($providerKey);
    }
}

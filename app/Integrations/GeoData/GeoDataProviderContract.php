<?php

namespace App\Integrations\GeoData;

interface GeoDataProviderContract
{
    public function fetchPolygons(string $country, ?string $state, array $params): array;
}

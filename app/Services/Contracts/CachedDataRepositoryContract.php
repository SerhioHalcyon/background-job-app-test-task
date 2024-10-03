<?php

namespace App\Services\Contracts;

use App\Services\DTO\GeoPoint;

interface CachedDataRepositoryContract
{
    public function searchStateByPoint(GeoPoint $geoPoint): array;
    public function deleteStateData(): bool;
}

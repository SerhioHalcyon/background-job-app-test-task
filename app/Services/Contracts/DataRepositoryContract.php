<?php

namespace App\Services\Contracts;

use App\Services\DTO\GeoPoint;

interface DataRepositoryContract
{
    public function getStates();
    public function searchStateByPoint(GeoPoint $geoPoint): array;
    public function updateStates(array $states): void;
    public function deleteStateData(): bool;
}

<?php

namespace App\Services\Contracts;

interface DataRepositoryContract
{
    public function getStates();
    public function searchStateByPoint(string $wktGeoPoint): array;
    public function updateStates(array $states): void;
    public function deleteStateData(): bool;
}

<?php

namespace App\Repositories;

use App\Models\State;
use App\Services\Contracts\DataRepositoryContract;
use App\Services\DTO\GeoPoint;

class DataRepository implements DataRepositoryContract
{
    public function getStates()
    {
        return State::all();
    }

    public function searchStateByPoint(GeoPoint $geoPoint): array
    {
        return State::whereRaw(
            sprintf(
                "ST_Contains(coordinates, ST_GeomFromText('%s'))",
                $geoPoint->toWKT(),
            ))
            ->firstOrFail()
            ->toArray();
    }

    public function updateStates(array $states): void
    {
        State::upsert($states, ['name'], ['coordinates']);
    }

    public function deleteStateData(): bool
    {
        return State::query()->update(['coordinates' => null]);
    }
}

<?php

namespace App\Repositories;

use App\Models\State;
use App\Services\Contracts\DataRepositoryContract;

class DataRepository implements DataRepositoryContract
{
    public function getStates()
    {
        return State::all();
    }

    public function searchStateByPoint(string $wktGeoPoint): array
    {
        return State::whereRaw(
            sprintf(
                "ST_Contains(coordinates, ST_GeomFromText('%s'))",
                $wktGeoPoint,
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
        return State::query()->update(['coordinates' => json_encode([])]);
    }
}

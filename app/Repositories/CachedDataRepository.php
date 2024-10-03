<?php

namespace App\Repositories;

use App\Enum\CacheStatus;
use App\Models\State;
use App\Services\Contracts\CachedDataRepositoryContract;
use App\Services\Contracts\DataRepositoryContract;
use App\Services\DTO\GeoPoint;
use Illuminate\Support\Facades\Cache;

class CachedDataRepository implements CachedDataRepositoryContract
{
    public function __construct(private DataRepositoryContract $dataRepository)
    {
        //
    }

    public function searchStateByPoint(GeoPoint $geoPoint): array
    {

        $cacheKey = "geo_data_{$geoPoint->getLongitude()}_{$geoPoint->getLatitude()}";

        if (Cache::has($cacheKey)) {
            $cachedData = Cache::get($cacheKey);

            return [
                'data' => [
                    'geo' => [
                        'oblast' => $cachedData,
                    ],
                ],
                'cache' => CacheStatus::HIT->value,
            ];
        }

        if (
            $state = State::whereRaw(
                sprintf(
                    "ST_Contains(coordinates, ST_GeomFromText('%s'))",
                    $geoPoint->toWKT(),
                ))->first()
        ) {
            Cache::put($cacheKey, $state->name, 3600);

            return [
                'data' => [
                    'geo' => [
                        'oblast' => $state->name,
                    ],
                ],
                'cache' => CacheStatus::MISS->value,
            ];
        }

        return [
            'data' => [
                'geo' => [
                    'oblast' => null,
                ],
            ],
            'cache' => null,
        ];
    }

    public function __call($method, $parameters)
    {
        return $this->dataRepository->$method(...$parameters);
    }
}

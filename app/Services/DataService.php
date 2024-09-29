<?php

namespace App\Services;

use App\Services\Contracts\DataRepositoryContract;
use App\Services\Contracts\DataServiceContract;
use App\Services\DTO\GeoPoint;
use App\Services\DTO\State;
use Illuminate\Support\Facades\DB;

class DataService implements DataServiceContract
{

    public function __construct(private DataRepositoryContract $repository)
    {
        //
    }

    public function getData()
    {
        return $this->repository->getStates();
    }

    public function searchData(GeoPoint $geoPoint): array
    {
        $state = $this->repository->searchStateByPoint($geoPoint->toWKT());

        return $state;
    }

    public function storeData(array $states): bool
    {
        $dataToStore = [];

        foreach ($states as $state) {
            if (! $state instanceof State) {
                continue;
            }

            $data = $state->toArray();
            $data['coordinates'] = $this->processCoordinatesToWKT($data['coordinates']);
            $dataToStore[] = $data;
        }

        $this->repository->updateStates($dataToStore);

        return true;
    }

    public function storeSingleData(State $state): bool
    {
        $state = $state->toArray();
        $state['coordinates'] = $this->processCoordinatesToWKT($state['coordinates']);

        $this->repository->updateStates([$state]);

        return true;
    }

    public function deleteData(): bool
    {
        return $this->repository->deleteStateData();
    }

    private function processCoordinatesToWKT(array $coordinates)
    {
        $polygonWKT = "POLYGON((" . implode(", ", array_map(fn($coord) => "{$coord[0]} {$coord[1]}", $coordinates)) . "))";

        return DB::raw("ST_GeomFromText('$polygonWKT')");
    }
}

<?php

namespace App\Services;

use App\Services\Contracts\DataRepositoryContract;
use App\Services\Contracts\DataServiceContract;

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

    public function storeData($states): bool
    {
        $states = $states->map(function ($state) {
            return [
                'name' => $state->name,
                'coordinates' => json_encode($state->coordinates),
            ];
        });

        $this->repository->updateStates($states->toArray());

        return true;
    }

    public function deleteData(): bool
    {
        return $this->repository->deleteStateData();
    }
}

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

    public function updateStates(array $states): void
    {
        State::upsert($states, ['name'], ['coordinates']);
    }
}

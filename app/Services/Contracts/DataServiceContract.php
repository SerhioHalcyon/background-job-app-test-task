<?php

namespace App\Services\Contracts;

use App\Services\DTO\State;

interface DataServiceContract
{
    public function getData();
    public function storeData(array $states): bool;
    public function storeSingleData(State $state): bool;
    public function deleteData(): bool;
}

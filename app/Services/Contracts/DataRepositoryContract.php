<?php

namespace App\Services\Contracts;

interface DataRepositoryContract
{
    public function getStates();
    public function updateStates(array $states): void;
}

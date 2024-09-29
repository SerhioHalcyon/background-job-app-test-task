<?php

namespace App\Services\Contracts;

interface DataServiceContract
{
    public function getData();
    public function storeData($states): bool;
}

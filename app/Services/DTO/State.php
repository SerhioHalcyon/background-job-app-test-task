<?php

namespace App\Services\DTO;

class State
{
    public function __construct(private string $name, private array $coordinates)
    {
        //
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'coordinates' => $this->coordinates,
        ];
    }
}

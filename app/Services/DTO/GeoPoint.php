<?php

namespace App\Services\DTO;

use PhpParser\Node\Expr\Cast\Double;

class GeoPoint
{
    public function __construct(private float $longitude, private float $latitude)
    {
        //
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function toWKT(): string
    {
        return sprintf('POINT(%s %s)', $this->longitude, $this->latitude);
    }
}

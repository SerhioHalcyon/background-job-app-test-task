<?php

namespace App\Integrations\GeoData;

enum PolygonType: string
{
    case POLYGON = 'Polygon';
    case MULTIPOLYGON = 'MultiPolygon';
}

<?php

namespace App\Enum;

enum CacheStatus: string
{
    case HIT = 'hit';
    case MISS = 'miss';
}

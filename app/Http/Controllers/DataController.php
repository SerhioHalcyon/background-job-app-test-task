<?php

namespace App\Http\Controllers;

use App\Enum\CacheStatus;
use App\Jobs\RefreshDataJob;
use App\Services\DataService;
use App\Services\DTO\GeoPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DataController extends Controller
{
    public function __construct(private DataService $dataService)
    {
        //
    }

    public function refresh(Request $request)
    {
        $delaySeconds = (int) $request->query('delaySeconds', 0);

        RefreshDataJob::dispatchSync($delaySeconds);
//        RefreshDataJob::dispatch($delaySeconds)->delay(now()->addSeconds($delaySeconds));

        return response()->json([
            'data' => ['success' => true]
        ]);
    }

    public function search(Request $request)
    {
        $lon = $request->query('lon');
        $lat = $request->query('lat');

        $getPoint = new GeoPoint($lon, $lat);
        $data = $this->dataService->searchData($getPoint);

        return response()->json($data);
        dd('This must be the end');
        $cacheKey = "geo_data_{$lon}_{$lat}";

        if (Cache::has($cacheKey)) {
            $cachedData = Cache::get($cacheKey);

            return response()->json([
                'data' => [
                    'geo' => [
                        'oblast' => $cachedData,
                    ],
                ],
                'cache' => CacheStatus::HIT->value,
            ]);
        }

        if ($state = $this->dataService->searchData($getPoint)) {
            Cache::put($cacheKey, $state['name'], 3600);

            return response()->json([
                'data' => [
                    'geo' => [
                        'oblast' => $state['name'],
                    ],
                ],
                'cache' => CacheStatus::MISS->value,
            ]);
        }

        return response()->json(['data' => null], 404);
    }

    public function delete()
    {
        Cache::flush();

        $this->dataService->deleteData();

        return response()->json([
            'data' => ['status' => 'success']
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Jobs\RefreshDataJob;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DataController extends Controller
{
    public function refresh(Request $request)
    {
        $delaySeconds = $request->query('delaySeconds', 0);

        RefreshDataJob::dispatchSync();
//        RefreshDataJob::dispatch()->delay(now()->addSeconds($delaySeconds));

        return response()->json([
            'data' => ['success' => true]
        ]);
    }

    public function search(Request $request)
    {
        $lat = $request->query('lat');
        $lon = $request->query('lon');

        if (!$lat || !$lon) {
            return response()->json(['error' => 'Invalid coordinates'], 400);
        }

        $cacheKey = "geo_data_{$lat}_{$lon}";

        if (Cache::has($cacheKey)) {
            $cachedData = Cache::get($cacheKey);
            return response()->json([
                'data' => $cachedData,
                'cache' => 'hit'
            ]);
        }

        $geoData = GeoData::where('latitude', $lat)
            ->where('longitude', $lon)
            ->first();

        if ($geoData) {
            Cache::put($cacheKey, $geoData, 3600);
            return response()->json([
                'data' => $geoData,
                'cache' => 'miss'
            ]);
        }

        return response()->json(['data' => null], 404);
    }

    public function delete()
    {
        Cache::flush();

        State::query()->update(['coordinates' => json_encode([])]);

        return response()->json([
            'data' => ['status' => 'success']
        ]);
    }
}

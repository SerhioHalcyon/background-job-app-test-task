<?php

namespace App\Integrations\GeoData;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeoData
{
    private string $baseUrl = 'https://nominatim.openstreetmap.org';

    private string $userAgent = 'BackgroundJobTestTask/1.0';

    private array $defaultParams = [
        'polygon_geojson' => 1,
        'format' => 'json'
    ];

    public function __construct()
    {
        //
    }

    public function fetchPolygons(
        string $country = 'Ukraine',
        ?string $state = null,
        array $params = []
    ): array {

        $url = $this->baseUrl . '/' . 'search';
        $params = array_merge(
            $this->defaultParams,
            [
                'country' => $country,
                'state' => $state,
            ],
            $params,
        );

        try {
            $response = Http::withHeaders([
                'User-Agent' => $this->userAgent,
            ])->get($url, $params);

            if ($response->successful()) {
                $data = $response->json();

                if (!empty($data) && isset($data[0]['geojson']['coordinates'][0])) {

                    switch ($data[0]['geojson']['type']) {
                        case PolygonType::POLYGON->value:
                            return $data[0]['geojson']['coordinates'][0];

                        case PolygonType::MULTIPOLYGON->value:
                            return $data[0]['geojson']['coordinates'][0][0];                    }
                }
            } else {
                Log::error(
                    'Error when requesting to Nominatim API',
                    ['status' => $response->status(), 'body' => $response->json()],
                );
            }
        } catch (Exception $e) {
            Log::error(
                'The request to Nominatim failed',
                ['message' => $e->getMessage()],
            );
        }

        return [];
    }
}

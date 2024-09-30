<?php

namespace App\Integrations\GeoData\Providers;

use App\Integrations\GeoData\GeoDataProviderContract;
use App\Integrations\GeoData\PolygonType;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NominatimProvider implements GeoDataProviderContract
{

    public function __construct(
        private readonly string $baseUrl,
        private readonly string $userAgent,
        private array $defaultParams,
    ) {
        //
    }

    public static function requestFailed(string $message): void
    {
        Log::error(
            'The request to Nominatim failed',
            ['message' => $message],
        );
    }

    public static function responseError(int $responseStatus, mixed $responseBody): void
    {
        Log::error(
            'Error when requesting to Nominatim API',
            ['status' => $responseStatus, 'body' => $responseBody],
        );
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

                if (empty($data) && ! isset($data[0]['geojson']['coordinates'][0])) {
                    self::responseError($response->status(), $response->json());

                    return [];
                }

                switch ($data[0]['geojson']['type']) {
                    case PolygonType::POLYGON->value:
                        return $data[0]['geojson']['coordinates'][0];

                    case PolygonType::MULTIPOLYGON->value:
                        return $data[0]['geojson']['coordinates'][0][0];
                }
            } else {
                self::responseError($response->status(), $response->json());
            }
        } catch (Exception $e) {
            self::requestFailed($e->getMessage());
        }

        return [];
    }
}

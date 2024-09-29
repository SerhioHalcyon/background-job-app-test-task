<?php

namespace App\Jobs;

use App\Integrations\GeoData\GeoData;
use App\Services\Contracts\DataServiceContract;
use App\Services\DataService;
use App\Services\DTO\State;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\App;

class RefreshDataJob implements ShouldQueue
{
    use Queueable;

    private GeoData $geoData;

    private DataServiceContract $dataService;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        // The memory increase is necessary if we save data in large chunks.
        ini_set('memory_limit', '512M');

        $this->geoData = App::make(GeoData::class);
        $this->dataService = App::make(DataService::class);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $states = $this->dataService->getData();
//        $polygons = [];

        foreach ($states as $state) {
//            $polygons[] = [
//                'state' => $state->name,
//                'coordinates' => $this->geoData->fetchPolygons(state: $state->name),
//            ];

            if ($coordinates = $this->geoData->fetchPolygons(state: $state->name)) {
//                $polygons[] = new State(
//                    $state->name,
//                    $coordinates,
//                );
                $polygon = new State(
                    $state->name,
                    $coordinates,
                );

                $this->dataService->storeSingleData($polygon);
            }


            sleep(1);

        }

//        $this->dataService->storeData($polygons);
    }
}

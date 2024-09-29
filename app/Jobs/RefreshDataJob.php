<?php

namespace App\Jobs;

use App\Integrations\GeoData\GeoData;
use App\Services\Contracts\DataServiceContract;
use App\Services\DataService;
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
        $this->geoData = App::make(GeoData::class);
        $this->dataService = App::make(DataService::class);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $states = $this->dataService->getData();

        foreach ($states as &$state) {
            $state->coordinates = $this->geoData->fetchPolygons(state: $state->name);
            sleep(1);
        }

        $this->dataService->storeData($states);
    }
}

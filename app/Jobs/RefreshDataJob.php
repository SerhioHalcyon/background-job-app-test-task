<?php

namespace App\Jobs;

use App\Events\JobCompleted;
use App\Events\JobFailed;
use App\Integrations\GeoData\GeoDataService;
use App\Services\Contracts\DataServiceContract;
use App\Services\DTO\State;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\App;
use Throwable;

class RefreshDataJob implements ShouldQueue
{
    use Queueable;

    public int $jobDelay;
    private GeoDataService $geoData;
    private DataServiceContract $dataService;

    /**
     * Create a new job instance.
     */
    public function __construct(int $jobDelay)
    {
        // The memory increase is necessary if we save data in large chunks.
        ini_set('memory_limit', '512M');

        $this->jobDelay = $jobDelay;
        $this->geoData = App::make(GeoDataService::class);
        $this->dataService = App::make(DataServiceContract::class);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
//        $this->fail('Something went wrong.');
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

        event(new JobCompleted($this->jobDelay));
    }

    /**
     * Handle a job failure.
     */
    public function failed(?Throwable $exception): void
    {
        event(new JobFailed($this->jobDelay));
    }
}

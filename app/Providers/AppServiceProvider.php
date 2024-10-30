<?php

namespace App\Providers;

use App\Events\JobCompleted as CustomJobCompleted;
use App\Events\JobFailed as CustomJobFailed;
use App\Integrations\GeoData\GeoDataFactory;
use App\Integrations\GeoData\GeoDataProviderContract;
use App\Integrations\GeoData\GeoDataService;
use App\Integrations\GeoData\Providers\MockProvider;
use App\Integrations\GeoData\Providers\NominatimProvider;
use App\Listeners\JobEventSubscriber;
use App\Repositories\CachedDataRepository;
use App\Repositories\DataRepository;
use App\Services\Contracts\CachedDataRepositoryContract;
use App\Services\Contracts\DataRepositoryContract;
use App\Services\Contracts\DataServiceContract;
use App\Services\DataService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Integrations
        $this->app->bind(GeoDataService::class, function (Application $app) {
            return new GeoDataService($app->make(GeoDataFactory::class));
        });

        $this->app->singleton(GeoDataFactory::class, function (Application $app) {
            return new GeoDataFactory([
                'nominatim' => NominatimProvider::class,
                'mock' => MockProvider::class,
            ]);
        });

        $this->app->bind(NominatimProvider::class, function (Application $app) {
            return new NominatimProvider(
                config('geo_data.providers.nominatim.base_url'),
                config('geo_data.providers.nominatim.user_agent'),
                config('geo_data.providers.nominatim.default_params'),
            );
        });


        // Services
        $this->app->bind(DataServiceContract::class, function (Application $app) {
            return $app->make(DataService::class);
        });

        // Repositories
        $this->app->bind(CachedDataRepositoryContract::class, function (Application $app) {
            return $app->make(CachedDataRepository::class);
        });
        $this->app->bind(DataRepositoryContract::class, function (Application $app) {
            return $app->make(DataRepository::class);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
//        Queue::after(function (JobProcessed $event) {
//            event(new CustomJobCompleted($event->job));
//            // $event->connectionName
//            // $event->job
//            // $event->job->payload()
//        });

//        Queue::failing(function (JobFailed $event) {
//            event(new CustomJobFailed($event->job));
//            // $event->connectionName
//            // $event->job
//            // $event->exception
//        });
    }
}

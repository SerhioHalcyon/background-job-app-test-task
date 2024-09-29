<?php

namespace App\Providers;

use App\Repositories\DataRepository;
use App\Services\Contracts\DataRepositoryContract;
use App\Services\Contracts\DataServiceContract;
use App\Services\DataService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Services
        $this->app->bind(DataServiceContract::class, function (Application $app) {
            return $app->make(DataService::class);
        });

        // Repositories
        $this->app->bind(DataRepositoryContract::class, function (Application $app) {
            return $app->make(DataRepository::class);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Application_Layer\Repository_Implementation\UserFinderRepositoryImplementation;
use App\Contracts\AuthServiceInterface;
use App\Contracts\UserFinderRepositoryInterface;
use App\Application_Layer\Services_Implementation\AuthServiceImplementation;
use App\Contracts\WarehouseStorageServiceInterface;
use App\Contracts\WarehouseStorageRepositoryInterface;
use App\Application_Layer\Services_Implementation\WarehouseStorageServiceImplementation;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind(
            AuthServiceInterface::class,
            AuthServiceImplementation::class
        );
        $this->app->bind(
            UserFinderRepositoryInterface::class,
            UserFinderRepositoryImplementation::class
        );

        $this->app->bind(
            WarehouseStorageServiceInterface::class,
            WarehouseStorageServiceImplementation::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

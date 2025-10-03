<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Application_Layer\Repository_Implementation\UserFinderRepositoryImplementation;
use App\Contracts\AuthServiceInterface;
use App\Contracts\UserFinderRepositoryInterface;
use App\Application_Layer\Services_Implementation\AuthServiceImplementation;


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
        $this->app->bind(AuthServiceInterface::class, AuthServiceImplementation::class);
        $this->app->bind(UserFinderRepositoryInterface::class, UserFinderRepositoryImplementation::class);
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

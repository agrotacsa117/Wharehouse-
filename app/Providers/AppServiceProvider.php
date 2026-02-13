<?php

namespace App\Providers;

use App\Application_Layer\Repository_Implementation\LocationRepositoryImplementation;
use Illuminate\Support\ServiceProvider;
use App\Application_Layer\Repository_Implementation\UserFinderRepositoryImplementation;
use App\Contracts\AuthServiceInterface;
use App\Contracts\UserFinderRepositoryInterface;
use App\Application_Layer\Services_Implementation\AuthServiceImplementation;
use App\Contracts\WarehouseStorageServiceInterface;
use App\Contracts\WarehouseStorageRepositoryInterface;
use App\Application_Layer\Services_Implementation\WarehouseStorageServiceImplementation;
use App\Contracts\LocationServiceInterface;
use App\Application_Layer\Services_Implementation\LocationServiceImplementation;
use App\Contracts\EntityToModelMapperInterface;
use App\Contracts\InterfaceEntityToDTOMapper;
use App\Contracts\InterfaceMapperToEntity;
use App\Contracts\LocationRepositoryInterface;
use App\Contracts\ModelMapperToEntityInterface;
use App\Enterprise_Layer\Location;
use App\Mappers\LocationRequestDTOToLocationEntity;
use App\Mappers\LocationEntityToLocationDetailDTO;
use App\Mappers\LocationEntityToLocationModel;
use App\Mappers\LocationModelToLocationEntityMapper;
use Dom\Entity;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            LocationServiceInterface::class,
            LocationServiceImplementation::class
        );
        //

        $this->app->bind(
            LocationRepositoryInterface::class,
            LocationRepositoryImplementation::class
        );

        $this->app->bind(
            ModelMapperToEntityInterface::class,
            LocationModelToLocationEntityMapper::class
        );

        $this->app->bind(
            EntityToModelMapperInterface::class,
            LocationEntityToLocationModel::class
        );

        $this->app->bind(
            InterfaceMapperToEntity::class,
            LocationRequestDTOToLocationEntity::class
        );

        $this->app->bind(
            InterfaceEntityToDTOMapper::class,
            LocationEntityToLocationDetailDTO::class
        );

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

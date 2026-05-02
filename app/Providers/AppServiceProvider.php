<?php

namespace App\Providers;

use App\Application_Layer\Repository_Implementation\LocationRepositoryImplementation;
use App\Application_Layer\Repository_Implementation\ProductRepositoryImplementation;
use Illuminate\Support\ServiceProvider;
use App\Application_Layer\Repository_Implementation\UserFinderRepositoryImplementation;
use App\Application_Layer\Repository_Implementation\WarehouseStorageRepositoryImplementation;
use App\Application_Layer\Repository_Implementation\WarehouseTypeRepositoryImplementation;
use App\Contracts\AuthServiceInterface;
use App\Contracts\UserFinderRepositoryInterface;
use App\Application_Layer\Services_Implementation\AuthServiceImplementation;
use App\Contracts\WarehouseStorageServiceInterface;
use App\Contracts\WarehouseStorageRepositoryInterface;
use App\Application_Layer\Services_Implementation\WarehouseStorageServiceImplementation;
use App\Contracts\LocationServiceInterface;
use App\Application_Layer\Services_Implementation\LocationServiceImplementation;
use App\Application_Layer\Services_Implementation\ProductServiceImplementation;
use App\Application_Layer\Services_Implementation\WarehouseInventoryServiceImplementation;
use App\Application_Layer\Services_Implementation\WarehouseTypeServiceImplementation;
use App\Contracts\EntityToModelMapperInterface;
use App\Contracts\InterfaceEntityToDTOMapper;
use App\Contracts\InterfaceMapperToEntity;
use App\Contracts\LocationEntityToLocationDetailDTOMapperI;
use App\Contracts\LocationEntityToLocationModelMapperI;
use App\Contracts\LocationModelToLocationEntityMapperI;
use App\Contracts\LocationRepositoryInterface;
use App\Contracts\LocationRequestDTOToLocationEntityMapperI;
use App\Contracts\ModelMapperToEntityInterface;
use App\Contracts\ProductRepositoryInterface;
use App\Contracts\ProductServiceInterface;
use App\Contracts\WarehouseTypeRepositoryInterface;
use App\Contracts\WarehouseTypeServiceInterface;
use App\Enterprise_Layer\Location;
use App\Enterprise_Layer\WarehouseType;
use App\Mappers\LocationRequestDTOToLocationEntity;
use App\Mappers\LocationEntityToLocationDetailDTO;
use App\Mappers\LocationEntityToLocationModel;
use App\Mappers\LocationModelToLocationEntityMapper;
use App\Mappers\WarehouseDTOToEntityMapper;
use App\Mappers\WarehouseToWarehouseModelMapper;
use App\Mappers\WarehouseTypeEntityToWarehouseTypeDetailDTO;
use App\Mappers\WarehouseTypeEntityToWarehouseTypeModel;
use Dom\Entity;
use App\Mappers\WarehouseTypeModelToWarehouseTypeEntityMapper;
use App\Mappers\WarehouseTypeRequestDTOToWarehouseTypeEntity;
use App\Contracts\WarehouseDTOToEntityMapperInterface;
use App\Contracts\WarehouseEntityToWarehouseModelMapperI;
use App\Contracts\WarehouseInventoryEntityToWarehouseInventoryModelMapperI;
use App\Application_Layer\Repository_Implementation\WarehouseInventoryRepositoryImplementation;
use App\Contracts\WarehouseInventoryRepositoryInterface;
use App\Contracts\WarehouseInventoryServiceInterface;
use App\Contracts\WarehouseTypeModelToWarehouseTypeEntityMapperI;
use App\Contracts\WarehouseTypeEntityToWarehouseTypeModelMapperI;
use App\Contracts\WarehouseTypeEntityToWarehouseTypeDetailDTOMapperI;
use App\Contracts\WarehouseTypeRequestDTOToWarehouseTypeEntityMapperI;
use App\Enterprise_Layer\WarehouseInventory;
use App\Mappers\WarehouseInventoryEntityToWarehouseInventoryModelMapper;
use App\Mappers\WarehouseInventoryRequestDTOToWarehouseInventoryMapper;
use App\Contracts\WarehouseInventoryRequestDTOToWarehouseInventoryMapperI;
use App\Application_Layer\Services_Implementation\WarehouseMovementsService;
use App\Contracts\WarehouseMovementsServiceI;
use App\Contracts\WarehouseMovementsRepositoryI;
use App\Application_Layer\Repository_Implementation\WarehouseMovementsRepository;
use App\Contracts\WarehouseInventoryMovementsEntityToModelMapperI;
use App\Mappers\WarehouseInventoryMovementsEntityToModelMapper;
use App\Contracts\WarehouseInventoryMovementsMapperI;
use App\Mappers\WarehouseInventoryMovementsMapper;
use App\Contracts\WarehouseInventoryMovementModelMapperI;
use App\Mappers\WarehouseInventoryMovementModelMapper;
use App\Contracts\WarehouseInventoryModelToWarehouseInventoryMapperI;
use App\Mappers\WarehouseInventoryModelToWarehouseInventoryMapper;
use App\Mappers\WarehouseInventoryToWarehouseInventoryOutDetailDTOMapper;
use App\Contracts\WarehouseInventoryToWarehouseInventoryOutDetailDTOMapperI;
use App\Infrastructure\Factories\WarehouseOutputStrategyFactory;
use App\Contracts\WarehouseOutputStrategyFactoryInterface;
use App\Application_Layer\Services_Implementation\WarehouseInventoryQueryService;
use App\Contracts\WarehouseInventoryQueryServiceI;
use App\Application_Layer\Services_Implementation\WarehouseSalesService;
use App\Contracts\WarehouseSalesServiceI;
use App\Contracts\WarehouseSalesRequestDTOToEntityMapperI;
use App\Mappers\WarehouseSalesRequestDTOToEntityMapper;
use App\Contracts\WarehouseSalesRepositoryI;
use App\Application_Layer\Repository_Implementation\WarehouseSalesRepository;
use App\Contracts\WarehouseSalesEntityToModelMapperI;
use App\Mappers\WarehouseSalesEntityToModelMapper;

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


        $this->app->bind(
            LocationRepositoryInterface::class,
            LocationRepositoryImplementation::class
        );

        $this->app->bind(
            LocationModelToLocationEntityMapperI::class,
            LocationModelToLocationEntityMapper::class
        );

        $this->app->bind(
            LocationEntityToLocationModelMapperI::class,
            LocationEntityToLocationModel::class
        );

        $this->app->bind(
            LocationRequestDTOToLocationEntityMapperI::class,
            LocationRequestDTOToLocationEntity::class
        );

        $this->app->bind(
            LocationEntityToLocationDetailDTOMapperI::class,
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

        $this->app->bind(
            WarehouseStorageRepositoryInterface::class,
            WarehouseStorageRepositoryImplementation::class
        );

        $this->app->bind(
            WarehouseEntityToWarehouseModelMapperI::class,
            WarehouseToWarehouseModelMapper::class
        );

        $this->app->bind(
            WarehouseDTOToEntityMapperInterface::class,
            WarehouseDTOToEntityMapper::class
        );


        $this->app->bind(
            WarehouseTypeServiceInterface::class,
            WarehouseTypeServiceImplementation::class
        );

        $this->app->bind(
            WarehouseTypeRepositoryInterface::class,
            WarehouseTypeRepositoryImplementation::class
        );


        $this->app->bind(
            WarehouseTypeModelToWarehouseTypeEntityMapperI::class,
            WarehouseTypeModelToWarehouseTypeEntityMapper::class
        );

        $this->app->bind(
            WarehouseTypeEntityToWarehouseTypeModelMapperI::class,
            WarehouseTypeEntityToWarehouseTypeModel::class
        );


        $this->app->bind(
            WarehouseTypeEntityToWarehouseTypeDetailDTOMapperI::class,
            WarehouseTypeEntityToWarehouseTypeDetailDTO::class
        );

        $this->app->bind(
            WarehouseTypeRequestDTOToWarehouseTypeEntityMapperI::class,
            WarehouseTypeRequestDTOToWarehouseTypeEntity::class
        );

        $this->app->bind(
            ProductServiceInterface::class,
            ProductServiceImplementation::class
        );

        $this->app->bind(
            ProductRepositoryInterface::class,
            ProductRepositoryImplementation::class
        );

        $this->app->bind(
            WarehouseInventoryServiceInterface::class,
            WarehouseInventoryServiceImplementation::class
        );


        $this->app->bind(
            WarehouseInventoryRepositoryInterface::class,
            WarehouseInventoryRepositoryImplementation::class
        );

        $this->app->bind(
            WarehouseInventoryEntityToWarehouseInventoryModelMapperI::class,
            WarehouseInventoryEntityToWarehouseInventoryModelMapper::class
        );

        $this->app->bind(
            WarehouseInventoryModelToWarehouseInventoryMapperI::class,
            WarehouseInventoryModelToWarehouseInventoryMapper::class
        );

        $this->app->bind(
            WarehouseInventoryToWarehouseInventoryOutDetailDTOMapperI::class,
            WarehouseInventoryToWarehouseInventoryOutDetailDTOMapper::class
        );

        $this->app->bind(
            WarehouseInventoryRequestDTOToWarehouseInventoryMapperI::class,
            WarehouseInventoryRequestDTOToWarehouseInventoryMapper::class
        );


        $this->app->bind(
            WarehouseMovementsServiceI::class,
            WarehouseMovementsService::class
        );

        $this->app->bind(
            WarehouseMovementsRepositoryI::class,
            WarehouseMovementsRepository::class
        );

        $this->app->bind(
            WarehouseInventoryMovementsEntityToModelMapperI::class,
            WarehouseInventoryMovementsEntityToModelMapper::class
        );

        $this->app->bind(
            WarehouseInventoryMovementsMapperI::class,
            WarehouseInventoryMovementsMapper::class
        );

        $this->app->bind(
            WarehouseInventoryMovementModelMapperI::class,
            WarehouseInventoryMovementModelMapper::class
        );

        $this->app->bind(
            WarehouseInventoryQueryServiceI::class,
            WarehouseInventoryQueryService::class
        );

        $this->app->bind(
            WarehouseOutputStrategyFactoryInterface::class,
            WarehouseOutputStrategyFactory::class
        );

        $this->app->bind(
            WarehouseSalesServiceI::class,
            WarehouseSalesService::class
        );

        $this->app->bind(
            WarehouseSalesRepositoryI::class,
            WarehouseSalesRepository::class
        );

        $this->app->bind(
            WarehouseSalesRequestDTOToEntityMapperI::class,
            WarehouseSalesRequestDTOToEntityMapper::class
        );

        $this->app->bind(
            WarehouseSalesEntityToModelMapperI::class,
            WarehouseSalesEntityToModelMapper::class
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

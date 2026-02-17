<?php

namespace App\Application_Layer\Services_Implementation;

use App\Contracts\WarehouseDTOToEntityMapperInterface;
use App\Contracts\WarehouseStorageRepositoryInterface;
use App\Contracts\WarehouseStorageServiceInterface;
use App\Models\WarehouseModel;
use App\Application_Layer\ResultPattern;
use App\Enterprise_Layer\Warehouse;
use App\Mappers\DTO\WarehouseDTO;

class WarehouseStorageServiceImplementation implements WarehouseStorageServiceInterface
{
    private WarehouseStorageRepositoryInterface $warehouseStorageRepository;
    private WarehouseDTOToEntityMapperInterface $dTOToEntityMapper;
    private Warehouse $warehouseEntity;
    public function __construct(
        WarehouseStorageRepositoryInterface $warehouseStorageRepository,
        WarehouseDTOToEntityMapperInterface             $dTOToEntityMapper
    ) {
        $this->warehouseStorageRepository = $warehouseStorageRepository;
        $this->dTOToEntityMapper = $dTOToEntityMapper;
    }


    public function registerWarehouse(WarehouseDTO $warehouseDTO): ResultPattern
    {
        $this->warehouseEntity = $this->dTOToEntityMapper->convertDTOToEntity(
            $warehouseDTO
        );

        $this->warehouseEntity->setCreationDate(
            new \DateTime(
                'now',
                new \DateTimeZone(
                    'America/Mexico_City'
                )
            )
        );

        $this->warehouseEntity->setLastUpdateDate(
            new \DateTime(
                'now',
                new \DateTimeZone(
                    'America/Mexico_City'
                )
            )
        );
        
        $result =  $this->warehouseStorageRepository->saveWarehouse(
            $this->warehouseEntity
        );


        if ($result->isFailure()) {
            return ResultPattern::failure($result->getError());
        }

        return ResultPattern::success(
            "¡Almacén registrado con éxito!"
        );
    }

    public function updateWarehouse(WarehouseDTO $warehouseDTO): ResultPattern
    {
        $warehouseEntity = $this->dTOToEntityMapper->convertDTOToEntity(
            $warehouseDTO
        );
        $this->warehouseStorageRepository->updateWarehouse($warehouseEntity);
        return ResultPattern::success("Warehouse has been updated");

    }

    public function deleteWarehouse(WarehouseDTO $warehouse): ResultPattern
    {
        return ResultPattern::success("Warehouse has been deleted");
    }

    public function deleteByWarehouseId(int $warehouseId): ResultPattern
    {
        $this->warehouseStorageRepository->deleteWarehouseByWarehouseId($warehouseId);
        return ResultPattern::success("Warehouse has been deleted");
    }

    public function updateFieldsByWarehouseId(int $warehouseId, array $fields): ResultPattern
    {
        $this->warehouseStorageRepository->updateFieldsByWarehouseId(
            $warehouseId,
            $fields
        );
        return ResultPattern::success("Warehouse has been updated");
    }
}

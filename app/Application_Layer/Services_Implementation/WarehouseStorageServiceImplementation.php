<?php

namespace App\Application_Layer\Services_Implementation;

use App\Contracts\InterfaceMapperToEntity;
use App\Contracts\WarehouseStorageRepositoryInterface;
use App\Contracts\WarehouseStorageServiceInterface;
use App\Models\WarehouseModel;
use App\Application_Layer\ResultPattern;
use App\Mappers\DTO\WarehouseDTO;

class WarehouseStorageServiceImplementation implements WarehouseStorageServiceInterface
{

    private WarehouseStorageRepositoryInterface $warehouseStorageRepository;
    private InterfaceMapperToEntity $dTOToEntityMapper;

    public function __construct(
        WarehouseStorageRepositoryInterface $warehouseStorageRepository,
        InterfaceMapperToEntity             $dTOToEntityMapper
    )
    {
        $this->warehouseStorageRepository = $warehouseStorageRepository;
        $this->$dTOToEntityMapper = $dTOToEntityMapper;
    }


    public function registerWarehouse(WarehouseDTO $warehouseDTO): ResultPattern
    {
        $warehouseEntity = $this->dTOToEntityMapper->convertDTOToEntity(
            $warehouseDTO);

        $this->warehouseStorageRepository->saveWarehouse(
            $warehouseEntity);
        return ResultPattern::success(
            "Warehouse has been registered");
    }

    public function updateWarehouse(WarehouseDTO $warehouseDTO): ResultPattern
    {
        $warehouseEntity = $this->dTOToEntityMapper->convertDTOToEntity(
            $warehouseDTO);
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
            $warehouseId, $fields);
        return ResultPattern::success("Warehouse has been updated");
    }
}

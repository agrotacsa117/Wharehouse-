<?php

namespace app\Application_Layer\Services_Implementation;

use App\Contracts\WarehouseStorageRepositoryInterface;
use App\Contracts\WarehouseStorageServiceInterface;
use app\Models\Warehouse;
use App\Application_Layer\ResultPattern;
use App\Mappers\DTO\WarehouseDTO;

class WarehouseStorageService implements WarehouseStorageServiceInterface
{

    private WarehouseStorageRepositoryInterface $warehouseStorageRepository;
    
    public function __construct(
        WarehouseStorageRepositoryInterface $warehouseStorageRepository
    ) {
        $this->warehouseStorageRepository = $warehouseStorageRepository;
    }

    public function registerWarehouse(WarehouseDTO $warehouse): ResultPattern
    {

        return ResultPattern::success();
    }

    public function updateWarehouse(
        WarehouseDTO $warehouse
    ): ResultPattern {
        return ResultPattern::success();
    }

    public function deleteWarehouse(
        WarehouseDTO $warehouse
    ): ResultPattern {
        return ResultPattern::success();
    }

    public function deleteByWarehouseId(int $warehouseId): ResultPattern
    {
        return  ResultPattern::success();
    }

    public function updateFieldsByWarehouseId(
        int $warehouseId,
        array $fields
    ): ResultPattern {
        return ResultPattern::success();
    }
}

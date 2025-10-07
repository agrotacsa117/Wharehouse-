<?php

namespace App\Application_Layer\Repository_Implementation;

use app\Contracts\WarehouseStorageRepositoryInterface;
use App\Enterprise_Layer\Warehouse;
use App\Models\WarehouseModel;

class WarehouseStorageRepositoryImplementation
implements WarehouseStorageRepositoryInterface
{

    public function save(Warehouse $warehouse): Warehouse
    {
        $warehouseModel = new WarehouseModel();
        $warehouseModel->warehouses_name = $warehouse->getWarehousesName();

        return $warehouse;
    }
    public function delete(Warehouse $warehouse): bool
    {
        return true;
    }
    public function deleteByWarehouseId(int $warehouseId): bool
    {
        return false;
    }
    public function update(Warehouse $warehouse): Warehouse
    {
        return $warehouse;
    }
    public function updateFieldsByWarehouseId(
        int $warehouseId,
        array $fields
    ): Warehouse {

        return $ware;
    }
}

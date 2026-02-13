<?php

namespace App\Contracts;

use App\Application_Layer\ResultPattern;
use App\Enterprise_Layer\Warehouse;
use App\Models\WarehouseModel;

interface WarehouseStorageRepositoryInterface {

    public function saveWarehouse(Warehouse $warehouse): ResultPattern;

    public function deleteWarehouseByWarehouseId(int $warehouseId): ResultPattern;

    public function updateWarehouse(Warehouse $warehouse): ResultPattern;

    public function updateFieldsByWarehouseId(
            int $warehouseId,
            array $fields
    ): ResultPattern;

    public function findWarehouseById(int $warehouseId): ?WarehouseModel;
}

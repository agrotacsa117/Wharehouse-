<?php

namespace App\Contracts;

use App\Enterprise_Layer\Warehouse;

interface WarehouseStorageRepositoryInterface
{
    public function save(Warehouse $warehouse): Warehouse;
    public function delete(Warehouse $warehouse): bool;
    public function deleteByWarehouseId(int $warehouseId): bool;
    public function update(Warehouse $warehouse): Warehouse;
    public function updateFieldsByWarehouseId(
        int $warehouseId,
        array $fields
    ): Warehouse;
}

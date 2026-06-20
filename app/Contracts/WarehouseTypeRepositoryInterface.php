<?php

namespace App\Contracts;

use App\Enterprise_Layer\WarehouseType;

interface WarehouseTypeRepositoryInterface
{
    public function findById(int $id): ?WarehouseType;

    public function getAllCategoryWarehouse(): array;

    public function saveWarehouseType(WarehouseType $warehouseType): void;

    public function deleteLocation(int $warehouseTypeId): void;

    public function updateLocation(WarehouseType $location): void;
}

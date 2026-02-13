<?php

namespace App\Contracts;

use App\Enterprise_Layer\WarehouseType;
use App\Models\WarehouseTypeModel;

interface WarehouseTypeRepositoryInterface
{
    public function findById(int $id): ?WarehouseType;
    
    public function getAllCategoryWarehouse(): array;

    public function saveLocation(WarehouseType $location): void;

    public function deleteLocation(int $warehouseTypeId): void;

    public function updateLocation(WarehouseType $location): void;
}

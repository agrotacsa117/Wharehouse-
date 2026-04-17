<?php

namespace App\Contracts;

use App\Application_Layer\ResultPattern;
use app\Mappers\DTO\WarehouseDTO;

interface WarehouseStorageServiceInterface
{
    public function registerWarehouse(WarehouseDTO $warehouse): ResultPattern;

    public function updateWarehouse(
        WarehouseDTO $warehouse
    ): ResultPattern;

    public function deleteWarehouse(
        WarehouseDTO $warehouse
    ): ResultPattern;

    public function deleteByWarehouseId(int $warehouseId): ResultPattern;

    public function updateFieldsByWarehouseId(
        int   $warehouseId,
        array $fields
    ): ResultPattern;

    public function getWarehouseIdAndName(): array;

    public function getWarehouseNameById(int $warehouseId): string;

    public function listAllWarehouses(): array;

    public function getTotalWarehouse(): int;

    public function getListAllWarehousesWithLocation(
        array $warehouseIds): array;

    public function getWarehousesByLocationId(int $locationId): array;

}

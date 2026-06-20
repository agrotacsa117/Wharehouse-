<?php

namespace App\Contracts;

use App\Application_Layer\ResultPattern;
use App\Mappers\DTO\RemoveWarehouseInventoryStockDTO;
use App\Mappers\DTO\Requests\WarehouseInventoryRequestDTO;
use App\Mappers\DTO\WarehouseInventoryOutDetailDTO;

interface WarehouseInventoryQueryServiceI
{
    public function getInventoryById(int $id): ResultPattern;

    public function relocateInventory(
        int $id,
        string $rack,
        int $level
    ): ResultPattern;

    public function updateOrCreateInventory(
        RemoveWarehouseInventoryStockDTO $removeWarehouseInventoryStockDTO,
        WarehouseInventoryOutDetailDTO $warehouseInventoryOutDetailDTO
    ): ResultPattern;

    public function saveInventory(
        WarehouseInventoryRequestDTO $warehouseInventoryDTO): ResultPattern;

    public function desactiveInventory(
        int $warehouseInventoryId): void;
}

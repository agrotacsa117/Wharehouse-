<?php

namespace App\Contracts;

use App\Application_Layer\ResultPattern;
use App\Mappers\DTO\Requests\WarehouseInventoryRequestDTO;
use App\Mappers\DTO\RemoveWarehouseInventoryStockDTO;

interface WarehouseInventoryServiceInterface
{
    public function getAllWarehouseInventories(): array;

    public function create(WarehouseInventoryRequestDTO $warehouseInventory): ResultPattern;

    public function update(WarehouseInventoryRequestDTO $warehouseInventory): ResultPattern;

    public function delete(int $id): ResultPattern;

    public function existProductInInventory(
        int $warehouseId,
        string $productId
    ): bool;

    public function getWarehouseIdsWithInventory() : array;

    public function getWarehouseInventoryByWarehouseId(
        int $warehouseId) : array;

    public function processInventoryOutput(
        RemoveWarehouseInventoryStockDTO $output
    ) : ResultPattern; 
}

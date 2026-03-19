<?php

namespace App\Contracts;

use App\Enterprise_Layer\WarehouseInventory;
use Illuminate\Support\Arr;

interface WarehouseInventoryRepositoryInterface
{
    public function findAll(): array;

    public function save(WarehouseInventory $warehouseInventory): WarehouseInventory;

    public function update(WarehouseInventory $warehouseInventory): void;

    public function delete(int $id): void;

    public function existById(int $warehouseId, string $productId): bool;

    function countDistinctByWarehouseId() : array;

    public function findInventoryByWarehouseId(int $warehouseId) : array;

    function updateQuantity(
        int $warehouseInventoryId, 
        int $quantity) : bool;
    

    function findQuantityById(
        int $warehouseInventoryId) : int;

   public function getInventoryStatsByState(): array;

   public function getInventoryStatsByStateAndWarehouse(): array;

   public function getInventoryByState(int $state): array;

   public function findById(int $id): ?array;

   public function updateById(int $id, array $data): bool;

   public function transferInventory(
       int $inventoryId,
       int $fromWarehouseId,
       int $toWarehouseId,
       string $rack,
       int $level,
       string $lotNumber,
       int $quantity,
       string $expirationDate
   ): array;
}

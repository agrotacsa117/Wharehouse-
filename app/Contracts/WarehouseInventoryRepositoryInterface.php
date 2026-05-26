<?php

namespace App\Contracts;

use App\Enterprise_Layer\WarehouseInventory;

interface WarehouseInventoryRepositoryInterface
{
    public function findAll(): array;

    public function save(WarehouseInventory $warehouseInventory): WarehouseInventory;

    public function update(WarehouseInventory $warehouseInventory): void;

    public function delete(int $id): void;

    public function existById(int $warehouseId, string $productId): bool;

    public function countDistinctByWarehouseId(): array;

    public function findInventoryByWarehouseId(int $warehouseId): array;

    public function updateQuantity(
        int $warehouseInventoryId,
        int $quantity
    ): bool;


    public function findQuantityById(
        int $warehouseInventoryId
    ): int;

    public function findQuantityByIdWithLock(int $warehouseInventoryId): int;

    public function getInventoryStatsByState(): array;

    public function getInventoryStatsByStateAndWarehouse(): array;

    public function getInventoryByState(int $state): array;

    public function findById(int $id): ?WarehouseInventory;

    public function updateById(int $id, array $data): bool;

    public function transferInventory(
        int $inventoryId,
        string $fromWarehouseId,
        string $lotNumber,
        int $quantity
    ): array;


    public function findExpired(): array;

    public function updateInventoryLocation(
        int $id,
        ?string $rack,
        ?int $level,
        ?int $module,
        ?int $bay,
        ?int $platform
    ): bool;


    public function findExpiredRanking(): array;

    public function findSpecificInventory(
        int $warehouseId,
        ?int $rack,
        ?int $level,
        ?int $module,
        ?int $platform,
        ?int $bay,
        string $productId,
        string $lotNumber
    ): ?WarehouseInventory;

    public function updateActiveInventory(
        int $inventoryId
    ) : void;

    public function findStockAndProductCountGroupedByWarehouse() : array;

    public function sumQuantityOfExpiredByWarehouse() : array;

    public function getStockByWarehouseId(int $warehouseId) : array;

    public function findByProductIdAndWarehouseId(
        int $warehouseId, 
        string $productId
    ) : array;
}

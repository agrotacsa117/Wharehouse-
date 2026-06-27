<?php

namespace App\Contracts;

use App\Application_Layer\ResultPattern;
use App\Mappers\DTO\InventoryExpirationMetricsDataDTO;

interface WarehouseInventoryQueryServiceInterface
{
    public function getAllWarehouseInventories(): array;

    public function getAllInventoryForManagement(): array;

    public function getWarehouseIdsWithInventory(): array;

    public function getWarehouseInventoryByWarehouseId(int $warehouseId): array;

    public function getInventoryStatsByState(): array;

    public function getInventoryStatsByStateAndWarehouse(): array;

    public function getInventoryByState(int $state): array;

    public function getExpiredInventory(): array;

    public function getExpiredInventoryRanking(): array;

    public function getStockSummaryPerWarehouse(): array;

    public function getStockByWarehouse(int $warehouseId): array;

    public function getProductInventory(int $warehouseId, string $productId): array;

    public function getProductExpirationMetrics(int $warehouseId, string $productId): InventoryExpirationMetricsDataDTO;

    public function getInventoryById(int $id): ResultPattern;

    public function getListFilteredByProductCodeOrName(string $product): array;

    public function existProductInInventory(int $warehouseId, string $productId): bool;
}

<?php

namespace App\Contracts;

use App\Application_Layer\ResultPattern;
use App\Mappers\DTO\InventoryExpirationMetricsDataDTO;
use App\Mappers\DTO\RemoveWarehouseInventoryStockDTO;
use App\Mappers\DTO\Requests\WarehouseInventoryRequestDTO;
use App\Mappers\DTO\TransferInventoryDTO;
use App\Mappers\DTO\UpdateInventoryDTO;

interface WarehouseInventoryServiceInterface
{
    public function saveInventory(
        WarehouseInventoryRequestDTO $warehouseInventoryRequestDTO
    ): ResultPattern;

    public function getAllWarehouseInventories(): array;

    public function create(WarehouseInventoryRequestDTO $warehouseInventory): ResultPattern;

    public function update(WarehouseInventoryRequestDTO $warehouseInventory): ResultPattern;

    public function delete(int $id): ResultPattern;

    public function existProductInInventory(
        int $warehouseId,
        string $productId
    ): bool;

    public function getWarehouseIdsWithInventory(): array;

    public function getWarehouseInventoryByWarehouseId(
        int $warehouseId
    ): array;

    public function processInventoryOutput(
        RemoveWarehouseInventoryStockDTO $output
    ): ResultPattern;

    public function getInventoryStatsByState(): array;

    public function getInventoryStatsByStateAndWarehouse(): array;

    public function getInventoryByState(int $state): array;

    public function getInventoryById(int $id): ResultPattern;

    public function getAllInventoryForManagement(): array;

    public function updateInventory(UpdateInventoryDTO $dto): ResultPattern;

    public function transferInventory(TransferInventoryDTO $dto): ResultPattern;

    public function getExpiredInventory(): array;

    public function relocateInventory(
        int $id,
        string $rack,
        int $level
    ): ResultPattern;

    public function getExpiredInventoryRanking(): array;

    public function getStockSummaryPerWarehouse(): array;

    public function getStockByWarehouse(int $warehouseId): array;

    public function getProductInventory(
        int $warehouseId,
        string $productId): array;

    public function getWarehouseInventory(int $warehouseId): array;

    public function getProductExpirationMetrics(
        int $warehouseId,
        string $productId
    ): InventoryExpirationMetricsDataDTO;

    public function revertMovement(
        string $folio,
        string $reason,
        int $responsableUserId,
        bool $foreceConfirm): ResultPattern;

    public function getListFilteredByProductCodeOrName(
        string $product
    ): array;
}

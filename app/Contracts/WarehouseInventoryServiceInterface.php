<?php

namespace App\Contracts;

use App\Application_Layer\ResultPattern;
use App\Mappers\DTO\Requests\WarehouseInventoryRequestDTO;
use App\Mappers\DTO\RemoveWarehouseInventoryStockDTO;
use App\Mappers\DTO\WarehouseInventoryOutDetailDTO;

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

    public function updateInventory(\App\Mappers\DTO\UpdateInventoryDTO $dto): \App\Application_Layer\ResultPattern;

    public function transferInventory(\App\Mappers\DTO\TransferInventoryDTO $dto): \App\Application_Layer\ResultPattern;

    public function getExpiredInventory(): array;

    public function relocateInventory(
        int $id,
        ?string $rack,
        ?int $level,
        ?int $module,
        ?int $bay,
        ?int $platform
    ): ResultPattern;

    public function getExpiredInventoryRanking(): array;

    function getStockSummaryPerWarehouse() : array;

    function getStockByWarehouse(int $warehouseId) : array;

    function getProductInventory(
        int $warehouseId, 
        string $productId) : array;
}

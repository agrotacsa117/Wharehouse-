<?php

namespace App\Contracts;

use App\Application_Layer\ResultPattern;
use App\Mappers\DTO\Requests\WarehouseInventoryRequestDTO;

interface WarehouseInventoryServiceInterface
{
    public function getAllWarehouseInventories(): array;

    public function create(WarehouseInventoryRequestDTO $warehouseInventory): ResultPattern;

    public function update(WarehouseInventoryRequestDTO $warehouseInventory): void;

    public function delete(int $id): void;
}

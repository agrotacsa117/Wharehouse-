<?php

namespace App\Contracts;

use App\Enterprise_Layer\WarehouseInventory;

interface WarehouseInventoryRepositoryInterface
{
    public function findAll(): array;

    public function save(WarehouseInventory $warehouseInventory): void;

    public function update(WarehouseInventory $warehouseInventory): void;

    function delete(int $id) : void;

}

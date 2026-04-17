<?php

namespace App\Contracts;

use App\Application_Layer\ResultPattern;

interface WarehouseInventoryQueryServiceI
{
    public function getInventoryById(int $id): ResultPattern;

    public function relocateInventory(
        int $id,
        string $rack,
        int $level
    ): ResultPattern;
}

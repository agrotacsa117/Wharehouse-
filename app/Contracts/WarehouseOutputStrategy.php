<?php

namespace App\Contracts;

use App\Application_Layer\ResultPattern;
use App\Mappers\DTO\RemoveWarehouseInventoryStockDTO;

interface WarehouseOutputStrategy
{
    public function processOutput(
        RemoveWarehouseInventoryStockDTO $removeWarehouseInventoryStockDTO
    ): ResultPattern;
    public function getType(): string;
}

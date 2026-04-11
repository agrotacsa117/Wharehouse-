<?php

namespace App\Application_Layer\Services_Implementation;

use App\Contracts\WarehouseOutputStrategy;
use App\Mappers\DTO\RemoveWarehouseInventoryStockDTO;
use App\Application_Layer\ResultPattern;
use App\Contracts\WarehouseInventoryServiceInterface;

class InternalRelocationService implements WarehouseOutputStrategy
{
    private WarehouseInventoryServiceInterface $warehouseInventoryService;

    public function __construct(WarehouseInventoryServiceInterface $warehouseInventoryService)
    {
        $this->warehouseInventoryService = $warehouseInventoryService;
    }

    public function processOutput(
        RemoveWarehouseInventoryStockDTO $removeWarehouseInventoryStockDTO
    ): ResultPattern {

        return ResultPattern::success($this);
    }

    public function getType(): string
    {
        return "RELOCATION";
    }
}

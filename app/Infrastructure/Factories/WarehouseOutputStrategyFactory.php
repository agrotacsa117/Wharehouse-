<?php

namespace App\Infrastructure\Factories;

use App\Contracts\WarehouseOutputStrategyFactoryInterface;
use App\Contracts\WarehouseOutputStrategy;
use App\Contracts\WarehouseInventoryServiceInterface;
use App\Contracts\WarehouseMovementsServiceI;
use App\Application_Layer\Services_Implementation\InternalRelocationService;

class WarehouseOutputStrategyFactory implements WarehouseOutputStrategyFactoryInterface
{
    private WarehouseInventoryServiceInterface $warehouseInventoryService;
    private WarehouseMovementsServiceI $warehouseMovementsService;

    public function __construct(
        WarehouseInventoryServiceInterface $warehouseInventoryService,
        WarehouseMovementsServiceI $warehouseMovementsService
    ) {
        $this->warehouseInventoryService = $warehouseInventoryService;
        $this->warehouseMovementsService = $warehouseMovementsService;
    }

    public function make(string $type): WarehouseOutputStrategy
    {
        switch ($type) {
            case 'RELOCATION':
                return new InternalRelocationService(
                    $this->warehouseInventoryService,
                    $this->warehouseMovementsService
                );
                break;

            default:
                throw new \InvalidArgumentException(
                    "Tipo de salida no soportado: {$type}"
                );
                break;
        }
    }

}

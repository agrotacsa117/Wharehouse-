<?php

namespace App\Infrastructure\Factories;

use App\Contracts\WarehouseOutputStrategyFactoryInterface;
use App\Contracts\WarehouseOutputStrategy;
use App\Contracts\WarehouseInventoryServiceInterface;
use App\Contracts\WarehouseMovementsServiceI;
use App\Application_Layer\Services_Implementation\InternalRelocationService;
use App\Contracts\WarehouseInventoryRepositoryInterface;
use App\Contracts\WarehouseInventoryQueryServiceI;

class WarehouseOutputStrategyFactory implements WarehouseOutputStrategyFactoryInterface
{
    private WarehouseMovementsServiceI $warehouseMovementsService;
    private WarehouseInventoryQueryServiceI $warehouseInventoryQueryService;

    public function __construct(
        WarehouseMovementsServiceI $warehouseMovementsService,
        WarehouseInventoryQueryServiceI $warehouseInventoryQueryService
    ) {
        $this->warehouseMovementsService = $warehouseMovementsService;
        $this->warehouseInventoryQueryService = $warehouseInventoryQueryService;
    }

    public function make(string $type): WarehouseOutputStrategy
    {
        switch ($type) {
            case 'RELOCATION':
                return new InternalRelocationService(
                    $this->warehouseInventoryQueryService,
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

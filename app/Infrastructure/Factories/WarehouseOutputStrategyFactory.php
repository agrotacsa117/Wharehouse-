<?php

namespace App\Infrastructure\Factories;

use App\Contracts\WarehouseOutputStrategyFactoryInterface;
use App\Contracts\WarehouseOutputStrategy;
use App\Contracts\WarehouseInventoryServiceInterface;
use App\Contracts\WarehouseMovementsServiceI;
use App\Application_Layer\Services_Implementation\InternalRelocationService;
use App\Contracts\WarehouseInventoryRepositoryInterface;
use App\Contracts\WarehouseInventoryQueryServiceI;
use App\Application_Layer\Services_Implementation\SimpleOutputService;
use App\Application_Layer\Services_Implementation\SaleOutputService;
use App\Contracts\WarehouseSalesServiceI;

class WarehouseOutputStrategyFactory implements WarehouseOutputStrategyFactoryInterface
{
    private WarehouseMovementsServiceI $warehouseMovementsService;
    private WarehouseInventoryQueryServiceI $warehouseInventoryQueryService;
    private WarehouseInventoryRepositoryInterface $inventoryRepository;
    private WarehouseSalesServiceI $warehouseSalesService;

    public function __construct(
        WarehouseMovementsServiceI $warehouseMovementsService,
        WarehouseInventoryQueryServiceI $warehouseInventoryQueryService,
        WarehouseInventoryRepositoryInterface $inventoryRepository,
        WarehouseSalesServiceI $warehouseSalesService
    ) {
        $this->warehouseMovementsService = $warehouseMovementsService;
        $this->warehouseInventoryQueryService = $warehouseInventoryQueryService;
        $this->inventoryRepository = $inventoryRepository;
        $this->warehouseSalesService = $warehouseSalesService;
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

            case "OUT":
                return new SimpleOutputService(
                    $this->inventoryRepository,
                    $this->warehouseMovementsService
                );

            case "SALE":
                return new SaleOutputService(
                    $this->inventoryRepository,
                    $this->warehouseMovementsService,
                    $this->warehouseSalesService
                );

            

            default:
                throw new \InvalidArgumentException(
                    "Tipo de salida no soportado: {$type}"
                );
                break;
        }
    }

}

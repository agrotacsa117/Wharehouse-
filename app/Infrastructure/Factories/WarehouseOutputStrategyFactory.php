<?php

namespace App\Infrastructure\Factories;

use App\Application_Layer\Services_Implementation\InternalRelocationService;
use App\Application_Layer\Services_Implementation\SaleOutputService;
use App\Application_Layer\Services_Implementation\SimpleOutputService;
use App\Application_Layer\Strategies\IntraWarehouseTransferStrategy;
use App\Contracts\WarehouseInventoryQueryServiceI;
use App\Contracts\WarehouseInventoryRepositoryInterface;
use App\Contracts\WarehouseMovementsServiceI;
use App\Contracts\WarehouseOutputStrategy;
use App\Contracts\WarehouseOutputStrategyFactoryInterface;
use App\Contracts\WarehouseSalesServiceI;
use App\Contracts\WarehouseStorageServiceInterface;

class WarehouseOutputStrategyFactory implements WarehouseOutputStrategyFactoryInterface
{
    private WarehouseMovementsServiceI $warehouseMovementsService;

    private WarehouseInventoryQueryServiceI $warehouseInventoryQueryService;

    private WarehouseInventoryRepositoryInterface $inventoryRepository;

    private WarehouseSalesServiceI $warehouseSalesService;

    private WarehouseStorageServiceInterface $warehouseStorageService;

    public function __construct(
        WarehouseMovementsServiceI $warehouseMovementsService,
        WarehouseInventoryQueryServiceI $warehouseInventoryQueryService,
        WarehouseInventoryRepositoryInterface $inventoryRepository,
        WarehouseSalesServiceI $warehouseSalesService,
        WarehouseStorageServiceInterface $warehouseStorageService
    ) {
        $this->warehouseMovementsService = $warehouseMovementsService;
        $this->warehouseInventoryQueryService = $warehouseInventoryQueryService;
        $this->inventoryRepository = $inventoryRepository;
        $this->warehouseSalesService = $warehouseSalesService;
        $this->warehouseStorageService = $warehouseStorageService;
    }

    public function make(string $type): WarehouseOutputStrategy
    {
        switch ($type) {
            case 'RELOCATION':
                return new InternalRelocationService(
                    $this->warehouseInventoryQueryService,
                    $this->warehouseMovementsService,
                    $this->inventoryRepository,
                    $this->warehouseStorageService
                );
                break;

            case 'OUT':
                return new SimpleOutputService(
                    $this->inventoryRepository,
                    $this->warehouseMovementsService
                );

            case 'SALE':
                return new SaleOutputService(
                    $this->inventoryRepository,
                    $this->warehouseMovementsService,
                    $this->warehouseSalesService
                );

            case 'LOCATION_UPDATE':
                return new IntraWarehouseTransferStrategy(
                    $this->warehouseInventoryQueryService,
                    $this->warehouseMovementsService,
                    $this->inventoryRepository
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

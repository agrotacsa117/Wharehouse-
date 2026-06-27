<?php

namespace App\Infrastructure\Factories;

use App\Application_Layer\Strategies\InReversalStrategy;
use App\Application_Layer\Strategies\OutReversalStrategy;
use App\Contracts\ReversalStrategyFactoryInterface;
use App\Contracts\ReversalStrategyInterface;
use App\Contracts\WarehouseInventoryRepositoryInterface;
use App\Contracts\WarehouseMovementsServiceI;

class ReversalStrategyFactory implements ReversalStrategyFactoryInterface
{
    private WarehouseInventoryRepositoryInterface $warehouseInventoryRepositoryInterface;

    private WarehouseMovementsServiceI $warehouseMovementsServiceI;

    public function __construct(
        WarehouseInventoryRepositoryInterface $warehouseInventoryRepositoryInterface,
        WarehouseMovementsServiceI $warehouseMovementsServiceI
    ) {
        $this->warehouseInventoryRepositoryInterface = $warehouseInventoryRepositoryInterface;
        $this->warehouseMovementsServiceI = $warehouseMovementsServiceI;
    }

    public function make(
        string $type): ReversalStrategyInterface
    {

        switch ($type) {
            case 'IN':
                return new InReversalStrategy(
                    $this->warehouseInventoryRepositoryInterface,
                    $this->warehouseMovementsServiceI
                );
                break;

            case 'OUT':
                return new OutReversalStrategy(
                    $this->warehouseInventoryRepositoryInterface,
                    $this->warehouseMovementsServiceI
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

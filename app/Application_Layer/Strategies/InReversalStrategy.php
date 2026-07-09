<?php

namespace App\Application_Layer\Strategies;

use App\Application_Layer\ManagesInventoryStock;
use App\Application_Layer\ResultPattern;
use App\Contracts\ReversalStrategyInterface;
use App\Contracts\WarehouseInventoryRepositoryInterface;
// use App\Mappers\DTO\RemoveWarehouseInventoryStockDTO;
use App\Contracts\WarehouseMovementsServiceI;
use App\Mappers\DTO\NegativeStockWarningDTO;
use App\Mappers\DTO\WarehouseMovementsDTO;

class InReversalStrategy implements ReversalStrategyInterface
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

    public function getInverseType(): string
    {
        return 'OUT';
    }

    public function processCountermovement(
        WarehouseMovementsDTO $warehouseMovementsDTO): ResultPattern
    {

        $currentQuantity = $this->warehouseInventoryRepositoryInterface
            ->findQuantityByIdWithLock(
                $warehouseMovementsDTO
                    ->getWarehouseInventoryId()
            );

        $result = ManagesInventoryStock::validateStockAvailability(
            $warehouseMovementsDTO->getQuantity(),
            $currentQuantity,
            $warehouseMovementsDTO->getForceNegativeStock()
        );

        if ($result->isFailure()
            &&
         ! $warehouseMovementsDTO
             ->getForceNegativeStock()) {

            $dependentMovements = $this
                ->warehouseMovementsServiceI
                ->getDependentMovements(
                    $warehouseMovementsDTO
                        ->getWarehouseInventoryId(),
                    $warehouseMovementsDTO
                        ->getFolio()
                );

            if (count($dependentMovements) === 0) {
                return $result;
            }

            $resultingStock = $currentQuantity - $warehouseMovementsDTO
                ->getQuantity();

            $negativeStockWarningDTO = new NegativeStockWarningDTO(
                $currentQuantity,
                $resultingStock,
                $dependentMovements
            );

            return ResultPattern::warning(
                'La reversión generará stock negativo. 
                Hay movimientos dependientes.',
                $negativeStockWarningDTO);
        }

        $currentQuantity = $result->getValue();

        $result = ManagesInventoryStock::reduceStock(
            $warehouseMovementsDTO->getWarehouseInventoryId(),
            $warehouseMovementsDTO->getQuantity(),
            $currentQuantity,
            $this->warehouseInventoryRepositoryInterface
        );

        if ($result->isFailure()) {
            return $result;
        }

        $this->warehouseMovementsServiceI->markStatusIsReserved(
            $warehouseMovementsDTO->getFolio(),
            true
        );

        return ResultPattern::success(true);
    }
}

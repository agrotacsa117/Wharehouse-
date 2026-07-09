<?php

namespace App\Application_Layer\Strategies;

use App\Application_Layer\ResultPattern;
use App\Contracts\ReversalStrategyInterface;
use App\Contracts\WarehouseInventoryRepositoryInterface;
use App\Contracts\WarehouseMovementsServiceI;
use App\Mappers\DTO\WarehouseMovementsDTO;

class OutReversalStrategy implements ReversalStrategyInterface
{
    private WarehouseInventoryRepositoryInterface $warehouseInventoryRepository;

    private WarehouseMovementsServiceI $warehouseMovementsService;

    public function __construct(
        WarehouseInventoryRepositoryInterface $warehouseInventoryRepository,
        WarehouseMovementsServiceI $warehouseMovementsService
    ) {
        $this->warehouseInventoryRepository = $warehouseInventoryRepository;
        $this->warehouseMovementsService = $warehouseMovementsService;
    }

    public function getInverseType(): string
    {
        return 'IN';
    }

    public function processCountermovement(
        WarehouseMovementsDTO $warehouseMovementsDTO): ResultPattern
    {
        $currentQuantity = $this->warehouseInventoryRepository
            ->findQuantityByIdWithLock(
                $warehouseMovementsDTO->getWarehouseInventoryId()
            );

        $newQuantity = $currentQuantity + $warehouseMovementsDTO->getQuantity();

        $updated = $this->warehouseInventoryRepository->updateQuantity(
            $warehouseMovementsDTO->getWarehouseInventoryId(),
            $newQuantity
        );

        if (! $updated) {
            return ResultPattern::failure(
                'Error al revertir la salida: no se pudo actualizar el stock.'
            );
        }

        $this->warehouseInventoryRepository->activateInventory(
            $warehouseMovementsDTO->getWarehouseInventoryId()
        );

        $this->warehouseMovementsService->markStatusIsReserved(
            $warehouseMovementsDTO->getFolio(),
            true
        );

        return ResultPattern::success(true);
    }
}

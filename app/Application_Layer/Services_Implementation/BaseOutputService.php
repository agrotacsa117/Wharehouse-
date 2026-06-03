<?php

namespace App\Application_Layer\Services_Implementation;

use App\Application_Layer\ResultPattern;
use App\Contracts\WarehouseInventoryRepositoryInterface;
use App\Contracts\WarehouseMovementsServiceI;
use App\Contracts\WarehouseOutputStrategy;
use App\Mappers\DTO\RemoveWarehouseInventoryStockDTO;
use App\Mappers\DTO\WarehouseMovementsDTO;

abstract class BaseOutputService implements WarehouseOutputStrategy
{
    protected WarehouseInventoryRepositoryInterface $inventoryRepository;

    protected WarehouseMovementsServiceI $warehouseMovementsService;

    public function __construct(
        WarehouseInventoryRepositoryInterface $inventoryRepository,
        WarehouseMovementsServiceI $movementsService
    ) {
        $this->inventoryRepository = $inventoryRepository;
        $this->warehouseMovementsService = $movementsService;
    }

    protected function validateStockAvailability(
        RemoveWarehouseInventoryStockDTO $removeWarehouseInventoryStockDTO
    ): ResultPattern {
        // 1. Validar stock disponible
        $currentQuantity = $this->inventoryRepository->findQuantityByIdWithLock(
            $removeWarehouseInventoryStockDTO->getWarehouseInventoryId()
        );

        if ($removeWarehouseInventoryStockDTO->getQuantity() > $currentQuantity) {
            return ResultPattern::failure(
                '¡Error! No puede retirar cantidad mayor al stock disponible.'
            );
        }

        return ResultPattern::success($currentQuantity);
    }

    public function reduceStock(
        int $currentQuantity,
        RemoveWarehouseInventoryStockDTO $removeWarehouseInventoryStockDTO
    ): ResultPattern {

        $newQuantity = $currentQuantity - $removeWarehouseInventoryStockDTO
            ->getQuantity();

        $updated = $this->inventoryRepository->updateQuantity(
            $removeWarehouseInventoryStockDTO->getWarehouseInventoryId(),
            $newQuantity
        );

        if (! $updated) {
            return ResultPattern::failure('Error al actualizar el inventario');
        }

        if ($newQuantity === 0) {
            $this->inventoryRepository
                ->updateActiveInventory(
                    $removeWarehouseInventoryStockDTO
                        ->getWarehouseInventoryId()
                );
        }

        return ResultPattern::success($newQuantity);
    }

    protected function recordMovement(
        RemoveWarehouseInventoryStockDTO $removeWarehouseInventoryStockDTO
    ): WarehouseMovementsDTO {

        $folio = $this->warehouseMovementsService
            ->generateMovementFolio();

        $movementDTO = new WarehouseMovementsDTO(
            $folio,
            $removeWarehouseInventoryStockDTO
                ->getWarehouseInventoryId(),
            $this->getType(),
            $removeWarehouseInventoryStockDTO->getQuantity(),
            $removeWarehouseInventoryStockDTO->getReason(),
            $removeWarehouseInventoryStockDTO->getUserId()
        );

        return $movementDTO;
    }
}

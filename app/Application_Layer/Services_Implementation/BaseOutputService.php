<?php

namespace App\Application_Layer\Services_Implementation;

use App\Application_Layer\ResultPattern;
use App\Contracts\WarehouseInventoryQueryServiceI;
use App\Contracts\WarehouseInventoryRepositoryInterface;
use App\Contracts\WarehouseMovementsServiceI;
use App\Contracts\WarehouseOutputStrategy;
use App\Mappers\DTO\RemoveWarehouseInventoryStockDTO;
use App\Mappers\DTO\WarehouseInventoryOutDetailDTO;
use App\Mappers\DTO\WarehouseMovementsDTO;
use Illuminate\Support\Facades\Log;

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

    /**
     * Mueve stock de una posición a otra (parcial o total), compartido por
     * RELOCATION (entre bodegas) y LOCATION_UPDATE (misma bodega): reduce el
     * origen, suma/crea en destino, registra el movimiento contra el origen
     * y guarda el lineage (relocatedFrom/relocatedTo) por separado de
     * reversalOf/reversedBy, que quedan reservados para la reversa real.
     */
    protected function relocateStock(
        RemoveWarehouseInventoryStockDTO $removeWarehouseInventoryStockDTO,
        WarehouseInventoryOutDetailDTO $sourceInventory,
        WarehouseInventoryQueryServiceI $warehouseInventoryQueryService
    ): ResultPattern {

        Log::info('BaseOutputService::relocateStock: iniciando', [
            'type' => $this->getType(),
            'sourceInventoryId' => $sourceInventory->getInventoryId(),
            'warehouseInventoryId' => $removeWarehouseInventoryStockDTO->getWarehouseInventoryId(),
            'quantity' => $removeWarehouseInventoryStockDTO->getQuantity(),
            'destinationWarehouseId' => $removeWarehouseInventoryStockDTO->getWarehouseId(),
        ]);

        $availability = $this->validateStockAvailability($removeWarehouseInventoryStockDTO);

        if ($availability->isFailure()) {
            Log::error('BaseOutputService::relocateStock: stock insuficiente', [
                'sourceInventoryId' => $sourceInventory->getInventoryId(),
                'quantity' => $removeWarehouseInventoryStockDTO->getQuantity(),
                'reason' => $availability->getError(),
            ]);

            return $availability;
        }

        if ($removeWarehouseInventoryStockDTO->getQuantity() <= 0) {
            Log::error('BaseOutputService::relocateStock: cantidad invalida', [
                'sourceInventoryId' => $sourceInventory->getInventoryId(),
                'quantity' => $removeWarehouseInventoryStockDTO->getQuantity(),
            ]);

            return ResultPattern::failure(
                '¡Error! La cantidad a reubicar debe ser mayor a cero.'
            );
        }

        $currentQuantity = $availability->getValue();

        $reduction = $this->reduceStock($currentQuantity, $removeWarehouseInventoryStockDTO);

        if ($reduction->isFailure()) {
            Log::error('BaseOutputService::relocateStock: fallo al reducir origen', [
                'sourceInventoryId' => $sourceInventory->getInventoryId(),
                'currentQuantity' => $currentQuantity,
                'reason' => $reduction->getError(),
            ]);

            return $reduction;
        }

        Log::info('BaseOutputService::relocateStock: origen reducido', [
            'sourceInventoryId' => $sourceInventory->getInventoryId(),
            'previousQuantity' => $currentQuantity,
            'newQuantity' => $reduction->getValue(),
        ]);

        $destinationResult = $warehouseInventoryQueryService->updateOrCreateInventory(
            $removeWarehouseInventoryStockDTO,
            $sourceInventory
        );

        if ($destinationResult->isFailure()) {
            Log::error('BaseOutputService::relocateStock: fallo al actualizar/crear destino', [
                'sourceInventoryId' => $sourceInventory->getInventoryId(),
                'destinationWarehouseId' => $removeWarehouseInventoryStockDTO->getWarehouseId(),
                'reason' => $destinationResult->getError(),
            ]);

            return $destinationResult;
        }

        $destinationId = $destinationResult->getValue()->getId();

        Log::info('BaseOutputService::relocateStock: destino resuelto', [
            'sourceInventoryId' => $sourceInventory->getInventoryId(),
            'destinationInventoryId' => $destinationId,
        ]);

        $movementDTO = $this->recordMovement($removeWarehouseInventoryStockDTO);

        $movementDTO->setRelocatedFromInventoryId($sourceInventory->getInventoryId());
        $movementDTO->setRelocatedToInventoryId($destinationId);

        $movementResult = $this->warehouseMovementsService->saveWarehouseMovement($movementDTO);

        if ($movementResult->isFailure()) {
            Log::error('BaseOutputService::relocateStock: fallo al guardar movimiento', [
                'sourceInventoryId' => $sourceInventory->getInventoryId(),
                'destinationInventoryId' => $destinationId,
                'reason' => $movementResult->getError(),
            ]);

            return $movementResult;
        }

        Log::info('BaseOutputService::relocateStock: completado', [
            'sourceInventoryId' => $sourceInventory->getInventoryId(),
            'destinationInventoryId' => $destinationId,
            'quantity' => $removeWarehouseInventoryStockDTO->getQuantity(),
        ]);

        return ResultPattern::success(true);
    }
}

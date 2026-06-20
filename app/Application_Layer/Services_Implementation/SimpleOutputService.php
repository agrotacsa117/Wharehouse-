<?php

namespace App\Application_Layer\Services_Implementation;

use App\Application_Layer\ResultPattern;
use App\Contracts\WarehouseInventoryRepositoryInterface;
use App\Contracts\WarehouseMovementsServiceI;
use App\Mappers\DTO\RemoveWarehouseInventoryStockDTO;

class SimpleOutputService extends BaseOutputService
{
    public function __construct(
        WarehouseInventoryRepositoryInterface $inventoryRepository,
        WarehouseMovementsServiceI $movementsService
    ) {
        parent::__construct(
            $inventoryRepository,
            $movementsService
        );
    }

    public function processOutput(
        RemoveWarehouseInventoryStockDTO $dto
    ): ResultPattern {
        // 1. Validar stock disponible
        $result = $this
            ->validateStockAvailability(
                $dto
            );

        if ($result->isFailure()) {
            return $result;
        }

        // 2. Actualizar cantidad
        try {
            $currentQuantity = $result->getValue();

            $result = $this->reduceStock(
                $currentQuantity,
                $dto
            );

            $newQuantity = $result->getValue();

            if ($result->isFailure()) {
                return $result;
            }

            // 3. Registrar movimiento
            $movementDTO = $this->recordMovement(
                $dto
            );

            $result = $this->warehouseMovementsService
                ->saveWarehouseMovement(
                    $movementDTO
                );

        } catch (\Throwable $th) {
            return ResultPattern::failure($th->getMessage());
        }

        return ResultPattern::success([
            'message' => 'Salida registrada exitosamente',
            'previous_quantity' => $currentQuantity,
            'new_quantity' => $newQuantity,
            'removed' => $dto->getQuantity(),
        ]);
    }

    public function getType(): string
    {
        return 'OUT';
    }
}

<?php

namespace App\Application_Layer\Services_Implementation;

use App\Contracts\WarehouseOutputStrategy;
use App\Mappers\DTO\RemoveWarehouseInventoryStockDTO;
use App\Application_Layer\ResultPattern;
use App\Contracts\WarehouseInventoryRepositoryInterface;
use App\Contracts\WarehouseMovementsServiceI;
use App\Mappers\DTO\WarehouseMovementsDTO;

class SimpleOutputService implements WarehouseOutputStrategy
{
    private WarehouseInventoryRepositoryInterface $inventoryRepository;
    private WarehouseMovementsServiceI $movementsService;

    public function __construct(
        WarehouseInventoryRepositoryInterface $inventoryRepository,
        WarehouseMovementsServiceI $movementsService
    ) {
        $this->inventoryRepository = $inventoryRepository;
        $this->movementsService = $movementsService;
    }

    public function processOutput(RemoveWarehouseInventoryStockDTO $dto): ResultPattern
    {
        // 1. Validar stock disponible
        $currentQuantity = $this->inventoryRepository->findQuantityById(
            $dto->getWarehouseInventoryId()
        );

        if ($dto->getQuantity() > $currentQuantity) {
            return ResultPattern::failure(
                "¡Error! No puede retirar cantidad mayor al stock disponible."
            );
        }

        // 2. Actualizar cantidad
        try {
            $newQuantity = $currentQuantity - $dto->getQuantity();

            $updated = $this->inventoryRepository->updateQuantity(
                $dto->getWarehouseInventoryId(),
                $newQuantity
            );

            if (!$updated) {
                return ResultPattern::failure("Error al actualizar el inventario");
            }

            // 3. Registrar movimiento
            $folio = $this->movementsService->generateMovementFolio();

            $movementDTO = new WarehouseMovementsDTO(
                $folio,
                $dto->getWarehouseInventoryId(),
                'OUT',
                $dto->getQuantity(),
                $dto->getReason(),
                $dto->getUserId()
            );

            $this->movementsService->saveWarehouseMovement($movementDTO);

        } catch (\Throwable $th) {
            return ResultPattern::failure($th->getMessage());
        }

        return ResultPattern::success([
            'message' => 'Salida registrada exitosamente',
            'previous_quantity' => $currentQuantity,
            'new_quantity' => $newQuantity,
            'removed' => $dto->getQuantity()
        ]);
    }

    public function getType(): string
    {
        return "OUT";
    }
}

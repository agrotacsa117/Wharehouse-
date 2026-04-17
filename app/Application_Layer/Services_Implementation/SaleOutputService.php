<?php

namespace App\Application_Layer\Services_Implementation;

use App\Contracts\WarehouseOutputStrategy;
use App\Mappers\DTO\RemoveWarehouseInventoryStockDTO;
use App\Application_Layer\ResultPattern;
use App\Contracts\WarehouseInventoryRepositoryInterface;
use App\Contracts\WarehouseMovementsServiceI;
use App\Mappers\DTO\WarehouseMovementsDTO;

class SaleOutputService implements WarehouseOutputStrategy
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
        // 1. Validar stock
        $currentQuantity = $this->inventoryRepository->findQuantityById(
            $dto->getWarehouseInventoryId()
        );

        if ($dto->getQuantity() > $currentQuantity) {
            return ResultPattern::failure(
                "¡Error! Stock insuficiente para la venta."
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
                return ResultPattern::failure("Error al actualizar inventario");
            }

            // 3. Preparar razón con datos de venta
            $reason = sprintf(
                "Venta - Cliente: %d | Factura SAP: %d | %s",
                $dto->getClientId(),
                $dto->getInvoiceId(),
                $dto->getReason()
            );

            // 4. Crear DTO de movimiento
            $folio = $this->movementsService->generateMovementFolio();

            $movementDTO = new WarehouseMovementsDTO(
                $folio,
                $dto->getWarehouseInventoryId(),
                $this->getType(),
                $dto->getQuantity(),
                $reason,
                $dto->getUserId()
            );


            $movementDTO->setClientId($dto->getClientId());
            $movementDTO->setInvoiceSap($dto->getInvoiceId());
            $movementDTO->setOperationDate(new \DateTime($dto->getOperationDate()));

            // 5. Persistir
            $this->movementsService->saveWarehouseMovement($movementDTO);

        } catch (\Throwable $th) {
            return ResultPattern::failure($th->getMessage());
        }

        return ResultPattern::success([
            'message' => 'Venta registrada exitosamente',
            'sold_quantity' => $dto->getQuantity(),
            'remaining_stock' => $newQuantity,
            'client_id' => $dto->getClientId(),
            'invoice_sap' => $dto->getInvoiceId()
        ]);
    }

    public function getType(): string
    {
        return "SALE";
    }
}

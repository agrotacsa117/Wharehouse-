<?php

namespace App\Application_Layer\Services_Implementation;

use App\Application_Layer\ResultPattern;
use App\Contracts\StockInTransitRepositoryI;
use App\Contracts\StockInTransitServiceI;
use App\Contracts\WarehouseInventoryServiceInterface;
use App\Contracts\WarehouseMovementsServiceI;
use App\Enterprise_Layer\StockInTransit;
use App\Mappers\DTO\TransferRequestDTO;
use App\Mappers\DTO\WarehouseMovementsDTO;
use App\Models\WarehouseInventoryModel;

class StockInTransitServiceImplementation implements StockInTransitServiceI
{
    private StockInTransitRepositoryI $stockInTransitRepository;
    private WarehouseInventoryServiceInterface $inventoryService;
    private WarehouseMovementsServiceI $movementsService;

    public function __construct(
        StockInTransitRepositoryI $stockInTransitRepository,
        WarehouseInventoryServiceInterface $inventoryService,
        WarehouseMovementsServiceI $movementsService
    ) {
        $this->stockInTransitRepository = $stockInTransitRepository;
        $this->inventoryService = $inventoryService;
        $this->movementsService = $movementsService;
    }

    public function createTransfer(TransferRequestDTO $dto, int $userId): ResultPattern
    {
        if ($dto->getOriginWarehouseId() === $dto->getDestinationWarehouseId()) {
            return ResultPattern::failure("El almacén origen y destino no pueden ser el mismo.");
        }

        $inventory = $this->inventoryService->getInventoryById($dto->getInventoryId());
        if (!$inventory) {
            return ResultPattern::failure("Inventario no encontrado.");
        }

        $currentStock = (int)($inventory['quantity'] ?? 0);
        if ($dto->getQuantity() > $currentStock) {
            return ResultPattern::failure(
                "Cantidad solicitada ({$dto->getQuantity()}) excede el stock disponible ({$currentStock})."
            );
        }

        try {
            $folio = $this->generateTransferFolio();

            $stockInTransit = new StockInTransit(
                $dto->getInventoryId(),
                $dto->getOriginWarehouseId(),
                $dto->getDestinationWarehouseId(),
                $dto->getQuantity(),
                $folio
            );

            $this->stockInTransitRepository->save($stockInTransit);

            $inventoryModel = WarehouseInventoryModel::find($dto->getInventoryId());
            $warehouseName = $inventory['warehouse']['warehouses_name'] ?? 'sucursal';

            $movementDTO = new WarehouseMovementsDTO(
                $this->movementsService->generateMovementFolio(),
                $dto->getInventoryId(),
                'OUT',
                $dto->getQuantity(),
                "Traslado {$folio} a {$warehouseName}",
                $userId,
                null,
                null,
                $dto->getOperationDate(),
                $dto->getOriginWarehouseId()
            );
            $this->movementsService->saveWarehouseMovement($movementDTO);

            $newQuantity = $currentStock - $dto->getQuantity();
            $inventoryModel->quantity = $newQuantity;
            $inventoryModel->save();

            return ResultPattern::success([
                'folio' => $folio,
                'status' => 'PENDING_RECEPTION'
            ]);

        } catch (\Throwable $e) {
            return ResultPattern::failure("Error al crear traslado: " . $e->getMessage());
        }
    }

    public function confirmReception(int $stockInTransitId, int $userId): ResultPattern
    {
        $stockInTransit = $this->stockInTransitRepository->findById($stockInTransitId);

        if (!$stockInTransit) {
            return ResultPattern::failure("Registro de tránsito no encontrado.");
        }

        if (!$stockInTransit->isPending()) {
            return ResultPattern::failure("Este traslado ya fue " . 
                ($stockInTransit->isReceived() ? "recibido" : "cancelado") . ".");
        }

        try {
            $stockInTransit->confirmReception($userId);
            $this->stockInTransitRepository->updateStatus(
                $stockInTransitId,
                StockInTransit::STATUS_RECEIVED,
                $userId
            );

            $originalInventory = WarehouseInventoryModel::find($stockInTransit->getInventoryId());

            $newInventory = new WarehouseInventoryModel();
            $newInventory->warehouse_id = $stockInTransit->getDestinationWarehouseId();
            $newInventory->product_id = $originalInventory->product_id;
            $newInventory->rack = $originalInventory->rack;
            $newInventory->_level = $originalInventory->_level;
            $newInventory->warehouse_name = $originalInventory->warehouse_name;
            $newInventory->quantity = $stockInTransit->getQuantity();
            $newInventory->lot_number = $originalInventory->lot_number;
            $newInventory->expiration_date = $originalInventory->expiration_date;
            $newInventory->reason = "Recepción de traslado {$stockInTransit->getFolio()}";
            $newInventory->save();

            $movementDTO = new WarehouseMovementsDTO(
                $this->movementsService->generateMovementFolio(),
                $newInventory->id,
                'IN',
                $stockInTransit->getQuantity(),
                "Recepción de traslado {$stockInTransit->getFolio()}",
                $userId,
                null,
                null,
                null,
                $stockInTransit->getOriginWarehouseId()
            );
            $this->movementsService->saveWarehouseMovement($movementDTO);

            return ResultPattern::success([
                'folio' => $stockInTransit->getFolio(),
                'status' => 'RECEIVED',
                'inventory_id' => $newInventory->id
            ]);

        } catch (\Throwable $e) {
            return ResultPattern::failure("Error al confirmar recepción: " . $e->getMessage());
        }
    }

    public function cancelTransfer(int $stockInTransitId): ResultPattern
    {
        $stockInTransit = $this->stockInTransitRepository->findById($stockInTransitId);

        if (!$stockInTransit) {
            return ResultPattern::failure("Registro de tránsito no encontrado.");
        }

        if (!$stockInTransit->isPending()) {
            return ResultPattern::failure("Este traslado ya no puede ser cancelado.");
        }

        try {
            $stockInTransit->cancel();
            $this->stockInTransitRepository->updateStatus(
                $stockInTransitId,
                StockInTransit::STATUS_CANCELLED
            );

            $inventoryModel = WarehouseInventoryModel::find($stockInTransit->getInventoryId());
            $inventoryModel->quantity += $stockInTransit->getQuantity();
            $inventoryModel->save();

            return ResultPattern::success([
                'folio' => $stockInTransit->getFolio(),
                'status' => 'CANCELLED'
            ]);

        } catch (\Throwable $e) {
            return ResultPattern::failure("Error al cancelar traslado: " . $e->getMessage());
        }
    }

    public function getPendingTransfers(int $warehouseId): array
    {
        return $this->stockInTransitRepository->findPendingByWarehouse($warehouseId);
    }

    public function getInTransitStock(?int $warehouseId = null): array
    {
        if ($warehouseId !== null) {
            return $this->stockInTransitRepository->findByOriginWarehouse($warehouseId);
        }

        $models = \App\Models\StockInTransitModel::with(['originWarehouse', 'destinationWarehouse', 'inventory'])
            ->where('status', 'PENDING_RECEPTION')
            ->orderBy('sent_at', 'desc')
            ->get();

        return $models->toArray();
    }

    public function generateTransferFolio(): string
    {
        return $this->stockInTransitRepository->getNextFolio();
    }
}

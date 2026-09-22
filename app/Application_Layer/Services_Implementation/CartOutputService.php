<?php

declare(strict_types=1);

namespace App\Application_Layer\Services_Implementation;

use App\Application_Layer\ResultPattern;
use App\Contracts\CartOutputServiceInterface;
use App\Contracts\WarehouseInventoryServiceInterface;
use App\Contracts\WarehouseOutputDtoMapperI;
use Illuminate\Support\Facades\Log;

class CartOutputService implements CartOutputServiceInterface
{
    private WarehouseInventoryServiceInterface $warehouseInventoryService;

    private WarehouseOutputDtoMapperI $outputDtoMapper;

    public function __construct(
        WarehouseInventoryServiceInterface $warehouseInventoryService,
        WarehouseOutputDtoMapperI $outputDtoMapper
    ) {
        $this->warehouseInventoryService = $warehouseInventoryService;
        $this->outputDtoMapper = $outputDtoMapper;
    }

    public function processBatch(
        string $movementType,
        array $shared,
        array $items,
        int $userId): array
    {
        Log::info('Cart batch iniciado', [
            'movement_type' => $movementType,
            'items_count' => $items,
            'shared' => $shared,
        ]);

        $results = [];
        foreach ($items as $item) {
            $results[] = $this->processItem(
                $movementType,
                $shared,
                $item,
                $userId);
        }

        return $results;
    }

    private function processItem(string $movementType, array $shared, array $item, int $userId): array
    {
        $warehouseInventoryId = (int) $item['warehouseInventoryId'];
        $data = array_merge($shared, $item);

        Log::info('processCartItem: procesando ítem', [
            'movementType' => $movementType,
            'warehouseInventoryId' => $warehouseInventoryId,
            'item' => $item,
            'data' => $data,
        ]);

        try {
            if ($movementType === 'TRANSFER') {
                $result = $this->transferItem($data, $warehouseInventoryId);
            } else {
                $dto = $this->outputDtoMapper->toDto($movementType, $data, $userId);
                $result = $this->warehouseInventoryService->processInventoryOutput($dto);
            }
        } catch (\Throwable $exception) {
            Log::error('processCartItem: ítem falló con excepción', [
                'warehouseInventoryId' => $warehouseInventoryId,
                'reason' => $exception->getMessage(),
            ]);

            return [
                'warehouseInventoryId' => $warehouseInventoryId,
                'ok' => false,
                'message' => $exception->getMessage(),
            ];
        }

        if ($result->isFailure()) {
            Log::error('processCartItem: ítem falló', [
                'warehouseInventoryId' => $warehouseInventoryId,
                'reason' => $result->getError(),
            ]);
        } else {
            Log::info('processCartItem: ítem OK', [
                'warehouseInventoryId' => $warehouseInventoryId,
            ]);
        }

        return [
            'warehouseInventoryId' => $warehouseInventoryId,
            'ok' => $result->isSuccess(),
            'message' => $result->isSuccess() ? 'Movimiento aplicado correctamente' : $result->getError(),
        ];
    }

    private function transferItem(array $data, int $warehouseInventoryId): ResultPattern
    {
        $inventoryResult = $this->warehouseInventoryService->getInventoryById($warehouseInventoryId);

        if ($inventoryResult->isFailure()) {
            return $inventoryResult;
        }

        $inventory = $inventoryResult->getValue();

        if ($inventory->getWarehouseId() == ($data['destination_warehouse_id'] ?? null)) {
            return ResultPattern::failure('No se puede transferir al mismo almacén');
        }

        $transferDTO = $this->outputDtoMapper->toTransferDto($warehouseInventoryId, $inventory, $data);

        return $this->warehouseInventoryService->transferInventory($transferDTO);
    }
}

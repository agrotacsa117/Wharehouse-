<?php

namespace App\Application_Layer\Services_Implementation;

use App\Application_Layer\ResultPattern;
use App\Contracts\ProductServiceInterface;
use App\Contracts\WarehouseInventoryServiceInterface;
use App\Mappers\DTO\Requests\WarehouseInventoryRequestDTO;
use App\Contracts\WarehouseInventoryRepositoryInterface;
use App\Contracts\WarehouseInventoryRequestDTOToWarehouseInventoryMapperI;
use App\Contracts\WarehouseMovementsServiceI;
use App\Contracts\WarehouseStorageServiceInterface;
use App\Enterprise_Layer\WarehouseInventory;
use App\Enterprise_Layer\WarehouseInventoryMovements;
use App\Mappers\DTO\WarehouseInventoryDetailDTO;
use App\Mappers\DTO\WarehouseInventoryOutDetailDTO;
use App\Mappers\DTO\WarehouseMovementsDTO;
use App\Mappers\DTO\RemoveWarehouseInventoryStockDTO;
use App\Mappers\DTO\InventoryStatsByStateDTO;
use App\Mappers\DTO\TransferInventoryDTO;
use App\Mappers\DTO\ExpiredInventoryRankingItemDTO;
use App\Mappers\DTO\WarehouseExpiredRankingDTO;

class WarehouseInventoryServiceImplementation implements WarehouseInventoryServiceInterface
{
    private WarehouseInventoryRepositoryInterface $warehouseInventoryRepository;
    private WarehouseInventoryRequestDTOToWarehouseInventoryMapperI $warehouseInventoryRequestDTOToWarehouseInventory;
    private ProductServiceInterface $productService;
    private WarehouseInventory $warehouseInventory;
    private WarehouseStorageServiceInterface $warehouseStorageService;
    private WarehouseMovementsServiceI $warehouseMovementsService;
    private WarehouseMovementsDTO $warehouseMovementsDTO;

    public function __construct(
        WarehouseInventoryRepositoryInterface $warehouseInventoryRepository,
        WarehouseInventoryRequestDTOToWarehouseInventoryMapperI $warehouseInventoryRequestDTOToWarehouseInventory,
        ProductServiceInterface $productService,
        WarehouseStorageServiceInterface $warehouseStorageService,
        WarehouseMovementsServiceI $warehouseMovementsService
    ) {
        $this->warehouseInventoryRepository = $warehouseInventoryRepository;
        $this->warehouseInventoryRequestDTOToWarehouseInventory = $warehouseInventoryRequestDTOToWarehouseInventory;
        $this->productService = $productService;
        $this->warehouseStorageService = $warehouseStorageService;
        $this->warehouseMovementsService = $warehouseMovementsService;
    }

    public function getAllWarehouseInventories(): array
    {
        $inventory = $this->warehouseInventoryRepository->findAll();

        for ($i = 0; $i < count($inventory); $i++) {
            $inventory[$i] = new WarehouseInventoryDetailDTO(
                $inventory[$i]['warehouse_name'],
                $inventory[$i]['product_id'],
                $inventory[$i]['warehouse_id'],
                $inventory[$i]['warehouse']['warehouses_name'],
                $inventory[$i]['_level'],
                $inventory[$i]['rack'],
                $inventory[$i]['quantity'],
                $inventory[$i]['expiration_date']
            );
        }

        return $inventory;
    }

    public function getAllInventoryForManagement(): array
    {
        return $this->warehouseInventoryRepository->findAll();
    }

    public function create(
        WarehouseInventoryRequestDTO $warehouseInventoryDTO
    ): ResultPattern {

        $warehouseName =  $this->warehouseStorageService
        ->getWarehouseNameById(
            $warehouseInventoryDTO->getWarehouseId()
        );

        if ($this->existProductInInventory(
            $warehouseInventoryDTO->getWarehouseId(),
            $warehouseInventoryDTO->getProductId()
        )) {
            return ResultPattern::failure(
                "Error: producto ".$warehouseInventoryDTO->getProductId()
                ." ya existe en el inventario de  ".$warehouseName
            );
        }

        $this->warehouseInventory = $this->warehouseInventoryRequestDTOToWarehouseInventory
        ->convertWarehouseInventoryRequestDTOToWarehouseInventory(
            $warehouseInventoryDTO
        );

        $productName =  $this->productService
        ->getProductNameById(
            $this->warehouseInventory->getProductId()
        )->getValue();

        $this->warehouseInventory->setWarehouseName($productName);

        try {
            $this->warehouseInventory =  $this->warehouseInventoryRepository->save(
                $this->warehouseInventory
            );

            $folio = $this->warehouseMovementsService
            ->generateMovementFolio();

            $this->warehouseMovementsDTO = $this->generateWarehouseMovementsDTO(
                $folio,
                $this->warehouseInventory->getId(),
                "IN",
                $warehouseInventoryDTO->getQuantity(),
                $warehouseInventoryDTO->getReason(),
                auth()->id()
            );


            $this->warehouseMovementsService->saveWarehouseMovement(
                $this->warehouseMovementsDTO
            );

        } catch (\Throwable $th) {
            return ResultPattern::failure($th->getMessage());
        }

        return ResultPattern::success(null);
    }

    public function update(
        WarehouseInventoryRequestDTO $warehouseInventory
    ): ResultPattern {
        return ResultPattern::success(null);
    }

    public function delete(int $id): ResultPattern
    {

        return ResultPattern::success(null);
    }


    public function existProductInInventory(
        int $warehouseId,
        string $productId
    ): bool {
        return $this->warehouseInventoryRepository
        ->existById(
            $warehouseId,
            $productId
        );
    }

    public function getWarehouseIdsWithInventory(): array
    {
        return $this->warehouseInventoryRepository
        ->countDistinctByWarehouseId();
    }


    public function getWarehouseInventoryByWarehouseId(
        int $warehouseId
    ): array {
        $inventory =  $this->warehouseInventoryRepository
        ->findInventoryByWarehouseId(
            $warehouseId
        );

        for ($i = 0; $i < count($inventory) ; $i++) {
            $inventory[$i] = new WarehouseInventoryOutDetailDTO(
                $inventory[$i]['id'],
                $inventory[$i]['warehouse_id'],
                $inventory[$i]['rack'],
                $inventory[$i]['_level'],
                $inventory[$i]['product_id'],
                $inventory[$i]['warehouse_name'],
                $inventory[$i]['quantity'],
                $inventory[$i]['lot_number'],
                $inventory[$i]['expiration_date']
            );
        }

        return $inventory;
    }

    public function processInventoryOutput(
        RemoveWarehouseInventoryStockDTO $output
    ): ResultPattern {

        $quantity = $this->warehouseInventoryRepository
        ->findQuantityById(
            $output->getWarehouseInventoryId()
        );

        if ($output->getQuantity() > $quantity) {
            return ResultPattern::failure(
                "¡Error! No puede retirar una cantidad "
                ."mayor al stock disponible."
            );
        }

        $quantityUpdated = $quantity - $output->getQuantity();

        $updated = $this->warehouseInventoryRepository->updateQuantity(
            $output->getWarehouseInventoryId(),
            $quantityUpdated
        );

        $folio = $this->warehouseMovementsService
        ->generateMovementFolio();

        $this->warehouseMovementsDTO =  $this->generateWarehouseMovementsDTO(
            $folio,
            $output->getWarehouseInventoryId(),
            'OUT',
            $output->getQuantity(),
            $output->getReason(),
            auth()->id()
        );

        $this->warehouseMovementsService->saveWarehouseMovement(
            $this->warehouseMovementsDTO
        );

        if (!$updated) {
            return ResultPattern::failure(
                "Error: no fue posible actualizar el inventario"
            );
        }

        return ResultPattern::success($output);
    }

    public function getInventoryStatsByState(): array
    {
        $stats  = $this->warehouseInventoryRepository->getInventoryStatsByState();

        foreach ($stats as $key => $stat) {
            $stats[$key] = new InventoryStatsByStateDTO(
                (int)($stat['state']),
                (int)($stat['total_stock'])
            );
        }
        return $stats;
    }

    public function getInventoryStatsByStateAndWarehouse(): array
    {
        $stats  = $this->warehouseInventoryRepository->getInventoryStatsByStateAndWarehouse();

        $groupedStats = [
            1 => [],
            2 => [],
            3 => []
        ];

        foreach ($stats as $stat) {
            $state = (int)($stat['state']);
            $groupedStats[$state][] = new InventoryStatsByStateDTO(
                $state,
                (int)($stat['total_stock']),
                $stat['warehouses_name'] ?? ''
            );
        }

        return $groupedStats;
    }

    public function getInventoryByState(int $state): array
    {
        return $this->warehouseInventoryRepository->getInventoryByState($state);
    }

    public function getInventoryByProductId(string $productId): array
    {
        return $this->warehouseInventoryRepository->findByProductId($productId);
    }

    public function getInventoryByWarehouse(int $warehouseId, ?string $rack = null, ?int $level = null): array
    {
        return $this->warehouseInventoryRepository->findByWarehouse($warehouseId, $rack, $level);
    }

    public function getExpiredInventory(): array
    {
        return $this->warehouseInventoryRepository->findExpired();
    }

    public function getInventoryById(int $id): ?array
    {
        return $this->warehouseInventoryRepository->findById($id);
    }

    public function updateInventory(\App\Mappers\DTO\UpdateInventoryDTO $dto): ResultPattern
    {
        $currentInventory = $this->warehouseInventoryRepository->findById($dto->getId());

        if (!$currentInventory) {
            return ResultPattern::failure("Inventario no encontrado.");
        }

        $quantityDiff = $dto->getQuantity() - (int)$currentInventory['quantity'];

        $hasChanges = 
            $quantityDiff !== 0 ||
            $dto->getRack() !== $currentInventory['rack'] ||
            $dto->getLevel() !== (int)$currentInventory['_level'] ||
            $dto->getLotNumber() !== $currentInventory['lot_number'] ||
            $dto->getExpirationDate() !== $currentInventory['expiration_date'];

        try {
            $updated = $this->warehouseInventoryRepository->updateById($dto->getId(), [
                'rack' => $dto->getRack(),
                '_level' => $dto->getLevel(),
                'lot_number' => $dto->getLotNumber(),
                'quantity' => $dto->getQuantity(),
                'expiration_date' => $dto->getExpirationDate(),
                'updated_at' => now()
            ]);

            if (!$updated) {
                return ResultPattern::failure("No se pudo actualizar el inventario.");
            }

            if ($hasChanges) {
                $folio = $this->warehouseMovementsService->generateMovementFolio();
                $reason = "Edición: " . $dto->getReason();

                $this->warehouseMovementsDTO = $this->generateWarehouseMovementsDTO(
                    $folio,
                    $dto->getId(),
                    'ADJUSTMENT',
                    abs($quantityDiff),
                    $reason,
                    auth()->id()
                );

                $this->warehouseMovementsService->saveWarehouseMovement(
                    $this->warehouseMovementsDTO
                );
            }

        } catch (\Throwable $th) {
            return ResultPattern::failure($th->getMessage());
        }

        return ResultPattern::success(null);
    }

    public function transferInventory(TransferInventoryDTO $dto): ResultPattern
    {
        $fromWarehouseName = $this->warehouseStorageService->getWarehouseNameById($dto->getFromWarehouseId());
        $toWarehouseName = $this->warehouseStorageService->getWarehouseNameById($dto->getToWarehouseId());

        if ($dto->getFromWarehouseId() === $dto->getToWarehouseId()) {
            return ResultPattern::failure("No se puede transferir al mismo almacén.");
        }

        try {
            $result = $this->warehouseInventoryRepository->transferInventory(
                $dto->getInventoryId(),
                $dto->getFromWarehouseId(),
                $dto->getToWarehouseId(),
                $dto->getRack(),
                $dto->getLevel(),
                $dto->getLotNumber(),
                $dto->getQuantity()
            );

            if (!$result['success']) {
                return ResultPattern::failure($result['error']);
            }

            $folio = $this->warehouseMovementsService->generateMovementFolio();
            $this->warehouseMovementsDTO = $this->generateWarehouseMovementsDTO(
                $folio,
                $dto->getInventoryId(),
                'TRANSFER',
                $dto->getQuantity(),
                "Traslado de {$fromWarehouseName} a {$toWarehouseName}: " . $dto->getReason(),
                auth()->id()
            );
            $this->warehouseMovementsService->saveWarehouseMovement($this->warehouseMovementsDTO);

        } catch (\Throwable $th) {
            return ResultPattern::failure($th->getMessage());
        }

        return ResultPattern::success([
            'newInventoryId' => $result['newInventoryId'],
            'remainingQuantity' => $result['remainingQuantity'] ?? 0
        ]);
    }

    public function generateWarehouseMovementsDTO(
        string $folio,
        int $warehouseInventoryId,
        string $typeMovement,
        int $quantity,
        string $reason,
        int $userId
    ): WarehouseMovementsDTO {

        return new WarehouseMovementsDTO(
            $folio,
            $warehouseInventoryId,
            $typeMovement,
            $quantity,
            $reason,
            $userId
        );
    }

    public function getExpiredInventoryRanking(): array
    {
        $rawResults = $this->warehouseInventoryRepository->findExpiredRanking();

        $groupedByWarehouse = [];

        foreach ($rawResults as $row) {
            $warehouseId = $row['warehouse_id'];

            if (!isset($groupedByWarehouse[$warehouseId])) {
                $groupedByWarehouse[$warehouseId] = [
                    'warehouseId' => $warehouseId,
                    'warehouseName' => $row['warehouse_name'],
                    'items' => []
                ];
            }

            if ($row['row_num'] <= 3) {
                $groupedByWarehouse[$warehouseId]['items'][] = [
                    'id' => (int)$row['id'],
                    'warehouseId' => (int)$row['warehouse_id'],
                    'productId' => $row['product_id'],
                    'productName' => $row['product_name'] ?? '',
                    'rack' => $row['rack'],
                    'level' => (int)$row['_level'],
                    'quantity' => (int)$row['quantity'],
                    'lotNumber' => $row['lot_number'],
                    'remainingDays' => (int)$row['remaining_days'],
                    'rank' => (int)$row['row_num']
                ];
            }
        }

        $result = [];
        foreach ($groupedByWarehouse as $warehouse) {
            $result[] = [
                'warehouseId' => $warehouse['warehouseId'],
                'warehouseName' => $warehouse['warehouseName'],
                'expiredItems' => $warehouse['items']
            ];
        }

        return $result;
    }
}

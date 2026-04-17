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
use App\Mappers\DTO\ExpiredInventoryDTO;
use App\Mappers\DTO\WarehouseInventoryDetailDTO;
use App\Mappers\DTO\WarehouseInventoryOutDetailDTO;
use App\Mappers\DTO\WarehouseMovementsDTO;
use App\Mappers\DTO\RemoveWarehouseInventoryStockDTO;
use App\Mappers\DTO\InventoryStatsByStateDTO;
use App\Mappers\DTO\TransferInventoryDTO;
use App\Mappers\DTO\UpdateInventoryDTO;
use LDAP\Result;
use App\Contracts\WarehouseInventoryToWarehouseInventoryOutDetailDTOMapperI;
use App\Contracts\WarehouseOutputStrategyFactoryInterface;

class WarehouseInventoryServiceImplementation implements WarehouseInventoryServiceInterface
{
    private WarehouseInventoryRepositoryInterface $warehouseInventoryRepository;
    private WarehouseInventoryRequestDTOToWarehouseInventoryMapperI $warehouseInventoryRequestDTOToWarehouseInventory;
    private ProductServiceInterface $productService;
    private WarehouseInventory $warehouseInventory;
    private WarehouseStorageServiceInterface $warehouseStorageService;
    private WarehouseMovementsServiceI $warehouseMovementsService;
    private WarehouseMovementsDTO $warehouseMovementsDTO;
    private WarehouseInventoryToWarehouseInventoryOutDetailDTOMapperI $warehouseInventoryToWarehouseInventoryOutDetailDTOMapper;
    //private WarehouseOutputStrategyFactoryInterface $warehouseOutputStrategyFactory;

    public function __construct(
        WarehouseInventoryRepositoryInterface $warehouseInventoryRepository,
        WarehouseInventoryRequestDTOToWarehouseInventoryMapperI $warehouseInventoryRequestDTOToWarehouseInventory,
        ProductServiceInterface $productService,
        WarehouseStorageServiceInterface $warehouseStorageService,
        WarehouseMovementsServiceI $warehouseMovementsService,
        WarehouseInventoryToWarehouseInventoryOutDetailDTOMapperI $warehouseInventoryToWarehouseInventoryOutDetailDTOMapper
        //WarehouseOutputStrategyFactoryInterface $warehouseOutputStrategyFactory
    ) {
        $this->warehouseInventoryRepository = $warehouseInventoryRepository;
        $this->warehouseInventoryRequestDTOToWarehouseInventory = $warehouseInventoryRequestDTOToWarehouseInventory;
        $this->productService = $productService;
        $this->warehouseStorageService = $warehouseStorageService;
        $this->warehouseMovementsService = $warehouseMovementsService;
        $this->warehouseInventoryToWarehouseInventoryOutDetailDTOMapper = $warehouseInventoryToWarehouseInventoryOutDetailDTOMapper;
        //$this->warehouseOutputStrategyFactory = $warehouseOutputStrategyFactory;
    }

    public function getAllWarehouseInventories(): array
    {
        $inventory = $this->warehouseInventoryRepository->findAll();
        return $this->generateWarehouseInventoryDetailDTO($inventory);
    }

    public function getAllInventoryForManagement(): array
    {
        return $this->warehouseInventoryRepository->findAll();
    }

    public function saveInventory(WarehouseInventoryRequestDTO $warehouseInventoryDTO): ResultPattern
    {
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
            $this->warehouseInventory = $this->warehouseInventoryRepository->save(
                $this->warehouseInventory
            );

            return ResultPattern::success(
                $this->warehouseInventory
            );

        } catch (\Throwable $th) {
            return ResultPattern::failure(
                $th->getMessage()
            );
        }
    }

    public function create(
        WarehouseInventoryRequestDTO $warehouseInventoryDTO
    ): ResultPattern {

        try {
            $result = $this->saveInventory($warehouseInventoryDTO);

            if ($result->isFailure()) {
                return $result;
            }

            $this->warehouseInventory = $result->getValue();

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

        return ResultPattern::success($warehouseInventoryDTO);
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
        $inventory = $this->warehouseInventoryRepository->getInventoryByState($state);
        return $this->generateWarehouseInventoryDetailDTO($inventory);
    }

    public function updateInventory(
        UpdateInventoryDTO $updateInventoryDTO
    ): ResultPattern {

        $currentInventory = $this->warehouseInventoryRepository->findById(
            $updateInventoryDTO->getId()
        );

        if (!$currentInventory) {
            return ResultPattern::failure(
                "Inventario no encontrado."
            );
        }

        $quantityDiff = $updateInventoryDTO->getQuantity()
        - $currentInventory->getQuantity();

        $hasChanges =
            $quantityDiff !== 0 ||
            $updateInventoryDTO->getRack()
            !== $currentInventory->getRack() ||
            $updateInventoryDTO->getLevel()
            !== $currentInventory->getLevel() ||
            $updateInventoryDTO->getLotNumber()
            !== $currentInventory->getLotNumber() ||
            $updateInventoryDTO->getExpirationDate()
            !== $currentInventory->getExpirationDate();

        if (!$hasChanges) {
            ResultPattern::failure("No se realizó ningun cambio");
        }

        try {
            $updated = $this->warehouseInventoryRepository->updateById($updateInventoryDTO->getId(), [
                'rack' => $updateInventoryDTO->getRack(),
                '_level' => $updateInventoryDTO->getLevel(),
                'lot_number' => $updateInventoryDTO->getLotNumber(),
                'quantity' => $updateInventoryDTO->getQuantity(),
                'expiration_date' => $updateInventoryDTO->getExpirationDate(),
                'updated_at' => now()
            ]);

            if (!$updated) {
                return ResultPattern::failure("No se pudo actualizar el inventario.");
            }

            if ($hasChanges) {
                $folio = $this->warehouseMovementsService->generateMovementFolio();
                $reason = "Edición: " . $updateInventoryDTO->getReason();

                $this->warehouseMovementsDTO = $this->generateWarehouseMovementsDTO(
                    $folio,
                    $updateInventoryDTO->getId(),
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

    public function transferInventory(
        TransferInventoryDTO $transferInventoryDTO
    ): ResultPattern {


        if ($transferInventoryDTO->getFromWarehouseId() === $transferInventoryDTO->getToWarehouseId()) {
            return ResultPattern::failure("No se puede transferir al mismo almacén.");
        }

        $fromWarehouseName = $this->warehouseStorageService->getWarehouseNameById(
            $transferInventoryDTO->getFromWarehouseId()
        );

        $toWarehouseName = $this->warehouseStorageService->getWarehouseNameById(
            $transferInventoryDTO->getToWarehouseId()
        );


        try {
            $result = $this->warehouseInventoryRepository->transferInventory(
                $transferInventoryDTO->getInventoryId(),
                $transferInventoryDTO->getFromWarehouseId(),
                $transferInventoryDTO->getToWarehouseId(),
                $transferInventoryDTO->getRack(),
                $transferInventoryDTO->getLevel(),
                $transferInventoryDTO->getLotNumber(),
                $transferInventoryDTO->getQuantity()
            );

            if (!$result['success']) {
                return ResultPattern::failure($result['error']);
            }

            $folio = $this->warehouseMovementsService->generateMovementFolio();
            $this->warehouseMovementsDTO = $this->generateWarehouseMovementsDTO(
                $folio,
                $transferInventoryDTO->getInventoryId(),
                'TRANSFER',
                $transferInventoryDTO->getQuantity(),
                "Traslado de {$fromWarehouseName} a {$toWarehouseName}: " . $transferInventoryDTO->getReason(),
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

    public function generateWarehouseInventoryDetailDTO(
        array $inventory
    ): array {

        for ($i = 0; $i < count($inventory) ; $i++) {
            $inventory[$i] = new WarehouseInventoryDetailDTO(
                $inventory[$i]['id'],
                $inventory[$i]['warehouse_name'],
                $inventory[$i]['product_id'],
                $inventory[$i]['warehouse_id'],
                $inventory[$i]['warehouse']['warehouses_name']
                ??  $inventory[$i]['warehouses_name'] ?? null,
                $inventory[$i]['_level'],
                $inventory[$i]['rack'],
                $inventory[$i]['quantity'],
                $inventory[$i]['expiration_date'],
                $inventory[$i]['lot_number'],
                $inventory[$i]['days_remaining'] ?? null
            );
        }

        return  $inventory;
    }

    public function getExpiredInventory(): array
    {
        $expiratedProducts = $this->warehouseInventoryRepository->findExpired();


        for ($i = 0; $i < count($expiratedProducts); $i++) {
            $inventory = $expiratedProducts[$i];

            $expiratedProducts[$i] = new ExpiredInventoryDTO(
                $inventory["product_id"],
                $inventory["product_name"],
                $inventory["warehouse_name"],
                $inventory["quantity"],
                $inventory["lot_number"],
                $inventory["expiration_date"],
                $inventory["expired_days"]
            );
        }

        return $expiratedProducts;
    }

    public function relocateInventory(
        int $id,
        string $rack,
        int $level
    ): ResultPattern {

        try {
            $updated = $this->warehouseInventoryRepository->updateInventoryLocation(
                $id,
                $rack,
                $level
            );

            if (!$updated) {
                ResultPattern::failure(
                    "¡Error: no fue posible "
                    ."modificar campos de ubicación!"
                );
            }

        } catch (\Throwable $th) {
            return ResultPattern::failure($th->getMessage());
        }

        return ResultPattern::success(true);
    }

    public function getInventoryById(int $id): ResultPattern
    {
        $warehouseInventory = $this->warehouseInventoryRepository->findById($id);

        if (!$warehouseInventory) {
            return ResultPattern::failure(
                "¡No se encontro ningun inventario "
                ."registrado con este id ".$id
            );
        }

        $warehouseInventoryOutDetailDTO =
        $this->warehouseInventoryToWarehouseInventoryOutDetailDTOMapper
        ->convertToOutDetailDTO(
            $warehouseInventory
        );

        return ResultPattern::success($warehouseInventoryOutDetailDTO);
    }

}

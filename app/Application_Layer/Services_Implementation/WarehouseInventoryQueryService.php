<?php

namespace App\Application_Layer\Services_Implementation;

use App\Application_Layer\ResultPattern;
use App\Contracts\WarehouseInventoryQueryServiceI;
use App\Contracts\WarehouseInventoryRepositoryInterface;
use App\Contracts\WarehouseInventoryServiceI;
use App\Contracts\WarehouseInventoryToWarehouseInventoryOutDetailDTOMapperI;
use App\Enterprise_Layer\WarehouseInventory;
use App\Mappers\DTO\RemoveWarehouseInventoryStockDTO;
use App\Mappers\DTO\Requests\WarehouseInventoryRequestDTO;
use App\Mappers\DTO\WarehouseInventoryOutDetailDTO;
use Illuminate\Support\Facades\Log;

class WarehouseInventoryQueryService implements WarehouseInventoryQueryServiceI
{
    private WarehouseInventoryRepositoryInterface $warehouseInventoryRepository;

    private WarehouseInventoryToWarehouseInventoryOutDetailDTOMapperI $warehouseInventoryToWarehouseInventoryOutDetailDTOMapper;

    private WarehouseInventoryServiceI $warehouseInventoryService;

    public function __construct(
        WarehouseInventoryRepositoryInterface $warehouseInventoryRepository,
        WarehouseInventoryToWarehouseInventoryOutDetailDTOMapperI $warehouseInventoryToWarehouseInventoryOutDetailDTOMapper,
        WarehouseInventoryServiceI $warehouseInventoryService
    ) {
        $this->warehouseInventoryRepository = $warehouseInventoryRepository;
        $this->warehouseInventoryToWarehouseInventoryOutDetailDTOMapper = $warehouseInventoryToWarehouseInventoryOutDetailDTOMapper;
        $this->warehouseInventoryService = $warehouseInventoryService;
    }

    public function getInventoryById(int $id): ResultPattern
    {
        $warehouseInventory = $this->warehouseInventoryRepository->findById($id);

        if (! $warehouseInventory) {
            return ResultPattern::failure(
                '¡No se encontro ningun inventario '
                .'registrado con este id '.$id
            );
        }

        $warehouseInventoryOutDetailDTO =
        $this->warehouseInventoryToWarehouseInventoryOutDetailDTOMapper
            ->convertToOutDetailDTO(
                $warehouseInventory
            );

        return ResultPattern::success($warehouseInventoryOutDetailDTO);
    }

    public function relocateInventory(
        int $id,
        ?string $rack,
        ?int $level,
        ?int $module,
        ?int $bay,
        ?int $platform
    ): ResultPattern {
        try {
            $updated = $this->warehouseInventoryRepository->updateInventoryLocation(
                $id,
                $rack,
                $level,
                $module,
                $bay,
                $platform
            );

            if (! $updated) {
                return ResultPattern::failure(
                    '¡Error: no fue posible '
                    .'modificar campos de ubicación!'
                );
            }

        } catch (\Throwable $th) {
            return ResultPattern::failure($th->getMessage());
        }

        return ResultPattern::success(true);
    }

    public function updateOrCreateInventory(
        RemoveWarehouseInventoryStockDTO $removeWarehouseInventoryStockDTO,
        WarehouseInventoryOutDetailDTO $warehouseInventoryOutDetailDTO
    ): ResultPattern {

        Log::info(
            'The removeWarehouseInventoryStockDTO is: ',
            [$removeWarehouseInventoryStockDTO]
        );

        Log::info(
            'The warehouseInventoryOutDetailDTO is: ',
            [$warehouseInventoryOutDetailDTO]
        );
        $manufacturingDate = $warehouseInventoryOutDetailDTO
            ->getManufacturingDate();

        if ($removeWarehouseInventoryStockDTO
            ->getManufacturingDate()) {
            $manufacturingDate = $removeWarehouseInventoryStockDTO
                ->getManufacturingDate();
        }

        Log::info('WarehouseInventoryQueryService::updateOrCreateInventory: buscando destino', [
            'warehouseId' => $removeWarehouseInventoryStockDTO->getWarehouseId(),
            'rack' => $removeWarehouseInventoryStockDTO->getRack(),
            'level' => $removeWarehouseInventoryStockDTO->getLevel(),
            'module' => $removeWarehouseInventoryStockDTO->getModule(),
            'bay' => $removeWarehouseInventoryStockDTO->getBay(),
            'platform' => $removeWarehouseInventoryStockDTO->getPlatform(),
            'productCode' => $warehouseInventoryOutDetailDTO->getProductCode(),
            'lotNumber' => $warehouseInventoryOutDetailDTO->getLotNumber(),
            'manufacturingDate' => $manufacturingDate,
        ]);

        $warehouseInventoryEntity = $this
            ->warehouseInventoryRepository
            ->findSpecificInventory(
                $removeWarehouseInventoryStockDTO->getWarehouseId(),
                $removeWarehouseInventoryStockDTO->getRack(),
                $removeWarehouseInventoryStockDTO->getLevel(),
                $removeWarehouseInventoryStockDTO->getModule(),
                $removeWarehouseInventoryStockDTO->getPlatform(),
                $removeWarehouseInventoryStockDTO->getBay(),
                $warehouseInventoryOutDetailDTO->getProductCode(),
                $warehouseInventoryOutDetailDTO->getLotNumber(),
                $manufacturingDate
            );

        if (! $warehouseInventoryEntity) {
            Log::info('WarehouseInventoryQueryService::updateOrCreateInventory: destino no existe, se creara uno nuevo', [
                'warehouseId' => $removeWarehouseInventoryStockDTO->getWarehouseId(),
                'rack' => $removeWarehouseInventoryStockDTO->getRack(),
                'level' => $removeWarehouseInventoryStockDTO->getLevel(),
                'quantity' => $removeWarehouseInventoryStockDTO->getQuantity(),
            ]);

            $timeZone = new \DateTimeZone('America/Mexico_City');
            $now = new \DateTime('now', $timeZone);
            $date = new \DateTime(
                $warehouseInventoryOutDetailDTO->getExpirationDate()
            );

            $date->format('Y-m-d');

            $warehouseInventory = new WarehouseInventory(
                $removeWarehouseInventoryStockDTO->getWarehouseId(),
                $warehouseInventoryOutDetailDTO->getProductCode(),
                $removeWarehouseInventoryStockDTO->getRack(),
                $removeWarehouseInventoryStockDTO->getLevel(),
                $now,
                $now,
                $warehouseInventoryOutDetailDTO->getProductName(),
                $removeWarehouseInventoryStockDTO->getQuantity(),
                $warehouseInventoryOutDetailDTO->getLotNumber(),
                $removeWarehouseInventoryStockDTO->getReason(),
                $date,
                $warehouseInventoryOutDetailDTO->getTransferFolio() !== null
                    ? (int) $warehouseInventoryOutDetailDTO->getTransferFolio()
                    : null
            );

            //
            $warehouseInventory
                ->setModule(
                    $removeWarehouseInventoryStockDTO
                        ->getModule());

            $warehouseInventory->setBay(
                $removeWarehouseInventoryStockDTO
                    ->getBay()
            );

            $warehouseInventory->setPlatform(
                $removeWarehouseInventoryStockDTO
                    ->getPlatform()
            );

            $manufacturingDate = $warehouseInventoryOutDetailDTO
                ->getManufacturingDate();

            if ($removeWarehouseInventoryStockDTO
                ->getManufacturingDate()) {
                $manufacturingDate = $removeWarehouseInventoryStockDTO
                    ->getManufacturingDate();
            }

            $warehouseInventory->setManufacturingDate(
                $manufacturingDate ? new \DateTime($manufacturingDate) : null
            );

            Log::info(
                'The inventory to be stored 
                in the storage: ',
                [$warehouseInventory]
            );

            $warehouseInventory = $this->warehouseInventoryRepository
                ->save($warehouseInventory);

            Log::info('WarehouseInventoryQueryService::updateOrCreateInventory: destino creado', [
                'destinationInventoryId' => $warehouseInventory->getId(),
                'quantity' => $removeWarehouseInventoryStockDTO->getQuantity(),
            ]);

            return ResultPattern::success(
                $warehouseInventory
            );
        }

        if ($warehouseInventoryEntity) {
            $finalQuantity = $warehouseInventoryEntity
                ->getQuantity()
            + $removeWarehouseInventoryStockDTO->getQuantity();

            Log::info('WarehouseInventoryQueryService::updateOrCreateInventory: destino existente, se hara merge de cantidad', [
                'destinationInventoryId' => $warehouseInventoryEntity->getId(),
                'previousQuantity' => $warehouseInventoryEntity->getQuantity(),
                'addedQuantity' => $removeWarehouseInventoryStockDTO->getQuantity(),
                'finalQuantity' => $finalQuantity,
            ]);

            $updated = $this->warehouseInventoryRepository->updateQuantity(
                $warehouseInventoryEntity->getId(),
                $finalQuantity
            );

            if (! $updated) {
                Log::error('WarehouseInventoryQueryService::updateOrCreateInventory: fallo al actualizar cantidad del destino', [
                    'destinationInventoryId' => $warehouseInventoryEntity->getId(),
                    'finalQuantity' => $finalQuantity,
                ]);
            }
        }

        return ResultPattern::success($warehouseInventoryEntity);
    }

    public function saveInventory(
        WarehouseInventoryRequestDTO $warehouseInventoryDTO): ResultPattern
    {
        $result = $this
            ->warehouseInventoryService
            ->saveInventory(
                $warehouseInventoryDTO);

        if ($result->isFailure()) {
            return $result;
        }

        return ResultPattern::success($result->getValue());
    }

    public function desactiveInventory(
        int $warehouseInventoryId): void
    {
        $this->warehouseInventoryRepository
            ->updateActiveInventory(
                $warehouseInventoryId
            );
    }
}

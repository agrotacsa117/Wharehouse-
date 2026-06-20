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
        string $rack,
        int $level
    ): ResultPattern {
        try {
            $updated = $this->warehouseInventoryRepository->updateInventoryLocation(
                $id,
                $rack,
                $level
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
                $warehouseInventoryOutDetailDTO->getLotNumber()
            );

        if (! $warehouseInventoryEntity) {
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
                null
            );

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

            $warehouseInventory->setManufacturingDate(
                new \DateTime(
                    $warehouseInventoryOutDetailDTO
                        ->getManufacturingDate()
                )
            );

            $warehouseInventory = $this->warehouseInventoryRepository
                ->save($warehouseInventory);

            return ResultPattern::success(
                $warehouseInventory
            );
        } else {

            $finalQuantity = $warehouseInventoryEntity
                ->getQuantity()
            + $removeWarehouseInventoryStockDTO->getQuantity();

            $this->warehouseInventoryRepository->updateQuantity(
                $warehouseInventoryEntity->getId(),
                $finalQuantity
            );
        }

        return ResultPattern::success(true);
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

<?php

namespace App\Application_Layer\Services_Implementation;

use App\Contracts\WarehouseOutputStrategy;
use App\Mappers\DTO\RemoveWarehouseInventoryStockDTO;
use App\Application_Layer\ResultPattern;
use App\Contracts\WarehouseInventoryRepositoryInterface;
use App\Contracts\WarehouseInventoryServiceInterface;
use App\Contracts\WarehouseMovementsServiceI;
use App\Mappers\DTO\WarehouseInventoryOutDetailDTO;
use App\Mappers\DTO\WarehouseMovementsDTO;
use App\Contracts\WarehouseInventoryQueryServiceI;
use App\Application_Layer\Services_Implementation\BaseOutputService;
use App\Contracts\WarehouseInventoryToWarehouseInventoryOutDetailDTOMapperI;
use App\Contracts\WarehouseStorageServiceInterface;

class InternalRelocationService extends BaseOutputService
{
    private ResultPattern $result;
    private WarehouseInventoryOutDetailDTO $warehouseInventoryOutDetailDTO;
    private WarehouseMovementsDTO $warehouseMovementsDTO;
    private WarehouseInventoryQueryServiceI $warehouseInventoryQueryService;
    private WarehouseInventoryToWarehouseInventoryOutDetailDTOMapperI $warehouseInventoryToWarehouseInventoryOutDetailDTOMapper;
    private WarehouseStorageServiceInterface $warehouseStorageService;

    public function __construct(
        WarehouseInventoryQueryServiceI $warehouseInventoryQueryService,
        WarehouseMovementsServiceI $warehouseMovementsService,
        WarehouseInventoryRepositoryInterface $warehouseInventoryRepository,
        WarehouseStorageServiceInterface $warehouseStorageService
    ) {
        $this->warehouseInventoryQueryService = $warehouseInventoryQueryService;
        $this->warehouseMovementsService = $warehouseMovementsService;
        $this->warehouseStorageService = $warehouseStorageService;
        parent::__construct(
            $warehouseInventoryRepository,
            $warehouseMovementsService
        );
    }

    public function processOutput(
        RemoveWarehouseInventoryStockDTO $removeWarehouseInventoryStockDTO
    ): ResultPattern {

        $warehouseDestination = $this->warehouseStorageService
        ->getWarehouseNameById(
            $removeWarehouseInventoryStockDTO->getWarehouseId()
        );
        $this->result =  $this->validateStockAvailability(
            $removeWarehouseInventoryStockDTO
        );

        if ($this->result->isFailure()) {
            return $this->result;
        }


        $this->result = $this->warehouseInventoryQueryService
        ->getInventoryById(
            $removeWarehouseInventoryStockDTO->getWarehouseInventoryId()
        );


        if ($this->result->isFailure()) {
            return $this->result;
        }

        $inventoryDTO = $this->result->getValue();
        if (
            !$removeWarehouseInventoryStockDTO->getRack()
            || $removeWarehouseInventoryStockDTO->getRack() === ""
        ) {
            return ResultPattern::failure(
                "¡Error el campo rack no ".
                "puede estar vacio!"
            );
        }

        if (!$removeWarehouseInventoryStockDTO->getLevel()
            || $removeWarehouseInventoryStockDTO->getLevel() === "") {
            return ResultPattern::failure(
                "¡Error el campo nivel no ".
                "puede estar vacio!"
            );
        }

        $this->warehouseInventoryOutDetailDTO = $this->result->getValue();

        $hasChange =
        $this->warehouseInventoryOutDetailDTO
        ->getLevel() !== $removeWarehouseInventoryStockDTO
        ->getLevel()
        || $this->warehouseInventoryOutDetailDTO->getRack()
        !== $removeWarehouseInventoryStockDTO->getRack();

        $successfulReduction =  $this->reduceStock(
            $this->warehouseInventoryOutDetailDTO->getQuantity(),
            $removeWarehouseInventoryStockDTO
        );

        if ($successfulReduction->isFailure()) {
            return  $successfulReduction;
        }




        if (!$hasChange) {
            return ResultPattern::success(
                "¡No hay cambios detectados!"
            );
        }

        try {

            if (
                $removeWarehouseInventoryStockDTO
                ->getWarehouseId()
            ) {
                $this->warehouseInventoryQueryService
                ->updateOrCreateInventory(
                    $removeWarehouseInventoryStockDTO,
                    $this->warehouseInventoryOutDetailDTO
                );
            }

            // $this->warehouseInventoryQueryService
            //  ->relocateInventory(
            //      $removeWarehouseInventoryStockDTO->getWarehouseInventoryId(),
            //      $removeWarehouseInventoryStockDTO->getRack(),
            //      $removeWarehouseInventoryStockDTO->getLevel()
            //  );


            $folio = $this->warehouseMovementsService
            ->generateMovementFolio();

            $movementDTO = new WarehouseMovementsDTO(
                $folio,
                $removeWarehouseInventoryStockDTO->getWarehouseInventoryId(),
                $this->getType(),
                0, // Cantidad 0 porque solo es cambio de ubicación
                sprintf(
                    "Reubicación: %s | Rack: %s→%s, Nivel: %d→%d | Destino: %s",
                    $removeWarehouseInventoryStockDTO->getReason(),
                    $inventoryDTO->getRack(),
                    $removeWarehouseInventoryStockDTO->getRack(),
                    $inventoryDTO->getLevel(),
                    $removeWarehouseInventoryStockDTO->getLevel(),
                    $warehouseDestination 
                ),
                $removeWarehouseInventoryStockDTO->getUserId()
            );

            $this->result =
            $this->warehouseMovementsService->saveWarehouseMovement(
                $movementDTO
            );

        } catch (\Throwable $th) {
            return ResultPattern::failure($th->getMessage());
        }

        return ResultPattern::success($this);
    }

    public function getType(): string
    {
        return "RELOCATION";
    }
}

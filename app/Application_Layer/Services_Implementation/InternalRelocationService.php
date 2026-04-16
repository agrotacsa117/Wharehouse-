<?php

namespace App\Application_Layer\Services_Implementation;

use App\Contracts\WarehouseOutputStrategy;
use App\Mappers\DTO\RemoveWarehouseInventoryStockDTO;
use App\Application_Layer\ResultPattern;
use App\Contracts\WarehouseInventoryServiceInterface;
use App\Contracts\WarehouseMovementsServiceI;
use App\Mappers\DTO\WarehouseInventoryOutDetailDTO;
use App\Mappers\DTO\WarehouseMovementsDTO;

class InternalRelocationService implements WarehouseOutputStrategy
{
    private WarehouseInventoryServiceInterface $warehouseInventoryService;
    private ResultPattern $result;
    private WarehouseInventoryOutDetailDTO $warehouseInventoryOutDetailDTO;
    private WarehouseMovementsServiceI $warehouseMovementsService;
    private WarehouseMovementsDTO $warehouseMovementsDTO;

    public function __construct(
        WarehouseInventoryServiceInterface $warehouseInventoryService,
        WarehouseMovementsServiceI $warehouseMovementsService
    ) {
        $this->warehouseInventoryService = $warehouseInventoryService;
        $this->warehouseMovementsService = $warehouseMovementsService;
    }

    public function processOutput(
        RemoveWarehouseInventoryStockDTO $removeWarehouseInventoryStockDTO
    ): ResultPattern {
        $this->result =  $this->warehouseInventoryService->getInventoryById(
            $removeWarehouseInventoryStockDTO->getWarehouseInventoryId()
        );

        if ($this->result->isFailure()) {
            return $this->result;
        }

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
        ->getLevel() != $removeWarehouseInventoryStockDTO
        ->getLevel()
        || $this->warehouseInventoryOutDetailDTO->getRack()
        != $removeWarehouseInventoryStockDTO->getRack();

        if (!$hasChange) {
            return ResultPattern::success(
                "¡No hay cambios detectados!"
            );
        }

        try {
            $this->warehouseInventoryService
            ->relocateInventory(
                $removeWarehouseInventoryStockDTO->getWarehouseInventoryId(),
                $removeWarehouseInventoryStockDTO->getRack(),
                $removeWarehouseInventoryStockDTO->getLevel()
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

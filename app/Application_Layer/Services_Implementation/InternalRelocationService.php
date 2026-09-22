<?php

namespace App\Application_Layer\Services_Implementation;

use App\Application_Layer\ResultPattern;
use App\Contracts\WarehouseInventoryQueryServiceI;
use App\Contracts\WarehouseInventoryRepositoryInterface;
use App\Contracts\WarehouseInventoryToWarehouseInventoryOutDetailDTOMapperI;
use App\Contracts\WarehouseMovementsServiceI;
use App\Mappers\DTO\RemoveWarehouseInventoryStockDTO;
use App\Mappers\DTO\WarehouseInventoryOutDetailDTO;
use App\Mappers\DTO\WarehouseMovementsDTO;
use Illuminate\Support\Facades\Log;

class InternalRelocationService extends BaseOutputService
{
    private ResultPattern $result;

    private WarehouseInventoryOutDetailDTO $warehouseInventoryOutDetailDTO;

    private WarehouseMovementsDTO $warehouseMovementsDTO;

    private WarehouseInventoryQueryServiceI $warehouseInventoryQueryService;

    private WarehouseInventoryToWarehouseInventoryOutDetailDTOMapperI $warehouseInventoryToWarehouseInventoryOutDetailDTOMapper;

    public function __construct(
        WarehouseInventoryQueryServiceI $warehouseInventoryQueryService,
        WarehouseMovementsServiceI $warehouseMovementsService,
        WarehouseInventoryRepositoryInterface $warehouseInventoryRepository
    ) {
        $this->warehouseInventoryQueryService = $warehouseInventoryQueryService;
        $this->warehouseMovementsService = $warehouseMovementsService;
        parent::__construct(
            $warehouseInventoryRepository,
            $warehouseMovementsService
        );
    }

    public function processOutput(
        RemoveWarehouseInventoryStockDTO $removeWarehouseInventoryStockDTO
    ): ResultPattern {

        $this->result = $this->warehouseInventoryQueryService
            ->getInventoryById(
                $removeWarehouseInventoryStockDTO->getWarehouseInventoryId()
            );

        if ($this->result->isFailure()) {
            return $this->result;
        }

        $inventoryDTO = $this->result->getValue();
        if (
            ! $removeWarehouseInventoryStockDTO->getRack()
            || $removeWarehouseInventoryStockDTO->getRack() === ''
        ) {
            return ResultPattern::failure(
                '¡Error el campo rack no '.
                'puede estar vacio!'
            );
        }

        if (! $removeWarehouseInventoryStockDTO->getLevel()
            || $removeWarehouseInventoryStockDTO->getLevel() === '') {
            return ResultPattern::failure(
                '¡Error el campo nivel no '.
                'puede estar vacio!'
            );
        }

        $this->warehouseInventoryOutDetailDTO = $inventoryDTO;

        try {
            return $this->relocateStock(
                $removeWarehouseInventoryStockDTO,
                $this->warehouseInventoryOutDetailDTO,
                $this->warehouseInventoryQueryService
            );
        } catch (\Throwable $th) {
            return ResultPattern::failure($th->getMessage());
        }
    }

    public function getType(): string
    {
        return 'RELOCATION';
    }
}

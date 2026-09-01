<?php

namespace App\Application_Layer\Strategies;

use App\Application_Layer\ResultPattern;
use App\Application_Layer\Services_Implementation\BaseOutputService;
use App\Contracts\WarehouseInventoryQueryServiceI;
use App\Contracts\WarehouseInventoryRepositoryInterface;
use App\Contracts\WarehouseMovementsServiceI;
use App\Mappers\DTO\RemoveWarehouseInventoryStockDTO;
use App\Mappers\DTO\WarehouseInventoryOutDetailDTO;
use Illuminate\Support\Facades\Log;

class IntraWarehouseTransferStrategy extends BaseOutputService
{
    private WarehouseInventoryQueryServiceI $warehouseInventoryQueryService;

    private ResultPattern $result;

    private WarehouseInventoryOutDetailDTO $warehouseInventoryOutDetailDTO;

    public function __construct(
        WarehouseInventoryQueryServiceI $warehouseInventoryQueryService,
        WarehouseMovementsServiceI $warehouseMovementsService,
        WarehouseInventoryRepositoryInterface $warehouseInventoryRepository
    ) {
        $this->warehouseInventoryQueryService = $warehouseInventoryQueryService;
        parent::__construct(
            $warehouseInventoryRepository,
            $warehouseMovementsService
        );
    }

    public function processOutput(
        RemoveWarehouseInventoryStockDTO $removeWarehouseInventoryStockDTO
    ): ResultPattern {

        Log::info('IntraWarehouseTransferStrategy: iniciando', [
            'warehouseInventoryId' => $removeWarehouseInventoryStockDTO->getWarehouseInventoryId(),
            'userId' => $removeWarehouseInventoryStockDTO->getUserId(),
            'targetRack' => $removeWarehouseInventoryStockDTO->getRack(),
            'targetLevel' => $removeWarehouseInventoryStockDTO->getLevel(),
            'targetModule' => $removeWarehouseInventoryStockDTO->getModule(),
            'targetBay' => $removeWarehouseInventoryStockDTO->getBay(),
            'targetPlatform' => $removeWarehouseInventoryStockDTO->getPlatform(),
        ]);

        $this->result = $this->warehouseInventoryQueryService
            ->getInventoryById(
                $removeWarehouseInventoryStockDTO
                    ->getWarehouseInventoryId()
            );

        if ($this->result->isFailure()) {
            Log::error('IntraWarehouseTransferStrategy: inventario no encontrado', [
                'warehouseInventoryId' => $removeWarehouseInventoryStockDTO->getWarehouseInventoryId(),
                'reason' => $this->result->getError(),
            ]);

            return $this->result;
        }

        $this->warehouseInventoryOutDetailDTO = $this
            ->result
            ->getValue();

        Log::info('The inventory record is: ',
            [$this->warehouseInventoryOutDetailDTO]);

        $hasChanges = (
            $this
                ->warehouseInventoryOutDetailDTO
                ->getRack()
        !== $removeWarehouseInventoryStockDTO
            ->getRack()
        || $this
            ->warehouseInventoryOutDetailDTO
            ->getLevel()
        !== $removeWarehouseInventoryStockDTO
            ->getLevel()
        || $this
            ->warehouseInventoryOutDetailDTO
            ->getModule()
        !== $removeWarehouseInventoryStockDTO
            ->getModule()
        || $this
            ->warehouseInventoryOutDetailDTO
            ->getBay()
        !== $removeWarehouseInventoryStockDTO
            ->getBay()
        || $this
            ->warehouseInventoryOutDetailDTO
            ->getPlatform()
        !== $removeWarehouseInventoryStockDTO
            ->getPlatform());

        if (! $hasChanges) {
            Log::info('IntraWarehouseTransferStrategy: sin cambios de posición', [
                'warehouseInventoryId' => $removeWarehouseInventoryStockDTO->getWarehouseInventoryId(),
            ]);

            return ResultPattern::failure(
                '¡No hay cambios
                detectados en
                la posición!');
        }

        $removeWarehouseInventoryStockDTO->setWarehouseId(
            $this->warehouseInventoryOutDetailDTO->getWarehouseId()
        );

        $relocationResult = $this->relocateStock(
            $removeWarehouseInventoryStockDTO,
            $this->warehouseInventoryOutDetailDTO,
            $this->warehouseInventoryQueryService
        );

        if ($relocationResult->isFailure()) {
            Log::error('IntraWarehouseTransferStrategy: reubicación fallida', [
                'warehouseInventoryId' => $removeWarehouseInventoryStockDTO->getWarehouseInventoryId(),
                'reason' => $relocationResult->getError(),
            ]);

            return $relocationResult;
        }

        Log::info('IntraWarehouseTransferStrategy: reubicación exitosa', [
            'sourceInventoryId' => $this->warehouseInventoryOutDetailDTO->getInventoryId(),
            'warehouseId' => $removeWarehouseInventoryStockDTO->getWarehouseId(),
            'rack' => $removeWarehouseInventoryStockDTO->getRack(),
            'level' => $removeWarehouseInventoryStockDTO->getLevel(),
            'module' => $removeWarehouseInventoryStockDTO->getModule(),
            'bay' => $removeWarehouseInventoryStockDTO->getBay(),
            'platform' => $removeWarehouseInventoryStockDTO->getPlatform(),
        ]);

        return $relocationResult;
    }

    public function getType(): string
    {
        return 'LOCATION_UPDATE';
    }
}

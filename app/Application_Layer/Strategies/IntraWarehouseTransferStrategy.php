<?php

namespace App\Application_Layer\Strategies;

use App\Application_Layer\ResultPattern;
use App\Application_Layer\Services_Implementation\BaseOutputService;
use App\Contracts\WarehouseInventoryQueryServiceI;
use App\Contracts\WarehouseInventoryRepositoryInterface;
use App\Contracts\WarehouseMovementsServiceI;
use App\Enterprise_Layer\WarehouseInventory;
use App\Mappers\DTO\RemoveWarehouseInventoryStockDTO;
use App\Mappers\DTO\Requests\WarehouseInventoryRequestDTO;
use App\Mappers\DTO\WarehouseInventoryOutDetailDTO;

class IntraWarehouseTransferStrategy extends BaseOutputService
{
    private WarehouseInventoryQueryServiceI $warehouseInventoryQueryService;

    private ResultPattern $result;

    private WarehouseInventoryOutDetailDTO $warehouseInventoryOutDetailDTO;

    private WarehouseInventory $warehouseInventory;

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

        $this->result = $this->warehouseInventoryQueryService
            ->getInventoryById(
                $removeWarehouseInventoryStockDTO
                    ->getWarehouseInventoryId()
            );

        if ($this->result->isFailure()) {
            return $this->result;
        }

        $this->warehouseInventoryOutDetailDTO = $this
            ->result
            ->getValue();

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
            return ResultPattern::failure(
                '¡No hay cambios 
                detectados en 
                la posición!');
        }

        // var_dump("I supuso!");

        $warehouseInventoryRequestDTO =
        new WarehouseInventoryRequestDTO(
            $this
                ->warehouseInventoryOutDetailDTO
                ->getProductCode(),
            $this
                ->warehouseInventoryOutDetailDTO
                ->getWarehouseId(),
            $removeWarehouseInventoryStockDTO->getRack(),
            $removeWarehouseInventoryStockDTO->getLevel(),
            $this->warehouseInventoryOutDetailDTO->getQuantity(),
            new \DateTime($this
                ->warehouseInventoryOutDetailDTO
                ->getExpirationDate()),
            $this->warehouseInventoryOutDetailDTO->getReason(),
            $this->warehouseInventoryOutDetailDTO->getLotNumber(),
            $this->warehouseInventoryOutDetailDTO->getTransferFolio()
        );

        $warehouseInventoryRequestDTO
            ->setModule(
                $removeWarehouseInventoryStockDTO->getModule()
            );

        $warehouseInventoryRequestDTO
            ->setBay(
                $removeWarehouseInventoryStockDTO
                    ->getBay()
            );

        $warehouseInventoryRequestDTO
            ->setPlatform(
                $removeWarehouseInventoryStockDTO
                    ->getPlatform()
            );

        $stringDate = $this->warehouseInventoryOutDetailDTO->getManufacturingDate();
        $dateObject = null;

        if ($stringDate !== null && $stringDate !== '') {
            try {
                $dateObject = new \DateTime($stringDate);
            } catch (\Exception $e) {
                // Aquí podrías loguear el error si la fecha es inválida
                $dateObject = null;
            }
        }

        $warehouseInventoryRequestDTO->setManufacturingDate($dateObject);

        $this->result = $this->warehouseInventoryQueryService
            ->saveInventory(
                $warehouseInventoryRequestDTO);

        if ($this->result->isFailure()) {
            return ResultPattern::failure(
                'No fue posible realizar
                 la reubicación interna');
        }

        $this->warehouseInventory = $this
            ->result
            ->getValue();

        $this->warehouseInventoryQueryService
            ->desactiveInventory(
                $this->warehouseInventoryOutDetailDTO
                    ->getInventoryId()
            );

        $removeWarehouseInventoryStockDTO
            ->setReason(
                sprintf(
                    'Reubicación interna: %s | Rack: %s→%s, | Nivel: %d→%d, | Modulo: %s→%s, | Bahía %s→%s, |Taríma %s→%s',
                    $removeWarehouseInventoryStockDTO->getReason(),
                    $this->warehouseInventoryOutDetailDTO->getRack(),
                    $removeWarehouseInventoryStockDTO->getRack(),
                    $this->warehouseInventoryOutDetailDTO->getLevel(),
                    $removeWarehouseInventoryStockDTO->getLevel(),
                    $this->warehouseInventoryOutDetailDTO->getModule(),
                    $removeWarehouseInventoryStockDTO->getModule(),
                    $this->warehouseInventoryOutDetailDTO->getBay(),
                    $removeWarehouseInventoryStockDTO->getBay(),
                    $this->warehouseInventoryOutDetailDTO->getPlatform(),
                    $removeWarehouseInventoryStockDTO->getPlatform()
                )
            );

        $removeWarehouseInventoryStockDTO
            ->setQuantity($this
                ->warehouseInventoryOutDetailDTO
                ->getQuantity());

        $removeWarehouseInventoryStockDTO
            ->setWarehouseInventoryId(
                $this->warehouseInventory->getId()
            );

        $warehouseMovementDTO = $this->recordMovement(
            $removeWarehouseInventoryStockDTO
        );

        $this->result = $this->warehouseMovementsService
            ->saveWarehouseMovement(
                $warehouseMovementDTO
            );

        if ($this->result->isFailure()) {
            return $this->result;
        }

        return ResultPattern::success(true);
    }

    public function getType(): string
    {
        return 'LOCATION_UPDATE';
    }
}

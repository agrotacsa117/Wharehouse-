<?php

namespace App\Application_Layer\Services_Implementation;

use App\Contracts\WarehouseOutputStrategy;
use App\Mappers\DTO\RemoveWarehouseInventoryStockDTO;
use App\Application_Layer\ResultPattern;
use App\Contracts\WarehouseInventoryRepositoryInterface;
use App\Contracts\WarehouseMovementsServiceI;
use App\Mappers\DTO\WarehouseMovementsDTO;
use App\Application_Layer\Services_Implementation\BaseOutputService;
use App\Contracts\WarehouseSalesServiceI;
use App\Mappers\DTO\Requests\WarehouseSalesRequestDTO;

class SaleOutputService extends BaseOutputService
{
    private WarehouseSalesServiceI $warehouseSalesService;

    public function __construct(
        WarehouseInventoryRepositoryInterface $inventoryRepository,
        WarehouseMovementsServiceI $movementsService,
        WarehouseSalesServiceI $warehouseSalesService
    ) {
        parent::__construct(
            $inventoryRepository,
            $movementsService
        );

        $this->warehouseSalesService = $warehouseSalesService;
    }

    public function processOutput(RemoveWarehouseInventoryStockDTO $removeWarehouseInventoryStockDTO): ResultPattern
    {

        $result =  $this->validateStockAvailability(
            $removeWarehouseInventoryStockDTO
        );

        if ($result->isFailure()) {
            return $result;
        }
        // 2. Actualizar cantidad
        try {
            $newQuantity = $result->getValue();

            $result = $this->reduceStock(
                $newQuantity,
                $removeWarehouseInventoryStockDTO
            );

            $newQuantity = $result->getValue();

            if ($result->isFailure()) {
                return $result;
            }

            $movementDTO = $this->recordMovement(
                $removeWarehouseInventoryStockDTO
            );

            $movementDTO->setOperationDate(
                new \DateTime(
                    $removeWarehouseInventoryStockDTO
                    ->getOperationDate()
                )
            );


            // 5. Persistir
            $result =  $this->warehouseMovementsService
            ->saveWarehouseMovement(
                $movementDTO
            );

            if ($result->isFailure()) {
                return $result;
            }

            $warehouseSalesDTO = new WarehouseSalesRequestDTO(
                $movementDTO->getFolio(),
                $removeWarehouseInventoryStockDTO->getClientId(),
                $removeWarehouseInventoryStockDTO->getInvoiceId()
            );

            $this->warehouseSalesService
            ->saveWarehouseSales(
                $warehouseSalesDTO
            );

        } catch (\Throwable $th) {
            return ResultPattern::failure($th->getMessage());
        }

        return ResultPattern::success([
            'message' => 'Venta registrada exitosamente',
            'sold_quantity' => $removeWarehouseInventoryStockDTO->getQuantity(),
            'remaining_stock' => $newQuantity,
            'client_id' => $removeWarehouseInventoryStockDTO->getClientId(),
            'invoice_sap' => $removeWarehouseInventoryStockDTO->getInvoiceId()
        ]);
    }

    public function getType(): string
    {
        return "SALE";
    }
}

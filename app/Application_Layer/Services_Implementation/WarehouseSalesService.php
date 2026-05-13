<?php

namespace App\Application_Layer\Services_Implementation;

use App\Application_Layer\ResultPattern;
use App\Contracts\WarehouseSalesServiceI;
use App\Mappers\DTO\Requests\WarehouseSalesRequestDTO;
use App\Contracts\WarehouseSalesRequestDTOToEntityMapperI;
use App\Contracts\WarehouseSalesRepositoryI;
use App\Mappers\DTO\SalesDTO;

class WarehouseSalesService implements WarehouseSalesServiceI
{
    private WarehouseSalesRequestDTOToEntityMapperI $warehouseSalesRequestDTOToEntityMapper;
    private WarehouseSalesRepositoryI $warehouseSalesRepository;

    public function __construct(
        WarehouseSalesRequestDTOToEntityMapperI $warehouseSalesRequestDTOToEntityMapper,
        WarehouseSalesRepositoryI $warehouseSalesRepository
    ) {
        $this->warehouseSalesRequestDTOToEntityMapper = $warehouseSalesRequestDTOToEntityMapper;
        $this->warehouseSalesRepository = $warehouseSalesRepository;
    }

    public function saveWarehouseSales(
        WarehouseSalesRequestDTO $warehouseSalesRequestDTO
    ): ResultPattern {

        $warehouseSalesEntity = $this->warehouseSalesRequestDTOToEntityMapper
        ->toWarehouseSalesEntity($warehouseSalesRequestDTO);

        $warehouseSalesEntity = $this->warehouseSalesRepository->save(
            $warehouseSalesEntity
        );

        if (!$warehouseSalesEntity) {
            return ResultPattern::failure(
                "¡Ocurrio un error "
                ."al intentar guardar "
                ."el registro de la venta"
            );
        }


        return ResultPattern::success($warehouseSalesEntity);
    }

    public function getSalesReport(): array
    {
        $salesReport = $this
        ->warehouseSalesRepository
        ->findAll();

        for ($i = 0; $i < count($salesReport) ; $i++) {
            $sale = $salesReport[$i];

            $createdDate = new \DateTime(
                $sale["created_at"]
            );

            $updatedDate = new \DateTime(
                $sale["created_at"]
            );

            $salesReport[$i] = new SalesDTO(
                $sale["movement_id"],
                $sale["invoice_sap"],
                $createdDate->format('Y-m-d'),
                $updatedDate->format('Y-m-d'),
                $sale["movement"]["inventory"]['product_id'],
                $sale["movement"]["inventory"]['warehouse_name'],
                $sale["movement"]["inventory"]['warehouse']['warehouses_name'],
                $sale["movement"]["inventory"]['lot_number'],
                $sale["movement"]["inventory"]['quantity'],
                $sale["movement"]['user']['name']
            );
        }

        return $salesReport;
    }

    public function filterSales(
        string $searchParam
    ): array {
        return [];

    }
}

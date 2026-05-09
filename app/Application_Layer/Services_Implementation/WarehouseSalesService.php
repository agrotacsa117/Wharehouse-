<?php

namespace App\Application_Layer\Services_Implementation;

use App\Application_Layer\ResultPattern;
use App\Contracts\WarehouseSalesServiceI;
use App\Mappers\DTO\Requests\WarehouseSalesRequestDTO;
use App\Contracts\WarehouseSalesRequestDTOToEntityMapperI;
use App\Contracts\WarehouseSalesRepositoryI;

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
        
        return [];
    }

    public function filterSales(
        string $searchParam
    ): array {
        return [];

    }
}

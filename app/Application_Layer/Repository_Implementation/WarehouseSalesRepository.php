<?php

namespace App\Application_Layer\Repository_Implementation;

use App\Contracts\WarehouseSalesRepositoryI;
use App\Enterprise_Layer\WarehouseSalesEntity;
use App\Contracts\WarehouseSalesEntityToModelMapperI;
use App\Models\WarehouseSalesModel;

class WarehouseSalesRepository implements WarehouseSalesRepositoryI
{
    private WarehouseSalesEntityToModelMapperI $warehouseSalesEntityToModelMapper;

    public function __construct(
        WarehouseSalesEntityToModelMapperI $warehouseSalesEntityToModelMapper
    ) {
        $this->warehouseSalesEntityToModelMapper = $warehouseSalesEntityToModelMapper;
    }

    public function save(
        WarehouseSalesEntity $warehouseSalesEntity
    ): ?WarehouseSalesEntity {

        $warehouseSalesModel = $this->warehouseSalesEntityToModelMapper
        ->toWarehouseSalesModel(
            $warehouseSalesEntity
        );


        $result = $warehouseSalesModel->save();

        if (!$result) {
            return null;
        }

        return $warehouseSalesEntity;
    }

    public function findAll(): array
    {
        $sales = WarehouseSalesModel::has(
            'movement'
        )->with([
                'movement.inventory.warehouse',
                'movement.user'
            ])->get();

     
        return $sales->toArray();
    }

    public function dateRangeFilter(
        string $startDate,
        string $endDate
    ): array {
        return [];
    }
}

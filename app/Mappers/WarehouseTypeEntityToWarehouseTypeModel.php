<?php

declare(strict_types=1);

namespace App\Mappers;

use App\Enterprise_Layer\WarehouseType;
use App\Models\WarehouseTypeModel;
use App\Contracts\WarehouseTypeEntityToWarehouseTypeModelMapperI;

class WarehouseTypeEntityToWarehouseTypeModel implements WarehouseTypeEntityToWarehouseTypeModelMapperI
{
    public function convertWarehouseTypeDomainEntityToWarehouseTypeModel(
        WarehouseType $warehouseType
    ): WarehouseTypeModel {

        if (!$warehouseType instanceof WarehouseType) {
            throw new \InvalidArgumentException('Expected instance of WarehouseType');
        }

        $model = new WarehouseTypeModel();

        // Si el entity ya tiene ID (caso update)
        if ($warehouseType->getId() !== 0) {
            $model->id = $warehouseType->getId();
        }

        $model->category_warehouse = $warehouseType->getCategoryWarehouse();

        // Si manejas timestamps desde dominio
        if (method_exists($warehouseType, 'getCreatedAt')) {
            $model->created_at = $warehouseType->getCreatedAt();

        }

        if (method_exists($warehouseType, 'getUpdatedAt')) {
            $model->updated_at = $warehouseType->getUpdatedAt();
        }

        return $model;
    }
}

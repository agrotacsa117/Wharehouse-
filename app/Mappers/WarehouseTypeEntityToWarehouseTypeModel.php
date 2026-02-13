<?php

declare(strict_types=1);

namespace App\Mappers;

use App\Enterprise_Layer\WarehouseType;
use App\Models\WarehouseTypeModel;
use App\Contracts\EntityToModelMapperInterface;

/**
 * @implements EntityToModelMapperInterface<WarehouseType, WarehouseTypeModel>
 */
class WarehouseTypeEntityToWarehouseTypeModel implements EntityToModelMapperInterface
{
    public function convertDomainEntityToModel($tEntity): WarehouseTypeModel
    {
        if (!$tEntity instanceof WarehouseType) {
            throw new \InvalidArgumentException('Expected instance of WarehouseType');
        }

        $model = new WarehouseTypeModel();

        // Si el entity ya tiene ID (caso update)
        if ($tEntity->getId() !== 0) {
            $model->id = $tEntity->getId();
        }

        $model->category_warehouse = $tEntity->getCategoryWarehouse();

        // Si manejas timestamps desde dominio
        if (method_exists($tEntity, 'getCreatedAt')) {
            $model->created_at = $tEntity->getCreatedAt();
        }

        if (method_exists($tEntity, 'getUpdatedAt')) {
            $model->updated_at = $tEntity->getUpdatedAt();
        }

        return $model;
    }
}

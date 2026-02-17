<?php

namespace App\Contracts;

/**
 * @template TEntity
 * @template Model
 */
interface WarehouseEntityToWarehouseModelMapperI
{
    /**
     * @param TEntity $tEntity
     * @return  Model
     */

    public function convertDomainEntityToModel($warehouseEntity);
}

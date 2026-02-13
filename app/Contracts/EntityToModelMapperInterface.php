<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;

/**
 * @template TEntity
 * @template Model
 */
interface EntityToModelMapperInterface
{


    /**
     * @param TEntity $tEntity
     * @param TModel $tModel
     * @return  Model
     */
    public function convertDomainEntityToModel($tEntity);
}

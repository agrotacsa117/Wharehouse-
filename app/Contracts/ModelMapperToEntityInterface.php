<?php

namespace App\Contracts;

/**
 * @template TModel
 * @template TEntity
 */
interface ModelMapperToEntityInterface
{
    /**
      * @param TModel $model
      * @return TEntity
      */
    public function convertModelToEntity($model);
}

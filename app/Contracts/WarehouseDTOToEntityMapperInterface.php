<?php

namespace App\Contracts;

/**
 * @template TDTO
 * @template TEntity
 */
interface WarehouseDTOToEntityMapperInterface
{
    /**
     * @param  TDTO  $tDTO
     * @return TEntity
     */
    public function convertDTOToEntity($tDTO);
}

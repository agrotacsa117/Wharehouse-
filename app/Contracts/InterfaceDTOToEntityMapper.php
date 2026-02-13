<?php

namespace App\Contracts;

/**
 * @template TDTO
 * @template TEntity
 */

interface InterfaceDTOToEntityMapper
{
    /**
     * @param  TDTO $tDTO
     *  @return  TEntity
     */

    public function convertDTOToEntity($tDTO);
}

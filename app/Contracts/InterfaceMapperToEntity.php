<?php

namespace App\Contracts;

/**
 * @template TDTO
 * @template TEntity
 * @interface
 */
interface InterfaceMapperToEntity
{

    /**
     * @param TDTO $tDTO
     * @return TEntity
     */
    public function convertDTOToEntity($tDTO) ;
}

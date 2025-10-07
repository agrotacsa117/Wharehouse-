<?php

namespace app\Contracts;

/**
 * @template TDTO
 * @template TEntity
 */

interface InterfaceMapperToEntity
{

    /**
     * @param  TDTO $tDTO
     *  @return TEntity
     */
    public function convertDTOToEntity($tDTO);
}

<?php

namespace App\Contracts;


/**
 * @template TDTO
 * @template TEntity
 */

interface InterfaceEntityToDTOMapper
{

    /**
     * @param  TEntity $tEntity
     *  @return  TDTO 
     */
    public function convertEntityToDTO($tEntity);
}

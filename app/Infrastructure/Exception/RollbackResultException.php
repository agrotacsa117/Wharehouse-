<?php

namespace App\Infrastructure\Exception;

use Exception;

class RollbackResultException extends Exception
{
    protected $result;

    public function __construct($result)
    {
        $this->result = $result;
        parent::__construct('Rollback triggered by ResultPattern failure');
    }

    public function getResult()
    {
        return $this->result;
    }
}

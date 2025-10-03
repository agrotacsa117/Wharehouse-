<?php

namespace App\Application_Layer;

use InvalidArgumentException;


class ResultPattern
{

    private bool $isSuccess;
    private string $error;

    protected function __construct(bool $isSuccess, string $error)
    {
        $this->isSuccess =  $isSuccess;
        $this->error = $error;
    }

    public function isSuccess(): bool
    {
        return $this->isSuccess;
    }


    public function isFailure(): bool
    {
        return !$this->isSuccess;
    }
    public function getError(): string
    {
        return $this->error;
    }

    public static function success(): ResultPattern
    {

        return new ResultPattern(true, "");
    }

    public static function failure(
        string $messge
    ): ResultPattern {

        if ($messge === "" || $messge === null) {
            throw new InvalidArgumentException('The error argument can´t be null');
        }
        return new ResultPattern(false, $messge);
    }
}

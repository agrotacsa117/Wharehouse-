<?php

namespace App\Application_Layer;

use InvalidArgumentException;

/**
 * @template T
 */
class ResultPattern
{
    private bool $isSuccess;
    private string $error;
    /** @var T|null */
    private $value;

    protected function __construct(
        bool $isSuccess,
        string $error,
        $value
    ) {
        $this->isSuccess =  $isSuccess;
        $this->error = $error;
        $this->value = $value;
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

    public static function success($value): ResultPattern
    {

        return new ResultPattern(
            true,
            "",
            $value
        );
    }

    public static function failure(
        string $failureMessage
    ): ResultPattern {

        if ($failureMessage === "" || $failureMessage === null) {
            throw new InvalidArgumentException('The error argument can´t be null');
        }
        return new ResultPattern(
            false,
            $failureMessage,
            null
        );
    }
}

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

    private bool $warningStatus = false;

    protected function __construct(
        bool $isSuccess,
        string $error,
        $value
    ) {
        $this->isSuccess = $isSuccess;
        $this->error = $error;
        $this->value = $value;
    }

    /**
     * @return T|null
     */
    public function getValue()
    {
        return $this->value;
    }

    public function isSuccess(): bool
    {
        return $this->isSuccess;
    }

    public function isFailure(): bool
    {
        return ! $this->isSuccess;
    }

    public function getError(): string
    {
        return $this->error;
    }

    public static function success($value): ResultPattern
    {

        return new ResultPattern(
            true,
            '',
            $value
        );
    }

    public static function failure(
        string $failureMessage
    ): ResultPattern {

        if ($failureMessage === '' || $failureMessage === null) {
            throw new InvalidArgumentException('The error argument can´t be null');
        }

        return new ResultPattern(
            false,
            $failureMessage,
            null
        );
    }

    public static function warning(
        string $message,
        $value
    ): ResultPattern {

        $result = new ResultPattern(
            false,
            $message,
            $value
        );

        $result->setWarningStatus(true);

        return $result;
    }

    public function setWarningStatus(
        bool $satus
    ): void {
        $this->warningStatus = $satus;
    }

    public function isWarning(): bool
    {
        return $this->warningStatus;
    }
}

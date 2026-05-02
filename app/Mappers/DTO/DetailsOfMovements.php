<?php

namespace App\Mappers\DTO;

class DetailsOfMovements
{
    private array $details;
    private array $statics;

    public function __construct(
        array $details,
        array $statics
    ) {
        $this->details = $details;
        $this->statics = $statics;
    }

    /**
     * Get the value of details
     */
    public function getDetails(): array
    {
        return $this->details;
    }

    /**
     * Get the value of statics
     */
    public function getStatics(): array
    {
        return $this->statics;
    }
}

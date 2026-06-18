<?php

namespace App\Mappers\DTO;

class NegativeStockWarningDTO
{
    private int $currentStock;

    private int $resultingStock;

    private array $dependentMovements;

    public function __construct(
        int $currentStock,
        int $resultingStock,
        array $dependentMovements)
    {
        $this->currentStock = $currentStock;
        $this->resultingStock = $resultingStock;
        $this->dependentMovements = $dependentMovements;
    }

    public function getCurrentStock(): int
    {
        return $this->currentStock;
    }

    public function getResultingStock(): int
    {
        return $this->resultingStock;
    }

    public function getDependentMovements(): array
    {
        return $this->dependentMovements;
    }
}

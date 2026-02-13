<?php

namespace App\Mappers\DTO\Requests;

class WarehouseTypeRequestDTO
{
    private string $categoryWarehouse;

    public function __construct(string $categoryWarehouse)
    {
        $this->categoryWarehouse = $categoryWarehouse;
    }

    public function getCategoryWarehouse(): string
    {
        return $this->categoryWarehouse;
    }

    public function setCategoryWarehouse(string $categoryWarehouse): void
    {
        $this->categoryWarehouse = $categoryWarehouse;
    }

}

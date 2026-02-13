<?php

namespace App\Mappers\DTO;

class WarehouseTypeListDTO
{
    private int $id;
    private string $categoryWarehouse;


    public function __construct(
        int $id,
        string $categoryWarehouse
    ) {
        $this->id = $id;
        $this->categoryWarehouse = $categoryWarehouse;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
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

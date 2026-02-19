<?php

namespace App\Mappers\DTO;

class WarehouseListDTO
{
    private int $id;
    private string $warehouseName;

    // Constructor
    public function __construct(int $id, string $warehouseName)
    {
        $this->id = $id;
        $this->warehouseName = $warehouseName;
    }

    // Getter para id
    public function getId(): int
    {
        return $this->id;
    }

    // Setter para id
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    // Getter para warehouseName
    public function getWarehouseName(): string
    {
        return $this->warehouseName;
    }

    // Setter para warehouseName
    public function setWarehouseName(string $warehouseName): void
    {
        $this->warehouseName = $warehouseName;
    }
}

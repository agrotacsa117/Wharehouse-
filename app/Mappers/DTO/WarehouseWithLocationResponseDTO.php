<?php

namespace App\Mappers\DTO;

class WarehouseWithLocationResponseDTO{

    private int $id;
    private string $warehouseName;
    private string $headquartersName;
    

    public function __construct(
        int $id,
        string $warehouseName,
        string $headquartersName
    ) {
        $this->id = $id;
        $this->warehouseName = $warehouseName;
        $this->headquartersName = $headquartersName;
    }

    public function getId() : int {
        return $this->id;
    }

    public function setId(int $id) : void {
        $this->id = $id;
    }

    public function getWarehouseName(): string
    {
        return $this->warehouseName;
    }

    public function getHeadquartersName(): string
    {
        return $this->headquartersName;
    }
}
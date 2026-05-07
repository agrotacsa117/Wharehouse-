<?php

namespace App\Mappers\DTO;

class WarehouseWithLocationResponseDTO implements \JsonSerializable
{
    private int $id;
    private string $warehouseName;
    private string $headquartersName;
    private ?int $locationId;

    public function __construct(
        int $id,
        string $warehouseName,
        string $headquartersName,
        ?int $locationId = null
    ) {
        $this->id = $id;
        $this->warehouseName = $warehouseName;
        $this->headquartersName = $headquartersName;
        $this->locationId = $locationId;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
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

    public function getLocationId(): ?int
    {
        return $this->locationId;
    }

    public function jsonSerialize(): array
    {
        return [
            'id'               => $this->id,
            'warehouseName'    => $this->warehouseName,
            'headquartersName' => $this->headquartersName,
            'locationId'       => $this->locationId,
        ];
    }
}

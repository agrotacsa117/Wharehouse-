<?php

namespace App\Mappers\DTO;

class LocationListDTO
{
    private int $id;
    private string $headquartersName;

    public function __construct(int $id, string $headquartersName)
    {
        $this->id = $id;
        $this->headquartersName = $headquartersName;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getHeadquartersName(): string
    {
        return $this->headquartersName;
    }

    public function setHeadquartersName(string $headquartersName): void
    {
        $this->headquartersName = $headquartersName;
    }

}

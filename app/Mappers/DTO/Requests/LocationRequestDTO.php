<?php

namespace App\Mappers\DTO\Requests;

class LocationRequestDTO
{
    private string $headquartersName;
    private int $postalCode;
    private string $state;
    private string $city;
    private string $address;

    public function __construct(
        string $headquartersName,
        int $postalCode,
        string $state,
        string $city,
        string $address
    ) {
        $this->headquartersName = $headquartersName;
        $this->postalCode = $postalCode;
        $this->state = $state;
        $this->city = $city;
        $this->address = $address;
    }

    public function getHeadquartersName(): string
    {
        return $this->headquartersName;
    }

    public function setHeadquartersName(string $headquartersName): void
    {
        $this->headquartersName = $headquartersName;
    }

    public function getPostalCode(): int
    {
        return $this->postalCode;
    }

    public function setPostalCode(int $postalCode): void
    {
        $this->postalCode = $postalCode;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }
}

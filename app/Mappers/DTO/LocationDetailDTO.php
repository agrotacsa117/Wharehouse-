<?php

namespace App\Mappers\DTO;

class LocationDetailDTO
{
    public int $id;
    public string $headquartersName;
    public int $postalCode;
    public string $state;
    public string $city;
    public string $address;
    public \DateTime $createdAt;
    public \DateTime $updatedAt;

    public function __construct(
        int $id,
        string $headquartersName,
        int $postalCode,
        string $state,
        string $city,
        string $address,
        \DateTime $createdAt,
        \DateTime $updatedAt
    ) {
        $this->id = $id;
        $this->headquartersName = $headquartersName;
        $this->postalCode = $postalCode;
        $this->state = $state;
        $this->city = $city;
        $this->address = $address;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
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

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}

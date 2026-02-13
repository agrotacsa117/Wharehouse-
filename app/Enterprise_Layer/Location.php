<?php

declare(strict_types=1);

namespace App\Enterprise_Layer;

use App\Enterprise_Layer\Exception\InvalidPostalCodeException;
use App\Enterprise_Layer\Exception\InvalidAddressException;
use App\Enterprise_Layer\Exception\InvalidCityNameException;
use App\Enterprise_Layer\Exception\InvalidStateNameException;
use App\Enterprise_Layer\Exception\InvalidHeadquartersName;
use DateTime;

class Location
{
    private int $id;
    private string $headquartersName;
    private int $postalCode;
    private string $state;
    private string $city;
    private DateTime $createdAt;
    private DateTime $updatedAt;
    private string $address;

    public function __construct(
        string $headquartersName,
        int $postalCode,
        string $state,
        string $city,
        string $address
    ) {
        $this->headquartersName = $headquartersName;
        $this->validatePostalCode($postalCode);
        $this->postalCode = $postalCode;
        $this->state = $state;
        $this->city = $city;
        $this->address = $address;
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

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }


    private function validatePostalCode(int $postalCode): void
    {
        if ($postalCode <= 0) {
            throw new InvalidPostalCodeException(
                "¡Error: código postal invalido!",
                23
            );
        }

        if (strlen((string)$postalCode) !== 5) {
            throw new InvalidPostalCodeException(
                "¡Error: código postal invalido!",
                24
            );
        }

    }


    private function validateAddress(
        string $address
    ): void {

        if (!preg_match(
            "^(?=.{3,50}$)[A-Za-z0-9ÁÉÍÓÚÜÑáéíóúüñ\s.,#\-\/()°º'&]+$",
            $address
        )) {
            throw new InvalidAddressException(
                "¡Error: dirección no valida!",
                25
            );
        }
    }

    private function validateCity(string $city)
    {
        if (!$this-> validateNames(
            $city,
            3,
            35
        )) {
            throw new InvalidCityNameException(
                "¡Error: nombre de ciudad no valido!",
                26
            );
        }
    }

    private function validateState(string $state): void
    {
        if (!$this->validateNames(
            $state,
            6,
            20
        )) {
            throw new InvalidStateNameException(
                "¡Error: nombre de estado no valido!",
                27
            );
        }
    }

    private function validateHeadquartersName(
        string $headquartersName
    ): void {

        if (!$this->validateNames(
            $headquartersName,
            6,
            50
        )) {
            throw new InvalidHeadquartersName(
                "¡Error: nombre de sede no valido!"
            );
        }
    }

    private function validateNames(
        string $name,
        int $minLenght,
        int $maxLenght
    ): bool {
        return preg_match(
            "^(?=.{".$minLenght.
            ",".$maxLenght."}$)[A-Za-zÁÉÍÓÚÜÑáéíóúüñ\s]+$",
            $name
        );
    }
}

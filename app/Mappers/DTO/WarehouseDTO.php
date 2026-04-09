<?php

namespace App\Mappers\DTO;

class WarehouseDTO implements \JsonSerializable
{
    private int $id;
    private int $userLastUpdate;
    private string $warehouseKey;
    private string $warehouseName;
    private string $responsiblePersonName;
    private string $phoneNumber;
    private string $email;
    private int $warehouseTypeId;
    private int $locationId;

    public function __construct(
        string $warehouseKey,
        string $warehouseName,
        string $responsiblePersonName,
        string $phoneNumber,
        string $email,
        int $warehouseTypeId,
        int $locationId,
        int $userLastUpdate,
        int $id = 0
    ) {
        $this->id = $id;
        $this->warehouseKey = $warehouseKey;
        $this->warehouseName = $warehouseName;
        $this->responsiblePersonName = $responsiblePersonName;
        $this->phoneNumber = $phoneNumber;
        $this->email = $email;
        $this->warehouseTypeId = $warehouseTypeId;
        $this->locationId = $locationId;
        $this->userLastUpdate = $userLastUpdate;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getUserLastUpdate(): int
    {
        return $this->userLastUpdate;
    }

    public function setUserLastUpdate(int $userLastUpdate): void
    {
        $this->userLastUpdate = $userLastUpdate;
    }

    public function getUserId(): int
    {
        return $this->userLastUpdate;
    }


    public function setUserId(int $userId): void
    {
        $this->userLastUpdate = $userId;
    }


    public function getWarehouseKey(): string
    {
        return $this->warehouseKey;
    }

    public function setWarehouseKey(string $warehouseKey): void
    {
        $this->warehouseKey = $warehouseKey;
    }

    // Getter y Setter para warehouseName
    public function getWarehouseName(): string
    {
        return $this->warehouseName;
    }

    public function setWarehouseName(string $warehouseName): void
    {
        $this->warehouseName = $warehouseName;
    }

    // Getter y Setter para responsiblePersonName
    public function getResponsiblePersonName(): string
    {
        return $this->responsiblePersonName;
    }

    public function setResponsiblePersonName(string $responsiblePersonName): void
    {
        $this->responsiblePersonName = $responsiblePersonName;
    }

    // Getter y Setter para phoneNumber
    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    // Getter y Setter para email
    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    // Getter y Setter para warehouseType
    public function getWarehouseTypeId(): int
    {
        return $this->warehouseTypeId;
    }

    public function setWarehouseType(int $warehouseTypeId): void
    {
        $this->warehouseTypeId = $warehouseTypeId;
    }

    public function getLocationId(): int
    {
        return $this->locationId;
    }

    public function setLocationId(int $locationId): void
    {
        $this->locationId = $locationId;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'userLastUpdate' => $this->userLastUpdate,
            'warehouseKey' => $this->warehouseKey,
            'warehouseName' => $this->warehouseName,
            'responsiblePersonName' => $this->responsiblePersonName,
            'phoneNumber' => $this->phoneNumber,
            'email' => $this->email,
            'warehouseTypeId' => $this->warehouseTypeId,
            'locationId' => $this->locationId,
        ];
    }


}

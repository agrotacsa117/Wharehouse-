<?php

namespace app\Mappers\DTO;

class WarehouseDTO
{

    private string $warehouseKey;
    private string $warehouseName;
    private string $responsiblePersonName;
    private string $phoneNumber;
    private string $email;
    private string $warehouseType;

    public function __construct(
        string $warehouseKey,
        string $warehouseName,
        string $responsiblePersonName,
        string $phoneNumber,
        string $email,
        string $warehouseType
    ) {
        $this->warehouseKey = $warehouseKey;
        $this->warehouseName = $warehouseName;
        $this->responsiblePersonName = $responsiblePersonName;
        $this->phoneNumber = $phoneNumber;
        $this->email = $email;
        $this->warehouseType = $warehouseType;
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
    public function getWarehouseType(): string
    {
        return $this->warehouseType;
    }

    public function setWarehouseType(string $warehouseType): void
    {
        $this->warehouseType = $warehouseType;
    }
}

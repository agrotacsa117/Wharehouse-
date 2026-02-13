<?php

namespace App\Enterprise_Layer;

use App\Enterprise_Layer\Warehouse;
use DateTime;

class WarehouseBuilder
{
    private int $warehouseId;
    private string $warehouseName;
    private int $userId;
    private DateTime $creationDate;
    private DateTime $lastUpdateDate;
    private int $userLastUpdate;
    private string $warehouseKey;
    private string $warehouseManager;
    private string $phoneNumber;
    private string $email;
    private int $warehouseTypeId;
    private int $locationId;


    public function build(): Warehouse
    {
        return new Warehouse($this);
    }

    // Getters

    public function getLocationId(): int
    {
        return $this->locationId;
    }

    public function getWarehousesId(): int
    {
        return $this->warehouseId;
    }

    public function getWarehousesName(): string
    {
        return $this->warehouseName;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getCreationDate(): DateTime
    {
        return $this->creationDate;
    }

    public function getLastUpdateDate(): DateTime
    {
        return $this->lastUpdateDate;
    }

    public function getUserLastUpdate(): int
    {
        return $this->userLastUpdate;
    }

    public function getWarehouseKey(): string
    {
        return $this->warehouseKey;
    }

    public function getWarehouseManager(): string
    {
        return $this->warehouseManager;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getWarehouseTypeId(): int
    {
        return $this->warehouseTypeId;
    }

    // Setters
    public function setLocationId(int $locationId): void
    {
        $this->locationId = $locationId;
    }

    public function setWarehouseId(int $warehouseId): WarehouseBuilder
    {
        $this->warehouseId = $warehouseId;
        return $this;
    }

    public function setWarehousesName(string $warehousesName): WarehouseBuilder
    {
        $this->warehouseName = $warehousesName;
        return $this;
    }

    public function setUserId(int $userId): WarehouseBuilder
    {
        $this->userId = $userId;
        return $this;
    }

    public function setCreationDate(DateTime $creationDate): WarehouseBuilder
    {
        $this->creationDate = $creationDate;
        return $this;
    }

    public function setLastUpdateDate(DateTime $lastUpdateDate): WarehouseBuilder
    {
        $this->lastUpdateDate = $lastUpdateDate;
        return $this;
    }

    public function setUserLastUpdate(int $userLastUpdate): WarehouseBuilder
    {
        $this->userLastUpdate = $userLastUpdate;
        return $this;
    }

    public function setWarehousesKey(string $warehousesKey): WarehouseBuilder
    {
        $this->warehouseKey = $warehousesKey;
        return $this;
    }

    public function setWarehouseManager(string $warehouseManager): WarehouseBuilder
    {
        $this->warehouseManager = $warehouseManager;
        return $this;
    }

    public function setPhoneNumber(string $phoneNumber): WarehouseBuilder
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    public function setEmail(string $email): WarehouseBuilder
    {
        $this->email = $email;
        return $this;
    }

    public function setWarehouseTypeId(int $warehouseTypeId): WarehouseBuilder
    {
        $this->warehouseTypeId = $warehouseTypeId;
        return $this;
    }
}

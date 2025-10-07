<?php

namespace App\Enterprise_Layer;

use DateTime;

class Warehouse
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
    private string $warehouseType;

    public function __construct(
        string $warehouseName,
        int $userId,
        int $userLastUpdate,
        string $warehouseKey,
        string $warehouseManager,
        string $phoneNumber,
        string $email,
        string $warehouseType
    ) {
        $this->warehouseName = $warehouseName;
        $this->userId = $userId;
        $this->creationDate = new DateTime();
        $this->lastUpdateDate = new DateTime();
        $this->userLastUpdate = $userLastUpdate;
        $this->warehouseKey = $warehouseKey;
        $this->warehouseManager = $warehouseManager;
        $this->phoneNumber = $phoneNumber;
        $this->email = $email;
        $this->warehouseType = $warehouseType;
    }

    // Getters
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
    public function getWarehousesKey(): string
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
    public function getWarehouseType(): string
    {
        return $this->warehouseType;
    }

    // Setters
    public function setWarehouseId(int $warehouseId): void
    {
        $this->warehouseId = $warehouseId;
    }

    public function setWarehousesName(string $warehousesName): void
    {
        $this->warehouseName = $warehousesName;
    }
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }
    public function setCreationDate(DateTime $creationDate): void
    {
        $this->creationDate = $creationDate;
    }
    public function setLastUpdateDate(DateTime $lastUpdateDate): void
    {
        $this->lastUpdateDate = $lastUpdateDate;
    }
    public function setUserLastUpdate(int $userLastUpdate): void
    {
        $this->userLastUpdate = $userLastUpdate;
    }
    public function setWarehousesKey(string $warehousesKey): void
    {
        $this->warehouseKey = $warehousesKey;
    }
    public function setWarehouseManager(string $warehouseManager): void
    {
        $this->warehouseManager = $warehouseManager;
    }
    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
    public function setWarehouseType(string $warehouseType): void
    {
        $this->warehouseType = $warehouseType;
    }
}

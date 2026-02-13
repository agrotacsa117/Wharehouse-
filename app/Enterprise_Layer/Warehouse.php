<?php

declare(strict_types=1);

namespace App\Enterprise_Layer;

use App\Enterprise_Layer\WarehouseBuilder;
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
    private int $warehouseTypeId;
    private int $locationId;

    public function __construct(
        WarehouseBuilder $warehouseBuilder
    ) {
        $this->warehouseName = $warehouseBuilder->getWarehousesName();
        $this->userId = $warehouseBuilder->getUserId();
        $this->creationDate = new DateTime();
        $this->lastUpdateDate = new DateTime();
        $this->userLastUpdate = $warehouseBuilder->getUserLastUpdate();
        $this->warehouseKey = $warehouseBuilder->getWarehouseKey();
        $this->warehouseManager = $warehouseBuilder->getWarehouseManager();
        $this->phoneNumber = $warehouseBuilder->getPhoneNumber();
        $this->email = $warehouseBuilder->getEmail();
        $this->warehouseTypeId = $warehouseBuilder->getWarehouseTypeId();
        $this->locationId = $warehouseBuilder->getLocationId();
    }

    public static function builder(): WarehouseBuilder
    {
        return new WarehouseBuilder();
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

    public function getWarehouseTypeId(): int
    {
        return $this->warehouseTypeId;
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
    public function setWarehouseTypeId(int $warehouseTypeId): void
    {
        $this->warehouseTypeId = $warehouseTypeId;
    }
}

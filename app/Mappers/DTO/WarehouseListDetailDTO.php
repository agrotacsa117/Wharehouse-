<?php

namespace App\Mappers\DTO;

use DateTime;

class WarehouseListDetailDTO
{
    private int $id;

    private string $warehouseName;

    private DateTime $createdAt;

    private DateTime $updatedAt;

    private int $userLastUpdate;

    private string $userName;

    private string $warehouseKey;

    private string $warehouseManager;

    private string $phoneNumber;

    private string $email;

    private int $warehouseTypeId;

    private string $categoryWarehouse;

    private int $locationId;

    private string $headquartersName;

    public function __construct(
        int $id,
        string $warehouseName,
        DateTime $createdAt,
        DateTime $updatedAt,
        int $userLastUpdate,
        string $userName,
        string $warehouseKey,
        string $warehouseManager,
        string $phoneNumber,
        string $email,
        int $warehouseTypeId,
        string $categoryWarehouse,
        int $locationId,
        string $headquartersName
    ) {
        $this->id = $id;
        $this->warehouseName = $warehouseName;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->userLastUpdate = $userLastUpdate;
        $this->userName = $userName;
        $this->warehouseKey = $warehouseKey;
        $this->warehouseManager = $warehouseManager;
        $this->phoneNumber = $phoneNumber;
        $this->email = $email;
        $this->warehouseTypeId = $warehouseTypeId;
        $this->categoryWarehouse = $categoryWarehouse;
        $this->locationId = $locationId;
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

    public function getWarehouseName(): string
    {
        return $this->warehouseName;
    }

    public function setWarehouseName(string $warehouseName): void
    {
        $this->warehouseName = $warehouseName;
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

    public function getUserLastUpdate(): int
    {
        return $this->userLastUpdate;
    }

    public function setUserLastUpdate(int $userLastUpdate): void
    {
        $this->userLastUpdate = $userLastUpdate;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): void
    {
        $this->userName = $userName;
    }

    public function getWarehouseKey(): string
    {
        return $this->warehouseKey;
    }

    public function setWarehouseKey(string $warehouseKey): void
    {
        $this->warehouseKey = $warehouseKey;
    }

    public function getWarehouseManager(): string
    {
        return $this->warehouseManager;
    }

    public function setWarehouseManager(string $warehouseManager): void
    {
        $this->warehouseManager = $warehouseManager;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getWarehouseTypeId(): int
    {
        return $this->warehouseTypeId;
    }

    public function setWarehouseTypeId(int $warehouseTypeId): void
    {
        $this->warehouseTypeId = $warehouseTypeId;
    }

    public function getCategoryWarehouse(): string
    {
        return $this->categoryWarehouse;
    }

    public function setCategoryWarehouse(string $categoryWarehouse): void
    {
        $this->categoryWarehouse = $categoryWarehouse;
    }

    public function getLocationId(): int
    {
        return $this->locationId;
    }

    public function setLocationId(int $locationId): void
    {
        $this->locationId = $locationId;
    }

    public function getHeadquartersName(): string
    {
        return $this->headquartersName;
    }

    public function setHeadquartersName(string $headquartersName): void
    {
        $this->headquartersName = $headquartersName;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'warehouseName' => $this->warehouseName,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d H:i:s'),
            'userLastUpdate' => $this->userLastUpdate,
            'userName' => $this->userName,
            'warehouseKey' => $this->warehouseKey,
            'warehouseManager' => $this->warehouseManager,
            'phoneNumber' => $this->phoneNumber,
            'email' => $this->email,
            'warehouseTypeId' => $this->warehouseTypeId,
            'categoryWarehouse' => $this->categoryWarehouse,
            'locationId' => $this->locationId,
            'headquartersName' => $this->headquartersName,
        ];
    }
}

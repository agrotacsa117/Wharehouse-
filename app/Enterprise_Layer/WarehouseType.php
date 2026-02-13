<?php

declare(strict_types=1);

namespace App\Enterprise_Layer;

use DateTime;

class WarehouseType
{
    private int $id;
    private string $categoryWarehouse;
    private DateTime $createdAt;
    private DateTime $updatedAt;

    public function __construct(string $categoryWarehouse)
    {
        $this->categoryWarehouse = $categoryWarehouse;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getCategoryWarehouse(): string
    {
        return $this->categoryWarehouse;
    }

    public function setCategoryWarehouse(string $categoryWarehouse): void
    {
        $this->categoryWarehouse = $categoryWarehouse;
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


}

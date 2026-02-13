<?php

namespace App\Mappers\DTO;

class WarehouseTypeDetailDTO
{
    private int $id;
    private string $categoryWarehouse;
    private \DateTime $createdAt;
    private \DateTime $updatedAt;


    public function __construct(
        int $id,
        string $categoryWarehouse,
        \DateTime $createdAt,
        \DateTime $updatedAt
    ) {
        $this->id = $id;
        $this->categoryWarehouse = $categoryWarehouse;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }


    public function getId(): int
    {
        return $this->id;
    }

    public function getCategoryWarehouse(): string
    {
        return $this->categoryWarehouse;
    }

    public function setCategoryWarehouse(string $categoryWarehouse): void
    {
        $this->categoryWarehouse = $categoryWarehouse;
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

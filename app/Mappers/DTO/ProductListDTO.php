<?php

namespace App\Mappers\DTO;

class ProductListDTO
{
    private string $id;
    private string $productName;

    public function __construct(string $id, string $productName)
    {
        $this->id = $id;
        $this->productName = $productName;
    }

    // Getter para id
    public function getId(): string
    {
        return $this->id;
    }

    // Setter para id
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    // Getter para productName
    public function getProductName(): string
    {
        return $this->productName;
    }

    // Setter para productName
    public function setProductName(string $productName): void
    {
        $this->productName = $productName;
    }
}

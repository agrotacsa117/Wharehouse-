<?php

namespace App\Mappers\DTO;

class WarehouseStockDTO implements \JsonSerializable{

    private string $productId;
    private string $warehouseName;
    private int $stock;

    public function __construct(
        string $productId,
        string $warehouseName, 
        int $stock)
    {
        $this->productId = $productId;
        $this->warehouseName = $warehouseName;
        $this->stock = $stock;
    }

    public function jsonSerialize(): array
    {
        return [
            'product_id'     => $this->productId,
            'product_name' => $this->warehouseName,
            'stock'          => $this->stock,
        ];
    }
    
    public function getProductId(): string
    {
        return $this->productId;
    }

    /**
     * @return string
     */
    public function getWarehouseName(): string
    {
        return $this->warehouseName;
    }

    /**
     * @return int
     */
    public function getStock(): int
    {
        return $this->stock;
    }
}
<?php

namespace App\Mappers\DTO;

class WarehouseStockDTO implements \JsonSerializable
{
    private ?int $warehouseId;

    private ?string $warehouseName;

    private string $productId;

    private string $productName;

    private int $stock;

    public function __construct(
        string $productId,
        string $productName,
        int $stock,
        ?int $warehouseId,
        ?string $warehouseName
    ) {
        $this->productId = $productId;
        $this->productName = $productName;
        $this->stock = $stock;
        $this->warehouseId = $warehouseId;
        $this->warehouseName = $warehouseName;
    }

    public function jsonSerialize(): array
    {
        return [
            'product_id' => $this->productId,
            'product_name' => $this->productName,
            'stock' => $this->stock,
            'warehouse_id' => $this->warehouseId,
            'warehouse_name' => $this->warehouseName,
        ];
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getProductName(): string
    {
        return $this->productName;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function getWarehouseId(): ?int
    {
        return $this->warehouseId;
    }

    public function getWarehouseName(): ?string
    {
        return $this->warehouseName;
    }
}

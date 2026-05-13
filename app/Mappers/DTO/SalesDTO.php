<?php

namespace App\Mappers\DTO;

class SalesDTO implements \JsonSerializable
{
    private string $movementId;
    private int $invoiceSap;
    private string $createdAt;
    private string $updatedAt;
    private string $productCode;
    private string $productName;
    private string $warehouseOrigin;
    private string $lotNumber;
    private int $quantity;
    private string $user;

    public function __construct(
        string $movementId,
        int $invoiceSap,
        string $createdAt,
        string $updatedAt,
        string $productCode,
        string $productName,
        string $warehouseOrigin,
        string $lotNumber,
        int $quantity,
        string $user
    ) {
        $this->movementId = $movementId;
        $this->invoiceSap = $invoiceSap;
        $this->createdAt  = $createdAt;
        $this->updatedAt  = $updatedAt;
        $this->productCode = $productCode;
        $this->productName = $productName;
        $this->warehouseOrigin = $warehouseOrigin;
        $this->lotNumber = $lotNumber;
        $this->quantity = $quantity;
        $this->user = $user;
    }

    /**
     * Getters
     */
    public function getMovementId(): string
    {
        return $this->movementId;
    }

    public function getInvoiceSap(): int
    {
        return $this->invoiceSap;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    public function getProductCode(): string
    {
        return $this->productCode;
    }
    public function getProductName(): string
    {
        return $this->productName;
    }
    public function getWarehouseOrigin(): string
    {
        return $this->warehouseOrigin;
    }
    public function getLotNumber(): string
    {
        return $this->lotNumber;
    }
    public function getQuantity(): int
    {
        return $this->quantity;
    }
    public function getUser(): string
    {
        return $this->user;
    }
    /**
     * Implementación de JsonSerializable
     */
    public function jsonSerialize(): array
    {
        return [
            'movementId' => $this->movementId,
            'invoiceSap' => $this->invoiceSap,
            'createdAt'  => $this->createdAt,
            'updatedAt'  => $this->updatedAt,
            'productCode'     => $this->productCode,
            'productName'     => $this->productName,
            'warehouseOrigin' => $this->warehouseOrigin,
            'lotNumber'       => $this->lotNumber,
            'quantity'        => $this->quantity,
            'user'            => $this->user
        ];
    }
}

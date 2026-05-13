<?php

namespace App\Mappers\DTO;

class WarehouseMovementsListDetailDTO implements \JsonSerializable
{
    private int $id;
    private string $folio;
    private int $warehouseInventoryId;
    private int $warehouseId;
    private string $warehousesName;
    private string $productId;
    private string $productName;
    private ?int $rack;
    private ?int $level;
    private string $lotNumber;
    private string $movementType;
    private int $quantity;
    private string $reason;
    private int $userId;
    private string $userName;
    private string $createdAt;
    private string $expirationDate;
    private string $destinationWarehouseName;


    public function __construct(
        int $id,
        string $folio,
        int $warehouseInventoryId,
        int $warehouseId,
        string $warehousesName,
        string $productId,
        string $productName,
        ?int $rack,
        ?int $level,
        string $lotNumber,
        string $movementType,
        int $quantity,
        string $reason,
        int $userId,
        string $userName,
        string $createdAt,
        string $expirationDate,
        string $destinationWarehouseName
    ) {
        $this->id = $id;
        $this->folio = $folio;
        $this->warehouseInventoryId = $warehouseInventoryId;
        $this->warehouseId = $warehouseId;
        $this->warehousesName = $warehousesName;
        $this->productId = $productId;
        $this->productName = $productName;
        $this->rack = $rack;
        $this->level = $level;
        $this->lotNumber = $lotNumber;
        $this->movementType = $movementType;
        $this->quantity = $quantity;
        $this->reason = $reason;
        $this->userId = $userId;
        $this->userName = $userName;
        $this->createdAt = $createdAt;
        $this->expirationDate = $expirationDate;
        $this->destinationWarehouseName = $destinationWarehouseName;
    }

    // Getters
    public function getExpirationDate(): string
    {
        return $this->expirationDate;
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function getFolio(): string
    {
        return $this->folio;
    }
    public function getWarehouseInventoryId(): int
    {
        return $this->warehouseInventoryId;
    }
    public function getWarehouseId(): int
    {
        return $this->warehouseId;
    }
    public function getWarehousesName(): string
    {
        return $this->warehousesName;
    }
    public function getProductId(): string
    {
        return $this->productId;
    }
    public function getProductName(): string
    {
        return $this->productName;
    }
    public function getRack(): ?int
    {
        return $this->rack;
    }
    public function getLevel(): ?int
    {
        return $this->level;
    }
    public function getLotNumber(): string
    {
        return $this->lotNumber;
    }
    public function getMovementType(): string
    {
        return $this->movementType;
    }
    public function getQuantity(): int
    {
        return $this->quantity;
    }
    public function getReason(): string
    {
        return $this->reason;
    }
    public function getUserId(): int
    {
        return $this->userId;
    }
    public function getUserName(): string
    {
        return $this->userName;
    }
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    // Setters

    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function setFolio(string $folio): void
    {
        $this->folio = $folio;
    }
    public function setWarehouseInventoryId(int $warehouseInventoryId): void
    {
        $this->warehouseInventoryId = $warehouseInventoryId;
    }
    public function setWarehouseId(int $warehouseId): void
    {
        $this->warehouseId = $warehouseId;
    }
    public function setWarehousesName(string $warehousesName): void
    {
        $this->warehousesName = $warehousesName;
    }
    public function setProductId(string $productId): void
    {
        $this->productId = $productId;
    }
    public function setProductName(string $productName): void
    {
        $this->productName = $productName;
    }
    public function setRack(int $rack): void
    {
        $this->rack = $rack;
    }
    public function setLevel(int $level): void
    {
        $this->level = $level;
    }
    public function setLotNumber(string $lotNumber): void
    {
        $this->lotNumber = $lotNumber;
    }
    public function setMovementType(string $movementType): void
    {
        $this->movementType = $movementType;
    }
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }
    public function setReason(string $reason): void
    {
        $this->reason = $reason;
    }
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }
    public function setUserName(string $userName): void
    {
        $this->userName = $userName;
    }
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }


    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'folio' => $this->folio,
            'warehouseInventoryId' => $this->warehouseInventoryId,
            'warehouseId' => $this->warehouseId,
            'warehousesName' => $this->warehousesName,
            'productId' => $this->productId,
            'productName' => $this->productName,
            'rack' => $this->rack,
            'level' => $this->level,
            'lotNumber' => $this->lotNumber,
            'movementType' => $this->movementType,
            'quantity' => $this->quantity,
            'reason' => $this->reason,
            'userId' => $this->userId,
            'userName' => $this->userName,
            'createdAt' => $this->createdAt,
            'expiration_date' => $this->expirationDate,
            'destinationWarehouseName' => $this->destinationWarehouseName
        ];
    }


    public static function fromModel(array $movement): self
    {    
        return new self(
            $movement['id'],
            $movement['folio'],
            $movement['warehouse_inventory_id'],
            $movement['inventory']['warehouse_id'],
            $movement['inventory']['warehouse']['warehouses_name'],
            $movement['inventory']['product_id'],
            $movement['inventory']['warehouse_name'],
            $movement['inventory']['rack'],
            $movement['inventory']['_level'],
            $movement['inventory']['lot_number'],
            $movement['movement_type'],
            $movement['quantity'],
            $movement['reason'],
            $movement['user_id'],
            $movement['user']['name'],
            date(
                'Y-m-d',
                strtotime(
                    $movement['created_at']
                )
            ),
            date(
                'Y-m-d',
                strtotime(
                    $movement['inventory']['expiration_date']
                )
            ),
            $movement['source_warehouse']['warehouses_name']?? "Sin destino"
        );


    }
}

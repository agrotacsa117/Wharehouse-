<?php

namespace App\Contracts;

use App\Application_Layer\ResultPattern;
use App\Mappers\DTO\TransferRequestDTO;

interface StockInTransitServiceI
{
    public function createTransfer(TransferRequestDTO $dto, int $userId): ResultPattern;

    public function confirmReception(int $stockInTransitId, int $userId): ResultPattern;

    public function cancelTransfer(int $stockInTransitId): ResultPattern;

    public function getPendingTransfers(int $warehouseId): array;

    public function getInTransitStock(?int $warehouseId = null): array;

    public function generateTransferFolio(): string;
}

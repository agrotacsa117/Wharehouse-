<?php

namespace App\Contracts;

use App\Application_Layer\ResultPattern;
use App\Enterprise_Layer\StockInTransit;

interface StockInTransitRepositoryI
{
    public function save(StockInTransit $stockInTransit): StockInTransit;

    public function findById(int $id): ?StockInTransit;

    public function findByFolio(string $folio): ?StockInTransit;

    public function findPendingByWarehouse(int $warehouseId): array;

    public function findByOriginWarehouse(int $warehouseId): array;

    public function updateStatus(int $id, string $status, ?int $receivedBy = null): bool;

    public function getNextFolio(): string;
}

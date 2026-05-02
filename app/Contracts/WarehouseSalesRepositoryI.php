<?php

namespace App\Contracts;

use App\Enterprise_Layer\WarehouseSalesEntity;

interface WarehouseSalesRepositoryI
{
    public function save(
        WarehouseSalesEntity $WarehouseSalesEntity
    ): ?WarehouseSalesEntity;

    public function findAll(): array;

    public function dateRangeFilter(
        string $startDate,
        string $endDate
    ): array;




}

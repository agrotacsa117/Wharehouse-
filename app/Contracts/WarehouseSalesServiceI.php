<?php

namespace App\Contracts;

use App\Application_Layer\ResultPattern;
use App\Mappers\DTO\Requests\WarehouseSalesRequestDTO;

interface WarehouseSalesServiceI
{
    public function saveWarehouseSales(
        WarehouseSalesRequestDTO $warehouseSalesRequestDTO
    ): ResultPattern;

    public function getSalesReport(): array;

    public function filterSales(
        string $searchParam
    ): array;
}

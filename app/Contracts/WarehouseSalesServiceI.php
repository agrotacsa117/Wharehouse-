<?php

namespace App\Contracts;

use App\Mappers\DTO\Requests\WarehouseSalesRequestDTO;
use App\Application_Layer\ResultPattern;

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

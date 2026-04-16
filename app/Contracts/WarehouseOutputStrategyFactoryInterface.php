<?php

namespace App\Contracts;

use App\Contracts\WarehouseOutputStrategy;

interface WarehouseOutputStrategyFactoryInterface
{
    public function make(string $type): WarehouseOutputStrategy;
}

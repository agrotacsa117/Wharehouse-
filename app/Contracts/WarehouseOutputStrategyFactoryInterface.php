<?php

namespace App\Contracts;

interface WarehouseOutputStrategyFactoryInterface
{
    public function make(string $type): WarehouseOutputStrategy;
}

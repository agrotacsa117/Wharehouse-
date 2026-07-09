<?php

namespace App\Contracts;

interface ReversalStrategyFactoryInterface
{
    public function make(
        string $type): ReversalStrategyInterface;
}

<?php

namespace App\Contracts;

use App\Application_Layer\ResultPattern;
use App\Mappers\DTO\WarehouseMovementsDTO;

interface ReversalStrategyInterface
{
    // Nos dice qué tipo de movimiento crear (ej. si es IN, devuelve OUT)
    public function getInverseType(): string;

    // Ejecuta la lógica matemática en la base de datos
    public function processCountermovement(
        WarehouseMovementsDTO $warehouseMovementsDTO): ResultPattern;
}

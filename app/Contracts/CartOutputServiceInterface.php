<?php

declare(strict_types=1);

namespace App\Contracts;

interface CartOutputServiceInterface
{
    /**
     * @param  array  $shared  Datos comunes a todos los ítems del carrito
     * @param  array  $items  Lista de ítems, cada uno con al menos 'warehouseInventoryId'
     * @return array Lista de resultados por ítem: ['warehouseInventoryId', 'ok', 'message']
     */
    public function processBatch(string $movementType, array $shared, array $items, int $userId): array;
}

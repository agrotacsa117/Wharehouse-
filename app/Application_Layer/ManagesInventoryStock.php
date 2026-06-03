<?php

namespace App\Application_Layer;

use App\Contracts\WarehouseInventoryRepositoryInterface;

class ManagesInventoryStock
{
    public static function validateStockAvailability(
        WarehouseInventoryRepositoryInterface $inventoryRepository,
        int $warehouseInventoryId,
        int $amountToWithdraw
    ): ResultPattern {

        $currentQuantity = $inventoryRepository->findQuantityByIdWithLock(
            $warehouseInventoryId
        );

        if ($amountToWithdraw > $currentQuantity) {
            return ResultPattern::failure(
                '¡Error! No puede retirar cantidad mayor al stock disponible.'
            );
        }

        return ResultPattern::success($currentQuantity);
    }

    public static function reduceStock(
        int $warehouseInventoryId,
        int $currentQuantity,
        int $amountToWithdraw,
        WarehouseInventoryRepositoryInterface $inventoryRepository
    ): ResultPattern {

        $newQuantity = $currentQuantity - $amountToWithdraw;

        $updated = $inventoryRepository->updateQuantity(
            $warehouseInventoryId,
            $newQuantity
        );

        if (! $updated) {
            return ResultPattern::failure('Error al actualizar el inventario');
        }

        if ($newQuantity === 0) {
            $inventoryRepository
                ->updateActiveInventory(
                    $warehouseInventoryId
                );
        }

        return ResultPattern::success($newQuantity);
    }
}

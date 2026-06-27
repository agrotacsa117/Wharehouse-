<?php

namespace App\Application_Layer;

use App\Contracts\WarehouseInventoryRepositoryInterface;

class ManagesInventoryStock
{
    public static function validateStockAvailability(
        int $amountToWithdraw,
        int $currentQuantity,
        bool $forceNegativeStock
    ): ResultPattern {

        if ($amountToWithdraw > $currentQuantity
        && ! $forceNegativeStock) {
            return ResultPattern::failure(
                '¡Error! No puede retirar cantidad mayor al stock disponible.'
            );
        }

        return ResultPattern::success($currentQuantity);
    }

    public static function reduceStock(
        int $warehouseInventoryId,
        int $amountToWithdraw,
        int $currentQuantity,
        WarehouseInventoryRepositoryInterface $inventoryRepository
    ): ResultPattern {

        $newQuantity = $currentQuantity - $amountToWithdraw;

        $updated = $inventoryRepository->updateQuantity(
            $warehouseInventoryId,
            $newQuantity
        );

        if (! $updated) {
            return ResultPattern::failure(
                'Error al actualizar el inventario');
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

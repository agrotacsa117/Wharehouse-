<?php

namespace App\Application_Layer\Repository_Implementation;

use App\Contracts\StockInTransitRepositoryI;
use App\Enterprise_Layer\StockInTransit;
use App\Models\StockInTransitModel;
use App\Models\WarehouseInventoryModel;

class StockInTransitRepositoryImplementation implements StockInTransitRepositoryI
{
    public function save(StockInTransit $stockInTransit): StockInTransit
    {
        $model = new StockInTransitModel();
        $model->inventory_id = $stockInTransit->getInventoryId();
        $model->origin_warehouse_id = $stockInTransit->getOriginWarehouseId();
        $model->destination_warehouse_id = $stockInTransit->getDestinationWarehouseId();
        $model->quantity = $stockInTransit->getQuantity();
        $model->status = $stockInTransit->getStatus();
        $model->folio = $stockInTransit->getFolio();
        $model->sent_at = $stockInTransit->getSentAt();
        $model->save();

        $stockInTransit->setId($model->id);
        return $stockInTransit;
    }

    public function findById(int $id): ?StockInTransit
    {
        $model = StockInTransitModel::find($id);
        if (!$model) {
            return null;
        }
        return $this->modelToEntity($model);
    }

    public function findByFolio(string $folio): ?StockInTransit
    {
        $model = StockInTransitModel::where('folio', $folio)->first();
        if (!$model) {
            return null;
        }
        return $this->modelToEntity($model);
    }

    public function findPendingByWarehouse(int $warehouseId): array
    {
        $models = StockInTransitModel::with(['originWarehouse', 'destinationWarehouse', 'inventory'])
            ->where('destination_warehouse_id', $warehouseId)
            ->where('status', 'PENDING_RECEPTION')
            ->orderBy('sent_at', 'desc')
            ->get();

        return $models->map(fn($m) => $this->modelToEntity($m))->toArray();
    }

    public function findByOriginWarehouse(int $warehouseId): array
    {
        $models = StockInTransitModel::with(['originWarehouse', 'destinationWarehouse', 'inventory'])
            ->where('origin_warehouse_id', $warehouseId)
            ->orderBy('sent_at', 'desc')
            ->get();

        return $models->map(fn($m) => $this->modelToEntity($m))->toArray();
    }

    public function updateStatus(int $id, string $status, ?int $receivedBy = null): bool
    {
        $model = StockInTransitModel::find($id);
        if (!$model) {
            return false;
        }

        $model->status = $status;
        if ($status === 'RECEIVED' && $receivedBy) {
            $model->received_at = now();
            $model->received_by = $receivedBy;
        }
        return $model->save();
    }

    public function getNextFolio(): string
    {
        $lastFolio = StockInTransitModel::orderBy('id', 'desc')->first();
        $nextNumber = $lastFolio ? ((int)substr($lastFolio->folio, 4) ?? 0) + 1 : 1;
        return 'TRF-' . str_pad((string)$nextNumber, 6, '0', STR_PAD_LEFT);
    }

    private function modelToEntity(StockInTransitModel $model): StockInTransit
    {
        $entity = new StockInTransit(
            $model->inventory_id,
            $model->origin_warehouse_id,
            $model->destination_warehouse_id,
            $model->quantity,
            $model->folio
        );

        $entity->setId($model->id);

        return $entity;
    }
}

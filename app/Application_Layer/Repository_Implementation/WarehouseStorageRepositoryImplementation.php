<?php

namespace App\Application_Layer\Repository_Implementation;

use App\Application_Layer\ResultPattern;
use App\Contracts\WarehouseEntityToWarehouseModelMapperI;
use App\Contracts\WarehouseStorageRepositoryInterface;
use App\Enterprise_Layer\Warehouse;
use App\Models\WarehouseModel;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class WarehouseStorageRepositoryImplementation implements WarehouseStorageRepositoryInterface
{
    private WarehouseEntityToWarehouseModelMapperI $entityToModelMapper;

    public function __construct(WarehouseEntityToWarehouseModelMapperI $entityToModelMapper)
    {
        $this->entityToModelMapper = $entityToModelMapper;
    }

    #[\Override]
    public function deleteWarehouseByWarehouseId(int $warehouseId): ResultPattern
    {
        $warehouseModel = $this->findWarehouseById($warehouseId);

        if (! $warehouseModel) {
            return ResultPattern::failure('Warehouse not found');
        }

        try {
            $warehouseModel->delete();
        } catch (QueryException $e) {
            return ResultPattern::failure($e->getMessage());
        }

        return ResultPattern::success('Warehouse deleted successfully');
    }

    #[\Override]
    public function findWarehouseById(int $warehouseId): ?WarehouseModel
    {
        return WarehouseModel::find($warehouseId);
    }

    #[\Override]
    public function saveWarehouse(Warehouse $warehouse): ResultPattern
    {
        $warehouseModel = $this->entityToModelMapper
            ->convertDomainEntityToModel(
                $warehouse
            );

        try {
            $warehouseModel->save();
        } catch (QueryException $e) {
            return ResultPattern::failure($e->getMessage());
        }

        return ResultPattern::success('Warehouse saved successfully');
    }

    #[\Override]
    public function updateFieldsByWarehouseId(int $warehouseId, array $fields): ResultPattern
    {
        $warehouseModel = $this->findWarehouseById($warehouseId);

        if (! $warehouseModel) {
            return ResultPattern::failure('Warehouse not found');
        }

        try {
            $warehouseModel->fill($fields);
            $warehouseModel->save();
        } catch (QueryException $e) {
            return ResultPattern::failure($e->getMessage());
        }

        return ResultPattern::success('Warehouse updated successfully');
    }

    #[\Override]
    public function updateWarehouse(Warehouse $warehouse): ResultPattern
    {

        try {
            Log::info('Repository: updateWarehouse called', [
                'warehouse_id' => $warehouse->getWarehousesId(),
                'name' => $warehouse->getWarehousesName(),
                'type_id' => $warehouse->getWarehouseTypeId(),
                'location_id' => $warehouse->getLocationId(),
            ]);

            $warehouseModel = $this->entityToModelMapper
                ->convertDomainEntityToModel($warehouse);

            $existingModel = WarehouseModel::find($warehouse->getWarehousesId());

            if (! $existingModel) {
                return ResultPattern::failure('Almacén no encontrado.');
            }

            $existingModel->warehouses_name = $warehouseModel->warehouses_name;
            $existingModel->warehouses_key = $warehouseModel->warehouses_key;
            $existingModel->warehouse_manager = $warehouseModel->warehouse_manager;
            $existingModel->phone_number = $warehouseModel->phone_number;
            $existingModel->email = $warehouseModel->email;
            $existingModel->location_id = $warehouseModel->location_id;
            $existingModel->warehouse_type_id = $warehouseModel->warehouse_type_id;
            $existingModel->user_last_update = $warehouseModel->user_last_update;
            $existingModel->save();

            Log::info('Repository: save completed');

            return ResultPattern::success('Almacén actualizado correctamente.');
        } catch (QueryException $e) {
            Log::error('Repository error: '.$e->getMessage());

            return ResultPattern::failure('Error al actualizar: '.$e->getMessage());
        }
    }

    public function getIdAndName(): array
    {
        $listWarehouses = WarehouseModel::select(
            'id',
            'warehouses_name'
        )->get()->toArray();

        return $listWarehouses;
    }

    public function getNameById(int $warehouseId): string
    {
        $warehousesName = WarehouseModel::where(
            'id',
            $warehouseId
        )->value('warehouses_name');

        return $warehousesName;
    }

    public function findAll(): array
    {
        $warehouses = WarehouseModel::with([
            'userLastUpdate:id,name',
            'location:id,headquarters_name',
            'warehouseType:id,category_warehouse',
        ])->get();

        return $warehouses->toArray();
    }

    public function count(): int
    {
        return WarehouseModel::count();
    }

    public function findWereIn(array $warehouseIds): array
    {
        $warehouse = WarehouseModel::select(
            'id',
            'warehouses_name',
            'location_id'
        )->with('location:id,headquarters_name')
            ->whereIn('id', $warehouseIds)
            ->get();
        $warehouse = $warehouse->toArray();

        return $warehouse;
    }

    public function findByLocationId(int $locationId): array
    {
        $warehouses = WarehouseModel::select(
            'id',
            'warehouses_name',
            'location_id'
        )->with('location:id,headquarters_name')
            ->where('location_id', $locationId)
            ->get();

        return $warehouses->toArray();
    }
}

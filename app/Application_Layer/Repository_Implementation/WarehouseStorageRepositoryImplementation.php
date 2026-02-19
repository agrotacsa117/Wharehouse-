<?php

namespace App\Application_Layer\Repository_Implementation;

use App\Application_Layer\ResultPattern;
use App\Contracts\InterfaceDTOToEntityMapper;
use App\Contracts\WarehouseStorageRepositoryInterface;
use App\Enterprise_Layer\Warehouse;
use App\Models\WarehouseModel;
use App\Mappers\WarehouseToWarehouseModelMapper;
use Illuminate\Database\QueryException;
use App\Contracts\WarehouseEntityToWarehouseModelMapperI;

class WarehouseStorageRepositoryImplementation implements
    WarehouseStorageRepositoryInterface
{
    private WarehouseEntityToWarehouseModelMapperI $entityToModelMapper;

    /**
     * @param WarehouseEntityToWarehouseModelMapperI $entityToModelMapper
     */
    public function __construct(WarehouseEntityToWarehouseModelMapperI $entityToModelMapper)
    {
        $this->entityToModelMapper = $entityToModelMapper;
    }

    #[\Override]
    public function deleteWarehouseByWarehouseId(int $warehouseId): ResultPattern
    {
        $warehouseModel = $this->findWarehouseById($warehouseId);

        if (!$warehouseModel) {
            return ResultPattern::failure("Warehouse not found");
        }

        try {
            $warehouseModel->delete();
        } catch (QueryException $e) {
            return ResultPattern::failure($e->getMessage());
        }

        return ResultPattern::success("Warehouse deleted successfully");
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

        return ResultPattern::success("Warehouse saved successfully");
    }

    #[\Override]
    public function updateFieldsByWarehouseId(int $warehouseId, array $fields): ResultPattern
    {
        $warehouseModel = $this->findWarehouseById($warehouseId);

        if (!$warehouseModel) {
            return ResultPattern::failure("Warehouse not found");
        }

        try {
            $warehouseModel->fill($fields);
            $warehouseModel->save();
        } catch (QueryException $e) {
            return ResultPattern::failure($e->getMessage());
        }

        return ResultPattern::success("Warehouse updated successfully");
    }

    #[\Override]
    public function updateWarehouse(Warehouse $warehouse): ResultPattern
    {

        return ResultPattern::success("Warehouse updated successfully");
    }

    public function getIdAndName(): array
    {
        $listWarehouses = WarehouseModel::select(
            'id',
            'warehouses_name'
        )->get()->toArray();

        return $listWarehouses;
    }
}

<?php

namespace App\Application_Layer\Repository_Implementation;

use App\Contracts\WarehouseTypeRepositoryInterface;
use App\Enterprise_Layer\WarehouseType;
use App\Contracts\ModelMapperToEntityInterface;
use App\Contracts\EntityToModelMapperInterface;
use App\Models\WarehouseTypeModel;
use App\Infrastructure\Exception\CouldNotPersistLocationException;
use App\Infrastructure\Exception\CouldNotDeleteLocationException;
use Illuminate\Database\QueryException;

class WarehouseTypeRepositoryImplementation implements WarehouseTypeRepositoryInterface
{
    private ModelMapperToEntityInterface $warehouseTypeModelToWarehouseTypeMapper;
    private EntityToModelMapperInterface $WarehouseTypeEntityToWarehouseTypeModelMapper;

    public function __construct(
        ModelMapperToEntityInterface $warehouseTypeModelToWarehouseTypeMapper,
        EntityToModelMapperInterface $WarehouseTypeEntityToWarehouseTypeModelMapper
    ) {
        $this->warehouseTypeModelToWarehouseTypeMapper = $warehouseTypeModelToWarehouseTypeMapper;
        $this->WarehouseTypeEntityToWarehouseTypeModelMapper = $WarehouseTypeEntityToWarehouseTypeModelMapper;
    }

    public function findById(int $id): ?WarehouseType
    {
        $warehouseTypeModel = WarehouseTypeModel::find($id);

        if (!$warehouseTypeModel) {
            return null;
        }

        return $this->warehouseTypeModelToWarehouseTypeMapper
        ->convertModelToEntity(
            $warehouseTypeModel
        );
    }


    public function getAllCategoryWarehouse(): array
    {
        $warehouseType = WarehouseTypeModel::select(
            'id',
            'category_warehouse'
        )->get()->toArray();

        return $warehouseType;
    }

    public function saveLocation(WarehouseType $location): void
    {

        try {
            $warehouseType = $this->WarehouseTypeEntityToWarehouseTypeModelMapper
            ->convertDomainEntityToModel($location);

            $warehouseType->save();
        } catch (\Throwable $th) {
            throw new CouldNotPersistLocationException(
                'Error saving warehouse type',
                0,
                $th
            );
        }
    }

    public function deleteLocation(int $warehouseTypeId): void
    {
        $warehouseTypeModel = WarehouseTypeModel::find(
            $warehouseTypeId
        );

        try {
            $warehouseTypeModel->delete();
        } catch (QueryException $th) {
            throw new CouldNotDeleteLocationException(
                'Error deleting warehouse type',
                0,
                $th
            );
        }
    }

    public function updateLocation(WarehouseType $location): void
    {
        $warehouseTypeModel = $this->WarehouseTypeEntityToWarehouseTypeModelMapper
        ->convertDomainEntityToModel(
            $location
        );

        try {
             $warehouseTypeModel->save();
        } catch (\Throwable $th) {
            throw new CouldNotPersistLocationException(
                'Error updating warehouse type',
                0,
                $th
            );
        }
    }
}

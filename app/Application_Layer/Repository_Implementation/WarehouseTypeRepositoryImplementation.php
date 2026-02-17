<?php

namespace App\Application_Layer\Repository_Implementation;

use App\Contracts\WarehouseTypeRepositoryInterface;
use App\Enterprise_Layer\WarehouseType;
use App\Contracts\WarehouseTypeModelToWarehouseTypeEntityMapperI;
use App\Contracts\WarehouseEntityToWarehouseModelMapperI;
use App\Contracts\WarehouseTypeEntityToWarehouseTypeModelMapperI;
use App\Models\WarehouseTypeModel;
use App\Infrastructure\Exception\CouldNotPersistLocationException;
use App\Infrastructure\Exception\CouldNotDeleteLocationException;
use App\Mappers\WarehouseTypeEntityToWarehouseTypeModel;
use Illuminate\Database\QueryException;

class WarehouseTypeRepositoryImplementation implements WarehouseTypeRepositoryInterface
{
    private WarehouseTypeModelToWarehouseTypeEntityMapperI $warehouseTypeModelToWarehouseTypeMapper;
    private WarehouseTypeEntityToWarehouseTypeModelMapperI $warehouseTypeEntityToWarehouseTypeModelMapper;

    public function __construct(
        WarehouseTypeModelToWarehouseTypeEntityMapperI $warehouseTypeModelToWarehouseTypeMapper,
        WarehouseTypeEntityToWarehouseTypeModelMapperI $warehouseTypeEntityToWarehouseTypeModelMapper
    ) {
        $this->warehouseTypeModelToWarehouseTypeMapper = $warehouseTypeModelToWarehouseTypeMapper;
        $this->warehouseTypeEntityToWarehouseTypeModelMapper = $warehouseTypeEntityToWarehouseTypeModelMapper;
    }

    public function findById(int $id): ?WarehouseType
    {
        $warehouseTypeModel = WarehouseTypeModel::find($id);

        if (!$warehouseTypeModel) {
            return null;
        }

        return $this->warehouseTypeModelToWarehouseTypeMapper
        ->convertWarehouseTypeModelToWarehouseTypeEntity(
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

    public function saveWarehouseType(WarehouseType $warehouseType): void
    {

        try {
            $warehouseType = $this->warehouseTypeEntityToWarehouseTypeModelMapper
            ->convertWarehouseTypeDomainEntityToWarehouseTypeModel($warehouseType);

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
        $warehouseTypeModel = $this->warehouseTypeEntityToWarehouseTypeModelMapper
        ->convertWarehouseTypeDomainEntityToWarehouseTypeModel(
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

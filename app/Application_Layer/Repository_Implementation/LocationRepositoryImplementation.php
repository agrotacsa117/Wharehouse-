<?php

namespace App\Application_Layer\Repository_Implementation;

use App\Contracts\LocationRepositoryInterface;
use App\Models\LocationModel;
use App\Enterprise_Layer\Location;
use App\Contracts\ModelMapperToEntityInterface;
use App\Contracts\EntityToModelMapperInterface;
use App\Infrastructure\Exception\CouldNotPersistLocationException;
use App\Infrastructure\Exception\CouldNotDeleteLocationException;
use Illuminate\Database\QueryException;

class LocationRepositoryImplementation implements LocationRepositoryInterface
{
    private ModelMapperToEntityInterface $locationModelToEntityMapper;
    private EntityToModelMapperInterface $locationEntityToLocationModelMapper;

    public function __construct(
        ModelMapperToEntityInterface $locationModelToEntityMapper,
        EntityToModelMapperInterface $locationEntityToLocationModelMapper
    ) {
        $this->locationModelToEntityMapper = $locationModelToEntityMapper;
        $this->locationEntityToLocationModelMapper = $locationEntityToLocationModelMapper;
    }

    public function findById(int $id): ?Location
    {

        $model = LocationModel::find($id);

        if (!$model) {
            return null;
        }

        return  $this->locationModelToEntityMapper
        ->convertModelToEntity(
            $model
        );
    }

    public function getAllHeadquartersName(): array
    {
        $locations = LocationModel::select(
            'id',
            'headquarters_name'
        )->get()->toArray();

        return $locations;
    }

    public function saveLocation(Location $location): void
    {

        try {
            $locationModel =  $this->locationEntityToLocationModelMapper
            ->convertDomainEntityToModel(
                $location
            );

            $locationModel->save();
        } catch (\Throwable $e) {
            throw new CouldNotPersistLocationException(
                'Error saving Location',
                0,
                $e
            );
        }
    }

    public function deleteLocation(int $idLocation): void
    {
        $locationModel = LocationModel::find($idLocation);

        try {
            $locationModel->delete();
        } catch (QueryException $e) {
            throw new CouldNotDeleteLocationException(
                'Error deleting location',
                0,
                $e
            );
        }
    }

    public function updateLocation(Location $location): void
    {

        $locationModelUpdated = $this->locationEntityToLocationModelMapper
        ->convertDomainEntityToModel(
            $location
        );

        try {
            $locationModelUpdated->save();
        } catch (\Throwable $e) {
            throw new CouldNotPersistLocationException(
                'Error updating Location',
                0,
                $e
            );
        }


    }
}

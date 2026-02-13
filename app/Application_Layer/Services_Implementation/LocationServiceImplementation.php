<?php

namespace App\Application_Layer\Services_Implementation;

use App\Contracts\LocationServiceInterface;
use App\Application_Layer\ResultPattern;
use App\Contracts\LocationRepositoryInterface;
use App\Mappers\DTO\Requests\LocationRequestDTO;
use App\Mappers\DTO\LocationListDTO;
use LDAP\Result;
use App\Contracts\InterfaceMapperToEntity;
use App\Enterprise_Layer\Location;
use App\Contracts\InterfaceEntityToDTOMapper;

class LocationServiceImplementation implements LocationServiceInterface
{
    private LocationRepositoryInterface $locationRepository;

    private InterfaceMapperToEntity $locationRequestDTOToLocation;

    private Location $updatedLocationEntity;

    private InterfaceEntityToDTOMapper $entityToDTOMapper;
    
    public function __construct(
        LocationRepositoryInterface $locationRepository,
        InterfaceMapperToEntity $locationRequestDTOToLocation,
        InterfaceEntityToDTOMapper $entityToDTOMapper
    ) {
        $this->locationRepository = $locationRepository;
        $this->locationRequestDTOToLocation = $locationRequestDTOToLocation;
        $this->entityToDTOMapper = $entityToDTOMapper;
    }

    public function getLocationById(int $id): ResultPattern
    {
        if ($id <= 0) {
            return ResultPattern::failure(
                "¡Error: id invalido!"
            );
        }

        $locationEntity = $this->locationRepository->findById($id);

        if (!$locationEntity) {
            return ResultPattern::failure(
                "Error: ubicación no encontrada"
            );
        }

        $this->entityToDTOMapper->convertEntityToDTO($locationEntity);
        return ResultPattern::success($locationEntity);
    }

    public function listHeadquartersNames(): array
    {
        $locations = $this->locationRepository->getAllHeadquartersName();

        for ($i = 0; $i < count($locations); $i++) {
            $locations[$i] = new LocationListDTO(
                $locations[$i]['id'],
                $locations[$i]['headquarters_name']
            );
        }

        return  $locations;
    }

    public function createLocation(LocationRequestDTO $locationRequestDTO): ResultPattern
    {
        try {
            $locationEntity =  $this->locationRequestDTOToLocation
            ->convertDTOToEntity(
                $locationRequestDTO
            );

            $this->locationRepository->saveLocation(
                $locationEntity
            );
        } catch (\Throwable $th) {
            return ResultPattern::failure($th->getMessage());
        }

        return ResultPattern::success($locationRequestDTO);
    }

    public function updateLocation(
        int $id,
        LocationRequestDTO $updatedLocationRequestDTO
    ): ResultPattern {

        if ($this->getLocationById($id)->isFailure()) {
            return ResultPattern::failure(
                "¡Error: no es posible actualizar "
                ."la ubicación porque no existe!"
            );
        }

        try {
            $this->updatedLocationEntity =  $this->locationRequestDTOToLocation
            ->convertDTOToEntity(
                $updatedLocationRequestDTO
            );

            $this->updatedLocationEntity->setId($id);

            $this->locationRepository->updateLocation(
                $this->updatedLocationEntity
            );

        } catch (\Throwable $th) {
            return ResultPattern::failure(
                $th->getMessage()
            );
        }

        return ResultPattern::success($updatedLocationRequestDTO);
    }

    public function deleteLocation(int $id): ResultPattern
    {

        if ($this->getLocationById($id)->isFailure()) {
            return ResultPattern::failure(
                "¡Error: no es posible eliminar "
                ."la ubicación porque no existe!"
            );
        }

        try {
            $this->locationRepository->deleteLocation($id);
        } catch (\Throwable $th) {
            return ResultPattern::failure($th->getMessage());
        }
        return ResultPattern::success(null);
    }
}

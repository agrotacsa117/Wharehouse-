<?php

namespace App\Application_Layer\Services_Implementation;

use App\Contracts\WarehouseTypeServiceInterface;
use App\Mappers\DTO\Requests\WarehouseTypeRequestDTO;
use App\Application_Layer\ResultPattern;
use App\Mappers\WarehouseTypeEntityToWarehouseTypeModel;
use LDAP\Result;
use App\Contracts\WarehouseTypeRepositoryInterface;
use App\Contracts\WarehouseTypeEntityToWarehouseTypeDetailDTOMapperI;
use App\Contracts\WarehouseTypeRequestDTOToWarehouseTypeEntityMapperI;
use App\Mappers\DTO\WarehouseTypeListDTO;

class WarehouseTypeServiceImplementation implements WarehouseTypeServiceInterface
{
    private WarehouseTypeRepositoryInterface $warehouseTypeRepository;
    private WarehouseTypeEntityToWarehouseTypeDetailDTOMapperI $warehouseTypeEntityToDTOMapper;
    private WarehouseTypeRequestDTOToWarehouseTypeEntityMapperI $warehouseTypeRequestDTOToWarehouseTypeEntity;

    public function __construct(
        WarehouseTypeRepositoryInterface $warehouseTypeRepository,
        WarehouseTypeEntityToWarehouseTypeDetailDTOMapperI $warehouseTypeEntityToDTOMapper,
        WarehouseTypeRequestDTOToWarehouseTypeEntityMapperI $warehouseTypeRequestDTOToWarehouseTypeEntity
    ) {
        $this->warehouseTypeRepository = $warehouseTypeRepository;
        $this->warehouseTypeEntityToDTOMapper = $warehouseTypeEntityToDTOMapper;
        $this->warehouseTypeRequestDTOToWarehouseTypeEntity = $warehouseTypeRequestDTOToWarehouseTypeEntity;
    }

    public function getWarehouseTypeById(int $id): ResultPattern
    {
        if ($id <= 0) {
            return ResultPattern::failure(
                "¡Error: id invalido!"
            );
        }

        $warehouseTypeEntity = $this->warehouseTypeRepository
        ->findById($id);

        if (!$warehouseTypeEntity) {
            return ResultPattern::failure(
                "¡Error: tipo de almacen no encontrado!"
            );
        }

        $warehouseTypeDTO = $this->warehouseTypeEntityToDTOMapper
        ->convertWarehouseTypeEntityToWarehouseTypeDetailDTO($warehouseTypeEntity);

        return ResultPattern::success($warehouseTypeDTO);
    }

    public function listWarehouseTypesNames(): array
    {
        $warehouseTypes = $this->warehouseTypeRepository->getAllCategoryWarehouse();

        for ($i = 0; $i < count($warehouseTypes) ; $i++) {
            $warehouseTypes[$i] = new WarehouseTypeListDTO(
                $warehouseTypes[$i]['id'],
                $warehouseTypes[$i]['category_warehouse']
            );
        }

        return $warehouseTypes;
    }

    public function createWarehouseType(WarehouseTypeRequestDTO $warehouseTypeRequestDTO): ResultPattern
    {
        try {
            $warehouseTypeEntity = $this->warehouseTypeRequestDTOToWarehouseTypeEntity
            ->convertWarehouseTypeRequestDTOToWarehouseTypeEntity(
                $warehouseTypeRequestDTO
            );

            $warehouseTypeEntity->setId(0);

            $createdAt = new \DateTime(
                'now',
                new \DateTimeZone(
                    'America/Mexico_City'
                )
            );

            $warehouseTypeEntity->setCreatedAt($createdAt);
            $warehouseTypeEntity->setUpdatedAt($createdAt);
            $this->warehouseTypeRepository->saveWarehouseType(
                $warehouseTypeEntity
            );
        } catch (\Throwable $th) {
            return ResultPattern::failure(
                $th->getMessage()
            );
        }

        return ResultPattern::success($warehouseTypeRequestDTO);
    }

    public function updateWarehouseType(
        int $id,
        WarehouseTypeRequestDTO $dto
    ): ResultPattern {

        if ($this->getWarehouseTypeById($id)->isFailure()) {
            return ResultPattern::failure(
                "¡Error: no es posible actualizar "
                ."el tipo de almacen porque no existe!"
            );
        }


        return ResultPattern::success(true);
    }

    public function deleteWarehouseType(int $id): ResultPattern
    {
        if ($this->getWarehouseTypeById($id)->isFailure()) {
            return ResultPattern::failure(
                "¡Error: no es posible eliminar "
                ."el tipo de almacen porque no existe!"
            );

        }

        try {
            $this->warehouseTypeRepository->deleteLocation($id);
        } catch (\Throwable $th) {
            return ResultPattern::failure($th->getMessage());
        }
        return ResultPattern::success(true);
    }
}

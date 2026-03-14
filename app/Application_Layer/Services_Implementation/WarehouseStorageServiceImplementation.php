<?php

namespace App\Application_Layer\Services_Implementation;

use App\Contracts\WarehouseDTOToEntityMapperInterface;
use App\Contracts\WarehouseStorageRepositoryInterface;
use App\Contracts\WarehouseStorageServiceInterface;
use App\Models\WarehouseModel;
use App\Application_Layer\ResultPattern;
use App\Enterprise_Layer\Warehouse;
use App\Mappers\DTO\WarehouseDTO;
use App\Mappers\DTO\WarehouseListDTO;
use App\Mappers\DTO\WarehouseListDetailDTO;
use App\Mappers\DTO\WarehouseWithLocationResponseDTO;

class WarehouseStorageServiceImplementation implements WarehouseStorageServiceInterface
{
    private WarehouseStorageRepositoryInterface $warehouseStorageRepository;
    private WarehouseDTOToEntityMapperInterface $dTOToEntityMapper;
    private Warehouse $warehouseEntity;
    public function __construct(
        WarehouseStorageRepositoryInterface $warehouseStorageRepository,
        WarehouseDTOToEntityMapperInterface             $dTOToEntityMapper
    ) {
        $this->warehouseStorageRepository = $warehouseStorageRepository;
        $this->dTOToEntityMapper = $dTOToEntityMapper;
    }


    public function registerWarehouse(WarehouseDTO $warehouseDTO): ResultPattern
    {
        $this->warehouseEntity = $this->dTOToEntityMapper->convertDTOToEntity(
            $warehouseDTO
        );

        $this->warehouseEntity->setCreationDate(
            new \DateTime(
                'now',
                new \DateTimeZone(
                    'America/Mexico_City'
                )
            )
        );

        $this->warehouseEntity->setLastUpdateDate(
            new \DateTime(
                'now',
                new \DateTimeZone(
                    'America/Mexico_City'
                )
            )
        );

        $result =  $this->warehouseStorageRepository->saveWarehouse(
            $this->warehouseEntity
        );


        if ($result->isFailure()) {
            return ResultPattern::failure($result->getError());
        }

        return ResultPattern::success(
            "¡Almacén registrado con éxito!"
        );
    }

    public function updateWarehouse(WarehouseDTO $warehouseDTO): ResultPattern
    {
        $warehouseEntity = $this->dTOToEntityMapper->convertDTOToEntity(
            $warehouseDTO
        );
        $this->warehouseStorageRepository->updateWarehouse($warehouseEntity);
        return ResultPattern::success("Warehouse has been updated");

    }

    public function deleteWarehouse(WarehouseDTO $warehouse): ResultPattern
    {
        return ResultPattern::success("Warehouse has been deleted");
    }

    public function deleteByWarehouseId(int $warehouseId): ResultPattern
    {
        $this->warehouseStorageRepository->deleteWarehouseByWarehouseId($warehouseId);
        return ResultPattern::success("Warehouse has been deleted");
    }

    public function updateFieldsByWarehouseId(int $warehouseId, array $fields): ResultPattern
    {
        $this->warehouseStorageRepository->updateFieldsByWarehouseId(
            $warehouseId,
            $fields
        );
        return ResultPattern::success("Warehouse has been updated");
    }

    public function getWarehouseIdAndName(): array
    {
        $warehouses = $this->warehouseStorageRepository->getIdAndName();

        for ($i = 0; $i < count($warehouses); $i++) {
            $warehouses[$i] = new WarehouseListDTO(
                $warehouses[$i]['id'],
                $warehouses[$i]['warehouses_name']
            );
        }

        return $warehouses;
    }

    public function getWarehouseNameById(int $warehouseId): string
    {
        return  $this->warehouseStorageRepository->getNameById(
            $warehouseId
        );
    }

    public function listAllWarehouses(): array
    {
        $warehouses = $this->warehouseStorageRepository
        ->findAll();

        for ($i = 0; $i < count($warehouses) ; $i++) {
            $warehouses[$i] = new WarehouseListDetailDTO(
                $warehouses[$i]['id'],
                $warehouses[$i]['warehouses_name'],
                new \DateTime($warehouses[$i]['created_at']),
                new \DateTime($warehouses[$i]['updated_at']),
                $warehouses[$i]['user_last_update']['id'],
                $warehouses[$i]['user_last_update']['name'],
                $warehouses[$i]['warehouses_key'],
                $warehouses[$i]['warehouse_manager'],
                $warehouses[$i]['phone_number'],
                $warehouses[$i]['email'],
                $warehouses[$i]['warehouse_type']['id'],
                $warehouses[$i]['warehouse_type']['category_warehouse'],
                $warehouses[$i]['location']['id'],
                $warehouses[$i]['location']['headquarters_name'],
            );
        }

        return  $warehouses;
    }


    public function getTotalWarehouse(): int
    {
        return $this->warehouseStorageRepository->count();
    }

    public function getListAllWarehousesWithLocation(
        array $warehouseIds) :  array{
        $warehouses = $this->warehouseStorageRepository
        ->findWereIn($warehouseIds);

        for ($i=0; $i <count($warehouseIds) ; $i++) { 
            $warehouses[$i] = new WarehouseWithLocationResponseDTO(
                $warehouses[$i]["id"],
                $warehouses[$i]["warehouses_name"],
                $warehouses[$i]["location"]["headquarters_name"]
            );
        }

        return $warehouses;
    }
}

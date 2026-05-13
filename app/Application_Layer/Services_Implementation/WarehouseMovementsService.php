<?php

namespace App\Application_Layer\Services_Implementation;

use App\Contracts\WarehouseMovementsServiceI;
use App\Contracts\WarehouseMovementsRepositoryI;
use App\Mappers\DTO\WarehouseMovementsListDetailDTO;
use App\Mappers\DTO\WarehouseMovementsDTO;
use App\Application_Layer\ResultPattern;
use App\Contracts\WarehouseInventoryMovementsMapperI;
use App\Mappers\DTO\MovementsByPeriodFilterDTO;
use App\Mappers\DTO\DetailsOfMovements;

class WarehouseMovementsService implements WarehouseMovementsServiceI
{
    private WarehouseMovementsRepositoryI $warehouseMovementsRepository;
    private WarehouseInventoryMovementsMapperI $warehouseInventoryMovementsMapper;

    public function __construct(
        WarehouseMovementsRepositoryI $warehouseMovementsRepository,
        WarehouseInventoryMovementsMapperI $warehouseInventoryMovementsMapper
    ) {
        $this->warehouseMovementsRepository = $warehouseMovementsRepository;
        $this->warehouseInventoryMovementsMapper = $warehouseInventoryMovementsMapper;
    }

    public function listAllMovements(): array
    {
        $movements = $this->warehouseMovementsRepository->findAll();

        for ($i = 0; $i < count($movements) ; $i++) {
            $movements[$i] = WarehouseMovementsListDetailDTO::fromModel(
                $movements[$i]
            );
        }
        return  $movements;
    }

    public function listAllMovementsPaginated(int $page = 1, int $perPage = 15): array
    {
        $result = $this->warehouseMovementsRepository->findAllPaginated($perPage);
        $movements = [];
        foreach ($result['data'] as $movement) {
            $movements[] = WarehouseMovementsListDetailDTO::fromModel($movement);
        }

        return [
            'data' => $movements,
            'total' => $result['total'],
            'per_page' => $result['per_page'],
            'current_page' => $result['current_page'],
            'last_page' => $result['last_page']
        ];
    }

    public function getTotalOfMovements(): int
    {
        return $this->warehouseMovementsRepository->count();
    }

    public function countByMovementType(string $movementType): int
    {
        return  $this->warehouseMovementsRepository
        ->countByMovementType(
            $movementType
        );
    }

    public function saveWarehouseMovement(
        WarehouseMovementsDTO $warehouseMovementsDTO
    ): ResultPattern {

        try {
            $warehouseInventoryMovements = $this
            ->warehouseInventoryMovementsMapper
            ->toWarehouseInventoryMovementsEntity(
                $warehouseMovementsDTO
            );


            $this->warehouseMovementsRepository->save(
                $warehouseInventoryMovements
            );
        } catch (\Throwable $th) {
            return ResultPattern::failure($th->getMessage());
        }

        return ResultPattern::success($warehouseMovementsDTO);
    }

    public function filterTransactionsByDateRange(
        MovementsByPeriodFilterDTO $movementsByPeriodFilterDTO
    ): ResultPattern {

        $movementsFiltered = $this->warehouseMovementsRepository
        ->findByDateRange(
            $movementsByPeriodFilterDTO->getStartDate(),
            $movementsByPeriodFilterDTO->getEndDate(),
            $movementsByPeriodFilterDTO->getWarehouseId(),
            $movementsByPeriodFilterDTO->getMovementType()
        );




        $statistics = $this->warehouseMovementsRepository
        ->getMovementCountsByType(
            $movementsByPeriodFilterDTO->getStartDate(),
            $movementsByPeriodFilterDTO->getEndDate()
        );


        $finalFiltered = array();
        $index = 0;

        for ($i = 0; $i < count($movementsFiltered) ; $i++) {

            $movementType =  $movementsFiltered[$i]['movement_type'];
            $saveMovementType = $movementType;

            switch ($movementType) {
                case 'IN':
                    $movementType = "Entrada";
                    break;
                case 'OUT':
                    $movementType = "Salida";
                    break;
                case 'ADJUSTMENT':
                    $movementType = "Ajuste";
                    break;
                case 'TRANSFER':
                    $movementType = "Traslado";
                    break;

                case 'SALE':
                    $movementType = "Ventas";
                    break;

                case 'RELOCATION':
                    $movementType = "Reubicacion";
                    break;
            }


            $movementsFiltered[$i]['movement_type'] = $movementType;


            $register = WarehouseMovementsListDetailDTO::fromModel(
                $movementsFiltered[$i]
            );

            $finalFiltered[$index] = $register;
            $index++;
        }

       


        $filteredReport = new DetailsOfMovements(
            $finalFiltered,
            $statistics
        );

        return ResultPattern::success($filteredReport);
    }

    public function generateMovementFolio(): string
    {
        return 'MOV-' . str_pad(
            $this->getTotalOfMovements() + 1,
            6,
            '0',
            STR_PAD_LEFT
        );
    }


}

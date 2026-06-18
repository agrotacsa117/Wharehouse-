<?php

namespace App\Application_Layer\Services_Implementation;

use App\Application_Layer\ResultPattern;
use App\Contracts\WarehouseInventoryMovementsMapperI;
use App\Contracts\WarehouseMovementMapperI;
use App\Contracts\WarehouseMovementsRepositoryI;
use App\Contracts\WarehouseMovementsServiceI;
use App\Mappers\DTO\DetailsOfMovements;
use App\Mappers\DTO\MovementsByPeriodFilterDTO;
use App\Mappers\DTO\WarehouseMovementsDTO;
use App\Mappers\DTO\WarehouseMovementsListDetailDTO;

class WarehouseMovementsService implements WarehouseMovementsServiceI
{
    private WarehouseMovementsRepositoryI $warehouseMovementsRepository;

    private WarehouseInventoryMovementsMapperI $warehouseInventoryMovementsMapper;

    private WarehouseMovementMapperI $warehouseMovementMapperI;

    public function __construct(
        WarehouseMovementsRepositoryI $warehouseMovementsRepository,
        WarehouseInventoryMovementsMapperI $warehouseInventoryMovementsMapper,
        WarehouseMovementMapperI $warehouseMovementMapperI
    ) {
        $this->warehouseMovementsRepository = $warehouseMovementsRepository;
        $this->warehouseInventoryMovementsMapper = $warehouseInventoryMovementsMapper;
        $this->warehouseMovementMapperI = $warehouseMovementMapperI;
    }

    public function listAllMovements(): array
    {
        $movements = $this->warehouseMovementsRepository->findAll();

        return $this
            ->convertToWarehouseMovementsListDetailDTO(
                $movements
            );

    }

    private function convertToWarehouseMovementsListDetailDTO(
        array $movements
    ): array {
        for ($i = 0; $i < count($movements); $i++) {
            $movements[$i] = WarehouseMovementsListDetailDTO::fromModel(
                $movements[$i]
            );
        }

        return $movements;
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
            'last_page' => $result['last_page'],
        ];
    }

    public function getTotalOfMovements(): int
    {
        return $this->warehouseMovementsRepository->count();
    }

    public function countByMovementType(string $movementType): int
    {
        return $this->warehouseMovementsRepository
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

        $finalFiltered = [];
        $index = 0;

        for ($i = 0; $i < count($movementsFiltered); $i++) {

            $movementType = $movementsFiltered[$i]['movement_type'];
            $saveMovementType = $movementType;

            switch ($movementType) {
                case 'IN':
                    $movementType = 'Entrada';
                    break;
                case 'OUT':
                    $movementType = 'Salida';
                    break;
                case 'ADJUSTMENT':
                    $movementType = 'Ajuste';
                    break;
                case 'TRANSFER':
                    $movementType = 'Traslado';
                    break;

                case 'SALE':
                    $movementType = 'Ventas';
                    break;

                case 'RELOCATION':
                    $movementType = 'Reubicacion';
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
        return 'MOV-'.str_pad(
            $this
                ->warehouseMovementsRepository
                ->countFolio() + 1,
            6,
            '0',
            STR_PAD_LEFT
        );
    }

    public function isReserved(string $folio): bool
    {
        return $this->warehouseMovementsRepository
            ->isReversed(
                $folio
            );
    }

    public function getWarehouseMovementsByFolio(
        string $folio
    ): ?WarehouseMovementsDTO {

        $warehouseMovements = $this
            ->warehouseMovementsRepository
            ->findByFolio($folio);

        if ($warehouseMovements) {
            return $this->warehouseMovementMapperI
                ->convertToWarehouseMovementsDTO(
                    $warehouseMovements
                );
        }

        return null;
    }

    public function getDependentMovements(
        int $inventoryId,
        string $folio
    ): array {

        $dependentMovements = $this->warehouseMovementsRepository
            ->findByWarehouseInventoryIdAndFolioNot(
                $inventoryId,
                $folio
            );

        return $this
            ->convertToWarehouseMovementsListDetailDTO(
                $dependentMovements
            );
    }

    public function getIdByFolio(string $folio): int
    {
        return $this->warehouseMovementsRepository
            ->findIdByFolio(
                $folio
            );
    }

    public function reserveAMovementFolio(
        string $folio,
        int $countermovementId): bool
    {
        return $this->warehouseMovementsRepository
            ->setReversedByFolio(
                $folio,
                $countermovementId
            );
    }

    public function markStatusIsReserved(
        string $folio, bool $status
    ): bool {
        return $this
            ->warehouseMovementsRepository
            ->setReversedByFolio(
                $folio,
                $status
            );
    }

    public function getTotalOfRelocation(): int
    {
        $reloactionTotal = $this->warehouseMovementsRepository
            ->countByMovementType(
                'RELOCATION');

        $internalRelocationTotal = $this
            ->warehouseMovementsRepository
            ->countByMovementType(
                'LOCATION_UPDATE'
            );

        $movementsTotal = $reloactionTotal
        + $internalRelocationTotal;

        return $movementsTotal;
    }
}

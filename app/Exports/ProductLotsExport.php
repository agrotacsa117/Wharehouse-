<?php

declare(strict_types=1);

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductLotsExport implements FromArray, WithHeadings
{
    private array $rows;

    public function __construct(array $rows)
    {
        $this->rows = $rows;
    }

    public function array(): array
    {
        return array_map('array_values', $this->rows);
    }

    public function headings(): array
    {
        return ['#', 'Lote', 'Ubicación', 'Cantidad', 'Fecha Caducidad', 'Días Restantes', 'Obsolescencia', 'Estado'];
    }
}
